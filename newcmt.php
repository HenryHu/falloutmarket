<?php

include 'session.php';

verify_session();

include_once 'util.php';
$gid = get_arg('gid');

include_once 'conn.php';

if (!isset($_POST['rating']) || !isset($_POST['content'])) {

?>

<html>
    <head>
        <title>New Comment</title>
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    </head>
    <body>
<div class="container">
        <h1>New Comment</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='myorders.php'>My Orders</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <h3>Write new comment for: </h3>
<?php

include_once 'info.php';
print_product_info($gid);

?>

        <p/>
        <form action="newcmt.php?gid=<?php echo $gid; ?>" method="POST">
            Rating:
<div class="btn-group">
            <label class="btn btn-default">1<input type="radio" name="rating" value="1"></input></label>
            <label class="btn btn-default">2<input type="radio" name="rating" value="2"></input></label>
            <label class="btn btn-default">3<input type="radio" name="rating" value="3"></input></label>
            <label class="btn btn-default">4<input type="radio" name="rating" value="4"></input></label>
            <label class="btn btn-default">5<input type="radio" name="rating" value="5" checked="yes"></input></label>
</div>
            <p/>
            Comment:<br/>
            <textarea name="content" class="form-control"></textarea><br/>
            <input type="submit" value="Submit" class="btn btn-lg btn-primary"/>
        </form>
</div>
</div>
    </body>
</html>

<?php

} else {

    check($_POST['rating'] >= 1 && $_POST['rating'] <= 5, "Invalid rating", "dashboard.php");
    check($_POST['content'] != '', "Comment cannot be empty", "newcmt.php?gid=" . $gid);
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into comments (userid, gid, cmtid, wrote, content, score) values (:userid, :gid, cmtid_seq.nextval, TO_DATE(:now, \'YYYY-MM-DD\'), :content, :score)',
        array('userid' => session_userid(), 'gid' => $gid, 'now' => now(), 'content' => $_POST['content'], 'score' => $_POST['rating']));
    db_close($conn);

    echo '<h3>Comment created.</h3>';
    jump_to('mycmts.php');
}
