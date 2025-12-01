<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Login Debug</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Login Debug Information</h1>";

echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Session Information</h2>
        <div class='text-sm'>";

echo "<p><strong>Session Status:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "</p>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";

echo "<h3 class='font-bold mt-4'>Session Variables:</h3>";
if (!empty($_SESSION)) {
    foreach ($_SESSION as $key => $value) {
        echo "<p><strong>$key:</strong> ";
        if (is_array($value)) {
            echo "<pre>" . print_r($value, true) . "</pre>";
        } else {
            echo htmlspecialchars($value);
        }
        echo "</p>";
    }
} else {
    echo "<p>No session variables found</p>";
}

echo "</div></div>";

// Check if login form was submitted
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>POST Data</h2>
        <div class='text-sm'>";

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
    }
} else {
    echo "<p>No POST data found</p>";
}

echo "</div></div>";

// Check database connection
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Database Connection</h2>
        <div class='text-sm'>";

include('includes/dbconnection.php');

try {
    $stmt = $dbh->query("SELECT 1");
    echo "<p class='text-green-600'>✓ Database connection successful</p>";
    
    // Try to fetch admin user
    if (isset($_SESSION['odmsaid'])) {
        $aid = $_SESSION['odmsaid'];
        $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        echo "<h3 class='font-bold mt-4'>Admin User Information:</h3>";
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                echo "<p><strong>ID:</strong> " . $row->ID . "</p>";
                echo "<p><strong>UserName:</strong> " . $row->UserName . "</p>";
                echo "<p><strong>FirstName:</strong> " . $row->FirstName . "</p>";
                echo "<p><strong>LastName:</strong> " . $row->LastName . "</p>";
                echo "<p><strong>AdminName:</strong> " . $row->AdminName . "</p>";
                echo "<p><strong>Email:</strong> " . $row->Email . "</p>";
                echo "<p><strong>branch_id:</strong> " . $row->branch_id . "</p>";
            }
        } else {
            echo "<p class='text-red-600'>✗ No admin user found with ID: $aid</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='text-red-600'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "</div></div>";

// Check required files
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Required Files</h2>
        <div class='text-sm'>";

$files = array(
    'includes/dbconnection.php',
    'includes/checklogin.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<p class='text-green-600'>✓ $file exists</p>";
    } else {
        echo "<p class='text-red-600'>✗ $file not found</p>";
    }
}

echo "</div></div>";

echo "<div class='text-center mt-8'>
        <a href='index.php' class='bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4'>
            <i class='fas fa-sign-in-alt mr-2'></i>Login
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