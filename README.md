# wiktionary.php

Wiktionary.php is a PHP "library" to convert Simple wiktionary HTML page convert to text and findout the definition of word.
MySQl Database connection to stored a definition of each word, select.php?word="<word>" to fetch data from database,display on json format and database can't fetch data of word internal call to simple wiktionary website to fetch the data to be stored in MySQL database and display to json format.

## Supported Syntax
* == Heading ==
* === Subheading ===
* ==== Subsubheading ====
* ''''' Bold-italic '''''
* ''' Bold '''
* '' Italic ''
* ---- Horizontal Line
* : Indentation
* :: Subindentation
* * Unordered list (up to four levels "**** text")
* # Ordered list (up to four levels "#### text")
* [[file:http://example.com/image.jpg title]] an image ([[file|img:http|https|ftp://example.com/image.jpg optional]])
* [http://example.com An Example Link] a link ([http|https|ftp://example.com optional])
* #: example of word 


## Howto use
	// Include the library (obviously)
	require_once("wiky.inc.php");
	
	// Create a new wiky to any variable You'd like. Could be $mooming
	// If you pass true to __construct (new wiky(true)), "S" PCRE modifier will be added to all regular expressions. This gives a performance boost when running parse thousands of times. Extreme usage only.
	$wiky=new wiky;
	
	// Call for the function parse() on the variable You created and pass some unparsed text to it, it will return parsed HTML or false if the content was empty. In this example we are loading the file input.wiki, escaping all html characters with htmlspecialchars, running parse and echoing the output
	$input=file_get_contents("input.wiki");
	$input=htmlspecialchars($input);
	echo $wiky->parse($input);

## Command Line to list of data stored in database 
	// word_list.list are many number of word

	$ php word_select.php > log.json

	$ sudo vi word_list.list

	// Output :
	[
		{
		    "word": "addition",
		    "text": [
		        "Addition",
		        "The part of arithmetic where numbers are added together.",
		        "The act of adding something.",
		        "Anything that is added."
		    ]
		}
	]	




## Author
Manish Kumar Paner
