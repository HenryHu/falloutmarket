<?php

include_once 'session.php';

verify_session();

include_once 'util.php';
$cid = post_arg('cid');
$qty = post_arg('qty');
$addrid = post_arg('addrid');
$acctid = post_arg('acctid');

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn, 'select contracts.gid, name from contracts, goods where cid = :cid and contracts.gid = goods.gid', array('cid' => $cid));
$ret = db_fetch_object($stmt);
$gid = $ret->GID;
$good_name = $ret->NAME;
$stmt = db_bind_exe($conn,
    'insert into orders (userid, gid, oid, qty, placed, fulfill, shipto, payby) values (:userid, :gid, oid_seq.nextval, :qty, TO_DATE(:placed, \'YYYY-MM-DD\'), :fulfill, :shipto, :payby)',
    array('userid' => session_userid(), 'gid' => $gid, 'qty' => $qty, 'placed' => now(), 'fulfill' => $cid, 'shipto' => $addrid, 'payby' => $acctid));
db_close($conn);

?>

<html>
    <head>
        <title>Confirmation</title>
    </head>
    <body>
        <h1>Confirmation</h1>
        <h3>Purchase succeeded.</h3>
        You just purchased <?php echo $qty; ?> <?php echo $good_name; ?>.
        <h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>


