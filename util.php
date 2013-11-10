<?php

function jump_to($url) {
    echo '<p/><h3>Please wait...</h3>';
    echo '<script type="text/javascript">
        setTimeout(function() {
            window.location="' . $url . '";
        }, 1000);</script>';
    exit;
}

function get_arg($key) {
    if (!isset($_GET[$key])) {
        jump_to('dashboard.php');
    }
    return $_GET[$key];
}

function post_arg($key) {
    if (!isset($_POST[$key])) {
        jump_to('dashboard.php');
    }
    return $_POST[$key];
}

function now() {
    return '22780501';
}

function check($cond, $msg, $target) {
    if (!$cond) {
        echo '<h3>' . $msg . '</h3>';
        jump_to($target);
    }
}

?>
