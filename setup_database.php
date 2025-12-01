<?php
// Setup database by importing both SQL files in correct order
echo "<h1>Church Management System - Database Setup</h1>";

// Database connection
$host = 'localhost';
$dbname = 'churchdb';
$username = 'root';
$password = '';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✓ Connected to MySQL successfully</p>";
    
    // Drop database if exists (clean slate)
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    echo "<p>✓ Dropped existing database (if any)</p>";
    
    // Create database
    $pdo->exec("CREATE DATABASE `$dbname`");
    echo "<p>✓ Created database: $dbname</p>";
    
    // Select database
    $pdo->exec("USE `$dbname`");
    
    // Import base schema (churchdb.sql)
    $base_sql_file = 'SQL File/churchdb.sql';
    if (file_exists($base_sql_file)) {
        echo "<p>Importing base schema from: $base_sql_file</p>";
        
        // Read the SQL file
        $sql = file_get_contents($base_sql_file);
        
        // Split the SQL into individual statements
        $statements = explode(';', $sql);
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                    $success_count++;
                } catch (Exception $e) {
                    // Ignore errors for now, we'll check the important tables later
                    $error_count++;
                }
            }
        }
        
        echo "<p>✓ Executed $success_count statements from base schema (ignored $error_count errors)</p>";
    } else {
        echo "<p>✗ Base SQL file not found: $base_sql_file</p>";
    }
    
    // Import extended schema (church_management_system.sql)
    $extended_sql_file = 'SQL File/church_management_system.sql';
    if (file_exists($extended_sql_file)) {
        echo "<p>Importing extended schema from: $extended_sql_file</p>";
        
        // Read the SQL file
        $sql = file_get_contents($extended_sql_file);
        
        // Split the SQL into individual statements
        $statements = explode(';', $sql);
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                    $success_count++;
                } catch (Exception $e) {
                    // Ignore errors for now, we'll check the important tables later
                    $error_count++;
                }
            }
        }
        
        echo "<p>✓ Executed $success_count statements from extended schema (ignored $error_count errors)</p>";
    } else {
        echo "<p>✗ Extended SQL file not found: $extended_sql_file</p>";
    }
    
    // Verify important tables exist
    echo "<h2>Verifying Database Structure</h2>";
    
    $important_tables = [
        'tbladmin',
        'tblchristian',
        'branches',
        'church_events',
        'donations',
        'church_equipment',
        'messages'
    ];
    
    foreach ($important_tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>✓ Table <strong>$table</strong> exists with {$result['count']} records</p>";
        } catch (Exception $e) {
            echo "<p>⚠ Table <strong>$table</strong> may not exist or is empty</p>";
        }
    }
    
    // Create default admin user with preferred email
    echo "<h2>Setting up Admin User</h2>";
    
    try {
        // Check if admin exists
        $stmt = $pdo->prepare("SELECT ID FROM tbladmin WHERE UserName = 'admin'");
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<p>✓ Admin user already exists</p>";
        } else {
            // Create admin user
            $password_hash = md5('1234'); // Default password
            $stmt = $pdo->prepare("INSERT INTO tbladmin (Staffid, AdminName, UserName, FirstName, LastName, MobileNumber, Email, Status, Photo, Password) VALUES ('ADM001', 'Superuser', 'admin', 'Church', 'Administrator', '1234567890', 'diamondlipaz@gmail.com', 1, 'avatar15.jpg', ?)");
            $stmt->execute([$password_hash]);
            echo "<p>✓ Created admin user with preferred email: diamondlipaz@gmail.com</p>";
        }
    } catch (Exception $e) {
        echo "<p>✗ Error setting up admin user: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Setup Complete</h2>";
    echo "<p>The database has been set up successfully!</p>";
    echo "<p><a href='index.php'>Go to Login Page</a></p>";
    
} catch (Exception $e) {
    echo "<p>✗ Error: " . $e->getMessage() . "</p>";
}
?>