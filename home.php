<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Home - GasPro</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="home-content">
        <h1>Welcome to GasPro</h1>
        <p>
            GasPro is an innovative platform designed to make gas purchases easy, fast, and reliable.
            Our service allows users to order various types of gas products such as small and large gas cylinders,
            portable gas stoves, and gas for water heating – all from the comfort of their home.
        </p>
        <p>
            This project aims to provide a safe and user-friendly system for both customers and administrators,
            while supporting future development as part of a final project.
        </p>

        <h2>About Us</h2>
        <p>
            This system was developed by Rita Miroshnik & Lihod Ivgi – a Software Engineering students who are passionate about building useful and modern web solutions.
        </p>

        <form method="post" action="logout.php">
            <input type="submit" value="Log Out" class="logout-btn">
        </form>
    </div>
</body>
</html>