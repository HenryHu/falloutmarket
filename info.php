<?php

include_once 'conn.php';

function print_product_info($gid) {
    $conn = db_connect();

    $stmt = db_bind_exe($conn, 'select goods.name, goods.description, goods.age_limit, avg_rating from (
        select avg(comments.score) avg_rating from comments where comments.gid = :gid
    ) ,goods where goods.gid = :gid', array('gid' => $gid));

    echo '<table>';
    while ($ret = db_fetch_object($stmt)) {
        echo '<tr><th>Name</th><td>' . $ret->NAME . '</td></tr>';
        echo '<tr><th>Description</th><td>' . $ret->DESCRIPTION . '</td></tr>';
        echo '<tr><th>Age limit</th><td>' . $ret->AGE_LIMIT . '</td></tr>';
        echo '<tr><th>Average rating</th><td>' . $ret->AVG_RATING . '</td></tr>';
    }
    echo '</table>';

    db_close($conn);
}

function print_user_info($uid) {
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 
        'select users.name, sold from (
            select sum(orders.qty) sold from orders, contracts
            where orders.fulfill = contracts.cid and contracts.userid = :userid
         ), users
         where users.userid = :userid',
        array('userid' => $uid));
    echo '<table>';
    while ($ret = db_fetch_object($stmt)) {
        echo '<tr><th>Name</th><td>' . $ret->NAME . '</td></tr>';
        echo '<tr><th>Items sold</th><td>' . $ret->SOLD . '</td></tr>';
    }
    echo '</table>';
    db_close($conn);
}

?>
