<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

function checkRole($requiredRole) {
    if ($_SESSION['role'] !== $requiredRole) {
        header("Location: ../login.php");
        exit();
    }
}
?>