<?php

include 'session.php';

verify_session();

include_once 'util.php';
$cid = get_arg('id');

?>
<html>
    <head>
        <title>Contract Information</title>
    </head>
    <body>
        <h1>Contract Information</h1>
<?php

include 'info.php';
print_contract_info($cid);

?>
    <h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>




