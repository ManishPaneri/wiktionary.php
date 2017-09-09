<?php
header('Content-Type: application/json; charset=utf-8');

function mysqlDBConnection(){

	$connection = mysqli_connect("localhost","root","root");
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

function  mysql_connection_select($word,$connection){	

	// $noun_type_start=explode("{{",$string);
	$table_data=array();
	$table_data['word']=$word;
	$query="select text from dictionary where word ='".$word."'  ORDER BY `dictionary`.`updateddate` DESC";
	// echo $query;
	$i=0;
	$result = mysqli_query($connection,$query);
   	while($row = mysqli_fetch_assoc($result)){
 		 $table_data["text"][$i]= $row["text"] ;
 		 $i=$i+1;
 	} 

   	return $table_data;

}

$word=$_GET["word"];
$connection=mysqlDBConnection();

$output=mysql_connection_select($word,$connection);

if(empty($output["text"][0]) && $output["text"][0]==null){
		$output1= file_get_contents("http://localhost/Wiky_php-master/insert.php?word=".$word."");
		echo ($output1); 
}else{
		echo json_encode($output,JSON_PRETTY_PRINT); 

}

?>
