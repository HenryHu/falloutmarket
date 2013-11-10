<?php

include_once 'session.php';

verify_session();

$gid = get_arg('gid');

if (!isset($_POST['price']) || !isset($_POST['qty']) || !isset($_POST['begin']) || !isset($_POST['end'])) {

?>

<html>
    <head>
        <title>Selling</title>
    </head>
    <body>
        <h1>Sell Product</h1>
<?php
include_once 'info.php';
print_product_info($gid);
?>
        <h3>Contract information: </h3>

        <form action="sellgood.php?gid=<?php echo $gid; ?>" method="POST">
            <table>
            <tr><th>Quantity:</th><td>
            <input type="text" name="qty" value="1"/></td><tr/>
            <tr><th>Price: </th><td>
            <input type="text" name="price" value="1.00"/></td><tr/>
            <tr><th>Begin (YYYYMMDD, default to today):</th><td>
            <input type="text" name="begin" value="<?php echo now(); ?>"/></td><tr/>
            <tr><th>End (YYYYMMDD, or leave empty):</th><td>
            <input type="text" name="end"/></td><tr/>
            </table>
            <input type="submit" value="Submit"/>
        </form>
<h3><a href='dashboard.php'>Dashboard</a></h3>
    </body>
</html>

<?php

} else {

    check($_POST['qty'] > 0, "Invalid quantity", "sellgood.php?gid=" . $gid);
    check($_POST['price'] >= 0, "Invalid price", "sellgood.php?gid=" . $gid);
    check(strtotime($_POST['begin']) >= strtotime(now()), "Cannot begin in the past", "sellgood.php?gid=" . $gid);
    check($_POST['end'] == '' || strtotime($_POST['end']) > strtotime($_POST['begin']), "Must end after you begin", "sellgood.php?gid=" . $gid);

    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into contracts (userid, gid, cid, price, qty, begin, end) values (:userid, :gid, cid_seq.nextval, :price, :qty, TO_DATE(:begin, \'YYYYMMDD\'), TO_DATE(:end, \'YYYYMMDD\'))',
        array('userid' => session_userid(), 'gid' => $gid, 'price' => $_POST['price'], 'now' => now(), 'qty' => $_POST['qty'], 'begin' => $_POST['begin'], 'end' => $_POST['end']));
    db_close($conn);

    echo '<h3>Contract created.</h3>';
    jump_to('mycontracts.php');
}
