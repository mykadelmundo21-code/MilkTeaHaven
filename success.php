<?php
$name = $_POST['customer_name'];
$product = $_POST['product_name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$total = $quantity * $price;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>

    <style>
        body {
            background: #f6eadf;
            font-family: Arial;
        }

        .box {
            width: 420px;
            background: white;
            margin: 80px auto;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 5px 15px rgba(0,0,0,.2);
            text-align: center;
        }

        h2 {
            color: #6f4e37;
        }

        .total {
            font-size: 24px;
            font-weight: bold;
            color: #b85c38;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            background: #6f4e37;
            color: white;
            padding: 12px 20px;
            border-radius: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Order Successful!</h2>

    <p>Thank you, <b><?php echo $name; ?></b></p>
    <p>Product: <b><?php echo $product; ?></b></p>
    <p>Quantity: <b><?php echo $quantity; ?></b></p>

    <p class="total">Total: ₱<?php echo $total; ?></p>

    <a href="index.php">Order Again</a>
</div>

</body>
</html>