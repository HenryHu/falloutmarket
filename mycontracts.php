<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Contracts</title>
    </head>
    <body>
        <h1>My Contracts</h1>
        <table>
            <tr> <th> Product </th> <th> Price </th> <th> Quantity </th> <th> Begin date </th> <th> Action </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select goods.name, contracts.gid, contracts.cid, contracts.qty, TO_CHAR(contracts.begin, \'YYYY-MM-DD\') begin, contracts.price from contracts, goods where contracts.userid = :userid and contracts.gid = goods.gid order by cid', array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td>' . $ret->NAME . '</td><td>' . $ret->PRICE . '</td>';
    echo '<td>' . $ret->QTY . '</td><td>' . $ret->BEGIN . '</td>';
    echo '<td><a href="orderdetail.php?id=' . $ret->OID . '">Detail</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>

<h3><a href='newacct.php'>New Account</a></h3>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>

