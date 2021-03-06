<?php

include_once 'session.php';

verify_session();

include_once 'util.php';
$cid = get_arg('cid');
$gid = get_arg('gid');
$qty = post_arg('qty');

check(is_numeric($qty), 'Invalid quantity', 'buygood.php?id=' . $gid);
check($qty > 0, 'Invalid quantity', 'buygood.php?id=' . $gid);
check($qty == intval($qty), 'Only complete items can be bought.', 'buygood.php?id=' . $gid);

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn,
    'select contracts.gid, contract_left, contracts.price, users.name seller, users.username seller_username, goods.name good_name
        from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < min(contracts.qty)
            union select cid, qty from contracts where cid not in (select fulfill from orders)
        ), contracts, users, goods
        where contracts.userid = users.userid
        and contracts.gid = goods.gid
        and contracts.cid = :cid
        and contracts.cid = fulfill_contract
        and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\')
        and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))',
            array('now' => now(), 'cid' => $cid)
    );

while ($ret = db_fetch_object($stmt)) {
    if ($qty > $ret->CONTRACT_LEFT) {
        echo '<h3>Sorry, not enough product is available.</h3>';
        jump_to('buygood.php?id=' . $ret->GID);
    }
    $gid = $ret->GID;
    $seller = $ret->SELLER;
    $seller_username = $ret->SELLER_USERNAME;
    $good_name = $ret->GOOD_NAME;
    $price = $ret->PRICE;
    $charge = $price * $qty;
}

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
        <h1>Order detail</h1>
</div>
<form action="buyconfirm.php" method="POST">
<?php
echo '<input type="hidden" name="cid" value="' . $cid . '"/>';
echo '<input type="hidden" name="qty" value="' . $qty . '"/>';
?>
<div class="row">
<div class="col-md-6">
<?php
include_once 'info.php';

print_product_info($gid);
?>
</div>
        
</div>
<div class="container">
<div class="col-md-6">
<?php
echo '<p/>';
echo 'Buy ' . $qty . ' ' . $good_name . ' from ' . $seller . '(' . $seller_username . '), ' . $price . ' dollar(s) each.<br/>';
?>

        <h3>Ship to address:</h3>
        <table class="table">
<?php
include_once 'conn.php';

$first = true;
$conn = db_connect();
$stmt = db_bind_exe($conn, 'select address, addrid from addresses where userid = :userid and removed = 0',
    array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td><label class="btn"><input style="margin-right: 20px;" type="radio" name="addrid" value="' . $ret->ADDRID . '"';
    if ($first) {
        echo ' checked="yes"';
        $first = false;
    }
    echo '/>' . $ret->ADDRESS . '</label></td>';
    echo '</tr>';
}

db_close($conn);
?>
        </table>

</div>
<div class="col-md-6">
<?php
echo '<p/>';
echo 'You will be charged for ' . $charge . ' dollar(s).';
?>
        <h3>Charge from account:</h3>
        <table class="table">
<?php

include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select act_number, valid_before, acctid from accounts where userid = :userid and valid_before > TO_DATE(:now, \'YYYYMMDD\') and removed = 0',
    array('userid' => session_userid(), 'now' => now()));

$first = true;
while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td><label class="btn"><input style="margin-right: 20px;" type="radio" name="acctid" value="' . $ret->ACCTID . '"';
    if ($first) {
        echo ' checked="yes"';
        $first = false;
    }
    echo '/>' . $ret->ACT_NUMBER . '</label></td>';
    echo '</tr>';
}

db_close($conn);
?>
        </table>
</div>
</div>
<input type="submit" value="Place order" class="btn btn-primary btn-lg"/>

</form>
</div>
    </body>
</html>
