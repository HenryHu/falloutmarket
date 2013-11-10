<?php

include 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Comments</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>My Comments</h1>
<div class="col-md-2">
<h3><a href='dashboard.php'>Dashboard</a></h3>
</div>
<div class="col-md-10">
        <table class="table">
            <tr> <th> Product </th> <th> Date </th> <th> Rating </th> <th> Comment </th> <th> </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select comments.gid, cmtid, goods.name, score, content, TO_CHAR(wrote, \'YYYY-MM-DD\') wrote from comments, goods where comments.userid = :userid and comments.gid = goods.gid order by cmtid', array('userid' => session_userid()));

include_once 'util.php';
while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td><a href="goodinfo.php?gid=' . $ret->GID . '">' . $ret->NAME . '</a></td><td>' . $ret->WROTE . '</td>';
    echo '<td>' . rating_stars($ret->SCORE) . '</td><td>' . $ret->CONTENT . '</td>';
    echo '<td><a href="removecmt.php?id=' . $ret->CMTID . '" class="btn btn-default">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>
</div>
</div>
    </body>
</html>

