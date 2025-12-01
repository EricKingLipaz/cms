<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Member Debug Information</h2>";

// Include database connection
include('includes/dbconnection.php');

try {
    // Check if tblchristian table exists
    $stmt = $dbh->query("SHOW TABLES LIKE 'tblchristian'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ tblchristian table exists</p>";
        
        // Count total members
        $stmt = $dbh->query("SELECT COUNT(*) as count FROM tblchristian");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_members = $result['count'];
        echo "<p>Total members in database: $total_members</p>";
        
        if ($total_members > 0) {
            // Display sample members
            echo "<h3>Sample Members:</h3>";
            $stmt = $dbh->query("SELECT * FROM tblchristian LIMIT 10");
            echo "<table border='1'>";
            echo "<tr>";
            $columns = array();
            for ($i = 0; $i < $stmt->columnCount(); $i++) {
                $col = $stmt->getColumnMeta($i);
                $columns[] = $col['name'];
                echo "<th>" . $col['name'] . "</th>";
            }
            echo "</tr>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                foreach ($columns as $col) {
                    echo "<td>" . (isset($row[$col]) ? $row[$col] : 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No members found in the database</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ tblchristian table does not exist</p>";
    }
    
    // Check branches table
    echo "<h3>Branches:</h3>";
    $stmt = $dbh->query("SELECT * FROM branches");
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Location</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No branches found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='members/member_list.php'>→ Member List Page</a></p>";
echo "<p><a href='index.php'>← Back to Login</a></p>";
?>