<?php
session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    die("User session not found. Please login again.");
}

$user_id = $_SESSION['user_id'];

$user_id = $_SESSION['user_id'];

$student_result = $conn->query("
    SELECT student_id, student_name
    FROM students
    WHERE user_id = $user_id
");

$student = $student_result->fetch_assoc();

$student_id = $student['student_id'];

$food_id = $_GET['food_id'];

$food_result = $conn->query("
    SELECT *
    FROM food_items
    WHERE food_id = $food_id
    AND availability = 1
");

$food = $food_result->fetch_assoc();

if (!$food) {
    die("Food item is not available.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Place Order - EWU Cafeteria</title>

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

    <a href="menu.php" class="active">
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


    <!-- Main -->

    <main class="student-main">

        <header class="student-header">

            <div>

                <h2>Place Order</h2>

                <p>Order your favorite food.</p>

            </div>

            <div class="student-profile">

                👤 <strong><?php echo $student['student_name']; ?></strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>Confirm Your Order 🍔</h1>

            <p>Review your food item and choose the quantity.</p>

        </section>


        <section class="student-card">

            <div class="student-card-icon">
                🍔
            </div>

            <div>

                <h2>
                    <?php echo $food['food_name']; ?>
                </h2>

                <p>
                    <?php echo $food['description']; ?>
                </p>

                <p>
                    Price:
                    <strong>
                        Tk <?php echo $food['price']; ?>
                    </strong>
                </p>


                <form method="POST" action="confirm_order.php">

                    <input
                        type="hidden"
                        name="food_id"
                        value="<?php echo $food['food_id']; ?>"
                    >

                    <label for="quantity">
                        Quantity:
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        min="1"
                        value="1"
                        required
                    >

                    <br><br>

                    <button type="submit">
                        Confirm Order
                    </button>

                </form>

            </div>

        </section>

    </main>

</div>

</body>

</html>