<?php

include 'session.php';

verify_session();

include_once 'util.php';
$gid = get_arg('gid');

?>
<html>
    <head>
        <title>Product Information</title>
    </head>
    <body>
        <h1>Product Information</h1>
<?php

include 'info.php';
print_product_info($gid);

?>
    <h3><a href='buygood.php?id=<?php echo $gid; ?>'>Buy</a></h3>
    <h3><a href='sellgood.php?gid=<?php echo $gid; ?>'>Sell</a></h3>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>



