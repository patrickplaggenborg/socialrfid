<?php

// get object unique code
if ( ($_GET['object']!='') && (preg_match('/^(thing:)?[a-zA-Z0-9]+$/', $_GET['object'])) )
{
	$object = $_GET['object'];
}
else
{
	unset($object);
}

// new string for valid filename
$validname = '';

// length of existing string
$sl = strlen($object);

// go through all characters and check them
for ($i=0;$i<$sl;$i++)
{
	if (ereg ("[a-zA-Z0-9]", $object{$i},$regs))
	{
		// valid alphanumeric character so add to filename string
		$validname .= $object{$i};
	}
}

$validname = strtolower($validname).'_'.mktime();


// Make Database connection
require_once('../private/config/system_database.php');

// add story to database

// get categories
if ($object_result = mysqli_query($conn,"
							INSERT INTO `srfid_stories`
								( `id` , `uc` , `filename` )
							VALUES
								(NULL , '".$object."', '".$validname."')
							"))
{
	header ("Content-type: text/plain");
	echo $validname; 

}

?>
