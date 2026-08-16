<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


/* Get Staff Information */

$stmt = $conn->prepare("
    SELECT staff_id, staff_name, position, phone
    FROM staff
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);

$stmt->execute();

$staff = $stmt->get_result()->fetch_assoc();


if (!$staff) {
    die("Staff record not found.");
}


/* Total Orders */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
");

$row = $result->fetch_assoc();

$total_orders = $row['total'];


/* Pending Orders */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Pending'
");

$row = $result->fetch_assoc();

$pending_orders = $row['total'];


/* Preparing Orders */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Preparing'
");

$row = $result->fetch_assoc();

$preparing_orders = $row['total'];


/* Ready Orders */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Ready'
");

$row = $result->fetch_assoc();

$ready_orders = $row['total'];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Staff Dashboard - EWU Cafeteria</title>

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


            <a
                href="dashboard.php"
                class="active"
            >
                🏠 Dashboard
            </a>


            <a href="orders.php">
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



    <!-- MAIN -->

    <main class="student-main">


        <!-- HEADER -->

        <header class="student-header">


            <div>

                <h2>Staff Dashboard</h2>

                <p>
                    EWU Cafeteria Staff Panel
                </p>

            </div>


            <div class="student-profile">

                👤

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $staff['staff_name']
                    );

                    ?>

                </strong>

            </div>


        </header>



        <!-- WELCOME -->

        <section class="student-welcome">


            <h1>

                Welcome back,

                <?php

                echo htmlspecialchars(
                    $staff['staff_name']
                );

                ?>

               ! 👋

            </h1>


            <p>

                Manage today's cafeteria orders
                and keep them moving.

            </p>


        </section>



        <!-- STATISTICS -->

        <section class="student-cards">


            <div class="student-card">

                <div class="student-card-icon">
                    🧾
                </div>

                <div>

                    <h3>
                        Total Orders
                    </h3>

                    <p>
                        <?php echo $total_orders; ?>
                        orders
                    </p>

                    <a href="orders.php">
                        View Orders →
                    </a>

                </div>

            </div>



            <div class="student-card">

                <div class="student-card-icon">
                    ⏳
                </div>

                <div>

                    <h3>
                        Pending
                    </h3>

                    <p>
                        <?php echo $pending_orders; ?>
                        orders
                    </p>

                    <a href="orders.php">
                        Manage →
                    </a>

                </div>

            </div>



            <div class="student-card">

                <div class="student-card-icon">
                    🍳
                </div>

                <div>

                    <h3>
                        Preparing
                    </h3>

                    <p>
                        <?php echo $preparing_orders; ?>
                        orders
                    </p>

                    <a href="orders.php">
                        Manage →
                    </a>

                </div>

            </div>



            <div class="student-card">

                <div class="student-card-icon">
                    ✅
                </div>

                <div>

                    <h3>
                        Ready
                    </h3>

                    <p>
                        <?php echo $ready_orders; ?>
                        orders
                    </p>

                    <a href="orders.php">
                        View →
                    </a>

                </div>

            </div>


        </section>



        <!-- QUICK ACTIONS -->

        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    Quick Actions
                </h2>

            </div>


            <div class="student-cards">


                <div class="student-card">

                    <div class="student-card-icon">
                        🧾
                    </div>

                    <div>

                        <h3>
                            Manage Orders
                        </h3>

                        <p>
                            View incoming orders
                            and update their status.
                        </p>

                        <a href="orders.php">
                            Open Orders →
                        </a>

                    </div>

                </div>



                <div class="student-card">

                    <div class="student-card-icon">
                        👤
                    </div>

                    <div>

                        <h3>
                            My Profile
                        </h3>

                        <p>
                            View and update your
                            staff information.
                        </p>

                        <a href="profile.php">
                            View Profile →
                        </a>

                    </div>

                </div>


            </div>


        </section>



</main>


</div>


</body>

</html>