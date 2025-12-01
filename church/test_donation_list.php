<?php
session_start();
error_reporting(0);

// Simulate a logged-in user
$_SESSION['odmsaid'] = 1; // Simulate user ID
$_SESSION['permission'] = 'Superuser'; // Simulate super admin
$_SESSION['branch_id'] = 1; // Simulate branch ID

// Now include the donation list page
include('donations/donation_list.php');
?>