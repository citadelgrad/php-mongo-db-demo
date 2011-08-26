<html>
<head>
	<title>Learn PHP & MongoDB</title>
<link rel="stylesheet" href="http://css.happyherbivore.com/960test/960.css" />

<style>
p.tweets {
	 overflow: hidden;
	 border-bottom: 3px solid orange;
	 background: #666;
	 padding: 5px;
	 border-radius: 3px;
	 color: white;
    }
p.tweets span {float:right;}

p.db-docs {
	 overflow: hidden;
	 border-bottom: 3px solid orange;
	 background: #000;
	 padding: 5px;
	 border-radius: 3px;
	 color: white;
    }
p.db-docs span {float:right;}

</style>

</head>
<body>
<div class="container_12">
     <div class="grid_6">

     <h2>MongoDB records</h2>
<?php
ini_set ('display_errors', 1);

error_reporting (E_ALL | E_STRICT);

//$your_input = '';
//$your_input = $_POST['input'];
?>

<?php
if ($_POST) {

   try {
	// open connection to MongoDB server
	$conn = new Mongo('localhost');

	// access database
	$db = $conn->test;

	// access collection
	$collection = $db->items;

	// Get twitter objects from static file
	$lines = file_get_contents('stream.json');
	$grp = json_decode($lines);
	
	// get a single tweet from a json doc
	$tweetdoc = $grp[$_POST['tweetitem']];

	$collection->insert($tweetdoc);	

	$conn->close();
   } catch (MongoConectionException$e) {
	die('Error connecting to MongoDB server');
   } catch (MongoException$e) {
	die('Error: ' .$e->getMessage());
  }

}  

?>

<?php
try {
	// open connection to MongoDB server
	$conn = new Mongo('localhost');

	// access database
	$db = $conn->test;

	// access collection
	$collection = $db->items;

	// execute query to retrieve all documents
	$cursor = $collection->find();

	echo $cursor -> count() .' document(s) found. <br>';
	foreach ($cursor as $obj) {
		echo "<p class='db-docs'><span" .$obj['user']['screen_name'];
		echo "<img src='" .$obj['user']['profile_image_url'] ."'></span>";
		echo $obj['text'] ."</p>";
	}

	$conn->close();
} catch (MongoConectionException$e) {
	die('Error connecting to MongoDB server');
} catch (MongoException$e) {
	die('Error: ' .$e->getMessage());
}

?>
	</div>	

<div class="grid_6">
 <?php
// Great starting reference for this example.
// http://webhole.net/2009/08/31/how-to-read-json-data-with-php/

// Make a string object
$lines = file_get_contents('stream.json');


// Make an object from a json string
$grp = json_decode($lines);

/* type debug */
//echo "Type of \$lines: " .gettype($lines) ."<br>";
//echo "Type of \$grp: " .gettype($grp) ."<br>";
//echo "type of \$string: " .gettype($string) ."<br><br>";
?>

<h2>Twitter Stream for <?php echo $grp[0]->user->screen_name; ?></h2>

<!--
<p>
<h4>Get twitter stream by username</h4>
<form action="." method="POST">
<input name="input" type="text">
<input type="submit">
</form>
</p>
 -->

<?php

// Display Twitter Stream for a based on USERNAME
foreach ($grp as $key => $json) {
	echo "<p class='tweets'><span>" .$json->user->screen_name;
	echo "<img src='" .$json->user->profile_image_url ."'></span>";
	echo $json->text; 
	echo "<form action='.' method=POST>";
	echo "<input name='tweetitem' value='$key' type='hidden'>";
	echo "<input type='submit' value='<-- Add to Database'></form></p>";
}

// source: http://de.php.net/json_last_error
switch (json_last_error()) {
        case JSON_ERROR_NONE:
            //echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

?>
</div>

</div> <!-- end Container_12 -->

</body>
</html>
