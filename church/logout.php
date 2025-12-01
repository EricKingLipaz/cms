<?php
session_start();
error_reporting(0);

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to landing page
header("Location: landing.php");
exit;
?>