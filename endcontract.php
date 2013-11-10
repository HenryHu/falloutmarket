<?php

include 'session.php';

verify_session();

$cid = get_arg('cid');

include_once 'conn.php';

$conn = db_connect();

$chkstmt = db_bind_exe($conn, 'select begin from contracts where cid = :cid and begin <= TO_DATE(:now, \'YYYYMMDD\') and userid = :userid',
    array('now' => now(), 'cid' => $cid, 'userid' => session_userid()));
check(db_fetch_object($chkstmt), "Can't end this contract now, try later.", "mycontracts.php");

include_once 'util.php';
$stmt = db_bind_exe($conn, 'update contracts set end = TO_DATE(:now, \'YYYYMMDD\') where cid = :cid and userid = :userid',
    array('now' => now(), 'cid' => $cid, 'userid' => session_userid()));

db_close($conn);

echo '<h3>Contract terminated.</h3>';
jump_to('mycontracts.php');

?>


