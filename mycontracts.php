<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Contracts</title>
    </head>
    <body>
        <h1>My Current Contracts</h1>
        <table>
            <tr> <th> Product </th> <th> Price </th> <th> Quantity </th> <th> Begin </th> <th> End </th> <th> Sold </th> <th> Action </th> </tr>
<?php
include_once 'conn.php';
include_once 'util.php';

$conn = db_connect();
$stmt = db_bind_exe($conn,
    'select name, gid, cid, qty, begin, end, price, NVL(sold, 0) sold from (
        select goods.name name, contracts.gid gid, contracts.cid cid, contracts.qty qty, TO_CHAR(contracts.begin, \'YYYY-MM-DD\') begin, TO_CHAR(contracts.end, \'YYYY-MM-DD\') end, contracts.price price
        from contracts, goods
        where contracts.userid = :userid and contracts.gid = goods.gid
        and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))
        order by cid
    )
    left join (select sum(qty) sold, fulfill sold_cid from orders group by fulfill)
    on cid = sold_cid

    ', array('userid' => session_userid(), 'now' => now()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td><a href="goodinfo.php?gid=' . $ret->GID . '">' . $ret->NAME . '</a></td><td>' . $ret->PRICE . '</td>';
    echo '<td>' . $ret->QTY . '</td><td>' . $ret->BEGIN . '</td>';
    $end = '';
    if ($ret->END)
        $end = $ret->END;
    else
        $end = 'never';
    echo '<td>' . $end . '</td><td>' . $ret->SOLD . '</td>';
    echo '<td><a href="contractdetail.php?id=' . $ret->CID . '">Detail</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>

<h3><a href='pastcontracts.php'>Past Contracts</a></h3>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>

