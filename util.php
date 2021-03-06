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
        echo '<h3>Missing argument "' . $key . '"</h3>';
        jump_to('dashboard.php');
    }
    return $_GET[$key];
}

function post_arg($key) {
    if (!isset($_POST[$key])) {
        echo '<h3>Missing argument "' . $key . '"</h3>';
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

function check_not_empty($stmt, $args) {
    if (!is_not_empty($stmt, $args)) {
        echo '<h3>Invalid arguments!</h3>';
        jump_to('dashboard.php');
    }
}

include_once 'conn.php';

function is_not_empty($stmt, $args) {
    $conn = db_connect();
    $res = db_bind_exe($conn, $stmt, $args);
    while ($ret = db_fetch_object($res)) {
        db_close($conn);
        return true;
    }
    db_close($conn);
    return false;
}

function rating_stars($score) {
    return
        '<div style="display: inline-block;">' .
        '<div style="background-size: auto 100%; display:block; width: 80; height: 16px; background-image: url(img/5nstar.png)";>' .
        '<div style="background-size: auto 100%; display: block; background-image: url(img/5star.png); height: 16px; width: ' . ($score * 20) . '%;">' .
        '</div></div></div>';
/*    return str_repeat('<img src="img/star.png" width=16 height=16/>', $score) .
    str_repeat('<img src="img/nstar.png" width=16 height=16/>', 5-$score);*/
}

function valid_date($str) {
    return preg_match("/^[0-9]{4}-(0[0-9]|1[0-2])-[0-3][0-9]$/", $str);
}

?>
