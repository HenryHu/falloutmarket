<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Dashboard</title>
    </head>
    <body>
        <h1>Dashboard</h1>
        <h3>Welcome, <?php echo session_user(); ?></h3>
        <ul>
            <li>
                <a href='addressbook.php'>Addressbook</a>
            </li>
            <li>
                <a href='myaccounts.php'>My accounts</a>
            </li>
            <li>
                <a href='myorders.php'>My orders</a>
            </li>
            <li>
                <a href='logout.php'>Logout</a>
            </li>
        </ul>
    </body>
</html>
