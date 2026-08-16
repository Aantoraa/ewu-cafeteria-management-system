<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$student_result = $conn->query("
    SELECT student_id, student_name, department, phone
    FROM students
    WHERE user_id = $user_id
");

$student = $student_result->fetch_assoc();

if (!$student) {
    die("Student record not found.");
}


/* Update Profile */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = $_POST['student_name'];
    $department = $_POST['department'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("
        UPDATE students
        SET student_name = ?, department = ?, phone = ?
        WHERE student_id = ?
    ");

    $stmt->bind_param(
        "sssi",
        $student_name,
        $department,
        $phone,
        $student['student_id']
    );

    if ($stmt->execute()) {

        header("Location: profile.php?success=1");
        exit;

    } else {

        $error = "Failed to update profile.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile - EWU Cafeteria</title>

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

    <a href="profile.php" class="active">
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

                <h2>My Profile</h2>

                <p>Manage your cafeteria account</p>

            </div>


            <div class="student-profile">

                👤

                <strong>
                    <?php echo htmlspecialchars($student['student_name']); ?>
                </strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>My Profile 👤</h1>

            <p>
                View and update your student information.
            </p>

        </section>


        <section class="management-section">

            <?php if (isset($_GET['success'])) { ?>

                <p style="color: green;">
                    Profile updated successfully!
                </p>

            <?php } ?>


            <?php if (isset($error)) { ?>

                <p style="color: red;">
                    <?php echo $error; ?>
                </p>

            <?php } ?>


            <form method="POST">

                <label>Student Name</label>

                <input
                    type="text"
                    name="student_name"
                    value="<?php echo htmlspecialchars($student['student_name']); ?>"
                    required
                >


                <br><br>


                <label>Department</label>

                <input
                    type="text"
                    name="department"
                    value="<?php echo htmlspecialchars($student['department']); ?>"
                    required
                >


                <br><br>


                <label>Phone</label>

                <input
                    type="text"
                    name="phone"
                    value="<?php echo htmlspecialchars($student['phone']); ?>"
                    required
                >


                <br><br>


                <button type="submit">
                    Update Profile
                </button>

            </form>

        </section>


    </main>

</div>

</body>

</html>