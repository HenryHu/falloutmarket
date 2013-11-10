<?php

include_once 'session.php';

verify_session();

$gid = get_arg('id');

?>

<html>
    <head>
        <title>Buy product</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
<div class="col-md-12">
        <h1>Purchase</h1>
</div>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='buy.php'>Continue shopping</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
<div class="container">
<div class="col-md-10">
<div class="col-md-6">
<?php
include_once 'info.php';

print_product_info($gid);
?>
</div>
</div>
<div class="col-md-10">
        <h3>This product is available from:</h3>
        <table class="table">
            <tr><th>Seller</th><th>Price</th><th>Available</th><th></th></tr>
<?php

include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn,
    'select contracts.userid, contracts.cid, contract_left, contracts.price, users.name, users.username
        from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < sum(contracts.qty)
            union select cid, qty from contracts where cid not in (select fulfill from orders)
        ), contracts, users
        where contracts.gid = :gid and contracts.userid = users.userid
        and contracts.cid = fulfill_contract
        and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\')
        and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))',
            array('now' => now(), 'gid' => $gid)
    );

while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td><a href="sellerinfo.php?uid=' . $ret->USERID . '">' . $ret->NAME . '(' . $ret->USERNAME . ')</a></td>';
    echo '<td>' . $ret->PRICE . '</td>';
    echo '<td>' . $ret->CONTRACT_LEFT . '</td>';
    echo '<td><form class="form-inline" action="buydetail.php?cid=' . $ret->CID . '&gid=' . $gid . '" method="POST"><div class="form-group"><input type="text" name="qty" value="1" class="form-control" placeholder="Quantity"/></div><input type="submit" value="Buy" class="btn btn-default"/></form></td>';
    echo '</tr>';
}
?>
        </table>
</div>
</div>
</div>
</div>
    </body>
</html>
