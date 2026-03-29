<?php
session_start();

include 'connectdb.php';
include 'users.php';

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = trim($_POST['id']);
    $password = trim($_POST['password']);

    if (empty($id) || empty($password)) {
        $error = "Please fill in all fields"; // אנא מלא את כל השדות
    } elseif ($_SESSION['login_attempts'] >= 3) {
        $error = "User locked out after 3 failed login attempts."; // המשתמש נעול עקב 3 ניסיונות כניסה לא מוצלחים.
    } else {
        $user = users::loginUser($conn, $id, $password);

        if ($user !== false) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['isAdmin'] = $user['isAdmin'];

            if ($user['isAdmin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            $_SESSION['login_attempts']++;
            $error = "Incorrect username or password."; // שם משתמש או סיסמה שגויים.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="login-container">
        <h2>System Login</h2> <!-- התחברות למערכת -->
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="id">Username (ID):</label>
            <input type="text" name="id" id="id" required />

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required />

            <input type="submit" value="Login" />
        </form>
        <div class="signup-link">
            <a href="signup.php">Sign Up</a>
        </div>
    </div>
</body>
</html>
