<?php

include_once 'conn.php';
include_once 'util.php';

function print_product_info($gid) {
    $conn = db_connect();

    $stmt = db_bind_exe($conn, 'select goods.name, goods.description, goods.age_limit, avg_rating from (
        select avg(comments.score) avg_rating from comments where comments.gid = :gid
    ) ,goods where goods.gid = :gid', array('gid' => $gid));

    echo '<table>';
    while ($ret = db_fetch_object($stmt)) {
        $avg_rating = 'N/A';
        if ($ret->AVG_RATING != 0) {
            $avg_rating = $ret->AVG_RATING;
        }
        $age_limit = 'No';
        if ($ret->AGE_LIMIT != 0) {
            $age_limit = $ret->AGE_LIMIT;
        }
        echo '<tr><th>Name</th><td>' . $ret->NAME . '</td></tr>';
        echo '<tr><th>Description</th><td>' . $ret->DESCRIPTION . '</td></tr>';
        echo '<tr><th>Age limit</th><td>' . $age_limit . '</td></tr>';
        echo '<tr><th>Average rating</th><td>' . $avg_rating . '</td></tr>';
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

function print_comments($gid) {
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 
        'select users.name, TO_CHAR(comments.wrote, \'YYYY-MM-DD\') wrote, comments.content, comments.score
        from comments, users
        where users.userid = comments.userid and comments.gid = :gid',
        array('gid' => $gid));
    echo '<h3>Comments:</h3>';
    while ($ret = db_fetch_object($stmt)) {
        echo 'On ' . $ret->WROTE . ', <b>' . $ret->NAME . '</b> wrote: <br/>';
        echo '<div style="margin-left: 10px;">';
        echo '<b>Rating: </b>', rating_stars($ret->SCORE), '<br/>';
        echo '<b>Comment: </b>';
        echo $ret->CONTENT . '<br/>';
        echo '</div>';
    }
    db_close($conn);

}

function print_product_sold($gid) {
    $conn = db_connect();

    $stmt = db_bind_exe($conn, '
        select NVL(sold, 0) sold, tot_price / sold avg_price from (
            select sum(qty) sold from orders where orders.gid = :gid
        ), (
            select sum(orders.qty * contracts.price) tot_price from orders, contracts
            where orders.gid = :gid and orders.fulfill = contracts.cid
        ) ', array('gid' => $gid));

    echo '<h3>Sell:</h3>';
    echo '<table>';
    while ($ret = db_fetch_object($stmt)) {
        $avg_price = 'N/A';
        if ($ret->SOLD != 0) {
            $avg_price = $ret->AVG_PRICE;
        }
        echo '<tr><th>Sold</th><td>' . $ret->SOLD . '</td></tr>';
        echo '<tr><th>Average price</th><td>' . $avg_price . '</td></tr>';
    }
    echo '</table>';

    db_close($conn);
}


?>
