<?php

include_once 'session.php';

verify_session();

?>

<?php

include_once 'util.php';
if (!isset($_POST['act_number']) || !isset($_POST['valid_before'])) {
?>

<html>
    <head>
        <title>New account</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>Please enter your new account: </h3>
        <form action='newacct.php' method='POST'>
<table class="table">
<tr><th>Account number: </th><td><input type='text' name='act_number'/ class="form-control"></td></tr>
<tr><th>Valid before(YYYY-MM-DD): </th><td><input type='text' name='valid_before' class="form-control"/></td></tr>
</table>
            <input type='submit' value='Add' class="btn btn-primary btn-lg"/>
        </form>
<h3><a href="myaccounts.php">My Accounts</a></h3>
</div>
    </body>
</html>

<?php
} else {
    check(preg_match("/^[0-9]{16}$/", $_POST['act_number']), "Invalid account number", "newacct.php");
    check(valid_date($_POST['valid_before']), "Invalid date", "newacct.php");
    include_once 'conn.php';
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into accounts (userid, acctid, act_number, valid_before) values (:userid, acctid_seq.nextval, :act_number, TO_DATE(:valid_before, \'YYYY-MM-DD\'))', array('userid' => session_userid(), 'act_number' => $_POST['act_number'], 'valid_before' => $_POST['valid_before']));
#    $stmt = db_bind_exe($conn, 'insert into addresses (userid, addrid, address) values (7, addrid_seq.nextval, :addr)', array('addr' => $_POST['addr']));
    db_close($conn);
    echo '<h3>Account added.</h3>';
    jump_to('myaccounts.php');
}

?>

