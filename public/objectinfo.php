<?php
// Disable error display, log errors instead
ini_set('display_errors', 0);
ini_set('log_errors', true);

// Set object unique code
if ( ($_GET['object']!='') && (preg_match('/^(thing:)?[a-zA-Z0-9]+$/', $_GET['object'],$match)) )
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

// Make Database connection
require_once('../private/config/system_database.php');

// Get object info using prepared statement
$stmt = mysqli_prepare($conn, "SELECT uc, db FROM srfid_objects WHERE uc = ? LIMIT 1");
if (!$stmt) {
	error_log("Prepare failed: " . mysqli_error($conn));
	http_response_code(500);
	exit(0);
}

mysqli_stmt_bind_param($stmt, "s", $object);
mysqli_stmt_execute($stmt);
$object_result = mysqli_stmt_get_result($stmt);
$object_result_count = mysqli_num_rows($object_result);
if ($object_result_count==1)
{
	// CREATE XML OUTPUT:
		
	// Set MIME Type
	if ($setmime!=1)
	{
		header ("Content-type: application/xml");
	}
	// Set encoding to UTF-8
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
	// get object database and unique code
	$object = mysqli_fetch_array($object_result);
	
	// Validate table name to prevent SQL injection
	$dbname = '';
	if ($object['db']==1)
	{
		// database is EPC Global
		$dbname = 'srfid_epc';
	} else if ($object['db']==2){
		// database is Thinglink
		$dbname = 'srfid_thing';
	}
	
	if (empty($dbname)) {
		error_log('Invalid database type: ' . $object['db']);
		http_response_code(500);
		exit(0);
	}
	
	// Escape table name (whitelist approach)
	$allowed_tables = ['srfid_epc', 'srfid_thing'];
	if (!in_array($dbname, $allowed_tables)) {
		error_log('Invalid table name: ' . $dbname);
		http_response_code(500);
		exit(0);
	}
?>
	<object>
		<db><?=htmlspecialchars($object['db'], ENT_XML1, 'UTF-8');?></db>
<?php
	// get object data using prepared statement
	$stmt2 = mysqli_prepare($conn, "SELECT * FROM `$dbname` WHERE uc = ? LIMIT 1");
	if (!$stmt2) {
		error_log("Prepare failed: " . mysqli_error($conn));
		http_response_code(500);
		exit(0);
	}
	
	mysqli_stmt_bind_param($stmt2, "s", $object['uc']);
	mysqli_stmt_execute($stmt2);
	$objectdata_result = mysqli_stmt_get_result($stmt2);
	$objectdata_result_count = mysqli_num_rows($objectdata_result);

	if ($objectdata_result_count==1)
	{
		// CREATE EXTRA DATA FIELDS XML OUTPUT:
		$objectdata_numfields = mysqli_num_fields($objectdata_result);

	function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
}
		// create xml tags for all fields
		for ($i=0;$i<$objectdata_numfields;$i++)
		{
			$fn = mysqli_fetch_field_direct($objectdata_result, $i);
			$fr = mysqli_result($objectdata_result,0, $i);
			//$fr = mysqli_result($objectdata_result,0,$i);
			if ($fn->name == 'selldate')
			{
				$timestamp = $fr;
			    $year = substr($timestamp, 0, 4);
			    $month = substr($timestamp, 4, 2);
			    $day = substr($timestamp, 6, 2);
			    $hour = substr($timestamp, 8, 2);
			    $min = substr($timestamp, 10, 2);
			    $sec = substr($timestamp, 12, 2);
			    $fr = date("dS \of F Y", mktime($hour, $min, $sec, $month, $day, $year));
			}
			echo '<'.$fn->name.'>'.htmlspecialchars($fr, ENT_XML1, 'UTF-8').'</'.$fn->name.'>';
		}		
	}


	// get object stories using prepared statement
	$stmt3 = mysqli_prepare($conn, "SELECT filename FROM srfid_stories WHERE uc = ?");
	if (!$stmt3) {
		error_log("Prepare failed: " . mysqli_error($conn));
		http_response_code(500);
		exit(0);
	}
	
	mysqli_stmt_bind_param($stmt3, "s", $object['uc']);
	mysqli_stmt_execute($stmt3);
	$story_result = mysqli_stmt_get_result($stmt3);
	$story_result_count = mysqli_num_rows($story_result);

	if ($story_result_count>0)
	{
		// CREATE STORY NODES
		echo '<stories>';

		for ($i=0;$i<$story_result_count;$i++)
		{
			$story = mysqli_fetch_assoc($story_result);
			
			echo '<story>'.htmlspecialchars($story['filename'], ENT_XML1, 'UTF-8').'</story>';			
		}
		echo '</stories>';	}
?>
	</object>
<?php
}
?>