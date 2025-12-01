<?php
include('includes/dbconnection.php');

echo "<h2>Database Structure Check</h2>";

try {
    // List all tables in the database
    $stmt = $dbh->query("SHOW TABLES");
    echo "<h3>Tables in database:</h3><ul>";
    $tables = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . $row[0] . "</li>";
        $tables[] = $row[0];
    }
    echo "</ul>";
    
    // Check specific required tables
    $required_tables = [
        'tbladmin', 
        'tblchristian', 
        'branches', 
        'church_events', 
        'donations', 
        'church_equipment',
        'communications',
        'worship_teams'
    ];
    
    echo "<h3>Required tables check:</h3><ul>";
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            echo "<li style='color: green;'>✓ $table exists</li>";
        } else {
            echo "<li style='color: red;'>✗ $table does not exist</li>";
        }
    }
    echo "</ul>";
    
    // Check table structures
    echo "<h3>Table Record Counts:</h3><ul>";
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            try {
                $stmt = $dbh->query("SELECT COUNT(*) as count FROM $table");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<li>$table: " . $result['count'] . " records</li>";
            } catch (Exception $e) {
                echo "<li>$table: Error counting records - " . $e->getMessage() . "</li>";
            }
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='main_dashboard.php'>← Back to Dashboard</a></p>";
?>