<?php
session_start();
session_unset();  // מוחק את כל משתני הסשן
session_destroy(); // סוגר את הסשן

header("Location: index.php");  // מפנה לדף ההתחברות (index.php או כל דף התחברות שברצונך)
exit();
?>
