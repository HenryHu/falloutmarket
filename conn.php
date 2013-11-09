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

function db_bind($conn, $stmt, $args) {
    $parsed = oci_parse($conn, $stmt);
    foreach ($args as $key => $value) {
        oci_bind_by_name($parsed, ':' . $key, $value);
    }
    return $parsed;
}

function db_bind_exe($conn, $stmt, $args) {
    $parsed = db_bind($conn, $stmt, $args);
    oci_execute($parsed);
    return $parsed;
}

function db_fetch_row($stmt) {
    return oci_fetch_row($stmt);
}

?>
