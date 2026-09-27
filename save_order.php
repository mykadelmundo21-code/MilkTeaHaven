<?php
$conn = mysqli_connect("localhost", "root", "", "milktea_haven");

if (!$conn) {
    die("Database connection failed");
}

$data = json_decode(file_get_contents("php://input"), true);

$customer = mysqli_real_escape_string($conn, $data['fullname']);
$phone = mysqli_real_escape_string($conn, $data['phone']);
$address = mysqli_real_escape_string($conn, $data['address']);
$total = $data['total'];
$payment = mysqli_real_escape_string($conn, $data['payment']);

mysqli_query($conn, "INSERT INTO orders(customer, phone, address, total, payment)
VALUES('$customer', '$phone', '$address', '$total', '$payment')");

$order_id = mysqli_insert_id($conn);

foreach ($data['items'] as $item) {
    $name = mysqli_real_escape_string($conn, $item['name']);
    $price = $item['price'];
    $qty = $item['qty'];
    $sugar = isset($item['sugar']) ? mysqli_real_escape_string($conn, $item['sugar']) : '';
    $ice = isset($item['ice']) ? mysqli_real_escape_string($conn, $item['ice']) : '';

    mysqli_query($conn, "INSERT INTO order_items(order_id, product_name, price, qty, sugar, ice)
    VALUES('$order_id', '$name', '$price', '$qty', '$sugar', '$ice')");
}

echo "success";
?>