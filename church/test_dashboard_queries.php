<?php
session_start();
include('includes/dbconnection.php');

echo "<h2>Dashboard Queries Test</h2>";

try {
    // Test 1: Check if tables exist
    echo "<h3>Table Status:</h3>";
    $tables = ['tblchristian', 'donations', 'church_events', 'church_equipment', 'branches'];
    
    foreach ($tables as $table) {
        $stmt = $dbh->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ $table exists</p>";
        } else {
            echo "<p style='color: red;'>✗ $table does not exist</p>";
        }
    }
    
    // Test 2: Count records in each table
    echo "<h3>Record Counts:</h3>";
    
    // Members
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM tblchristian");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Members: " . $result['count'] . "</p>";
    
    // Donations
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM donations");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Donations: " . $result['count'] . "</p>";
    
    // Events
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM church_events");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Events: " . $result['count'] . "</p>";
    
    // Resources
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM church_equipment");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Resources: " . $result['count'] . "</p>";
    
    // Branches
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM branches");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Branches: " . $result['count'] . "</p>";
    
    // Test 3: Check if there are upcoming events
    echo "<h3>Upcoming Events Query:</h3>";
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM church_events WHERE event_date >= CURDATE()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Upcoming events: " . $result['count'] . "</p>";
    
    // Test 4: Check this month's donations
    echo "<h3>This Month's Donations:</h3>";
    $stmt = $dbh->query("SELECT SUM(amount) as total FROM donations WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total donations this month: " . ($result['total'] ? number_format($result['total'], 2) : "0.00") . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='main_dashboard.php'>→ Main Dashboard</a></p>";
echo "<p><a href='simple_dashboard.php'>→ Simple Dashboard</a></p>";
?>