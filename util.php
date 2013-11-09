<?php

function jump_to($url) {
    echo '<p/><h3>Please wait...</h3>';
    echo '<script type="text/javascript">
        setTimeout(function() {
            window.location="' . $url . '";
        }, 1000);</script>';
}

function get_arg($key) {
    if (!isset($_GET[$key])) {
        jump_to('dashboard.php');
    }
    return $_GET[$key];
}

?>
