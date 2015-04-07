<?php
/******************************************
 ******************************************
 **        Copyright (C) 2012            **
 **               by                     **
 **           MediaWorldZ                **
 ******************************************
 ******************************************/
 
class template_conditions{
    private $template;
    private $assign_replace;
    private $assign_replacement;
    private $conditions;
    
    // Daten aus der Main-Klasse hier importieren
    public function load_data($template, $assign_replace, $assign_replacement){
        $this->template           = $template;
        $this->assign_replace     = $assign_replace;
        $this->assign_replacement = $assign_replacement;
        
        $this->search_condition();
        return $this->template;
    }
    
    // IF-Bedingungen suchen
    public function search_condition(){
        $this->conditions = array();
        
        $pattern = '~(\{if\s?.*\})(.*)\{\/if}~USs';
        preg_match_all($pattern, $this->template, $this->conditions);
        
        //echo "<pre>";
        //print_r($this->conditions);
        //echo "</pre>";
        
        $this->evaluate_conditions();
    }
    
    // Gefundene Bedingungen auswerten und dementsprechend löschen
    public function evaluate_conditions(){
        $count = count($this->conditions[0]);
        for($i=0; $i<$count; $i++){
            // Bedingung rausschneiden
            $condition = $this->conditions[1][$i];
            $condition = substr($condition, 3);
            $condition = substr($condition, 0, -1);
            
            // String länge rausbekommen und Bedingungstyp kontrollieren
            $length  = strlen($condition);
            $equal   = strpos($condition, "==");
            
            // Es ist eine "IST-GLEICH" Abfrage
            if($equal){
                  // Template Funktion
                  $tmpl_function = trim(substr($condition, 0, $equal));
                  $assign_id = array_search($tmpl_function, $this->assign_replace);
            
                  // Nach {else} suchen und Position speichern
                  $else = strpos($this->conditions[0][$i], "{else}");
               
                  // Den Inhalt nach "==" suchen und speichern
                  $replacement = substr($condition, $equal+2);
              
                  // Wenn {else} vorhanden
                  if($else){
                       $else_replace = strpos($this->conditions[2][$i], "{else}");
                       // Bedingung = true
                       if($this->assign_replacement[$assign_id] === $replacement){
                           $replace = substr($this->conditions[2][$i], 0, $else_replace);
                           $this->template = str_replace($this->conditions[0][$i], $replace, $this->template);
                       // Bedingung = false
                       } else {
                           $replace = substr($this->conditions[2][$i], $else_replace+6);
                           $this->template = str_replace($this->conditions[0][$i], $replace, $this->template);
                       }
               
                  // Wenn kein {else} vorhanden    
                  } else {
                      // Bedingung = true
                      if($this->assign_replacement[$assign_id] === $replacement){
                            $this->template = str_replace($this->conditions[0][$i], $this->conditions[2][$i], $this->template);
                        // Bedingung = false
                      } else {
                            $this->template = str_replace($this->conditions[0][$i], "", $this->template);
                      }
                  }
            } // equal ende
            
            // Es ist eine "isset" Abfrage
            else {
                  // Nach {else} suchen und Position speichern
                  $else = strpos($this->conditions[0][$i], "{else}");
                  
                  // Wenn {else} vorhanden
                  if($else){
                       $else_replace = strpos($this->conditions[2][$i], "{else}");
                       $assign_id = array_search(trim($condition), $this->assign_replace);
                       
                       // Bedingung = true
                       if(trim($condition)==trim($this->assign_replace[$assign_id])&&$this->assign_replacement[$assign_id]!=""&&$this->assign_replacement[$assign_id]!="0"&&isset($this->assign_replacement[$assign_id])){
                             $replace = substr($this->conditions[2][$i], 0, $else_replace);
                             $this->template = str_replace($this->conditions[0][$i], $replace, $this->template);
                       
                       // Bedingung = false
                       } else {
                             $replace = substr($this->conditions[2][$i], $else_replace+6);
                             $this->template = str_replace($this->conditions[0][$i], $replace, $this->template);
                       }
                  // Wenn kein {else} vorhanden
                  } else {
                       $assign_id = array_search(trim($condition), $this->assign_replace);
                       
                       // Bedingung = true
                       if(trim($condition)==trim($this->assign_replace[$assign_id])&&$this->assign_replacement[$assign_id]!=""&&$this->assign_replacement[$assign_id]!="0"&&isset($this->assign_replacement[$assign_id])){
                             $this->template = str_replace($this->conditions[0][$i], $this->conditions[2][$i], $this->template);
                             
                       // Bedingung = false
                       } else {
                             $this->template = str_replace($this->conditions[0][$i], "", $this->template);
                       }
                  }
            }
        } // For-Schleife Ende   
    }
}