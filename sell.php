<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Selling</title>
    </head>
    <body>
        <h1>Selling</h1>
        <h3>I want to ...</h3>
        <ul>
            <li><a href='sell.php?mode=viewall'>View the list of products to sell</a></li>
            <li>Search for a specific item to sell.
                <form action='sell.php?mode=search' method='POST'>
                    Search for: <input type='text' name='q' />
                    <input type='submit' value='Search' />
                </form>
            <li><a href='newgood.php'>Sell something new</a></li>
        </ul>
<?php

include_once 'util.php';
include_once 'conn.php';

if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
    if ($mode == 'viewall') {
        $conn = db_connect();
        $stmt = db_bind_exe($conn, 
        'select aname, agid, age_limit, available, min_price, avg_rating from (
            select goods.name aname, goods.gid agid, goods.age_limit age_limit, available, min_price
            from goods
            left outer join (
                select contracts.gid good_id, sum(contract_left) available, min(contracts.price) min_price from (
                    select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                        from orders, contracts
                        where orders.fulfill = contracts.cid
                        group by orders.fulfill
                ), contracts
                where contracts.cid = fulfill_contract and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\') and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))
                group by contracts.gid
            )
            on goods.gid = good_id
        )
        left outer join  (
            select goods.gid all_cmt_good_id, avg_rating
            from goods
            left outer join (
                select comments.gid cmt_good_id, avg(comments.score) avg_rating from comments
                    group by comments.gid
            ) on goods.gid = cmt_good_id
        ) on agid = all_cmt_good_id', array('userid' => session_userid(), 'now' => now()));
    } else if ($mode == 'search') {
        $query = post_arg('q');
        $conn = db_connect();
        $stmt = db_bind_exe($conn, 
        'select aname, agid, age_limit, available, min_price, avg_rating from (
            select aname, agid, age_limit, available, min_price
            from (
                select goods.name aname, goods.gid agid, goods.age_limit age_limit, available, min_price
                from goods
                left outer join (
                    select contracts.gid good_id, sum(contract_left) available, min(contracts.price) min_price from (
                        select orders.fulfill fulfill_contract, min(contracts.qty) - sum(orders.qty) contract_left
                            from orders, contracts
                            where orders.fulfill = contracts.cid
                            group by orders.fulfill
                    ), contracts
                    where contracts.cid = fulfill_contract and contracts.begin <= TO_DATE(:now, \'YYYYMMDD\') and (contracts.end is null or contracts.end > TO_DATE(:now, \'YYYYMMDD\'))
                    group by contracts.gid
                )
                on goods.gid = good_id
            ) where UPPER(aname) like UPPER(:query)
        )
        left outer join  (
            select goods.gid all_cmt_good_id, avg_rating
            from goods
            left outer join (
                select comments.gid cmt_good_id, avg(comments.score) avg_rating from comments
                    group by comments.gid
            ) on goods.gid = cmt_good_id
        ) on agid = all_cmt_good_id', array('userid' => session_userid(), 'now' => now(), 'query' => '%' . $query . '%'));

    }
?>
        <table border="1">
            <tr><th>Product</th><th>Available</th><th>Minimal Price</th><th>Average Rating</th><th>Age limit</th><th></th></tr>
<?php
    while ($ret = db_fetch_object($stmt)) {
        $avail = 0;
        $minprice = 'N/A';
        $avg_rating = 'N/A';
        if ($ret->AVAILABLE != 0) {
            $avail = $ret->AVAILABLE;
            $minprice = $ret->MIN_PRICE;
        }
        if ($ret->AVG_RATING != 0) {
            $avg_rating = $ret->AVG_RATING;
        }
        echo '<tr><td>' . $ret->ANAME . '</td><td>' . $avail . '</td>';
    echo '<td>' . $minprice . '</td><td>' . $avg_rating . '</td><td>' . $ret->AGE_LIMIT . '</td>';
        echo '<td><a href=\'sellgood.php?gid=' . $ret->AGID . '\'>Sell</a></td>';
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
