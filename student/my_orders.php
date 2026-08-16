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

/* Get All Orders */

$orders_result = $conn->query("
    SELECT
        orders.order_id,
        orders.order_date,
        orders.total_amount,
        orders.status,
        order_details.quantity,
        order_details.unit_price,
        food_items.food_name
    FROM orders
    LEFT JOIN order_details
        ON orders.order_id = order_details.order_id
    LEFT JOIN food_items
        ON order_details.food_id = food_items.food_id
    WHERE orders.student_id = $student_id
    ORDER BY orders.order_date DESC
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

    <title>My Orders - EWU Cafeteria</title>

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

    <a href="my_orders.php" class="active">
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

                <h2>My Orders</h2>

                <p>View all your cafeteria orders</p>

            </div>

            <div class="student-profile">

                👤

                <strong>
                    <?php echo htmlspecialchars($student['student_name']); ?>
                </strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>Your Orders 🧾</h1>

            <p>
                Here you can view all your current and previous orders.
            </p>

        </section>


        <section class="student-orders">

            <div class="student-section-header">

                <h2>All Orders</h2>

                <a href="menu.php">
                    Order Food →
                </a>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
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

                                <?php

                                if ($order['quantity']) {
                                    echo $order['quantity'];
                                } else {
                                    echo "-";
                                }

                                ?>

                            </td>

                            <td>

                                <?php

                                if ($order['unit_price']) {
                                    echo "Tk " . $order['unit_price'];
                                } else {
                                    echo "-";
                                }

                                ?>

                            </td>

                            <td>
                                Tk <?php echo $order['total_amount']; ?>
                            </td>

                            <td>

                                <span class="student-status">

                                    <?php
                                    echo htmlspecialchars($order['status']);
                                    ?>

                                </span>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="7">
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