<?php
require_once "../db.php";

$result = $conn->query("
    SELECT food_items.*, categories.category_name
    FROM food_items
    JOIN categories
    ON food_items.category_id = categories.category_id
    ORDER BY food_items.food_name
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Browse Menu - EWU Cafeteria</title>

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

            <a href="menu.php" class="active">
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

                <h2>Browse Menu</h2>

                <p>Choose something delicious today!</p>

            </div>

            <div class="student-profile">

                👤 <strong>Student</strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>Today's Menu 🍽️</h1>

            <p>Explore our available food items.</p>

        </section>


        <section>

            <?php if ($result->num_rows > 0) { ?>

                <?php while ($food = $result->fetch_assoc()) { ?>

                    <div class="student-card">

                        <div class="student-card-icon">
                            🍔
                        </div>

                        <div>

                            <h3>
                                <?php echo $food['food_name']; ?>
                            </h3>

                            <p>
                                Category:
                                <?php echo $food['category_name']; ?>
                            </p>

                            <p>
                                <?php echo $food['description']; ?>
                            </p>

                            <p>
                                <strong>
                                    Tk <?php echo $food['price']; ?>
                                </strong>
                            </p>

                            <?php if ($food['availability']) { ?>

                                <p>
                                    <span class="student-status completed">
                                        Available
                                    </span>
                                </p>

                                <a href="place_order.php?food_id=<?php echo $food['food_id']; ?>">
    Order →
</a>

                            <?php } else { ?>

                                <p>
                                    <span class="student-status">
                                        Unavailable
                                    </span>
                                </p>

                            <?php } ?>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p>No food items available right now.</p>

            <?php } ?>

        </section>

    </main>

</div>

</body>

</html>