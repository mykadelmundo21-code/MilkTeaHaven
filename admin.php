<?php
$conn = mysqli_connect("localhost","root","","milktea_haven");
if(!$conn){ die("Database connection failed"); }

$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Orders - Milk Tea Haven</title>
<style>
body{
    font-family:Arial;
    background:#fff3dd;
    margin:0;
    padding:30px;
}
h1{color:#7a2f00}
.order{
    background:white;
    border-radius:18px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 10px 25px #0002;
}
.badge{
    background:#ff9100;
    color:white;
    padding:8px 15px;
    border-radius:20px;
    float:right;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#fff3dd;
}
.total{
    color:#ef6c00;
    font-size:22px;
    font-weight:bold;
}
</style>
</head>
<body>

<h1>🧋 Milk Tea Haven Admin Orders</h1>
<a href="index.php">← Back to Shop</a>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>
<div class="order">
    <span class="badge">Order #<?php echo $order['id']; ?></span>

    <h2><?php echo $order['customer']; ?></h2>
    <p><b>Phone:</b> <?php echo $order['phone']; ?></p>
    <p><b>Address:</b> <?php echo $order['address']; ?></p>
    <p><b>Payment:</b> <?php echo $order['payment']; ?></p>
    <p><b>Date:</b> <?php echo $order['created_at']; ?></p>

    <table>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Sugar</th>
            <th>Ice</th>
        </tr>

        <?php
        $order_id = $order['id'];
        $items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='$order_id'");
        while($item = mysqli_fetch_assoc($items)){
        ?>
        <tr>
            <td><?php echo $item['product_name']; ?></td>
            <td><?php echo $item['qty']; ?></td>
            <td>₱<?php echo number_format($item['price'],2); ?></td>
            <td><?php echo $item['sugar']; ?></td>
            <td><?php echo $item['ice']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <p class="total">Total: ₱<?php echo number_format($order['total'],2); ?></p>
</div>
<?php } ?>

</body>
</html>