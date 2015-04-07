<?php
/******************************************
 ******************************************
 **        Copyright (C) 2012            **
 **               by                     **
 **           MediaWorldZ                **
 ******************************************
 ******************************************/
 
class tmpl_operators {
    private $template;
    private $ops_matches;
    
    public function load_data($template, $modul){
        // Template in $this->template speichern
        $this->template = $template;
        
        // Module includen
        include 'modules/'.trim(strtolower($modul)).'/template.operators.php';
        
        // Modul-Operator-Klasse erstellen
        $modul = $modul."_operator";
        $class = new $modul;
        
        // Operatoren suchen
        $pattern = '~\{\s?.*(.*)}~USs';
        preg_match_all($pattern,$this->template,$this->ops_matches);
        
        // Daten an Klasse weitergeben und verarbeiten lassen
        $operator_modul = $class->loadData($this->template, $this->ops_matches);
        
        return $operator_modul;
    }
}
 
 ?>