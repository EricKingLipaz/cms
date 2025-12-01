<?php
// Create default admin user
include('includes/dbconnection.php');

echo "<h1>Creating Default Admin User</h1>";

try {
    // Check if admin user already exists
    $sql = "SELECT ID FROM tbladmin WHERE UserName = 'admin'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p>Admin user already exists. <a href='index.php'>Go to Login</a></p>";
    } else {
        // Create new admin user
        $password = md5('1234'); // Default password
        $sql = "INSERT INTO tbladmin (Staffid, AdminName, UserName, FirstName, LastName, MobileNumber, Email, Status, Photo, Password) 
                VALUES ('ADM001', 'Superuser', 'admin', 'Church', 'Administrator', '1234567890', 'admin@example.com', 1, 'avatar15.jpg', :password)";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        
        if($query->execute()) {
            echo "<p>✓ Admin user created successfully!</p>";
            echo "<p>Username: admin</p>";
            echo "<p>Password: 1234</p>";
            echo "<p><a href='index.php'>Go to Login Page</a></p>";
        } else {
            echo "<p>✗ Failed to create admin user.</p>";
        }
    }
} catch(Exception $e) {
    echo "<p>✗ Error: " . $e->getMessage() . "</p>";
}
?>