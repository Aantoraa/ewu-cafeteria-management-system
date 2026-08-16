<?php

session_start();

require_once "../db.php";


/* Total Orders */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
");

$row = $result->fetch_assoc();

$total_orders = $row['total'];


/* Total Food Items */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM food_items
");

$row = $result->fetch_assoc();

$total_food_items = $row['total'];

/* Total Categories */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM categories
");

$row = $result->fetch_assoc();

$total_categories = $row['total'];


/* Total Students */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM students
");

$row = $result->fetch_assoc();

$total_students = $row['total'];


/* Total Staff */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM staff
");

$row = $result->fetch_assoc();

$total_staff = $row['total'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - EWU Cafeteria</title>

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

            <a href="dashboard.php" class="active">
    🏠 Dashboard
</a>

<a href="food_items.php">
    🍔 Food Items
</a>

<a href="categories.php">
    📂 Categories
</a>

<a href="orders.php">
    🧾 Orders
</a>

<a href="student.php">
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


    <!-- Main Content -->
    <main class="admin-main">

        <!-- Header -->
        <header class="admin-header">

            <div>
                <h2>Admin Dashboard</h2>
                <p>Manage your cafeteria system</p>
            </div>

            <div class="admin-profile">
                👤 <strong>Admin</strong>
            </div>

        </header>


        <!-- Welcome -->
        <section class="admin-welcome">

            <h1>Welcome back, Admin! 👋</h1>

            <p>
                Here's an overview of your EWU Cafeteria Management System.
            </p>

        </section>


        <!-- Summary Cards -->
        <section class="admin-cards">

            <div class="admin-card">

                <div class="card-icon">
                    🧾
                </div>

                <div>
                    <p>Total Orders</p>
                    <h2><?php echo $total_orders; ?></h2>
                </div>

            </div>


            <div class="admin-card">

                <div class="card-icon">
                    🍔
                </div>

                <div>
                    <p>Food Items</p>
                    
                    <h2><?php echo $total_food_items; ?></h2>
                </div>

            </div>


            <div class="admin-card">

                <div class="card-icon">
                    👥
                </div>

                <div>
                    <p>Students</p>
                    <h2><?php echo $total_students; ?></h2>
                </div>

            </div>


            <div class="admin-card">

    <div class="card-icon">
        📂
    </div>

    <div>
        <p>Categories</p>
        <h2><?php echo $total_categories; ?></h2>
    </div>

</div>

        </section>


        <!-- Quick Management -->
        <section class="management-section">

            <h2>Quick Management</h2>

            <div class="management-buttons">

                <a href="food_items.php">
    <button>🍔 Manage Food Items</button>
</a>

<a href="categories.php">
    <button>📂 Manage Categories</button>
</a>

<a href="orders.php" class="management-button">
    <button>🧾 Manage Orders</button>
</a>

<a href="students.php">
    <button>👨‍🎓 Manage Students</button>
</a>

<a href="staff.php">
    <button>👨‍🍳 Manage Staff</button>
</a>
            </div>

        </section>

    </main>

</div>

</body>

</html>