<?php
header('Content-Type: application/json; charset=utf-8');
$myfile = fopen("word_list.list", "r") or die("Unable to open file!");
$string= fread($myfile,filesize("word_list.list"));
fclose($myfile);

$array = explode("\n",$string);
$output=array();$z=0;
for($i=0;$i<count($array);$i++){

	$ouput=file_get_contents("http://localhost/Wiky_php-master/select.php?word=".strtolower(trim($array[$i]))."");
	if($ouput!=null){
		$output[$z]=json_decode($ouput);
		$z=$z+1;
	}
}

echo json_encode($output,JSON_PRETTY_PRINT);
?>