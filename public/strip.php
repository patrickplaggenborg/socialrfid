<?php
// Disable error display, log errors instead
ini_set('display_errors', 0);
ini_set('log_errors', true);

// get object unique code
if ( ($_GET['object']!='') && (preg_match('/^(thing:)?[a-zA-Z0-9]+$/', $_GET['object'])) )
{
	$object = $_GET['object'];
}
else
{
	unset($object);
}

// Exit early if no valid object code
if (!isset($object)) {
	error_log('Invalid or missing object parameter');
	http_response_code(400);
	exit(0);
}

// new string for valid filename
$validname = '';

// length of existing string
$sl = strlen($object);

// go through all characters and check them (using modern preg_match instead of deprecated ereg)
for ($i=0;$i<$sl;$i++)
{
	if (preg_match("/[a-zA-Z0-9]/", $object[$i]))
	{
		// valid alphanumeric character so add to filename string
		$validname .= $object[$i];
	}
}

$validname = strtolower($validname).'_'.mktime();

// Make Database connection
require_once('../private/config/system_database.php');

// add story to database using prepared statement
$stmt = mysqli_prepare($conn, "INSERT INTO `srfid_stories` (`id`, `uc`, `filename`) VALUES (NULL, ?, ?)");
if (!$stmt) {
	error_log("Prepare failed: " . mysqli_error($conn));
	http_response_code(500);
	exit(0);
}

mysqli_stmt_bind_param($stmt, "ss", $object, $validname);
if (mysqli_stmt_execute($stmt)) {
	header ("Content-type: text/plain");
	echo $validname;
} else {
	error_log("Insert failed: " . mysqli_error($conn));
	http_response_code(500);
	exit(0);
}

mysqli_stmt_close($stmt);
?>
