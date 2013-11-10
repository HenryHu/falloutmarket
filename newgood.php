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
    </head>
    <body>
        <h1>New Product</h1>
        <h3>Product information: </h3>

        <form action="newgood.php" method="POST">
            <table>
            <tr><th>Name:</th><td>
            <input type="text" name="name"/></td><tr/>
            <tr><th>Minimal age:</th><td>
            <input type="text" name="age_limit" value="0"/></td><tr/>
            <tr><th>Description:</th><td>
            <textarea name="description"></textarea></td></tr>
            </table>
            <input type="submit" value="Submit"/>
        </form>
<h3><a href='dashboard.php'>Dashboard</a></h3>
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

