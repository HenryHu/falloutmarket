<?php

include_once 'conn.php';
include_once 'util.php';
include_once 'session.php';

if (!array_key_exists('username', $_POST) || !array_key_exists('password', $_POST)) {
    echo "Invalid form";
    return;
}

$conn = db_connect();

$chk_user = db_bind_exe($conn,
    'select userid, name from users where username = :username and password = :password',
    array('username' => $_POST['username'], 'password' => $_POST['password']));

$ret = db_fetch_row($chk_user);

if ($ret) {
    echo "<h3>Login OK</h3>";
    session_init($ret[0], $ret[1]);
    jump_to('dashboard.php');
} else {
    echo "<h3>Invalid username or password</h3>";
    echo "<a href='index.php'>Try again</a>";
}

db_close($conn);

?>
