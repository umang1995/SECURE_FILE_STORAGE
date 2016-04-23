<?php
$mysql_host = 'localhost';
$mysql_user= 'umang';
$mysql_pass= '';
$conn_error='could not connect';
$mysql_db='sfs';
if(!@mysql_connect($mysql_host,$mysql_user,$mysql_pass)||!@mysql_select_db($mysql_db)){
	die($conn_error);
}
?>