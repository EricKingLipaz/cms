<?php
// Test database connection
include('includes/dbconnection.php');

echo "<h1>Database Connection Test</h1>";

// Test PDO connection
try {
    // Test query to check if we can access the database
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM tbladmin");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>✓ PDO Connection successful. Found {$result['count']} admin users in tbladmin table.</p>";
} catch (Exception $e) {
    echo "<p>✗ PDO Connection failed: " . $e->getMessage() . "</p>";
}

// Test MySQLi connection
try {
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tbladmin");
    $row = mysqli_fetch_assoc($result);
    echo "<p>✓ MySQLi Connection successful. Found {$row['count']} admin users in tbladmin table.</p>";
} catch (Exception $e) {
    echo "<p>✗ MySQLi Connection failed: " . $e->getMessage() . "</p>";
}

echo "<h2>Available Tables</h2>";
try {
    $stmt = $dbh->query("SHOW TABLES");
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>{$row[0]}</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p>✗ Failed to list tables: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php?show_login=true'>Go to Login Page</a></p>";
?>