<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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

/* Get Recent Orders */

$orders_result = $conn->query("
    SELECT
        orders.order_id,
        orders.order_date,
        orders.total_amount,
        orders.status,
        food_items.food_name
    FROM orders
    LEFT JOIN order_details
        ON orders.order_id = order_details.order_id
    LEFT JOIN food_items
        ON order_details.food_id = food_items.food_id
    WHERE orders.student_id = $student_id
    ORDER BY orders.order_date DESC
    LIMIT 5
");

if (!$orders_result) {
    die("Order query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - EWU Cafeteria</title>

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

    <a href="dashboard.php" class="active">
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

        <!-- Header -->

        <header class="student-header">

            <div>

                <h2>Student Dashboard</h2>

                <p>Your EWU Cafeteria account</p>

            </div>

            <div class="student-profile">

                👤

                <strong>
                    <?php echo htmlspecialchars($student['student_name']); ?>
                </strong>

            </div>

        </header>


        <!-- Welcome -->

        <section class="student-welcome">

            <h1>
                Welcome back,
                <?php echo htmlspecialchars($student['student_name']); ?>! 👋
            </h1>

            <p>
                What would you like to enjoy today?
            </p>

        </section>


        <!-- Quick Cards -->

        <section class="student-cards">


            <!-- Browse Menu -->

            <div class="student-card">

                <div class="student-card-icon">
                    🍔
                </div>

                <div>

                    <h3>Browse Menu</h3>

                    <p>
                        Explore today's available food.
                    </p>

                    <a href="menu.php">
                        View Menu →
                    </a>

                </div>

            </div>


            <!-- My Orders -->

            <div class="student-card">

                <div class="student-card-icon">
                    🛒
                </div>

                <div>

                    <h3>My Orders</h3>

                    <p>
                        Check your current orders.
                    </p>

                    <a href="my_orders.php">
                        View Orders →
                    </a>

                </div>

            </div>


            <!-- Order History -->

            <div class="student-card">

                <div class="student-card-icon">
                    📋
                </div>

                <div>

                    <h3>Order History</h3>

                    <p>
                        View your previous orders.
                    </p>

                    <a href="order_history.php">
                        View History →
                    </a>

                </div>

            </div>


        </section>


        <!-- Recent Orders -->

        <section class="student-orders">

            <div class="student-section-header">

                <h2>Recent Orders</h2>

                <a href="my_orders.php">
                    View All
                </a>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>Order ID</th>

                        <th>Date</th>

                        <th>Items</th>

                        <th>Total</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                <?php if ($orders_result->num_rows > 0) { ?>

                    <?php while ($order = $orders_result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                #<?php echo $order['order_id']; ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($order['order_date'])
                                );
                                ?>
                            </td>

                            <td>

                                <?php

                                if ($order['food_name']) {
                                    echo htmlspecialchars($order['food_name']);
                                } else {
                                    echo "N/A";
                                }

                                ?>

                            </td>

                            <td>
                                Tk <?php echo $order['total_amount']; ?>
                            </td>

                            <td>

                                <span class="student-status">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="5">
                            No orders found.
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </section>

    </main>

</div>

</body>

</html>