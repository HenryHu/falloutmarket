<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Orders</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>Orders</h1>
        <table class="table">
            <tr> <th> Product </th> <th> Seller </th> <th> Price </th> <th> Quantity </th> <th> Date </th> <th> </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select goods.name, orders.gid, orders.oid, orders.qty, TO_CHAR(placed, \'YYYY-MM-DD\') placed, fulfill, contracts.price, users.userid seller_uid, users.name seller from orders, goods, contracts, users where orders.userid = :userid and orders.gid = goods.gid and contracts.cid = fulfill and contracts.userid = users.userid order by oid', array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td><a href="goodinfo.php?gid=' . $ret->GID . '">' . $ret->NAME . '</a></td><td><a href="sellerinfo.php?uid=' . $ret->SELLER_UID . '">' . $ret->SELLER . '</a></td>';
    echo '<td>' . $ret->PRICE . '</td><td>' . $ret->QTY . '</td>';
    echo '<td>' . $ret->PLACED . '</td>';
    echo '<td><a href="orderdetail.php?id=' . $ret->OID . '" class="btn btn-default">Detail</a>';
    echo '<a href="newcmt.php?gid=' . $ret->GID . '" class="btn btn-default">Comment</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>

<h3><a href='dashboard.php'>Dashboard</a></h3>
</div>
    </body>
</html>
