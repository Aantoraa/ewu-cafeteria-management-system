<?php
session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    die("User session not found. Please login again.");
}

$user_id = $_SESSION['user_id'];

$order_id = (int) $_GET['order_id'];

if ($order_id <= 0) {
    die("Invalid order.");
}


/* Get Student */

$student_result = $conn->query("
    SELECT student_id, student_name
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


/* Get Order Details */

$details_result = $conn->query("
    SELECT 
        order_details.quantity,
        order_details.unit_price,
        food_items.food_name
    FROM order_details
    JOIN food_items
        ON order_details.food_id = food_items.food_id
    WHERE order_details.order_id = $order_id
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="student-dashboard">

    <!-- Sidebar -->

    <aside class="student-sidebar">

        <div class="student-sidebar-logo">

            <h2>🍽️ EWU</h2>

            <p>Cafeteria</p>

        </div>

        <nav class="student-sidebar-menu">

            <a href="dashboard.php">
                🏠 Dashboard
            </a>

            <a href="menu.php">
                🍔 Browse Menu
            </a>

            <a href="menu.php">
                🛒 Place Order
            </a>

            <a href="my_orders.php">
                📋 My Orders
            </a>

            <a href="order_history.php">
                🕒 Order History
            </a>

            <a href="profile.php">
                👤 My Profile
            </a>

        </nav>

        <div class="student-sidebar-logout">

            <a href="../login.php">
                🚪 Logout
            </a>

        </div>

    </aside>


    <!-- Main Content -->

    <main class="student-main">

        <header class="student-header">

            <div>

                <h2>Payment</h2>

                <p>Complete your order payment</p>

            </div>

            <div class="student-profile">

                👤 <strong><?php echo $student['student_name']; ?></strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>Order Summary 🧾</h1>

            <p>Review your order before making payment.</p>

        </section>


        <section class="student-card">

            <div>

                <h2>
                    Order #<?php echo $order['order_id']; ?>
                </h2>

                <br>


                <?php while ($detail = $details_result->fetch_assoc()) { ?>

                    <div>

                        <h3>
                            <?php echo $detail['food_name']; ?>
                        </h3>

                        <p>
                            Quantity:
                            <?php echo $detail['quantity']; ?>
                        </p>

                        <p>
                            Unit Price:
                            Tk <?php echo $detail['unit_price']; ?>
                        </p>

                        <p>
                            Subtotal:
                            Tk <?php echo $detail['quantity'] * $detail['unit_price']; ?>
                        </p>

                        <hr>

                    </div>

                <?php } ?>


                <h2>
                    Total:
                    Tk <?php echo $order['total_amount']; ?>
                </h2>


                <br>


                <h3>Select Payment Method</h3>

                <form method="POST" action="process_payment.php">

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php echo $order_id; ?>"
                    >

                    <br>

                    <label>
                        <input
                            type="radio"
                            name="payment_method"
                            value="Cash"
                            required
                        >

                        Cash
                    </label>

                    <br><br>

                    <label>
                        <input
                            type="radio"
                            name="payment_method"
                            value="bKash"
                        >

                        bKash
                    </label>

                    <br><br>

                    <label>
                        <input
                            type="radio"
                            name="payment_method"
                            value="Card"
                        >

                        Card
                    </label>

                    <br><br>

                    <button type="submit">
                        Confirm Payment
                    </button>

                </form>

            </div>

        </section>

    </main>

</div>

</body>

</html>