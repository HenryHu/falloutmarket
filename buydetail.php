<?php

include_once 'session.php';

verify_session();

include_once 'util.php';
$cid = get_arg('cid');
$gid = get_arg('gid');
$qty = post_arg('qty');

check($qty > 0, 'Invalid quantity', 'buygood.php?id=' . $gid);

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
    </head>
    <body>
        <h1>Order detail</h1>
<form action="buyconfirm.php" method="POST">
<?php
echo '<input type="hidden" name="cid" value="' . $cid . '"/>';
echo '<input type="hidden" name="qty" value="' . $qty . '"/>';
?>
<?php
include_once 'info.php';

print_product_info($gid);
?>
        
<?php
echo '<p/>';
echo 'Buy ' . $qty . ' ' . $good_name . ' from ' . $seller . '(' . $seller_username . '), ' . $price . ' dollar(s) each.<br/>';
?>

        <h3>Ship to:</h3>
        <table>
            <tr><th></th><th>Address</th></tr>
<?php

include_once 'conn.php';

$first = true;
$conn = db_connect();
$stmt = db_bind_exe($conn, 'select address, addrid from addresses where userid = :userid and removed = 0',
    array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td><input type="radio" name="addrid" value="' . $ret->ADDRID . '"';
    if ($first) {
        echo ' checked="yes"';
        $first = false;
    }
    echo '/></td>';
    echo '<td>' . $ret->ADDRESS . '</td>';
    echo '</tr>';
}

db_close($conn);
?>
        </table>

<?php
echo '<p/>';
echo 'You will be charged for ' . $charge . ' dollar(s).';
?>
        <h3>Charge from:</h3>
        <table>
            <tr><th></th><th>Account</th></tr>
<?php

include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select act_number, valid_before, acctid from accounts where userid = :userid and valid_before > TO_DATE(:now, \'YYYYMMDD\') and removed = 0',
    array('userid' => session_userid(), 'now' => now()));

$first = true;
while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td><input type="radio" name="acctid" value="' . $ret->ACCTID . '"';
    if ($first) {
        echo ' checked="yes"';
        $first = false;
    }
    echo '/></td>';
    echo '<td>' . $ret->ACT_NUMBER . '</td>';
    echo '</tr>';
}

db_close($conn);
?>
        </table>
<input type="submit" value="Place order"/>

</form>
    </body>
</html>
