<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Addressbook</title>
    </head>
    <body>
        <h1>Addressbook</title>
        <h3>Your addresses: </h3>
        <table>
            <tr> <th> ID </th> <th> Address </th> <th> Action </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select addrid, address from addresses where userid = :userid and removed = 0', array('userid' => session_userid()));

while ($ret = db_fetch_row($stmt)) {
    echo '<tr><td>' . $ret[0] . '</td><td>' . $ret[1] . '</td>';
    echo '<td><a href="removeaddr.php?id=' . $ret[0] . '">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>
    </body>
</html>
