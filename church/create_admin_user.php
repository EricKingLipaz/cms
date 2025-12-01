<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Create/Reset Admin User</h2>";

// Include database connection
include('includes/dbconnection.php');

try {
    // Check if admin user already exists
    $stmt = $dbh->query("SELECT * FROM tbladmin WHERE UserName='admin'");
    
    if ($stmt->rowCount() > 0) {
        echo "<p>Admin user already exists. Updating password...</p>";
        
        // Update existing admin user password
        $password = md5('admin');
        $sql = "UPDATE tbladmin SET Password=:password, Status='1' WHERE UserName='admin'";
        $query = $dbh->prepare($sql);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        
        echo "<p style='color: green;'>✓ Admin user password updated successfully</p>";
    } else {
        echo "<p>Creating new admin user...</p>";
        
        // Insert new admin user
        $password = md5('admin');
        $sql = "INSERT INTO tbladmin (UserName, Password, FirstName, LastName, AdminName, Status, branch_id) 
                VALUES ('admin', :password, 'Admin', 'User', 'Superuser', '1', NULL)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        
        echo "<p style='color: green;'>✓ Admin user created successfully</p>";
    }
    
    // Verify the admin user
    $stmt = $dbh->query("SELECT * FROM tbladmin WHERE UserName='admin'");
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h3>Admin User Details:</h3>";
        echo "<ul>";
        echo "<li>ID: " . $user['ID'] . "</li>";
        echo "<li>UserName: " . $user['UserName'] . "</li>";
        echo "<li>FirstName: " . $user['FirstName'] . "</li>";
        echo "<li>LastName: " . $user['LastName'] . "</li>";
        echo "<li>AdminName: " . $user['AdminName'] . "</li>";
        echo "<li>Status: " . $user['Status'] . "</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
echo "<p>Default login credentials: username: admin, password: admin</p>";
?>