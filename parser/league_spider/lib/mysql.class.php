<?php

class MySQL {
   private $type;
   private $db = null;
   
   public function __construct($type, $server, $user, $pw, $db){
      $this->type = 1;    // MySQL
      if($type == "mysqli"){
         $this->type = 2; // MySQLi
      }
      
      if($this->type == 2){
         $this->db = mysqli_connect($server, $user, $pw, $db) or die("MysqliConnection error");
      } else {
         mysql_connect($server, $user, $pw) or die("MysqlConnection error");
         mysql_select_db($db) or die("MysqlDBConnection error");
      }
   }
   
   public function query($sql){
      if($this->type == 2){
         $query = mysqli_query($this->db, $sql) or die(mysqli_error($this->db));
      } else {
         $query = mysql_query($sql) or die(mysql_error());
      }
      return $query;
   }
   
   public function fetch_array($query){
      if($this->type == 2){
         $return = mysqli_fetch_assoc($query);
      } else {
         $return = mysql_fetch_assoc($query);
      }
      return $return;
   }
   
   public function fetch_object($query){
      if($this->type == 2){
         $return = mysqli_fetch_object($query) or error(mysqli_error($this->db));
      } else {
         $return = mysql_fetch_object($query) or error(mysql_error());
      }
      return $return;
   }
   
   public function num_rows($query){
      if($this->type == 2){
         $return = mysqli_num_rows($query);
      } else {
         $return = mysql_num_rows($query);
      }
      return $return;
   }
   
   public function real_escape_string($value){
      if($this->type == 2){
         $return = mysqli_real_escape_string($this->db, $value);
      } else {
         $return = mysql_real_escape_string($value);
      }
      return $return;
   }
   
   public function get_mysqli_object(){
      if($this->type == 2){
         return $this->db;
      }
      return null;
   }
   
   public function get_type(){
      return $this->type;
   }
}

?>