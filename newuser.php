<?php

include_once 'session.php';

?>


<?php

if (!isset($_POST['username']) || !isset($_POST['age']) || !isset($_POST['name']) || !isset($_POST['password'])) {
?>

<html>
    <head>
        <title>New user</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
        <h1>Please enter your information: </h3>
        <form action='newuser.php' method='POST'>
            <table>
            <tr><th>User name: </th><td><input type='text' name='username'/></td></tr>
            <tr><th>Password: </th><td><input type='password' name='password'/></td></tr>
            <tr><th>Your name: </th><td><input type='text' name='name' /></td></tr>
            <tr><th>Your age: </th><td><input type='text' name='age' /></td></tr>
            </table>
            <input type='submit' value='Create'/>
        </form>
<h3><a href='index.php'>Login</a></h3>
    </body>
</html>

<?php
} else {
    include_once 'conn.php';
    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into users (userid, age, name, username, password) values (userid_seq.nextval, :age, :name, :username, :password)', array('age' => $_POST['age'], 'name' => $_POST['name'], 'username' => $_POST['username'], 'password' => $_POST['password']));
#    $stmt = db_bind_exe($conn, 'insert into addresses (userid, addrid, address) values (7, addrid_seq.nextval, :addr)', array('addr' => $_POST['addr']));
    db_close($conn);
    echo '<h3>User created.</h3>';
    jump_to('index.php');
}

?>


