<?php
session_start();

include 'connectdb.php';
include 'users.php';

$error = "";
$success = "";

// בדיקה אם נשלח טופס
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST['id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // בדיקה אם כל השדות מלאים
    if (empty($id) || empty($full_name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $newUser = new users($id, $full_name, $password, $email, 0);

        // בדיקה אם המשתמש כבר קיים
        if ($newUser->isusersExist($conn)) {
            $error = "User already exists.";
        } else {
            // ניסיון להוספת המשתמש למסד
            if ($newUser->addusers($conn)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="login-container">
        <h2>Sign Up</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="id">ID:</label>
            <input type="text" name="id" id="id" required />

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" required />

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required />

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required />

            <input type="submit" value="Register" />
        </form>

        <div class="signup-link">
            <a href="index.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
