<?php

/* Wiktionary.php is a PHP "library" to convert Simple wiktionary HTML page convert to text
 * Author: Manish Kumar Paneri 
 */
include("function/function.php");

/*$myfile = fopen("word.txt", "r") or die("Unable to open file!");
$string= fread($myfile,filesize("word.txt"));
fclose($myfile);
*/
$connection = mysqlDBConnection();
$output_array=array();

/*$array = explode("-",$string);
// print_r($array);exit();

for($i=0;$i<count($array);$i++){
*/
	$ouput=file_get_contents("http://".$_SERVER['HTTP_HOST']."/Wiky_php-master/howto.php?word=".$_GET["word"]."");
	// echo "http://localhost/Wiky_php-master/howto.php?word=".trim($array[$i])."";
	// $ouput=file_get_contents("http://localhost/Wiky_php-master/howto.php?word=".trim($array[$i])."");
	$output_array = json_decode($ouput,true);

	for($j=0;$j<count($output_array["text"]);$j++){

		mysql_connection_insert($output_array["text"][$j],$output_array["word"],$connection);	
	}
	
	print_r(json_encode($output_array,JSON_PRETTY_PRINT));
// }





?>	