<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate a logged-in user
$_SESSION['odmsaid'] = 1; // Simulate user ID
$_SESSION['permission'] = 'Superuser'; // Simulate super admin
$_SESSION['branch_id'] = 1; // Simulate branch ID

// Include the main dashboard content
include('main_dashboard.php');
?>