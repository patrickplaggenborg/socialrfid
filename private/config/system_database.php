<?php
// Database configuration using environment variables with fallbacks
$hostname = getenv('DB_HOST') ?: "mariadb-legacy";
$username = getenv('DB_USER') ?: "socialrfid";
$password = getenv('DB_PASSWORD') ?: "placeholder";
$database = getenv('DB_NAME') ?: "socialrfid";

// Initialize connection with SSL support
$conn = mysqli_init();

// Enable SSL certificate verification for security
// Note: If connection fails, ensure proper SSL certificates are configured
// For internal Docker networks, you may need to set MYSQLI_OPT_SSL_VERIFY_SERVER_CERT to false
// and configure certificates properly
$verify_ssl = getenv('DB_VERIFY_SSL') !== 'false';
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, $verify_ssl);
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($conn, $hostname, $username, $password, 
                    $database, 3306, NULL, MYSQLI_CLIENT_SSL);

// Check connection
if (mysqli_connect_errno()) {
    error_log("Database connection failed: " . mysqli_connect_error());
    exit("Database connection failed");
}

// Set charset to utf8mb4 for proper Unicode support
mysqli_set_charset($conn, "utf8mb4");
?>