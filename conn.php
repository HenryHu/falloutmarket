<?php

ini_set('display_errors', 'On');

function db_connect() {
    include 'cred.php';
	$db = "w4111b.cs.columbia.edu:1521/adb";
	$conn = oci_connect($username, $password, $db);
    return $conn;
}

function db_close($conn) {
    oci_close($conn);
}

?>
