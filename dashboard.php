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
        <h3>Welcome, <?php echo session_user(); ?> <a href='logout.php'>Logout</a> </h3>
        <h2>Personal Information</h2>
        <ul>
            <li> <a href='addressbook.php'>Addressbook</a> </li>
            <li> <a href='myaccounts.php'>My accounts</a> </li>
        </ul>
        <h2>Buyer</h2>
        <ul>
            <li><a href='buy.php'>I want to buy...</a></li>
            <li> <a href='myorders.php'>My orders</a> </li>
            <li> <a href='mycmts.php'>My comments</a> </li>
        </ul>
        <h2>Seller</h2>
        <ul>
            <li><a href='sell.php'>I want to sell...</a></li>
            <li> <a href='mycontracts.php'>My contracts</a> </li>
        </ul>
        <p/>
    </body>
</html>
