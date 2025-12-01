<?php
include('includes/dbconnection.php');

echo "<h2>Database Tables Check</h2>";

try {
    // List all tables in the database
    $stmt = $dbh->query("SHOW TABLES");
    echo "<h3>Tables in database:</h3><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    // Check specific required tables
    $required_tables = ['tbladmin', 'tblchristian', 'branches', 'church_events', 'donations', 'church_equipment'];
    
    echo "<h3>Required tables check:</h3><ul>";
    foreach ($required_tables as $table) {
        $stmt = $dbh->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<li style='color: green;'>✓ $table exists</li>";
        } else {
            echo "<li style='color: red;'>✗ $table does not exist</li>";
        }
    }
    echo "</ul>";
    
    // Check tbladmin data
    echo "<h3>Admin users:</h3>";
    $stmt = $dbh->query("SELECT * FROM tbladmin");
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'><tr><th>ID</th><th>UserName</th><th>FirstName</th><th>LastName</th><th>AdminName</th><th>branch_id</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['UserName'] . "</td>";
            echo "<td>" . $row['FirstName'] . "</td>";
            echo "<td>" . $row['LastName'] . "</td>";
            echo "<td>" . $row['AdminName'] . "</td>";
            echo "<td>" . $row['branch_id'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No admin users found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
?>