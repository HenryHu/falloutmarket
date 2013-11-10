<?php

include 'session.php';

verify_session();

include_once 'util.php';

include_once 'conn.php';

if (!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['age'])) {

    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'select * from users where userid = :userid', array('userid' => session_userid()));
    $ret = db_fetch_object($stmt);

?>

<html>
    <head>
        <title>My Information</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>


    </head>
    <body>
<div class="container">
        <h1>My Information</h1>
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
        <h3>Change information: </h3>

        <form action="myinfo.php" method="POST">
            <table class="table">
            <tr><th>Username: </th><td><?php echo $ret->USERNAME; ?> </td></tr>
            <tr><th>Password:</th><td>
            <input type="password" name="password" value="<?php echo $ret->PASSWORD; ?>" class="form-control"/></td><tr/>
            <tr><th>Name: </th><td>
            <input type="text" name="name" value="<?php echo $ret->NAME; ?>" class="form-control"/></td><tr/>
            <tr><th>Age:</th><td>
            <input type="text" name="age" value="<?php echo $ret->AGE; ?>" class="form-control"/></td><tr/>
            </table>
            <input type="submit" value="Update" class="btn btn-lg btn-primary"/>
        </form>
</div>
</div>
    </body>
</html>

<?php

} else {

    check($_POST['name'] != '', "Name cannot be empty", "myinfo.php");
    check($_POST['password'] != '', "Password cannot be empty", "myinfo.php");
    check($_POST['age'] >= 1 && $_POST['age'] <= 1000, "Invalid age", "myinfo.php");
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'update users set name = :name, password = :password, age = :age where userid = :userid',
        array('userid' => session_userid(), 'name' => $_POST['name'], 'now' => now(), 'password' => $_POST['password'], 'age' => $_POST['age']));
    db_close($conn);

    echo '<h3>User info updated.</h3>';
    jump_to('dashboard.php');
}
