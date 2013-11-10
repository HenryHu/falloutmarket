<?php

include 'session.php';

verify_session();

include_once 'util.php';
$uid = get_arg('uid');

?>
<html>
    <head>
        <title>Seller Information</title>
    </head>
    <body>
        <h1>Seller Information</h1>
<?php

include 'info.php';
print_user_info($uid);

?>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>

