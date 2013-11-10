<?php

include 'session.php';

verify_session();

include_once 'util.php';

include_once 'conn.php';

if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['age_limit'])) {

?>

<html>
    <head>
        <title>New product</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    </head>
    <body>
<div class="container">
        <h1>New Product</h1>
<div class="col-md-12">
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
        <h3>Product information: </h3>

        <form action="newgood.php" method="POST">
            <table class="table">
            <tr><th>Name:</th><td>
            <input type="text" name="name" class="form-control" placeholder="Product name"/></td><tr/>
            <tr><th>Minimal age:</th><td>
            <input type="text" name="age_limit" value="0" class="form-control" placeholder="Age limit, 0 for unlimited"/></td><tr/>
            <tr><th>Description:</th><td>
            <textarea name="description" class="form-control" placeholder="Product description"></textarea></td></tr>
            </table>
            <input type="submit" value="Submit" class="btn btn-primary btn-lg"/>
        </form>
</div>
</div>
    </body>
</html>

<?php

} else {
    check($_POST['name'] != '', "Name cannot be empty", "newgood.php");
    check($_POST['description'] != '', "Password cannot be empty", "newgood.php");
    check($_POST['age_limit'] >= 0 && $_POST['age_limit'] <= 1000, "Invalid age limit", "newgood.php");
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into goods (gid, name, description, age_limit) values (gid_seq.nextval, :name, :description, :age_limit)',
        array('userid' => session_userid(), 'name' => $_POST['name'], 'now' => now(), 'description' => $_POST['description'], 'age_limit' => $_POST['age_limit']));
    db_close($conn);

    echo '<h3>New product info entered.</h3>';
    jump_to('sell.php');
}

