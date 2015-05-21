<?php

class LeagueSpiderLog {
    private $content;
    private $final_message;
    private $error_count;
    private $curl_errors;
    
    public function __construct(){
        $this->content       = "";
        $this->final_message = "";
        $this->error_count   = 0;
        $this->curl_errors   = 0;
    }
    
    public function log($message){
        $this->content .= date("H:i:s d.m.Y").": ".trim($message)."\n";
    }
    
    public function log_error($message){
        $this->content .= "[ERROR] ".date("H:i:s d.m.Y").": ".trim($message)."\n";
        $this->error_count++;
    }
    
    public function log_curl_error($message, $curl){
        $this->content .= "[ERROR] ".date("H:i:s d.m.Y").": ".trim($message);
        
        if(isset($curl["info"]) && isset($curl["info"]["http_code"]) && isset($curl["info"]["url"])){
            $this->content .= " == URL: ".$curl["info"]["url"];
            $this->content .= " == HTTP-Code: ".$curl["info"]["http_code"];
        }
        $this->content .= "\n";
        $this->error_count++;
        $this->curl_errors++;
    }

    public function set_final_message($message){
        $this->final_message = date("H:i:s d.m.Y").": ".trim($message);
    }
    
    public function append_final_message($message){
        $this->final_message = $this->final_message.trim($message);
    }

    public function get($type = "console"){
        $array = array("error_count" => $this->error_count, "final_message" => $this->final_message, "log" => "");
        if($type == "html"){
            $array["log"] = nl2br($this->content);
        } else {
            $array["log"] = $this->content;
        }
        return $array;
    }
}