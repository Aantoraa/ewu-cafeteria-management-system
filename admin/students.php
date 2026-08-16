<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


/* =========================
   DELETE STUDENT
========================= */

if (isset($_GET['delete'])) {

    $student_id = intval($_GET['delete']);

    $stmt = $conn->prepare("
        DELETE FROM students
        WHERE student_id = ?
    ");

    $stmt->bind_param(
        "i",
        $student_id
    );

    $stmt->execute();

    header("Location: students.php");
    exit;
}


/* =========================
   ADD STUDENT
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['add_student'])) {

    $user_id =
        intval($_POST['user_id']);

    $student_name =
        trim($_POST['student_name']);

    $department =
        trim($_POST['department']);

    $phone =
        trim($_POST['phone']);


    $stmt = $conn->prepare("

        INSERT INTO students
        (
            user_id,
            student_name,
            department,
            phone
        )

        VALUES (?, ?, ?, ?)

    ");


    $stmt->bind_param(
        "isss",
        $user_id,
        $student_name,
        $department,
        $phone
    );


    $stmt->execute();


    header("Location: students.php");
    exit;
}


/* =========================
   GET STUDENTS
========================= */

$students_result = $conn->query("

    SELECT
        student_id,
        user_id,
        student_name,
        department,
        phone

    FROM students

    ORDER BY student_id DESC

");


if (!$students_result) {

    die(
        "Student query failed: "
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

    <title>Manage Students - EWU Cafeteria</title>

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


            <a
                href="students.php"
                class="active"
            >
                👨‍🎓 Students
            </a>


            <a href="staff.php">
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
                    Manage Students
                </h2>

                <p>
                    View and manage cafeteria students
                </p>

            </div>


        </header>



        <!-- ADD STUDENT -->

        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    Add Student
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
                    name="student_name"
                    placeholder="Student Name"
                    required
                >


                <input
                    type="text"
                    name="department"
                    placeholder="Department"
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
                    name="add_student"
                >
                    Add Student
                </button>


            </form>


        </section>



        <!-- STUDENT LIST -->

        <section class="student-orders">


            <div class="student-section-header">

                <h2>
                    All Students
                </h2>

            </div>


            <table>


                <thead>

                    <tr>

                        <th>
                            Student ID
                        </th>

                        <th>
                            User ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Department
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

                if ($students_result->num_rows > 0) {

                    while (
                        $student =
                        $students_result->fetch_assoc()
                    ) {

                ?>


                    <tr>


                        <td>

                            <?php
                            echo $student['student_id'];
                            ?>

                        </td>


                        <td>

                            <?php
                            echo $student['user_id'];
                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['student_name']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['department']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['phone']
                            );

                            ?>

                        </td>


                        <td>

                            <a
                                href="students.php?delete=<?php
                                    echo $student['student_id'];
                                ?>"
                                onclick="
                                    return confirm(
                                        'Delete this student?'
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
                            No students found.
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