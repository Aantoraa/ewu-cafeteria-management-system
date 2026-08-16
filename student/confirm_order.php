<?php
session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    die("User session not found. Please login again.");
}

$user_id = $_SESSION['user_id'];


/* Get Student */

$student_result = $conn->query("
    SELECT student_id
    FROM students
    WHERE user_id = $user_id
");

$student = $student_result->fetch_assoc();

if (!$student) {
    die("Student record not found.");
}

$student_id = $student['student_id'];


/* Get Food Information */

$food_id = (int) $_POST['food_id'];
$quantity = (int) $_POST['quantity'];

if ($quantity < 1) {
    die("Invalid quantity.");
}

$food_result = $conn->query("
    SELECT food_id, food_name, price
    FROM food_items
    WHERE food_id = $food_id
    AND availability = 1
");

$food = $food_result->fetch_assoc();

if (!$food) {
    die("Food item is not available.");
}


/* Calculate Total */

$unit_price = $food['price'];

$total_amount = $unit_price * $quantity;


/* Create Order */

$order_sql = "
    INSERT INTO orders
    (student_id, order_date, status, total_amount)
    VALUES
    ($student_id, NOW(), 'Pending', $total_amount)
";

if (!$conn->query($order_sql)) {
    die("Error creating order: " . $conn->error);
}

$order_id = $conn->insert_id;


/* Create Order Detail */

$detail_sql = "
    INSERT INTO order_details
    (order_id, food_id, quantity, unit_price)
    VALUES
    ($order_id, $food_id, $quantity, $unit_price)
";

if (!$conn->query($detail_sql)) {
    die("Error creating order detail: " . $conn->error);
}


/* Go to Payment */

header("Location: payment.php?order_id=$order_id");
exit;
?>