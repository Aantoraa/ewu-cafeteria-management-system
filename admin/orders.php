<?php

session_start();

require_once "../db.php";

$result = $conn->query("
    SELECT
        orders.order_id,
        orders.order_date,
        orders.total_amount,
        orders.status,

        students.student_name,

        food_items.food_name,
        order_details.quantity,

        payments.payment_method,
        payments.payment_status

    FROM orders

    LEFT JOIN students
        ON orders.student_id = students.student_id

    LEFT JOIN order_details
        ON orders.order_id = order_details.order_id

    LEFT JOIN food_items
        ON order_details.food_id = food_items.food_id

    LEFT JOIN payments
        ON orders.order_id = payments.order_id

    ORDER BY orders.order_date DESC
");

if (!$result) {
    die("Order query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Orders - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="admin-dashboard">


    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <h2>🍽️ EWU</h2>

            <p>Cafeteria</p>

        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php">
                🏠 Dashboard
            </a>

            <a href="food_items.php">
                🍔 Food Items
            </a>

            <a href="categories.php">
                📂 Categories
            </a>

            <a href="orders.php" class="active">
                🧾 Orders
            </a>

            <a href="students.php">
                👨‍🎓 Students
            </a>

            <a href="staff.php">
                👨‍🍳 Staff
            </a>

        </nav>


        <div class="sidebar-logout">

            <a href="../login.php">
                🚪 Logout
            </a>

        </div>

    </aside>


    <!-- Main -->

    <main class="admin-main">


        <header class="admin-header">

            <div>

                <h2>Manage Orders</h2>

                <p>View and manage student orders</p>

            </div>


            <div class="admin-profile">

                👤 <strong>Admin</strong>

            </div>

        </header>


        <section class="management-section">

            <h2>All Orders</h2>


            <table>

                <thead>

<tr>

    <th>Order ID</th>

    <th>Student</th>

    <th>Food</th>

    <th>Qty</th>

    <th>Date</th>

    <th>Total</th>

    <th>Payment</th>

    <th>Payment Status</th>

    <th>Order Status</th>

</tr>

</thead>


               <tbody>

<?php if ($result->num_rows > 0) { ?>

    <?php while ($order = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                #<?php echo $order['order_id']; ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $order['student_name'] ?? 'Unknown'
                );
                ?>
            </td>

            <td>

                <?php

                if ($order['food_name']) {

                    echo htmlspecialchars(
                        $order['food_name']
                    );

                } else {

                    echo "N/A";

                }

                ?>

            </td>

            <td>

                <?php

                echo $order['quantity']
                    ? $order['quantity']
                    : "-";

                ?>

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
                Tk <?php echo $order['total_amount']; ?>
            </td>

            <td>

                <?php

                echo $order['payment_method']
                    ? $order['payment_method']
                    : "Not Paid";

                ?>

            </td>

            <td>

                <?php

                echo $order['payment_status']
                    ? $order['payment_status']
                    : "Not Paid";

                ?>

            </td>

            <td>

                <form
                    method="POST"
                    action="update_order_status.php"
                >

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php echo $order['order_id']; ?>"
                    >

                    <select name="status">

                        <option value="Pending"
                            <?php
                            if ($order['status'] == 'Pending')
                                echo 'selected';
                            ?>>
                            Pending
                        </option>

                        <option value="Preparing"
                            <?php
                            if ($order['status'] == 'Preparing')
                                echo 'selected';
                            ?>>
                            Preparing
                        </option>

                        <option value="Ready"
                            <?php
                            if ($order['status'] == 'Ready')
                                echo 'selected';
                            ?>>
                            Ready
                        </option>

                        <option value="Completed"
                            <?php
                            if ($order['status'] == 'Completed')
                                echo 'selected';
                            ?>>
                            Completed
                        </option>

                    </select>

                    <button type="submit">
                        Update
                    </button>

                </form>

            </td>

        </tr>

    <?php } ?>

<?php } else { ?>

    <tr>

        <td colspan="9">
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