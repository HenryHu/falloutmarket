<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Shopping</title>
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
        <h1>Shopping</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <h3>I want to ...</h3>
        <ul>
            <li><a href='buy.php?mode=viewall'>View the list of products available.</a></li>
            <li>Search for a specific item.
                <form action='buy.php?mode=search' method='POST'>
                    <table class="table"><tr><th>Search for: </th><td><input type='text' name='q' class="form-control" placeholder="Name of the product"/></td>
                    <td><input type='submit' value='Search' class="btn btn-primary"/></td></tr></table>
                </form>
        </ul>
<?php

include_once 'util.php';
include_once 'conn.php';

if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
    if ($mode == 'viewall') {
        $conn = db_connect();
        $stmt = db_bind_exe($conn, 
            'select goods.name, goods.gid, available, min_price, avg_rating from (
    select contracts.gid good_id, sum(contract_left) available, min(contracts.price) min_price
        from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < min(contracts.qty)
            union select cid, qty from contracts where cid not in (select fulfill from orders)
        ), contracts
        where contracts.cid = fulfill_contract and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\') and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))
        group by contracts.gid
    ), (
        select goods.gid all_cmt_good_id, avg_rating
        from goods
        left outer join (
            select comments.gid cmt_good_id, avg(comments.score) avg_rating from comments
                group by comments.gid
        ) on goods.gid = cmt_good_id
    ), goods, users
    where goods.gid = good_id and goods.gid = all_cmt_good_id and goods.age_limit <= users.age and users.userid = :userid', array('userid' => session_userid(), 'now' => now()));
    } else if ($mode == 'search') {
        $query = post_arg('q');
        $conn = db_connect();
        $stmt = db_bind_exe($conn, 
            'select goods.name, goods.gid, available, min_price, avg_rating from (
    select contracts.gid good_id, sum(contract_left) available, min(contracts.price) min_price
        from (
            select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < min(contracts.qty)
            union select cid, qty from contracts where cid not in (select fulfill from orders)
        ), contracts
        where contracts.cid = fulfill_contract and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\') and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))
        group by contracts.gid
    ), (
        select goods.gid all_cmt_good_id, avg_rating
        from goods
        left outer join (
            select comments.gid cmt_good_id, avg(comments.score) avg_rating from comments
                group by comments.gid
        ) on goods.gid = cmt_good_id
    ), goods, users
    where goods.gid = good_id and goods.gid = all_cmt_good_id and goods.age_limit <= users.age and users.userid = :userid and UPPER(goods.name) like UPPER(:query)', array('userid' => session_userid(), 'now' => now(), 'query' => '%' . $query . '%'));

    }
?>
<div class="col-md-12">
        <table class="table">
            <tr><th>Product</th><th>Available</th><th>Minimal Price</th><th>Average Rating</th><th></th></tr>
<?php
    while ($ret = db_fetch_object($stmt)) {
        $avg_rating = 'N/A';
        if ($ret->AVG_RATING != 0) {
            $avg_rating = rating_stars($ret->AVG_RATING);
        }
        echo '<tr><td><a href="goodinfo.php?gid=' . $ret->GID . '">' . $ret->NAME . '</a></td><td>' . $ret->AVAILABLE . '</td>';
    echo '<td>' . $ret->MIN_PRICE . '</td><td>' . $avg_rating . '</td>';
        echo '<td><a href=\'buygood.php?id=' . $ret->GID . '\' class="btn btn-default">Buy</a></td>';
        echo '</tr>';
    }

        db_close($conn);

?>
        </table>
</div>
</div>
<?php
}
?>
</div>
</div>
    </body>
</html>
