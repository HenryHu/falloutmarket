<?php

include_once 'session.php';

verify_session();

$gid = get_arg('gid');

if (!isset($_POST['price']) || !isset($_POST['qty']) || !isset($_POST['begin']) || !isset($_POST['end'])) {

?>

<html>
    <head>
        <title>Selling</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

    </head>
    <body>
<div class="container">
        <h1>Sell Product</h1>
<div class="col-md-2">
<div class="navbar">
<div class="navbar-inner">
<ul class="nav" style="font-size: 18pt;">
<li><a href='sell.php'>Other choices</a></li>
<li><a href='dashboard.php'>Dashboard</a></li>
</ul>
</div>
</div>
</div>
<div class="col-md-10">
<?php
include_once 'info.php';
print_product_info($gid);
?>
        <h3>Contract information: </h3>

        <form action="sellgood.php?gid=<?php echo $gid; ?>" method="POST">
            <table class="table">
            <tr><th>Quantity:</th><td>
            <input type="text" name="qty" value="1" class="form-control"/></td><tr/>
            <tr><th>Price: </th><td>
            <input type="text" name="price" value="1.00" class="form-control"/></td><tr/>
            <tr><th>Begin (YYYYMMDD, default to today):</th><td>
            <input type="text" name="begin" value="<?php echo now(); ?>" class="form-control"/></td><tr/>
            <tr><th>End (YYYYMMDD, or leave empty):</th><td>
            <input type="text" name="end" class="form-control"/></td><tr/>
            </table>
            <input type="submit" value="Sell" class="btn btn-lg btn-primary"/>
        </form>
</div>
</div>
    </body>
</html>

<?php

} else {
    $qty = $_POST['qty'];
    check(is_numeric($qty), "Invalid quantity", "sellgood.php?gid=" . $gid);
    check($qty > 0, "Invalid quantity", "sellgood.php?gid=" . $gid);
    check($qty == intval($qty), "Only complete items can be sold.", "sellgood.php?gid=" . $gid);
    $price = $_POST['price'];
    check(is_numeric($price), "Invalid price.", "sellgood.php?gid=" . $gid);
    $price = floatval($price);
    check($price >= 0, "Invalid price", "sellgood.php?gid=" . $gid);
    $begin = $_POST['begin'];
    $end = $_POST['end'];
    try {
        $enddate = new DateTime($end);
        $begindate = new DateTime($begin);
        $nowdate = new DateTime(now());
        check($begindate->format('Ymd') == $begin, "Invalid time format for begin", "sellgood.php?gid=" . $gid);
        check($begindate >= $nowdate, "Cannot begin in the past", "sellgood.php?gid=" . $gid);
        check($end == '' || $enddate->format('Ymd') == $end, "Invalid time format for end", "sellgood.php?gid=" . $gid);
        check($end == '' || $enddate > $begindate, "Must end after you begin", "sellgood.php?gid=" . $gid);
    } catch (Exception $e) {
        check(0, "Invalid date format", "sellgood.php?gid=" . $gid);
    }

    $conn = db_connect();
    $stmt = db_bind_exe($conn, 'insert into contracts (userid, gid, cid, price, qty, begin, end) values (:userid, :gid, cid_seq.nextval, :price, :qty, TO_DATE(:begin, \'YYYYMMDD\'), TO_DATE(:end, \'YYYYMMDD\'))',
        array('userid' => session_userid(), 'gid' => $gid, 'price' => $price, 'now' => now(), 'qty' => $qty, 'begin' => $begin, 'end' => $end));
    db_close($conn);

    echo '<h3>Contract created.</h3>';
    jump_to('mycontracts.php');
}
