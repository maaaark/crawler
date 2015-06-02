<?php

// INCLUDES
include 'system/tmpl_conditions.class.php';
include 'system/tmpl_operators.class.php';

class Template {
    private $template;
    private $assign_replace;
    private $assign_replacement;
    
    private $status_diplay;
    private $status_diplay_file;
    
    private $parser_template;
    
    // $assigns zum Array machen
    public function __construct() {
        $this->assign_replace     = array();
        $this->assign_replacement = array();
        
        $this->loadBasics();
    }
    
    // Template-Datei laden und in $template speichern
    public function load($file, $global_template = false, $bbcode = false){        
        if(defined("CURRENT_MODULE") && CURRENT_MODULE != "" && $global_template == false){
            $this->parser_template = true;
            $this->addParserConfig();
            $this->template = file_get_contents(ROOT."/parser/".CURRENT_MODULE."/templates/".$file.".tpl");
        } else {
            $this->template = file_get_contents(ROOT."/templates/".$file.".tpl");
        }

        if($bbcode){
           $system = new MediaBaseCore;
           $this->template = $system->bbcode(utf8_encode($this->template));
        }

        $array_inc = array();;
        $pattern = '~\{include (.*)\}~USs';
        preg_match_all($pattern, $this->template, $array_inc);
        
        for($i = 0; $i<count($array_inc[0]); $i++){
           if(isset($array_inc[0][$i])&&isset($array_inc[1][$i])){
              if(file_exists("templates/".$array_inc[1][$i].".tpl")){
                 $con = file_get_contents("templates/".$array_inc[1][$i].".tpl");
                 $this->template = str_replace($array_inc[0][$i], $con, $this->template);
              } else {
                 $this->template = str_replace($array_inc[0][$i], "Fehler: Template-Include nicht gefunden", $this->template);
              }
           }
        }
    }
    
    // Template-Funktionen zu $assigns hinzufügen
    public function assign($replace, $replacement){
        $count = count($this->assign_replace);
        if($count == 0){
            $count = 0;
        } elseif($count == 1){
            $count = 1;
        }
        
        $this->assign_replace[$count]       = $replace;
        $this->assign_replacement[$count]   = $replacement;
    }
    
    // Operatoren-Liste
    public function operators(){
        $tmpl = $this->template;
        //$tmpl = $this->load_operators($tmpl, "news");
        return $tmpl;
    }
    
    // Template-Operatoren zu $assign_operatoren hinzufügen
    public function load_operators($template, $modul){
        $operator_class = new tmpl_operators;
        $operator = $operator_class->load_data($template, $modul);
        return $operator;
    }
    
    // Ersetzen Funktion aufrufen + Ergebnis ausgeben
    public function display($content = false, $file = false){
        $this->status_diplay = $content;
        $this->status_diplay_file = $file;
        
        if($content == true){
            if($file == false){
               $file = "design";
            }
            $design_data = file_get_contents("templates/".$file.".tpl");
            $this->template = str_replace("{CONTENT}", $this->template, $design_data);
            
            $this->replace_assigns();
            $this->conditions();
            return $this->template;
        } else {
            $this->replace_assigns();
            $this->conditions();
            return $this->template;
        }
    }
    
    // In $template nach Funktionen suchen und diese ersetzen
    public function replace_assigns(){
        $count = count($this->assign_replace);
        for($i=0; $i<=$count; $i++){
            @$this->template = str_replace("{".$this->assign_replace[$i]."}", $this->assign_replacement[$i], $this->template);
        }
        
        if(checkInstantMessages()){
            if(strpos($this->template, "{INSTANT_MESSAGES}") > 0){
               $this->template = str_replace("{INSTANT_MESSAGES}", getInstantMessages(), $this->template);
            }
        }
        $this->template = str_replace("{INSTANT_MESSAGES}", "", $this->template);
        
        if(strpos($this->template, "{NAVIGATION_PARSER}") > 0){
            $parser_navi = $this->loadParserNavi();
            $this->template = str_replace("{NAVIGATION_PARSER}", $parser_navi["parser"], $this->template);
            
            if(trim($parser_navi["modules"]) != ""){
               $this->template = str_replace("{NAVIGATION_MODULES}", $parser_navi["modules"], $this->template);
            } else {
               $this->template = str_replace("{NAVIGATION_MODULES}", "<div class='no_rights_navi'>Du hast momentan keinen Zugriff auf Module</div>", $this->template);
            }
        }
    }
    
    public function conditions(){
        $conditions_class = new template_conditions;
        $conditions_load = $conditions_class->load_data($this->template, $this->assign_replace, $this->assign_replacement);
        $this->template = $conditions_load;
    }
    
    public function loadBasics(){
        $this->assign("ROOT", ROOT);
        $this->assign("DOMAIN", DOMAIN);
        $this->assign("CURRENT_LOL_VERSION", CURRENT_LOL_VERSION);
        
        if(defined("CURRENT_MODULE") && trim(CURRENT_MODULE) != ""){
           $this->assign("CURRENT_MODULE", trim(CURRENT_MODULE));
        }

        $get_vars = array();
        if(isset($_GET) && count($_GET) > 0){
            $get_vars = $_GET;
        }
        foreach($get_vars as $column => $value){
            if(isset($value) && $value){
                $this->assign("REQUEST.".strtoupper($column), $value);
            } else {
                $this->assign("REQUEST.".strtoupper($column), "false");
            }
        }
        
        if(isset($_SESSION["username"])){
            $this->assign("LOGGED_USERNAME", $_SESSION["username"]);
        }
        
        if(defined("FLASHIGNITE_URL")){
            $this->assign("FLASHIGNITE_URL", FLASHIGNITE_URL);
        }
    }
    
    private function addParserConfig(){
        if($this->parser_template && defined("CURRENT_MODULE") && trim(CURRENT_MODULE) != ""){
           if(file_exists(ROOT."/parser/".CURRENT_MODULE."/config.json")){
               $config = json_decode(file_get_contents(ROOT."/parser/".CURRENT_MODULE."/config.json"), true);
               foreach($config as $column => $value){
                  if(is_array($value)){
                     // Hier muss irgendwann das array noch hinzugefügt werden
                  } else {
                     $this->assign("PARSER_".strtoupper($column), $value);
                  }
               }
           }
        }
    }
    
    private function loadParserNavi(){
        $dir = opendir("parser");
        $return = array("parser" => "", "modules" => "");
        while($folder = readdir($dir)){
           if(trim($folder) != "." && trim($folder) != ".." && trim($folder) != ""){
              if(file_exists("parser/".$folder."/init.php") && file_exists("parser/".$folder."/config.json")){
                 $out    = "";
                 $config = json_decode(file_get_contents("parser/".$folder."/config.json"), true);
                 
                 $icon   = '<i class="fa fa-database"></i>';
                 
                 $class_add = "";
                 if(defined("CURRENT_MODULE") && trim(CURRENT_MODULE) == trim($folder)){
                     $class_add = "active";
                 }
                 
                 $status = false;
                 if(isset($config["roles"])){
                    if(checkCanSee($_SESSION["roles"], $config["roles"])){
                       $status = true;
                       $out   .= '<div class="navi_el '.$class_add.'">';
                       $out   .= '<div class="title">'.$icon.trim($config["name"]).'</div>';
                    }
                 } else {
                    $status = true;
                    $out   .= '<div class="navi_el '.$class_add.'">';
                    $out   .= '<div class="title">'.$icon.trim($config["name"]).'</div>';
                 }
                 
                 if($status){
                    if(isset($config["subnavi"]) && is_array($config["subnavi"])){
                       $out .= '<div class="subnavi">';
                       foreach($config["subnavi"] as $element){
                          $out .= '<div class="subnavi_el"><a href="'.trim($element["link"]).'">'.trim($element["label"]).'</a></div>';
                       }
                       $out .= "</div>";
                       $out  = str_replace('class="navi_el '.$class_add.'"', 'class="navi_el '.$class_add.' subnavi"', $out);
                       $out  = str_replace('<div class="title">', '<div class="title"><div class="subnavi_toggle"></div>', $out);
                    }
                    $out .= "</div>";
                 }
                 
                 if(isset($config["type"]) && strtolower(trim($config["type"])) == "module"){
                    $return["modules"] .= $out;
                 } else {
                    $return["parser"] .= $out;
                 }
              }
           }
        }
        return $return;
    }
}
?>