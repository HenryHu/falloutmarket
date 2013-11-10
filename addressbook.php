<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Addressbook</title>
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

   </head>
    <body>
<div class="container">
<div class="col-md-12">
        <h1>Addressbook</h1>
</div>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='newaddr.php'>New Address</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
        <h3>Your addresses: </h3>
<div class="col-md-10">
        <table class="table">
            <tr> <th> Address </th> <th> </th> </tr>
<?php
include_once 'conn.php';

$conn = db_connect();
$stmt = db_bind_exe($conn, 'select addrid, address from addresses where userid = :userid and removed = 0 order by addrid', array('userid' => session_userid()));

while ($ret = db_fetch_row($stmt)) {
    echo '<tr><td>' . $ret[1] . '</td>'; //<td>' . $ret[1] . '</td>';
    echo '<td><a href="removeaddr.php?id=' . $ret[0] . '" class="btn btn-default">Remove</a></td>';
    echo '</tr>';
}

db_close($conn);
?>
</table>
</div>
</div>

    </body>
</html>
