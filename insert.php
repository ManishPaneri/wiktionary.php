<?php

/* Wiktionary.php is a PHP "library" to convert Simple wiktionary HTML page convert to text
 * Author: Manish Kumar Paneri 
 */

function mysqlDBConnection(){

	$connection = mysqli_connect("localhost","root","reloded23");
		if (!$connection) {
			die("database connection failed: " . mysqli_connect_error());
		}
		// Select a database to use
		$db_select = mysqli_select_db($connection,"engine1");
		
		if (!$db_select) {
			die("database selection failed: " . mysqli_connect_error());
	}
	return $connection;
}

function  mysql_connection_insert($string,$word,$connection){	

	// $noun_type_start=explode("{{",$string);
	
	$noun_type="simple";
	
	$query ='INSERT INTO `dictionary`( `word`,`word_type` , `text`, `type`) VALUES ("'.$word.'","def", "'.$string.'","'.$noun_type.'")';
   	//echo $query ; echo "\n";	
    if(mysqli_query($connection,$query)){
      $data = array("status"=>"success", "message"=>"data submited!");
    }
    else{
    	 $data = array("status"=>"error", "message"=>"database query failed !");
	
    }

    return $data;

}



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
	$ouput=file_get_contents("http://localhost/Wiky_php-master/howto.php?word=".$_GET["word"]."");
	// echo "http://localhost/Wiky_php-master/howto.php?word=".trim($array[$i])."";
	// $ouput=file_get_contents("http://localhost/Wiky_php-master/howto.php?word=".trim($array[$i])."");
	$output_array = json_decode($ouput,true);

	for($j=0;$j<count($output_array["text"]);$j++){

		mysql_connection_insert($output_array["text"][$j],$output_array["word"],$connection);	
	}
	
	print_r(json_encode($output_array,JSON_PRETTY_PRINT));
// }





?>	