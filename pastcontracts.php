<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Contracts</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>My Past Contracts</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='mycontracts.php'>Current Contracts</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <table class="table">
            <tr> <th> Product </th> <th> Price </th> <th> Quantity </th> <th> Begin </th> <th> End </th> <th> Sold </th> <th> Left </th> <th> </th> </tr>
<?php
include_once 'conn.php';
include_once 'util.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select goods.name, contracts.gid, contracts.cid, contracts.qty, TO_CHAR(contracts.begin, \'YYYY-MM-DD\') begin, TO_CHAR(contracts.end, \'YYYY-MM-DD\') end, contracts.price, sold from contracts, goods, (select sum(qty) sold, fulfill sold_cid from orders group by fulfill)  where contracts.userid = :userid and contracts.gid = goods.gid and contracts.cid = sold_cid and (contracts.end is not null and contracts.end <= TO_DATE(:now, \'YYYYMMDD\')) order by cid', array('userid' => session_userid(), 'now' => now()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td>' . $ret->NAME . '</td><td>' . $ret->PRICE . '</td>';
    echo '<td>' . $ret->QTY . '</td><td>' . $ret->BEGIN . '</td>';
    $end = '';
    if ($ret->END)
        $end = $ret->END;
    else
        $end = 'never';
    echo '<td>' . $end . '</td><td>' . $ret->SOLD . '</td>';
    echo '<td>' . ($ret->QTY - $ret->SOLD) . '</td>';
    echo '<td><a href="contractdetail.php?id=' . $ret->CID . '" class="btn">Detail</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>
</div>
</div>
    </body>
</html>

