<?php
include('includes/dbconnection.php');

echo "<h2>Member Table Structure</h2>";

try {
    // Check if tblchristian table exists
    $stmt = $dbh->query("SHOW TABLES LIKE 'tblchristian'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ tblchristian table exists</p>";
        
        // Get table structure
        $stmt = $dbh->query("DESCRIBE tblchristian");
        echo "<h3>Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count members
        $stmt = $dbh->query("SELECT COUNT(*) as count FROM tblchristian");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Total members: " . $result['count'] . "</p>";
        
        // Check if branch_id column exists
        $stmt = $dbh->query("SHOW COLUMNS FROM tblchristian LIKE 'branch_id'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ branch_id column exists</p>";
        } else {
            echo "<p style='color: red;'>✗ branch_id column does not exist</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ tblchristian table does not exist</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='debug_members.php'>← Back to Member Debug</a></p>";
?>