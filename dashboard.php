<?php

include_once 'session.php';

verify_session();

?>

<html>
    <head>
        <title>Dashboard</title>
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
        <h1>Dashboard</h1>
        <h3>Welcome, <?php echo session_user(); ?> <a href='logout.php' class="btn btn-lg">Logout</a> </h3>
<div class="col-md-4">
        <h2>Personal Information</h2>
<div class="col-md-12">
        <div class="list-group">
            <a href='myinfo.php' class="list-group-item">My Information</a>
            <a href='addressbook.php' class="list-group-item">Addressbook</a>
            <a href='myaccounts.php' class="list-group-item">My accounts</a>
        </div>
</div>
</div>
<div class="col-md-4">
        <h2>Buyer</h2>
<div class="col-md-12">
        <div class="list-group">
            <a href='buy.php' class="list-group-item">I want to buy...</a>
            <a href='myorders.php' class="list-group-item">My orders</a>
            <a href='mycmts.php' class="list-group-item">My comments</a>
        </div>
</div>
</div>
<div class="col-md-4">
        <h2>Seller</h2>
<div class="col-md-12">
        <div class="list-group">
            <a href='sell.php' class="list-group-item">I want to sell...</a>
            <a href='mycontracts.php' class="list-group-item">My contracts</a>
        </div>
</div>
</div>
</div>
</div>
        <p/>
    </body>
</html>
