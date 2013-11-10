<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Shopping</title>
    </head>
    <body>
        <h1>Shopping</h1>
        <h3>I want to ...</h3>
        <ul>
            <li><a href='buy.php?mode=viewall'>View the list of products available.</a></li>
            <li>Search for a specific item.
                <form action='buy.php?mode=search' method='POST'>
                    Search for: <input type='text' name='q' />
                    <input type='submit' value='Search' />
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
            select orders.fulfill fulfill_contract, sum(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < sum(contracts.qty)
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
            select orders.fulfill fulfill_contract, sum(contracts.qty) - sum(orders.qty) contract_left
                from orders, contracts
                where orders.fulfill = contracts.cid
                group by orders.fulfill
                having sum(orders.qty) < sum(contracts.qty)
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
        <table border="1">
            <tr><th>Product</th><th>Available</th><th>Minimal Price</th><th>Average Rating</th><th></th></tr>
<?php
    while ($ret = db_fetch_object($stmt)) {
        echo '<tr><td>' . $ret->NAME . '</td><td>' . $ret->AVAILABLE . '</td>';
    echo '<td>' . $ret->MIN_PRICE . '</td><td>' . $ret->AVG_RATING . '</td>';
        echo '<td><a href=\'buygood.php?id=' . $ret->GID . '\'>Buy</a></td>';
        echo '</tr>';
    }

        db_close($conn);

?>
        </table>
<?php
}
?>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>
