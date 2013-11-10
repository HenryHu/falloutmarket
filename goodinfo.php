<?php

include 'session.php';

verify_session();

include_once 'util.php';
$gid = get_arg('gid');

?>
<html>
    <head>
        <title>Product Information</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    </head>
    <body>
<div class="container">
        <h1>Product Information</h1>
<?php

include 'info.php';
?>
<div class="container">
<div class="col-md-6">
<?php print_product_info($gid); ?>
</div>
<div class="col-md-6">
<?php print_product_sold($gid); ?>
</div>
</div>
    <a href='buygood.php?id=<?php echo $gid; ?>' class="btn btn-lg btn-default">Buy</a>
    <a href='sellgood.php?gid=<?php echo $gid; ?>' class="btn btn-lg btn-default">Sell</a>
    <a href="newcmt.php?gid=<?php echo $gid; ?>" class="btn btn-lg btn-default">Comment</a>
<div class="col-md-12">
<?php print_comments($gid); ?>

    <h3><a href='dashboard.php'>Dashboard</a></h3>
</div>
    </body>
</html>



