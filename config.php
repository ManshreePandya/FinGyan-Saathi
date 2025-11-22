<?php

// Database connection parameters
$db_host = "localhost";
$db_user = "root";
$db_pass = "1234i"; // Ensure this password is correct for your MySQL environment
$db_name = "Fingyaan_sathi";

// Attempt to create a PDO connection
try {    
    // Create PDO connection object
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    
    // Optional: Set PDO attributes for error mode (highly recommended)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Show error if connection fails and immediately stop the script
    die("Not Available: " . $e->getMessage());
}

?>
