<?php

include_once 'session.php';

verify_session();

include_once 'util.php';
$cid = post_arg('cid');
$qty = post_arg('qty');
$addrid = post_arg('addrid');
$acctid = post_arg('acctid');

$args = array('addrid' => $addrid, 'acctid' => $acctid, 'userid' => session_userid(), 'qty' => $qty, 'cid' => $cid, 'now' => now());
check_not_empty('select addrid from addresses where addrid = :addrid and userid = :userid', $args);
check_not_empty('select acctid from accounts where acctid = :acctid and userid = :userid and valid_before >= TO_DATE(:now, \'YYYY-MM-DD\')', $args);
check_not_empty('select contracts.cid from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < min(contracts.qty)
            union select cid, qty from contracts where cid not in (select fulfill from orders)
        ), contracts
        where contracts.cid = fulfill_contract
        and contracts.cid = :cid and contract_left >= :qty', $args);
check_not_empty('select cid from contracts where cid = :cid and begin <= TO_DATE(:now, \'YYYY-MM-DD\') and (end is null or end > TO_DATE(:now, \'YYYY-MM-DD\'))', $args);

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn, 'select contracts.gid, name from contracts, goods where cid = :cid and contracts.gid = goods.gid', array('cid' => $cid));
$ret = db_fetch_object($stmt);
$gid = $ret->GID;
$good_name = $ret->NAME;

$args['gid'] = $gid;

check_not_empty('select gid from goods, users where users.userid = :userid and goods.gid = :gid and goods.age_limit <= users.age', $args);

$stmt = db_bind_exe($conn,
    'insert into orders (userid, gid, oid, qty, placed, fulfill, shipto, payby) values (:userid, :gid, oid_seq.nextval, :qty, TO_DATE(:placed, \'YYYY-MM-DD\'), :fulfill, :shipto, :payby)',
    array('userid' => session_userid(), 'gid' => $gid, 'qty' => $qty, 'placed' => now(), 'fulfill' => $cid, 'shipto' => $addrid, 'payby' => $acctid));
db_close($conn);

?>

<html>
    <head>
        <title>Confirmation</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="container">
        <h1>Confirmation</h1>
        <h3>Purchase succeeded.</h3>
        You just purchased <?php echo $qty; ?> <?php echo $good_name; ?>.
        <h3><a href='dashboard.php' class="btn btn-lg btn-primary">Dashboard</a></h3>
        </div>
    </body>
</html>


