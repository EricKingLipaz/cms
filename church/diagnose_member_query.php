<?php
session_start();
include('includes/dbconnection.php');
include('includes/checklogin.php');

echo "<h2>Member Query Diagnosis</h2>";

// Simulate user permissions
$is_super_admin = is_super_admin();
$user_branch_id = get_user_branch();

echo "<p>User is super admin: " . ($is_super_admin ? 'Yes' : 'No') . "</p>";
echo "<p>User branch ID: " . ($user_branch_id ? $user_branch_id : 'None') . "</p>";

// Build SQL query based on user permissions
$sql = "SELECT c.*, b.branch_name FROM tblchristian c LEFT JOIN branches b ON c.branch_id = b.id";

if(!$is_super_admin && $user_branch_id) {
    $sql .= " WHERE c.branch_id = :branch_id";
}

$sql .= " ORDER BY c.CreationDate DESC";

echo "<p>SQL Query: " . $sql . "</p>";

try {
    $query = $dbh->prepare($sql);
    
    if(!$is_super_admin && $user_branch_id) {
        $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
    }
    
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    echo "<p>Query executed successfully</p>";
    echo "<p>Number of rows found: " . $query->rowCount() . "</p>";
    
    if($query->rowCount() > 0) {
        echo "<h3>Members found:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Branch ID</th><th>Branch Name</th></tr>";
        foreach($results as $row) {
            echo "<tr>";
            echo "<td>" . $row->ID . "</td>";
            echo "<td>" . $row->Name . " " . $row->lastname . "</td>";
            echo "<td>" . $row->Email . "</td>";
            echo "<td>" . $row->branch_id . "</td>";
            echo "<td>" . ($row->branch_name ? $row->branch_name : 'None') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No members found with current query</p>";
        
        // Try a broader query to see if there are members at all
        echo "<h3>Checking all members in database:</h3>";
        $stmt = $dbh->query("SELECT * FROM tblchristian");
        if ($stmt->rowCount() > 0) {
            echo "<p>Total members in database: " . $stmt->rowCount() . "</p>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Branch ID</th></tr>";
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['Name'] . " " . $row['lastname'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['branch_id'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No members found in database at all</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='members/member_list.php'>→ Member List Page</a></p>";
echo "<p><a href='debug_members.php'>← Back to Member Debug</a></p>";
?>