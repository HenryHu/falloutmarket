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
    </head>
    <body>
        <h1>Please enter your new address: </h3>
        <form action='newaddr.php' method='POST'>
            Address: <input type='text' name='addr'/>
            <input type='submit' value='Add'/>
        </form>
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
