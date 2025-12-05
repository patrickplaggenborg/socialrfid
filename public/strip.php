<?php
// Disable error display, log errors instead
ini_set('display_errors', 0);
ini_set('log_errors', true);

// Basic rate limiting: allow max 10 requests per minute per IP
session_start();
$rate_limit_key = 'strip_' . md5($_SERVER['REMOTE_ADDR']);
$rate_limit_file = sys_get_temp_dir() . '/' . $rate_limit_key . '.ratelimit';

// Check rate limit
$current_time = time();
$rate_limit_window = 60; // 60 seconds
$max_requests = 10;

if (file_exists($rate_limit_file)) {
	$rate_data = json_decode(file_get_contents($rate_limit_file), true);
	if ($rate_data && isset($rate_data['count']) && isset($rate_data['reset_time'])) {
		if ($current_time < $rate_data['reset_time']) {
			if ($rate_data['count'] >= $max_requests) {
				error_log('Rate limit exceeded for IP: ' . $_SERVER['REMOTE_ADDR']);
				http_response_code(429); // Too Many Requests
				header('Retry-After: ' . ($rate_data['reset_time'] - $current_time));
				exit(0);
			}
			$rate_data['count']++;
		} else {
			// Reset window
			$rate_data = ['count' => 1, 'reset_time' => $current_time + $rate_limit_window];
		}
	} else {
		$rate_data = ['count' => 1, 'reset_time' => $current_time + $rate_limit_window];
	}
} else {
	$rate_data = ['count' => 1, 'reset_time' => $current_time + $rate_limit_window];
}

file_put_contents($rate_limit_file, json_encode($rate_data));

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
