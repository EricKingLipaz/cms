<?php
include('includes/dbconnection.php');

echo "<h2>Fix Member Branches</h2>";

try {
    // Check if branch_id column exists
    $stmt = $dbh->query("SHOW COLUMNS FROM tblchristian LIKE 'branch_id'");
    if ($stmt->rowCount() == 0) {
        echo "<p>Adding branch_id column to tblchristian table...</p>";
        
        // Add branch_id column
        $sql = "ALTER TABLE tblchristian ADD COLUMN branch_id INT(11) DEFAULT NULL AFTER ID";
        $dbh->query($sql);
        
        echo "<p style='color: green;'>✓ branch_id column added successfully</p>";
    } else {
        echo "<p style='color: green;'>✓ branch_id column already exists</p>";
    }
    
    // Check if foreign key exists
    $stmt = $dbh->query("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='churchdb' AND TABLE_NAME='tblchristian' AND COLUMN_NAME='branch_id' AND REFERENCED_TABLE_NAME='branches'");
    if ($stmt->rowCount() == 0) {
        echo "<p>Adding foreign key constraint for branch_id...</p>";
        
        // Add foreign key constraint
        $sql = "ALTER TABLE tblchristian ADD FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL";
        $dbh->query($sql);
        
        echo "<p style='color: green;'>✓ Foreign key constraint added successfully</p>";
    } else {
        echo "<p style='color: green;'>✓ Foreign key constraint already exists</p>";
    }
    
    // Check if there are members without branch_id
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM tblchristian WHERE branch_id IS NULL");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $null_branch_count = $result['count'];
    
    if ($null_branch_count > 0) {
        echo "<p>Found $null_branch_count members without branch_id. Assigning default branch...</p>";
        
        // Get default branch (first active branch)
        $stmt = $dbh->query("SELECT id FROM branches WHERE status='active' ORDER BY id LIMIT 1");
        if ($stmt->rowCount() > 0) {
            $branch_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $default_branch_id = $branch_row['id'];
            
            // Update members without branch_id
            $sql = "UPDATE tblchristian SET branch_id=:branch_id WHERE branch_id IS NULL";
            $query = $dbh->prepare($sql);
            $query->bindParam(':branch_id', $default_branch_id, PDO::PARAM_INT);
            $query->execute();
            
            echo "<p style='color: green;'>✓ Updated $null_branch_count members with default branch ID: $default_branch_id</p>";
        } else {
            echo "<p style='color: orange;'>⚠ No active branches found to assign to members</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ All members have branch_id assigned</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='members/member_list.php'>→ Member List Page</a></p>";
echo "<p><a href='debug_members.php'>← Back to Member Debug</a></p>";
?>