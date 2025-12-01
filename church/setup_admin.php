<?php
// Setup admin user with preferred email
include('includes/dbconnection.php');

echo "<h1>Admin User Setup</h1>";

$preferred_email = 'diamondlipaz@gmail.com';
$default_username = 'admin';
$default_password = '1234';

try {
    // Check if admin user with preferred email already exists
    $sql = "SELECT ID, UserName FROM tbladmin WHERE Email = :email OR UserName = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $preferred_email, PDO::PARAM_STR);
    $query->bindParam(':username', $default_username, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() > 0) {
        $result = $query->fetch(PDO::FETCH_OBJ);
        echo "<p>✓ Admin user already exists with username: <strong>" . $result->UserName . "</strong></p>";
        echo "<p>You can login with this user at <a href='index.php'>the login page</a>.</p>";
        
        // Show login instructions
        echo "<h2>Login Instructions</h2>";
        echo "<p>If you don't remember the password, you can reset it:</p>";
        echo "<ol>";
        echo "<li>Go to <a href='index.php'>Login Page</a></li>";
        echo "<li>Click on 'Forgot Password' link</li>";
        echo "<li>Enter your email: <strong>" . $preferred_email . "</strong></li>";
        echo "<li>Follow the password reset instructions</li>";
        echo "</ol>";
    } else {
        // Create new admin user with preferred email
        $password_hash = md5($default_password);
        $sql = "INSERT INTO tbladmin (Staffid, AdminName, UserName, FirstName, LastName, MobileNumber, Email, Status, Photo, Password) 
                VALUES ('ADM001', 'Superuser', :username, 'Church', 'Administrator', '1234567890', :email, 1, 'avatar15.jpg', :password)";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $default_username, PDO::PARAM_STR);
        $query->bindParam(':email', $preferred_email, PDO::PARAM_STR);
        $query->bindParam(':password', $password_hash, PDO::PARAM_STR);
        
        if($query->execute()) {
            echo "<p>✓ Admin user created successfully!</p>";
            echo "<p><strong>Login Credentials:</strong></p>";
            echo "<ul>";
            echo "<li>Username: <strong>" . $default_username . "</strong></li>";
            echo "<li>Password: <strong>" . $default_password . "</strong> (Please change after first login)</li>";
            echo "<li>Email: <strong>" . $preferred_email . "</strong></li>";
            echo "</ul>";
            echo "<p><a href='index.php'>Go to Login Page</a></p>";
        } else {
            echo "<p>✗ Failed to create admin user.</p>";
        }
    }
} catch(Exception $e) {
    echo "<p>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<h2>Alternative Access</h2>";
echo "<p>If you continue to have issues, you can try the public landing page:</p>";
echo "<p><a href='landing.php'>Public Landing Page</a></p>";
?>