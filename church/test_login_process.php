<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Login Process Test</h2>";

// Include database connection
include('includes/dbconnection.php');

// Test with default admin credentials
$username = 'admin';
$password = md5('admin');

echo "<p>Testing login with username: $username, password hash: $password</p>";

$sql = "SELECT * FROM tbladmin WHERE UserName=:username and Password=:password";
$query = $dbh->prepare($sql);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->bindParam(':password', $password, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

echo "<p>Query executed, found " . $query->rowCount() . " matching users</p>";

if ($query->rowCount() > 0) {
    echo "<p style='color: green;'>✓ Login successful!</p>";
    
    foreach ($results as $result) {
        echo "<h3>User Details:</h3>";
        echo "<ul>";
        echo "<li>ID: " . $result->ID . "</li>";
        echo "<li>UserName: " . $result->UserName . "</li>";
        echo "<li>FirstName: " . $result->FirstName . "</li>";
        echo "<li>LastName: " . $result->LastName . "</li>";
        echo "<li>AdminName: " . $result->AdminName . "</li>";
        echo "<li>Status: " . $result->Status . "</li>";
        echo "<li>branch_id: " . $result->branch_id . "</li>";
        echo "</ul>";
        
        // Set session variables
        $_SESSION['odmsaid'] = $result->ID;
        $_SESSION['login'] = $result->UserName;
        $_SESSION['names'] = $result->FirstName;
        $_SESSION['permission'] = $result->AdminName;
        
        echo "<p style='color: green;'>Session variables set successfully</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Login failed - no matching user found</p>";
    
    // Check if any users exist
    try {
        $stmt = $dbh->query("SELECT * FROM tbladmin");
        echo "<p>Total admin users in database: " . $stmt->rowCount() . "</p>";
        
        if ($stmt->rowCount() > 0) {
            echo "<h3>All Admin Users:</h3>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>UserName</th><th>Password Hash</th><th>FirstName</th><th>LastName</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['UserName'] . "</td>";
                echo "<td>" . $row['Password'] . "</td>";
                echo "<td>" . $row['FirstName'] . "</td>";
                echo "<td>" . $row['LastName'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error fetching users: " . $e->getMessage() . "</p>";
    }
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
echo "<p><a href='main_dashboard.php'>→ Try Dashboard</a></p>";
?>