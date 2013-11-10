<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Comments</title>
    </head>
    <body>
        <h1>My Comments</h1>
        <table>
            <tr> <th> Product </th> <th> Date </th> <th> Rating </th> <th> Comment </th> <th> Action </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select comments.gid, cmtid, goods.name, score, content, TO_CHAR(wrote, \'YYYY-MM-DD\') wrote from comments, goods where comments.userid = :userid and comments.gid = goods.gid order by cmtid', array('userid' => session_userid()));

include_once 'util.php';
while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td>' . $ret->NAME . '</td><td>' . $ret->WROTE . '</td>';
    echo '<td>' . rating_stars($ret->SCORE) . '</td><td>' . $ret->CONTENT . '</td>';
    echo '<td><a href="removecmt.php?id=' . $ret->CMTID . '">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>

<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>

