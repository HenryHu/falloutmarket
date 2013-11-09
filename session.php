<?php
ini_set('display_errors', 'On');

include_once 'util.php';

session_start();

function verify_session() {
    if (!isset($_SESSION['userid']) ||
        !isset($_SESSION['name'])) {
        jump_to('index.php');
        exit;
    }
}

function session_init($userid, $name) {
    $_SESSION['userid'] = $userid;
    $_SESSION['name'] = $name;
}

function session_user() {
    return $_SESSION['name'];
}

function session_userid() {
    return $_SESSION['userid'];
}

?>
