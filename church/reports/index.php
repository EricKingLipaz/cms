<?php
session_start();
error_reporting(0);
include('../includes/checklogin.php');
check_login();

// Redirect to dashboard
header("Location: dashboard.php");
exit;
?>