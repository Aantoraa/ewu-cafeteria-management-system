<?php

session_start();

require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: orders.php");
    exit;
}

$order_id = intval($_POST['order_id']);
$status = $_POST['status'];

$allowed_statuses = [
    'Pending',
    'Preparing',
    'Ready',
    'Completed'
];

if (!in_array($status, $allowed_statuses)) {
    die("Invalid status.");
}

$stmt = $conn->prepare("
    UPDATE orders
    SET status = ?
    WHERE order_id = ?
");

$stmt->bind_param(
    "si",
    $status,
    $order_id
);

$stmt->execute();

header("Location: orders.php");
exit;

?>