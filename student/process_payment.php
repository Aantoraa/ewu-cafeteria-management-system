<?php
session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    die("User session not found. Please login again.");
}

$user_id = $_SESSION['user_id'];

$order_id = (int) $_POST['order_id'];

$payment_method = $_POST['payment_method'];


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


/* Get Order */

$order_result = $conn->query("
    SELECT *
    FROM orders
    WHERE order_id = $order_id
    AND student_id = $student_id
");

$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}


/* Prevent duplicate payment */

$payment_check = $conn->query("
    SELECT payment_id
    FROM payments
    WHERE order_id = $order_id
");

if ($payment_check->num_rows > 0) {
    die("This order has already been paid.");
}


/* Create Payment */

$amount = $order['total_amount'];

$payment_sql = "
    INSERT INTO payments
    (order_id, payment_method, payment_status, payment_date, amount)
    VALUES
    (
        $order_id,
        '$payment_method',
        'Paid',
        NOW(),
        $amount
    )
";

if (!$conn->query($payment_sql)) {
    die("Payment error: " . $conn->error);
}


/* Update Order Status */

$conn->query("
    UPDATE orders
    SET status = 'Preparing'
    WHERE order_id = $order_id
");


/* Success Page */

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Successful - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="student-dashboard">

    <main class="student-main">

        <section class="student-welcome">

            <h1>Payment Successful! 🎉</h1>

            <p>
                Your order has been confirmed successfully.
            </p>

        </section>


        <section class="student-card">

            <div>

                <h2>
                    Order #<?php echo $order_id; ?>
                </h2>

                <p>
                    Payment Method:
                    <strong><?php echo $payment_method; ?></strong>
                </p>

                <p>
                    Amount Paid:
                    <strong>
                        Tk <?php echo $amount; ?>
                    </strong>
                </p>

                <p>
                    Status:
                    <strong>Confirmed</strong>
                </p>

                <br>

                <a href="dashboard.php">
                    ← Back to Dashboard
                </a>

                &nbsp;&nbsp;

                <a href="menu.php">
                    Browse More Food →
                </a>

            </div>

        </section>

    </main>

</div>

</body>

</html>