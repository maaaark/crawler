<?php

function check_array($array, $value){
	for($i = 0; $i<count($array); $i++){
		if($array[$i] == $value){
			return true;
		}
	}
	return false;
}

function pre_print($data){
	echo "<pre>", print_r($data), "</pre>";
}

?>
