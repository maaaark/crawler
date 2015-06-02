<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function error($error){
   print($error);
}

define("ROOT_DIR", dirname(dirname(__FILE__)));

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/curl.func.php';
require_once dirname(__FILE__).'/config_db.php';
require_once dirname(__FILE__).'/mysql.class.php';
$GLOBALS["db"] = new MySQL(MYSQL_TYPE, MYSQL_HOST, MYSQL_USER, MYSQL_PW, MYSQL_DB);
$GLOBALS["db_fi"] = new MySQL(MYSQL_FI_TYPE, MYSQL_FI_HOST, MYSQL_FI_USER, MYSQL_FI_PW, MYSQL_FI_DB);
$GLOBALS["db_nw"] = new MySQL(MYSQL_NW_TYPE, MYSQL_NW_HOST, MYSQL_NW_USER, MYSQL_NW_PW, MYSQL_NW_DB);

session_start();

require_once dirname(__FILE__).'/global_functions.php';