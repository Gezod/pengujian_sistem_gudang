<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke login.php dengan pesan logout
header('Location: login.php?logout=success');
exit;
?>