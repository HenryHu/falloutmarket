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
    </head>
    <body>
        <h1>My Information</h1>
        <h3>Change information: </h3>

        <form action="myinfo.php" method="POST">
            <table>
            <tr><th>Username: </th><td><?php echo $ret->USERNAME; ?> </td></tr>
            <tr><th>Password:</th><td>
            <input type="password" name="password" value="<?php echo $ret->PASSWORD; ?>"/></td><tr/>
            <tr><th>Name: </th><td>
            <input type="text" name="name" value="<?php echo $ret->NAME; ?>"/></td><tr/>
            <tr><th>Age:</th><td>
            <input type="text" name="age" value="<?php echo $ret->AGE; ?>"/></td><tr/>
            </table>
            <input type="submit" value="Submit"/>
        </form>
<h3><a href='dashboard.php'>Dashboard</a></h3>
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
