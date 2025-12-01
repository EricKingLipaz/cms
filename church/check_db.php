<?php
include('includes/dbconnection.php');

echo "<h2>Database Connection Test</h2>";

try {
    // Test connection
    $stmt = $dbh->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if tbladmin table exists
    $stmt = $dbh->query("SHOW TABLES LIKE 'tbladmin'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ tbladmin table exists</p>";
        
        // Check table structure
        $stmt = $dbh->query("DESCRIBE tbladmin");
        echo "<h3>tbladmin structure:</h3><ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>" . $row['Field'] . " - " . $row['Type'] . "</li>";
        }
        echo "</ul>";
        
        // Check if we have any admin users
        $stmt = $dbh->query("SELECT * FROM tbladmin LIMIT 5");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Found " . $stmt->rowCount() . " admin user(s)</p>";
            echo "<h3>Admin users:</h3><ul>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>ID: " . $row['ID'] . " | UserName: " . $row['UserName'] . " | AdminName: " . $row['AdminName'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠ No admin users found</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ tbladmin table does not exist</p>";
    }
    
    // Check if branches table exists
    $stmt = $dbh->query("SHOW TABLES LIKE 'branches'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ branches table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ branches table does not exist</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
?>