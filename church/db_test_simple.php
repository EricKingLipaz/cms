<?php
include('includes/dbconnection.php');

echo "<h2>Database Connection Test</h2>";

try {
    // Test connection by listing tables
    $stmt = $dbh->query("SHOW TABLES");
    echo "<h3>Tables in database:</h3><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}
?>