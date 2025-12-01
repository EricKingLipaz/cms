<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Authentication Test</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Authentication Test</h1>";

echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Before Including checklogin.php</h2>
        <div class='text-sm'>";

echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>odmsaid in session:</strong> " . (isset($_SESSION['odmsaid']) ? $_SESSION['odmsaid'] : 'Not set') . "</p>";

echo "</div></div>";

// Include the checklogin file
include('includes/checklogin.php');

echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>After Including checklogin.php</h2>
        <div class='text-sm'>";

echo "<p><strong>Functions available:</strong></p>";
echo "<ul class='list-disc pl-5'>";
echo "<li>check_login(): " . (function_exists('check_login') ? 'Available' : 'Not available') . "</li>";
echo "<li>is_super_admin(): " . (function_exists('is_super_admin') ? 'Available' : 'Not available') . "</li>";
echo "<li>is_branch_admin(): " . (function_exists('is_branch_admin') ? 'Available' : 'Not available') . "</li>";
echo "<li>get_user_branch(): " . (function_exists('get_user_branch') ? 'Available' : 'Not available') . "</li>";
echo "<li>can_access_branch(): " . (function_exists('can_access_branch') ? 'Available' : 'Not available') . "</li>";
echo "</ul>";

// Test the functions
echo "<h3 class='font-bold mt-4'>Function Tests:</h3>";

if (function_exists('check_login')) {
    echo "<p>check_login() function exists</p>";
} else {
    echo "<p class='text-red-600'>check_login() function does not exist</p>";
}

if (function_exists('is_super_admin')) {
    $is_super = is_super_admin();
    echo "<p>is_super_admin(): " . ($is_super ? 'true' : 'false') . "</p>";
} else {
    echo "<p class='text-red-600'>is_super_admin() function does not exist</p>";
}

echo "</div></div>";

// Test database connection and fetch user data
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Database Test</h2>
        <div class='text-sm'>";

include('includes/dbconnection.php');

if (isset($_SESSION['odmsaid'])) {
    $aid = $_SESSION['odmsaid'];
    echo "<p>Attempting to fetch user with ID: $aid</p>";
    
    try {
        $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        if ($query->rowCount() > 0) {
            echo "<p class='text-green-600'>✓ User found in database</p>";
            foreach ($results as $row) {
                echo "<p><strong>UserName:</strong> " . $row->UserName . "</p>";
                echo "<p><strong>AdminName:</strong> " . $row->AdminName . "</p>";
                echo "<p><strong>branch_id:</strong> " . $row->branch_id . "</p>";
            }
        } else {
            echo "<p class='text-red-600'>✗ No user found with ID: $aid</p>";
        }
    } catch (Exception $e) {
        echo "<p class='text-red-600'>✗ Database query failed: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>No user ID in session</p>";
}

echo "</div></div>";

echo "<div class='text-center mt-8'>
        <a href='index.php' class='bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4'>
            <i class='fas fa-sign-in-alt mr-2'></i>Login
        </a>
        <a href='debug_login.php' class='bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-4'>
            <i class='fas fa-bug mr-2'></i>Debug Login
        </a>
        <a href='main_dashboard.php' class='bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded'>
            <i class='fas fa-tachometer-alt mr-2'></i>Dashboard
        </a>
      </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js'></script>
</body>
</html>";
?>