<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


/* =========================
   UPDATE ORDER STATUS
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed_statuses = [
        "Pending",
        "Preparing",
        "Ready",
        "Completed"
    ];

    if (in_array($status, $allowed_statuses)) {

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
    }

    header("Location: orders.php");
    exit;
}


/* =========================
   GET ORDERS
========================= */

$orders_result = $conn->query("
    SELECT
        o.order_id,
        o.order_date,
        o.total_amount,
        o.status,
        s.student_name,
        GROUP_CONCAT(
            CONCAT(f.food_name, ' x', od.quantity)
            SEPARATOR ', '
        ) AS items
    FROM orders o

    LEFT JOIN students s
        ON o.student_id = s.student_id

    LEFT JOIN order_details od
        ON o.order_id = od.order_id

    LEFT JOIN food_items f
        ON od.food_id = f.food_id

    GROUP BY
        o.order_id,
        o.order_date,
        o.total_amount,
        o.status,
        s.student_name

    ORDER BY o.order_id DESC
");

if (!$orders_result) {
    die("Order query failed: " . $conn->error);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Staff Orders - EWU Cafeteria</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<div class="student-dashboard">


    <!-- SIDEBAR -->

    <aside class="student-sidebar">


        <div class="student-sidebar-logo">

            <h2>🍽️ EWU</h2>

            <p>Cafeteria Staff</p>

        </div>


        <nav class="student-sidebar-menu">


            <a href="dashboard.php">
                🏠 Dashboard
            </a>


            <a
                href="orders.php"
                class="active"
            >
                🧾 Manage Orders
            </a>


            <a href="../student/menu.php">
                🍔 View Menu
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


    <!-- MAIN CONTENT -->

    <main class="student-main">


        <header class="student-header">

            <div>

                <h2>
                    Manage Orders
                </h2>

                <p>
                    View and process student orders
                </p>

            </div>


            <div class="student-profile">

                👨‍🍳

                <strong>
                    Staff
                </strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>
                Cafeteria Orders 🧾
            </h1>

            <p>
                Update orders as they are prepared.
            </p>

        </section>


        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    All Orders
                </h2>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>Order ID</th>

                        <th>Student</th>

                        <th>Items</th>

                        <th>Total</th>

                        <th>Date</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>


                <?php if ($orders_result->num_rows > 0) { ?>


                    <?php while ($order = $orders_result->fetch_assoc()) { ?>


                        <tr>


                            <td>

                                #<?php
                                echo $order['order_id'];
                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $order['student_name']
                                    ?? 'Unknown'
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $order['items']
                                    ?? 'No items'
                                );

                                ?>

                            </td>


                            <td>

                                Tk

                                <?php

                                echo number_format(
                                    $order['total_amount'],
                                    2
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $order['order_date']
                                    )
                                );

                                ?>

                            </td>


                            <td>


                                <form
                                    method="POST"
                                >


                                    <input
                                        type="hidden"
                                        name="order_id"
                                        value="<?php
                                            echo $order['order_id'];
                                        ?>"
                                    >


                                    <select name="status">


                                        <option
                                            value="Pending"
                                            <?php

                                            if (
                                                $order['status']
                                                == 'Pending'
                                            ) {
                                                echo 'selected';
                                            }

                                            ?>
                                        >
                                            Pending
                                        </option>


                                        <option
                                            value="Preparing"
                                            <?php

                                            if (
                                                $order['status']
                                                == 'Preparing'
                                            ) {
                                                echo 'selected';
                                            }

                                            ?>
                                        >
                                            Preparing
                                        </option>


                                        <option
                                            value="Ready"
                                            <?php

                                            if (
                                                $order['status']
                                                == 'Ready'
                                            ) {
                                                echo 'selected';
                                            }

                                            ?>
                                        >
                                            Ready
                                        </option>


                                        <option
                                            value="Completed"
                                            <?php

                                            if (
                                                $order['status']
                                                == 'Completed'
                                            ) {
                                                echo 'selected';
                                            }

                                            ?>
                                        >
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

                        <td colspan="6">
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