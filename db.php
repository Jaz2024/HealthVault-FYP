<?php
// Establish a new connection to the database
$con = new mysqli("localhost", "root", "", "healthvault");

// Check the connection
if ($con->connect_error) {
    // If the connection fails, stop the script and display an error message
    die("Connection failed: " . $con->connect_error);
}

// Return the connection object so it can be used in other files
return $con;
