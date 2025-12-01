<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Simple Test</h2>";

// Check session
echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check if odmsaid is set
if (isset($_SESSION['odmsaid'])) {
    echo "<p>odmsaid is set: " . $_SESSION['odmsaid'] . "</p>";
    
    // Include database connection
    include('includes/dbconnection.php');
    
    // Try to fetch user
    $aid = $_SESSION['odmsaid'];
    echo "<p>Trying to fetch user with ID: $aid</p>";
    
    try {
        $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        echo "<p>Query executed, row count: " . $query->rowCount() . "</p>";
        
        if ($query->rowCount() > 0) {
            echo "<p style='color: green;'>User found!</p>";
            foreach ($results as $row) {
                echo "<p>ID: " . $row->ID . "</p>";
                echo "<p>UserName: " . $row->UserName . "</p>";
                echo "<p>FirstName: " . $row->FirstName . "</p>";
                echo "<p>LastName: " . $row->LastName . "</p>";
                echo "<p>AdminName: " . $row->AdminName . "</p>";
                echo "<p>branch_id: " . $row->branch_id . "</p>";
            }
        } else {
            echo "<p style='color: red;'>No user found with this ID</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'>odmsaid is not set in session</p>";
}

echo "<p><a href='index.php'>← Back to Login</a></p>";
echo "<p><a href='main_dashboard.php'>→ Try Dashboard</a></p>";
?>