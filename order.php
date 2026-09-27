<?php
$product_name = $_POST['product_name'];
$price = $_POST['price'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order MilkTea Haven</title>

    <style>
        body {
            background: #f6eadf;
            font-family: Arial;
        }

        .box {
            width: 400px;
            background: white;
            margin: 80px auto;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 5px 15px rgba(0,0,0,.2);
        }

        h2 {
            text-align: center;
            color: #6f4e37;
        }

        input, button {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        button {
            background: #6f4e37;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #6f4e37;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Order Form</h2>

    <form action="success.php" method="POST">
        <input type="text" name="customer_name" placeholder="Your Name" required>

        <input type="text" name="product_name" value="<?php echo $product_name; ?>" readonly>

        <input type="number" name="quantity" placeholder="Quantity" required>

        <input type="text" name="price" value="<?php echo $price; ?>" readonly>

        <button type="submit">Place Order</button>
    </form>

    <a href="index.php">Back to Menu</a>
</div>

</body>
</html>