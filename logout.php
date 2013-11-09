<?php

include_once 'session.php';

verify_session();

session_destroy();

jump_to('index.php');

?>


