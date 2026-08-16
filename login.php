<?php
session_start();

require_once "db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
            WHERE email = '$email'
            AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'Student') {

            header("Location: student/dashboard.php");
            exit;

        } elseif ($user['role'] == 'Admin') {

            header("Location: admin/dashboard.php");
            exit;

        } elseif ($user['role'] == 'Staff') {

            header("Location: staff/dashboard.php");
            exit;

        }

    } else {

        $error = "Invalid email or password.";

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EWU Cafeteria - Login</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="login-page">

        <!-- Left Side -->
        <div class="login-image">

            <div class="welcome-text">

                <h1>EWU Cafeteria</h1>

                <p>
                    Delicious food, easy ordering,
                    and a better cafeteria experience.
                </p>

            </div>

        </div>


        <!-- Right Side -->
        <div class="login-section">

            <div class="login-box">

                <h2>Welcome Back!</h2>

                <p class="login-subtitle">
                    Login to access your cafeteria account.
                </p>
                <?php
                    if (isset($error)) {
                         echo "<p>$error</p>";
                }
                ?>

                <form action="" method="POST">

                    <label for="email">Email Address</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >


                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >


                    <button type="submit" name="login">
    Login
</button>

                </form>


                <p class="login-info">
                    Login as Student, Staff, or Admin
                </p>

            </div>

        </div>

    </div>

</body>

</html>