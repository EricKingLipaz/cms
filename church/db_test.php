<?php
// Database connection test
include('includes/dbconnection.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Database Connection Test</h1>";

echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>Database Connection Status</h2>";

try {
    // Test PDO connection
    if ($dbh) {
        echo "<p class='text-green-600'>✓ PDO connection successful</p>";
        
        // Test basic query
        $stmt = $dbh->query("SELECT DATABASE() as db_name");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Connected to database: " . $result['db_name'] . "</p>";
        
        // Check if required tables exist
        $tables = array('tbladmin', 'tblchristian', 'church_events', 'donations', 'church_equipment', 'branches');
        echo "<h3 class='font-bold mt-4'>Required Tables:</h3>";
        foreach ($tables as $table) {
            try {
                $stmt = $dbh->prepare("SELECT COUNT(*) as count FROM $table");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p class='text-green-600'>✓ $table exists ({$result['count']} records)</p>";
            } catch (Exception $e) {
                echo "<p class='text-red-600'>✗ $table does not exist or is inaccessible</p>";
            }
        }
    } else {
        echo "<p class='text-red-600'>✗ PDO connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p class='text-red-600'>✗ Database connection error: " . $e->getMessage() . "</p>";
}

echo "</div>
    </div>
</body>
</html>";
?>