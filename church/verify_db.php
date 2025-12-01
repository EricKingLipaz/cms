<?php
// Verify database import
include('includes/dbconnection.php');

echo "<h1>Database Verification</h1>";

$tables_to_check = [
    'tbladmin',
    'tblchristian',
    'church_events',
    'donations',
    'church_equipment',
    'branches',
    'messages'
];

foreach($tables_to_check as $table) {
    try {
        $sql = "SELECT COUNT(*) as count FROM " . $table;
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        echo "<p>✓ Table <strong>$table</strong> exists with " . $result->count . " records</p>";
    } catch(Exception $e) {
        echo "<p>✗ Table <strong>$table</strong> error: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>Checking Admin Users</h2>";

try {
    $sql = "SELECT ID, UserName, AdminName, FirstName, LastName FROM tbladmin";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<p>✓ Found " . $query->rowCount() . " admin user(s):</p>";
        echo "<ul>";
        foreach($results as $row) {
            echo "<li><strong>" . $row->UserName . "</strong> (" . $row->AdminName . ") - " . $row->FirstName . " " . $row->LastName . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠ No admin users found. You may need to create one.</p>";
    }
} catch(Exception $e) {
    echo "<p>✗ Error checking admin users: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>Go to Login Page</a></p>";
?>