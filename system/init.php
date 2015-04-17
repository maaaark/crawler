<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function error($error){
   print($error);
}

require_once 'system/config.php';
require_once 'system/curl.func.php';
require_once 'system/config_db.php';
require_once 'system/mysql.class.php';
$GLOBALS["db"] = new MySQL(MYSQL_TYPE, MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);
$GLOBALS["db_fi"] = new MySQL(MYSQL_FI_TYPE, MYSQL_FI_HOST, MYSQL_FI_USER, MYSQL_FI_PW, MYSQL_FI_DB);

session_start();

require_once 'system/global_functions.php';

?>