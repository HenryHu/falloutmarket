<?php

include_once 'session.php';

verify_session();

$addrid = $_GET['id'];

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn, 'update addresses set removed = 1 where userid = :userid and addrid = :addrid', array('userid' => session_userid(), 'addrid' => $addrid));
db_close($conn);

?>
<h3>Address removed.</h3>
<?php

include_once 'util.php';

jump_to('addressbook.php');

?>
