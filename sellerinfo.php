<?php

include 'session.php';

verify_session();

include_once 'util.php';
$uid = get_arg('uid');

?>
<html>
    <head>
        <title>Seller Information</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    </head>
    <body>
<div class="container">
        <h1>Seller Information</h1>
<?php

include 'info.php';
print_user_info($uid);

?>
<h3><a href='dashboard.php'>Dashboard</a></h3>
</div>
    </body>
</html>

