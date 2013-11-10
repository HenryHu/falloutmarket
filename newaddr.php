<?php

include_once 'session.php';

verify_session();

?>

<?php

if (!isset($_POST['addr'])) {
?>

<html>
    <head>
        <title>New address</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>Please enter your new address: </h3>
        <form action='newaddr.php' method='POST'>
<table class="table">
<tr><th>            Address: </th><td><input type='text' name='addr' class="form-control"/></td></tr>
</table>
            <input type='submit' value='Add' class="btn btn-lg btn-primary"/>
        </form>
<h3><a href='addressbook.php'>Addressesbook</a></h3>
</div>
    </body>
</html>

<?php
} else {
    include_once 'conn.php';
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into addresses (userid, addrid, address) values (:userid, addrid_seq.nextval, :addr)', array('userid' => session_userid(), 'addr' => $_POST['addr']));
#    $stmt = db_bind_exe($conn, 'insert into addresses (userid, addrid, address) values (7, addrid_seq.nextval, :addr)', array('addr' => $_POST['addr']));
    db_close($conn);
    echo '<h3>Address added.</h3>';
    jump_to('addressbook.php');
}

?>
