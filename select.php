<?php

/* Wiktionary.php is a PHP "library" to convert Simple wiktionary HTML page convert to text
 * Author: Manish Kumar Paneri 
 */

header('Content-Type: application/json; charset=utf-8');

include("function/function.php");

$word=$_GET["word"];
$connection=mysqlDBConnection();

$output=mysql_connection_select($word,$connection);

if(empty($output["text"][0]) && $output["text"][0]==null){
		$output1= file_get_contents("http://".$_SERVER['HTTP_HOST']."/Wiky_php-master/insert.php?word=".$word."");
		echo ($output1); 
}else{
		echo json_encode($output,JSON_PRETTY_PRINT); 

}

?>