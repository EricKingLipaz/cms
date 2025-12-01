<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Session Test</h2>";

echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Session Variables:</h3>";
echo "<ul>";
echo "<li>odmsaid: " . (isset($_SESSION['odmsaid']) ? $_SESSION['odmsaid'] : 'Not set') . "</li>";
echo "<li>permission: " . (isset($_SESSION['permission']) ? $_SESSION['permission'] : 'Not set') . "</li>";
echo "<li>branch_id: " . (isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 'Not set') . "</li>";
echo "</ul>";

// Include checklogin functions
include('includes/checklogin.php');

echo "<h3>Function Tests:</h3>";
echo "<ul>";
echo "<li>is_super_admin(): " . (is_super_admin() ? 'true' : 'false') . "</li>";
echo "<li>is_branch_admin(): " . (is_branch_admin() ? 'true' : 'false') . "</li>";
echo "<li>get_user_branch(): " . (get_user_branch() ? get_user_branch() : 'null') . "</li>";
echo "</ul>";

// Test database connection
include('includes/dbconnection.php');

try {
    $stmt = $dbh->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    if (isset($_SESSION['odmsaid'])) {
        $aid = $_SESSION['odmsaid'];
        $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        echo "<h3>Admin User:</h3>";
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                echo "<ul>";
                echo "<li>ID: " . $row->ID . "</li>";
                echo "<li>UserName: " . $row->UserName . "</li>";
                echo "<li>FirstName: " . $row->FirstName . "</li>";
                echo "<li>LastName: " . $row->LastName . "</li>";
                echo "<li>AdminName: " . $row->AdminName . "</li>";
                echo "<li>branch_id: " . $row->branch_id . "</li>";
                echo "</ul>";
            }
        } else {
            echo "<p style='color: red;'>No admin user found</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='main_dashboard.php'>→ Main Dashboard</a></p>";
echo "<p><a href='index.php'>← Login</a></p>";
?>