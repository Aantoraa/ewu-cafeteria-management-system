<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


/* =========================
   GET STAFF
========================= */

$stmt = $conn->prepare("
    SELECT
        staff_id,
        staff_name,
        position,
        phone
    FROM staff
    WHERE user_id = ?
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$staff = $stmt->get_result()->fetch_assoc();


if (!$staff) {
    die("Staff profile not found.");
}


/* =========================
   UPDATE PROFILE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $staff_name = trim(
        $_POST['staff_name']
    );

    $position = trim(
        $_POST['position']
    );

    $phone = trim(
        $_POST['phone']
    );


    $stmt = $conn->prepare("
        UPDATE staff
        SET
            staff_name = ?,
            position = ?,
            phone = ?
        WHERE staff_id = ?
    ");


    $stmt->bind_param(
        "sssi",
        $staff_name,
        $position,
        $phone,
        $staff['staff_id']
    );


    $stmt->execute();


    header(
        "Location: profile.php?success=1"
    );

    exit;
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

    <title>Staff Profile - EWU Cafeteria</title>

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


            <a href="orders.php">
                🧾 Manage Orders
            </a>


            <a href="../student/menu.php">
                🍔 View Menu
            </a>


            <a
                href="profile.php"
                class="active"
            >
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


        <header class="student-header">

            <div>

                <h2>
                    My Profile
                </h2>

                <p>
                    Manage your staff information
                </p>

            </div>


            <div class="student-profile">

                👨‍🍳

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $staff['staff_name']
                    );

                    ?>

                </strong>

            </div>

        </header>


        <section class="student-welcome">

            <h1>
                Staff Profile 👤
            </h1>

            <p>
                View and update your information.
            </p>

        </section>


        <section class="student-orders">


            <?php if (isset($_GET['success'])) { ?>

                <p style="color: green;">
                    Profile updated successfully!
                </p>

            <?php } ?>


            <form method="POST">


                <label>
                    Staff Name
                </label>


                <input
                    type="text"
                    name="staff_name"
                    value="<?php

                    echo htmlspecialchars(
                        $staff['staff_name']
                    );

                    ?>"
                    required
                >


                <br><br>


                <label>
                    Position
                </label>


                <input
                    type="text"
                    name="position"
                    value="<?php

                    echo htmlspecialchars(
                        $staff['position']
                    );

                    ?>"
                    required
                >


                <br><br>


                <label>
                    Phone
                </label>


                <input
                    type="text"
                    name="phone"
                    value="<?php

                    echo htmlspecialchars(
                        $staff['phone']
                    );

                    ?>"
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