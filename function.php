<?php

function configReader($Config_file_path, $var){
	$xml= simplexml_load_file($Config_file_path) or die("Error: config a.xml in error");
	$file_base_url = $xml->mysql->$var;
	$array =  (array) $file_base_url;
	return trim($array[0]," ");
}


function mysqlDBConnection(){
	// $Config_file_path="/etc/data/a.xml";
	$data['server_address'] = "localhost"; //configReader($Config_file_path, "mysql_hostname");
	$data['db_username'] = "root"; //configReader($Config_file_path, "mysql_db_username");
	$data['db_password'] = "root"; //configReader($Config_file_path, "mysql_db_password");
	$data['db_name'] = "wiktionary"; //configReader($Config_file_path, "mysql_db_name");
	
	$connection = mysqli_connect($data['server_address'],$data['db_username'],$data['db_password']);
		if (!$connection) {
			die("database connection failed: " . mysqli_connect_error());
		}
		// Select a database to use
		$db_select = mysqli_select_db($connection,$data['db_name']);
		
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

?>