<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Accounts</title>
    </head>
    <body>
        <h1>Account list</h1>
        <h3>Your accounts: </h3>
        <table>
            <tr> <th> Account </th> <th> Valid before </th> <th> Action </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select acctid, act_number, TO_CHAR(valid_before, \'YYYY-MM-DD\') valid_before from accounts where userid = :userid and removed = 0 order by acctid', array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td>' . $ret->ACT_NUMBER . '</td><td>' . $ret->VALID_BEFORE . '</td>';
    echo '<td><a href="removeacct.php?id=' . $ret->ACCTID . '">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>

<h3><a href='newacct.php'>New Account</a></h3>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>
