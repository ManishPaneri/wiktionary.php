<?php
/* Wiky.php - A tiny PHP "library" to convert Wiki Markup language to HTML
 * Author: Toni Lähdekorpi <toni@lygon.net>
 *
 * Code usage under any of these licenses:
 * Apache License 2.0, http://www.apache.org/licenses/LICENSE-2.0
 * Mozilla Public License 1.1, http://www.mozilla.org/MPL/1.1/
 * GNU Lesser General Public License 3.0, http://www.gnu.org/licenses/lgpl-3.0.html
 * GNU General Public License 2.0, http://www.gnu.org/licenses/gpl-2.0.html
 * Creative Commons Attribution 3.0 Unported License, http://creativecommons.org/licenses/by/3.0/
 */

// This file demonstrates how to use Wiky.php.

// Include the library (obviously)
header('Content-Type: application/json; charset=utf-8');
require_once("wiky.inc.php");

function get_string_word($string){
	$array=array(
		"Verb[change]",
		"Adjective[change]",
		"Antonyms[change]",
		"Phrases[change]",
		"Related words[change]",		
		"Homophones[change]",
		"Pronunciation[change]",
		"Synonyms[change]",
		"Idioms[change]",
		"See also[change]",
		"Derived words[change]",
		"judges[change]",
		"Verb 1[change]",
		"Note[change]",
		"Related words and phrases[change]",
		"The Periodic Table",
		"Related words",
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

function removeSpecialtext($string){
	$array=array(
		"(countable) ", 
		"(countable) ",
		"(countable &amp; uncountable) ",
		"(singular) ",
		"(uncountable) ",
		"&#160;",
		"&#160;&#160;&#160;&#160; ",
		"(uncountable), (old, no longer used) ",
		"(countable); (art) ",
		"(countable &amp; uncountable) ",
		"(countable), (usually plural) ",
		"(uncountable); (mathematics) ",
		"(usually singular) ",
		"(countable)",
		"(countable &amp; uncountable);",
		"(countable),",
		"(usually plural)",
		"(mathematics and countable) ",
		"&#160;&#160;&#160;&#160;",
		"none",
		"  ",
		"(astronomy)",
		"(mathematics)",
		"displaystyle",
		"(mathematics) ",
		"(no longer used) ",
		"(technical);",
		"(chemistry) ",
		" (uncountable)",
		"(uncountable); (physics) ",
		"(uncountable); "
		);

	for($i=0;$i<count($array);$i++){

		$string = str_replace($array[$i], '', $string);
	}

	return $string;

}
function get_removeExample($string){

	$array= array(
			"example--",
			"example",
			"sign, symbol",
			"{\\",
			"Synonyms:",
			"Antonyms:"

		);

	for($i=0;$i<count($array);$i++){
		if(strpos($string, $array[$i]) !== false){
			$string= 0;	
		}
	}
	
	if(strlen($string)==1){
		$string=0;
	}
	return $string;
		

}

function clean($string,$word) {
   // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$array= explode("Noun[change]", $string);
	$output_word=array();
	if(!empty($array[1])){
		$output= get_string_word($array[1]);	
		$output = removeSpecialtext($output);
		$output = get_correct_text($output);
		$output_array=explode("\n", $output);
		$z=0;
		$output_word["word"]=$word;
		for($i=0;$i<count($output_array);$i++){
			$output_array[$i]=get_removeExample($output_array[$i]);
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
$word=/*"wrong";*/ $_GET['word'];
$word=strtolower($word);
$input= file_get_contents("https://simple.wiktionary.org/wiki/".$word."");
$input= str_replace("<dd><i>", "<dd><i> example--", $input);
$input= str_replace("example", " example--", $input);
$input= str_replace('<p>', '<p> example--', $input);
$input= str_replace('<b>', '<b> example-- ', $input);
$output=$wiky->parse($input);
$output=strip_tags($output);
// echo $output;
echo clean($output,$word);

?>
