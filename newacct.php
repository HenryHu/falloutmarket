<?php

include_once 'session.php';

verify_session();

?>

<?php

if (!isset($_POST['act_number']) || !isset($_POST['valid_before'])) {
?>

<html>
    <head>
        <title>New account</title>
    </head>
    <body>
        <h1>Please enter your new account: </h3>
        <form action='newacct.php' method='POST'>
<table>
<tr><th>Account number: </th><td><input type='text' name='act_number'/></td></tr>
<tr><th>Valid before(YYYY-MM-DD): </th><td><input type='text' name='valid_before'/></td></tr>
</table>
            <input type='submit' value='Add'/>
        </form>
    </body>
</html>

<?php
} else {
    include_once 'conn.php';
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into accounts (userid, acctid, act_number, valid_before) values (:userid, acctid_seq.nextval, :act_number, TO_DATE(:valid_before, \'YYYY-MM-DD\'))', array('userid' => session_userid(), 'act_number' => $_POST['act_number'], 'valid_before' => $_POST['valid_before']));
#    $stmt = db_bind_exe($conn, 'insert into addresses (userid, addrid, address) values (7, addrid_seq.nextval, :addr)', array('addr' => $_POST['addr']));
    db_close($conn);
    echo '<h3>Account added.</h3>';
    jump_to('myaccounts.php');
}

?>

