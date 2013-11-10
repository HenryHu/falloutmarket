<?php

include_once 'session.php';

verify_session();

$gid = get_arg('id');

?>

<html>
    <head>
        <title>Buy product</title>
    </head>
    <body>
        <h1>Purchase</h1>
<?php
include_once 'info.php';

print_product_info($gid);
?>
        <h3>This product is available from:</h3>
        <table>
            <tr><th>Seller</th><th>Price</th><th>Available</th><th></th></tr>
<?php

include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn,
    'select contracts.cid, contract_left, contracts.price, users.name, users.username
        from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < sum(contracts.qty)
        ), contracts, users
        where contracts.gid = :gid and contracts.userid = users.userid
        and contracts.cid = fulfill_contract
        and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\')
        and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))',
            array('now' => now(), 'gid' => $gid)
    );

while ($ret = db_fetch_object($stmt)) {
    echo '<tr>';
    echo '<td>' . $ret->NAME . '(' . $ret->USERNAME . ')</td>';
    echo '<td>' . $ret->PRICE . '</td>';
    echo '<td>' . $ret->CONTRACT_LEFT . '</td>';
    echo '<td><form action="buydetail.php?cid=' . $ret->CID . '&gid=' . $gid . '" method="POST"><input type="text" name="qty" value="1"/><input type="submit" value="Buy"/></form></td>';
    echo '</tr>';
}
?>
        </table>
    </body>
</html>
