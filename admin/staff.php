<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit;
}


/* =========================
   DELETE STAFF
========================= */

if (isset($_GET['delete'])) {

    $staff_id =
        intval($_GET['delete']);


    $stmt = $conn->prepare("

        DELETE FROM staff

        WHERE staff_id = ?

    ");


    $stmt->bind_param(
        "i",
        $staff_id
    );


    $stmt->execute();


    header("Location: staff.php");

    exit;
}


/* =========================
   ADD STAFF
========================= */

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['add_staff'])
) {

    $user_id =
        intval($_POST['user_id']);

    $staff_name =
        trim($_POST['staff_name']);

    $position =
        trim($_POST['position']);

    $phone =
        trim($_POST['phone']);


    $stmt = $conn->prepare("

        INSERT INTO staff
        (
            user_id,
            staff_name,
            position,
            phone
        )

        VALUES (?, ?, ?, ?)

    ");


    $stmt->bind_param(
        "isss",
        $user_id,
        $staff_name,
        $position,
        $phone
    );


    $stmt->execute();


    header("Location: staff.php");

    exit;
}


/* =========================
   GET STAFF
========================= */

$staff_result = $conn->query("

    SELECT
        staff_id,
        user_id,
        staff_name,
        position,
        phone

    FROM staff

    ORDER BY staff_id DESC

");


if (!$staff_result) {

    die(
        "Staff query failed: "
        . $conn->error
    );

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

    <title>Manage Staff - EWU Cafeteria</title>

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

            <p>Admin Panel</p>

        </div>


        <nav class="student-sidebar-menu">


            <a href="dashboard.php">
                🏠 Dashboard
            </a>


            <a href="categories.php">
                📂 Categories
            </a>


            <a href="food_items.php">
                🍔 Food Items
            </a>


            <a href="students.php">
                👨‍🎓 Students
            </a>


            <a
                href="staff.php"
                class="active"
            >
                👨‍🍳 Staff
            </a>


            <a href="orders.php">
                🧾 Orders
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
                    Manage Staff
                </h2>

                <p>
                    View and manage cafeteria staff
                </p>

            </div>


        </header>



        <!-- ADD STAFF -->

        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    Add Staff
                </h2>

            </div>


            <form method="POST">


                <input
                    type="number"
                    name="user_id"
                    placeholder="User ID"
                    required
                >


                <input
                    type="text"
                    name="staff_name"
                    placeholder="Staff Name"
                    required
                >


                <input
                    type="text"
                    name="position"
                    placeholder="Position"
                    required
                >


                <input
                    type="text"
                    name="phone"
                    placeholder="Phone"
                    required
                >


                <button
                    type="submit"
                    name="add_staff"
                >
                    Add Staff
                </button>


            </form>


        </section>



        <!-- STAFF LIST -->

        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    All Staff
                </h2>

            </div>


            <table>


                <thead>

                    <tr>

                        <th>
                            Staff ID
                        </th>

                        <th>
                            User ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if ($staff_result->num_rows > 0) {

                    while (
                        $staff =
                        $staff_result->fetch_assoc()
                    ) {

                ?>


                    <tr>


                        <td>

                            <?php
                            echo $staff['staff_id'];
                            ?>

                        </td>


                        <td>

                            <?php
                            echo $staff['user_id'];
                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $staff['staff_name']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $staff['position']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $staff['phone']
                            );

                            ?>

                        </td>


                        <td>

                            <a
                                href="staff.php?delete=<?php
                                    echo $staff['staff_id'];
                                ?>"
                                onclick="
                                    return confirm(
                                        'Delete this staff member?'
                                    );
                                "
                            >
                                Delete
                            </a>

                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td colspan="6">
                            No staff found.
                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>


            </table>


        </section>


    </main>


</div>


</body>

</html>