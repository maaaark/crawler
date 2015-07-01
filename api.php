<?php

header('Content-Type: application/json');
require_once 'system/init.php';

function check_api_key(){
    $out = array("verify_status" => false, "verify_error" => "");
    if(isset($_GET["api_key"])){
        if($_GET["api_key"] == "API_KEY__ANDROID_APP_2015"){
            $out["verify_status"] = true;
        } else {
            $out["verify_error"] = "invalid api_key.";
        }
    } else {
        $out["verify_error"] = "no api_key given.";
    }
    return $out;
}

$return = array();
if(($api_key_check = check_api_key()) && isset($api_key_check["verify_status"]) && $api_key_check["verify_status"] == true){
    if(isset($_GET["last_matches"])){
        $query  = $GLOBALS["db_fi"]->query("SELECT * FROM esports_match WHERE is_finished = '1' ORDER BY date DESC LIMIT 0, 20");
        while($row = $GLOBALS["db_fi"]->fetch_object($query)){
            $arr_temp = (array)$row;
            $temp     = $arr_temp;
            $temp["tournament_data"] = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_tournament WHERE tournament_id = '".$GLOBALS["db_fi"]->real_escape_string($arr_temp["tournament_id"])."'"));
            
            $temp["team1_data"]      = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE team_id = '".$GLOBALS["db_fi"]->real_escape_string($arr_temp["team1_id"])."'"));
            $temp["team2_data"]      = $GLOBALS["db_fi"]->fetch_array($GLOBALS["db_fi"]->query("SELECT * FROM esports_team WHERE team_id = '".$GLOBALS["db_fi"]->real_escape_string($arr_temp["team1_id"])."'"));
            $return[] = $temp;
        }
    } else {
        $return["route_error"] = "unknown route";
    }
}
$return["verify_error"] = $api_key_check["verify_error"];
echo json_encode($return);