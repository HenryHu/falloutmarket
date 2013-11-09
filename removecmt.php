<?php

include 'session.php';

verify_session();

include_once 'util.php';

$cmtid = get_arg('id');

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn, 'delete from comments where userid = :userid and cmtid = :cmtid', array('userid' => session_userid(), 'cmtid' => $cmtid));
db_close($conn);

?>
<h3>Comment removed.</h3>
<?php

include_once 'util.php';

jump_to('mycmts.php');

?>

