<?php

include_once 'session.php';

verify_session();

if (!isset($_GET['id'])) {
    jump_to('index.php');
}

$acctid = $_GET['id'];

include_once 'conn.php';
$conn = db_connect();
$stmt = db_bind_exe($conn, 'update accounts set removed = 1 where userid = :userid and acctid = :acctid', array('userid' => session_userid(), 'acctid' => $acctid));
db_close($conn);

?>
<h3>Account removed.</h3>
<?php

include_once 'util.php';

jump_to('myaccounts.php');

?>

