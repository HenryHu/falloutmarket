<?php

include_once 'session.php';

?>


<?php

if (!isset($_POST['username']) || !isset($_POST['age']) || !isset($_POST['name']) || !isset($_POST['password'])) {
?>

<html>
    <head>
        <title>New user</title>
    </head>
    <body>
        <h1>Please enter your information: </h3>
        <form action='newuser.php' method='POST'>
            User name: <input type='text' name='username'/><br/>
            Password: <input type='password' name='password'/><br/>
            Your name: <input type='text' name='name' /><br/>
            Your age: <input type='text' name='age' /><br/>
            <input type='submit' value='Create'/>
        </form>
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


