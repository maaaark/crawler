<?php

define("CAN_SET_ADMIN", true); // Es kann ein Admin Benutzer angelegt werden. Sollte auf false sein!
define("RIOT_KEY", "cc157cc5-58b2-417a-83ac-7d4579bd2d1d");

define("DOMAIN", "http://localhost/crawler");	// Am ende kein Slash!
define("ROOT", dirname(dirname(__FILE__))."/");

if(file_exists("logs/lol_version.txt")){
   define("CURRENT_LOL_VERSION", file_get_contents("logs/lol_version.txt"));
} else {
   define("CURRENT_LOL_VERSION", "");
}
?>