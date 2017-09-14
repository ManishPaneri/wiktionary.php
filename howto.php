<?php

/* Wiktionary.php is a PHP "library" to convert Simple wiktionary HTML page convert to text
 * Author: Manish Kumar Paneri 
 */

// This file demonstrates how to use Wiky.php.

// Include the library (obviously)
header('Content-Type: application/json; charset=utf-8');
require_once("wiky.inc.php");

function get_string_word($string){
	$array=array(
		"Verb",
		"Adjective",
		"Antonyms",
		"Phrases",
		"Related words",		
		"Homophones",
		"Pronunciation",
		"Synonyms",
		"Idioms",
		"See also",
		"Derived words",
		"judges",
		"Verb 2",
		"Adverb",
		"Verb 1",
		"Note",
		"Usage notes",
		"Interjection",
		"Usage",
		"Related word",
		"Gallery",
		"&lt;gallery>",
		"{{periodic table}}",
		"{{wikipedia}}",
		"{{family}}",
		"{{basic math}}",
		"{{solar system}}",
		"{{astronomies}}",
		"Common Terminology",
		"Related words and phrases",
		"The Periodic Table",
		"Related words",
		"Abbreviation",
		"[[Category",
		"\t",
		'"\t\t\t\t\t",',
		"\t\t",
		"Basic math (edit)",
		"Picture dictionary"
		);

	for($i=0;$i<count($array);$i++){
		$string_array=explode($array[$i],$string);
		$string=$string_array[0];
	}
	return $string;
}

function get_correct_text($string){
	$array=array(
		"Singular",
		"Plural",
		"Famous ",
		);

	for($i=0;$i<count($array);$i++){
		if(strpos($string, $array[$i]) !== false){
			$string_array=explode($array[$i],$string);
			$string=$string_array[1];	
		}
		
	}
	return $string;

}

function removeSpecialtext($string,$word){
	$array=array(
		"[[",
		"]]",
		"none",
		"  ",
		"==",
		"===",
		"&#160;",
		"&#160;&#160;&#160;&#160;",
		"{{countable}}, ", 
		"{{singular}}, ",
		"{{uncountable}}, ",
		"{{old, no longer used}}, ",
		"{{art}}, ",
		"{{usually plural}}, ",
		"{{usually singular}}, ",
		"{{astronomy}}, ",
		"{{mathematics}}, ",
		"{{vulgar}}, ",
		"{{irrnoun}}","{{galaxies}}",
		"{{context}}","{{special}}",
		"{{synonyms}}","{{silence}}","{{hush}}","{{peace}}","{{stillness}}","{{tranquility}}","{{calm}}",
		"{{serenity}}","{{peacefulness}}","{{softness}}","{{antonyms}}","{{noise}}","{{sound}}",
		"{{din}}","{{racket}}","{{clamor}}","{{clatter}}","{{blast}}","{{blare}}","{{commotion}}","{{disturbance}}","{{pandemonium}}","{{tumult}}","{{rumpus}}","{{turmoil}}","{{row}}",
		"{{synonyms|gradient}}, ",
		"{{-}}, ",
		"{{technical}}, ",
		"{{chemistry}}, ",
		"{{uncountable}}, ",
		"{{uncountable}}, ",
		"{{physics}}, ",
		"{{uncountable}}, ",
		"{{countable}}", 
		"{{singular}}",
		"{{uncountable}}",
		"{{old, no longer used}}",
		"{{art}} ",
		"{{usually plural}}",
		"{{usually singular}}",
		"{{astronomy}}",
		"{{mathematics}}",
		"{{vulgar}}",
		"{{synonyms|gradient}}",
		"{{-}}",
		"{{technical}}",
		"{{chemistry}}",
		"{{uncountable}}",
		"{{uncountable}}",
		"{{physics}}",
		"{{uncountable}}",
		"{{usually \"the country\"}}",
		"{{colloquial}}",
		"{{cu noun}}",
		"{{periodic table}}",
		"{{stub}}",
		"{{colorbox}}","{{gold}}",
		"{{plural}}",
		"{{icopies}}",
		"{{countries}}",
		"{{Stub}}",
		"{{a}}","{{mathematics and countable}}","{{diplomacy}}",
		"{{printing/countable}}",
		// "A ".$word."",
		);

	for($i=0;$i<count($array);$i++){

		$string = str_replace($array[$i], '', $string);
	}

	return $string;

}
function get_removeExample($string,$word){

	$array= array(
			"example--",
			"example",
			"sign, symbol",
			// "{\\",
			"Synonyms:",
			"Antonyms:"

		);

	for($i=0;$i<count($array);$i++){
		if(strpos($string, $array[$i]) !== false){
			$string= 0;	
		}
	}
	
	if(strlen($string)==1 || strtolower($string)=="a ".$word."" || $string ==strtolower($word)."s" || $word==strtolower($string)){
		$string=0;
	}
	return $string;
		

}

function clean($string,$word) {
   // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$array= explode("Noun", $string);
	$output_word=array();
	if(!empty($array[1])){
		$output= get_string_word($array[1]);	
		$output = removeSpecialtext($output,$word);
		$output = get_correct_text($output);
		$output_array=explode("\n", $output);
		$z=0;
		$output_word["word"]=$word;
		for($i=0;$i<count($output_array);$i++){
			$output_array[$i]=get_removeExample($output_array[$i],$word);
			if(!empty($output_array[$i])&&$output_array[$i]!=null ){
					$output_word["text"][$z]=$output_array[$i];
					$z=$z+1;
			}

		}	
		return json_encode($output_word,JSON_PRETTY_PRINT); 
	}
	else{
		return "empty";
	}

   // return preg_replace('/[^A-Za-z0-9]/', "/n, $string); // Removes special chars.

   
}


// Create a new wiky to any variable You'd like. Could be $mooming
// If you pass true to __construct (new wiky(true)), "S" PCRE modifier will be added to all regular expressions. This gives a performance boost when running parse thousands of times. Extreme usage only.
$wiky=new wiky;

// Call for the function parse() on the variable You created and pass some unparsed text to it, it will return parsed HTML or false if the content was empty. In this example we are loading the file input.wiki, escaping all html characters with htmlspecialchars, running parse and echoing the output
// $input=file_get_contents("input.wiki");
// $input=htmlspecialchars($input);
$word=/*"stop"*/ $_GET['word'];
$word=strtolower($word);
$input= file_get_contents("https://simple.wiktionary.org/wiki/".$word."?action=edit");
$input= str_replace("{{noun", "{{ example--", $input);
$input= str_replace(":", " : example-- ", $input);
$input= str_replace("*", " * example-- ", $input);
$input= str_replace("File :", "\n", $input);
$input= str_replace("<gallery>", "Gallery ", $input);
$input= str_replace("}}", " example-- }} \n ", $input);
$input= str_replace("{{", "\n {{ example--", $input);
// $input= str_replace("|", "}}{{", $input);
/*$input= str_replace('<p>', '<p> example--', $input);*/
// $input= str_replace('<b>', '<b> example-- ', $input);
/*$input= str_replace('<div class="thumbcaption">','<div class="thumbcaption"> example--', $input);*/
$output=$wiky->parse($input);
$output=strip_tags($output);
// echo $output;
echo clean($output,$word);

?>
