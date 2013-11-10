<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>My Accounts</title>
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

   </head>
    <body>
<div class="container">
        <h1>Account list</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='newacct.php'>New Account</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <h3>Your accounts: </h3>
        <table class="table">
            <tr> <th> Account </th> <th> Valid before </th> <th> </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select acctid, act_number, TO_CHAR(valid_before, \'YYYY-MM-DD\') valid_before from accounts where userid = :userid and removed = 0 order by acctid', array('userid' => session_userid()));

while ($ret = db_fetch_object($stmt)) {
    echo '<tr><td>' . $ret->ACT_NUMBER . '</td><td>' . $ret->VALID_BEFORE . '</td>';
    echo '<td><a href="removeacct.php?id=' . $ret->ACCTID . '" class="btn btn-default">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>
</div>
</div>
    </body>
</html>
