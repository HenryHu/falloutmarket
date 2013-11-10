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
    </head>
    <body>
        <h1>New Comment</h1>
        <h3>Write new comment for: </h3>
<?php

include_once 'info.php';
print_product_info($gid);

?>

        <p/>
        <form action="newcmt.php?gid=<?php echo $gid; ?>" method="POST">
            Rating:
            <input type="radio" name="rating" value="1">1</input>
            <input type="radio" name="rating" value="2">2</input>
            <input type="radio" name="rating" value="3">3</input>
            <input type="radio" name="rating" value="4">4</input>
            <input type="radio" name="rating" value="5" checked="yes">5</input>
            <br/>
            Comment:<br/>
            <textarea name="content"></textarea><br/>
            <input type="submit" value="Submit"/>
        </form>
<h3><a href='dashboard.php'>Dashboard</a></h3>
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
