<?php
// Set object unique code
if ( ($_GET['object']!='') && (preg_match('/^(thing:)?[a-zA-Z0-9]+$/', $_GET['object'],$match)) )
{
	$object = $_GET['object'];
}
else
{
	unset($object);
}
// Make Database connection
require_once('../private/config/system_database.php');
// Get object info
// get categories
$object_result = mysqli_query($conn,"
					SELECT uc,db
					FROM srfid_objects
					WHERE uc='".$object."'
					LIMIT 1
					");
$object_result_count = mysqli_num_rows($object_result);
if ($object_result_count==1)
{
	// CREATE XML OUTPUT:
		
	// Set MIME Type
	if ($setmime!=1)
	{
		header ("Content-type: application/xml");
	}
	// Set encoding
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	
	// get object database and unique code
	$object = mysqli_fetch_array($object_result);
	
	// set id database
	if ($object['db']==1)
	{
		// database is EPC Global
		$dbname = 'srfid_epc';
	} else if ($object['db']==2){
		// database is Thinglink
		$dbname = 'srfid_thing';
	}
?>
	<object>
		<db><?=$object['db'];?></db>
<?php
	// get object data
	$objectdata_result = mysqli_query($conn,"
		SELECT *
		FROM ".$dbname."
		WHERE uc='".$object['uc']."'
		LIMIT 1
		");
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
			echo '<'.$fn->name.'>'.$fr.'</'.$fn->name.'>';
		}		
	}


	// get object stories
	$story_result = mysqli_query($conn,"
		SELECT filename
		FROM srfid_stories
		WHERE uc='".$object['uc']."'
		");
	$story_result_count = mysqli_num_rows($story_result);

	if ($story_result_count>0)
	{
		// CREATE STORY NODES
		echo '<stories>';

		for ($i=0;$i<$story_result_count;$i++)
		{
			$story = mysqli_fetch_assoc($story_result);
			
			echo '<story>'.$story[filename].'</story>';			
		}
		echo '</stories>';	}
?>
	</object>
<?php
}
?>