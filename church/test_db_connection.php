<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Include database connection
include('includes/dbconnection.php');

try {
    // Test basic connection
    $stmt = $dbh->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if required tables exist
    $tables = ['tbladmin', 'branches', 'tblchristian', 'church_events', 'donations', 'church_equipment'];
    
    echo "<h3>Table Status:</h3><ul>";
    foreach ($tables as $table) {
        try {
            $stmt = $dbh->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "<li style='color: green;'>✓ $table exists ($count records)</li>";
        } catch (Exception $e) {
            echo "<li style='color: red;'>✗ $table does not exist or is inaccessible</li>";
        }
    }
    echo "</ul>";
    
    // Check admin users
    echo "<h3>Admin Users:</h3>";
    try {
        $stmt = $dbh->query("SELECT * FROM tbladmin");
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>UserName</th><th>FirstName</th><th>LastName</th><th>AdminName</th><th>Status</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['UserName'] . "</td>";
                echo "<td>" . $row['FirstName'] . "</td>";
                echo "<td>" . $row['LastName'] . "</td>";
                echo "<td>" . $row['AdminName'] . "</td>";
                echo "<td>" . $row['Status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No admin users found</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error fetching admin users: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
?>