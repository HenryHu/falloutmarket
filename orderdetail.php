<?php

include_once 'session.php';
include_once 'util.php';

verify_session();

$oid = get_arg('id');

?>

<html>
    <head>
        <title>Order Detail</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="container">
        <h1>Order Detail</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='myorders.php'>My Orders</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <table class="table">


<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select goods.name, orders.gid, orders.qty, TO_CHAR(placed, \'YYYY-MM-DD\') placed, fulfill, contracts.price, users.name seller, users.username seller_username, users.userid seller_uid, address, act_number from orders, goods, contracts, users, accounts, addresses where orders.userid = :userid and orders.gid = goods.gid and contracts.cid = fulfill and contracts.userid = users.userid and orders.oid = :oid and accounts.acctid = orders.payby and addresses.addrid = orders.shipto', array('userid' => session_userid(), 'oid' => $oid));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><th>Product</th><td><a href="goodinfo.php?gid=' . $ret->GID . '">' . $ret->NAME . '</a></td></tr>';
    echo '<tr><th>Seller</th><td><a href="sellerinfo.php?uid=' . $ret->SELLER_UID . '">' . $ret->SELLER .
        '(' . $ret->SELLER_USERNAME . ')</a></td></tr>';
    echo '<tr><th>Price</th><td>' . $ret->PRICE . '</td></tr>';
    echo '<tr><th>Quantity</th><td>' . $ret->QTY . '</td></tr>';
    echo '<tr><th>Date</th><td>' . $ret->PLACED . '</td></tr>';
    echo '<tr><th>Shipping</th><td>' . $ret->ADDRESS . '</td></tr>';
    echo '<tr><th>Payment</th><td>' . $ret->ACT_NUMBER . '</td></tr>';
}

db_close($conn);

?>

</table>
</div>
</div>
</body>
</html>
