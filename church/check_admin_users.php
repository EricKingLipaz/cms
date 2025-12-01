<?php
include('includes/dbconnection.php');

echo "<h2>Checking Admin Users</h2>";

try {
    $stmt = $dbh->query("SELECT * FROM tbladmin");
    echo "<p>Admin users found: " . $stmt->rowCount() . "</p>";
    
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>UserName</th><th>FirstName</th><th>LastName</th><th>AdminName</th><th>Status</th><th>branch_id</th></tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['UserName'] . "</td>";
            echo "<td>" . $row['FirstName'] . "</td>";
            echo "<td>" . $row['LastName'] . "</td>";
            echo "<td>" . $row['AdminName'] . "</td>";
            echo "<td>" . $row['Status'] . "</td>";
            echo "<td>" . $row['branch_id'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No admin users found in the database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
?>