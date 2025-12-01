<?php
// Integration Test for Church Management System
// This script tests the integration between different modules of the system

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../includes/dbconnection.php');

echo "<h1>Church Management System - Integration Tests</h1>\n";
echo "<p>Running integration tests for all modules...</p>\n";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>\n";
try {
    // Test PDO connection
    $sql = "SELECT 1 as test";
    $query = $dbh->prepare($sql);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if($result['test'] == 1) {
        echo "<p style='color: green;'>✓ PDO Database connection successful</p>\n";
    } else {
        echo "<p style='color: red;'>✗ PDO Database connection failed</p>\n";
    }
    
    // Test MySQLi connection
    if($conn) {
        echo "<p style='color: green;'>✓ MySQLi Database connection successful</p>\n";
    } else {
        echo "<p style='color: red;'>✗ MySQLi Database connection failed</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Database connection test failed: " . $e->getMessage() . "</p>\n";
}

// Test 2: Authentication System
echo "<h2>Test 2: Authentication System</h2>\n";
include('../includes/checklogin.php');

// Test login function
try {
    // This would normally redirect, but we'll just test if the function exists
    if(function_exists('check_login')) {
        echo "<p style='color: green;'>✓ Authentication functions exist</p>\n";
    } else {
        echo "<p style='color: red;'>✗ Authentication functions missing</p>\n";
    }
    
    // Test role functions
    if(function_exists('is_super_admin') && function_exists('is_branch_admin')) {
        echo "<p style='color: green;'>✓ Role checking functions exist</p>\n";
    } else {
        echo "<p style='color: red;'>✗ Role checking functions missing</p>\n";
    }
    
    // Test branch access functions
    if(function_exists('get_user_branch') && function_exists('can_access_branch')) {
        echo "<p style='color: green;'>✓ Branch access functions exist</p>\n";
    } else {
        echo "<p style='color: red;'>✗ Branch access functions missing</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Authentication system test failed: " . $e->getMessage() . "</p>\n";
}

// Test 3: Member Management Module
echo "<h2>Test 3: Member Management Module</h2>\n";
try {
    // Check if member table exists
    $sql = "SHOW TABLES LIKE 'tblchristian'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Member table exists</p>\n";
        
        // Test member creation (without actually inserting)
        $sql = "DESCRIBE tblchristian";
        $query = $dbh->prepare($sql);
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_COLUMN);
        
        $required_columns = ['ID', 'Name', 'Age', 'Sex', 'Email', 'Phone', 'branch_id'];
        $missing_columns = [];
        
        foreach($required_columns as $column) {
            if(!in_array($column, $columns)) {
                $missing_columns[] = $column;
            }
        }
        
        if(empty($missing_columns)) {
            echo "<p style='color: green;'>✓ Member table has required columns</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Member table missing columns: " . implode(', ', $missing_columns) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Member table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Member management test failed: " . $e->getMessage() . "</p>\n";
}

// Test 4: Event Management Module
echo "<h2>Test 4: Event Management Module</h2>\n";
try {
    // Check if event table exists
    $sql = "SHOW TABLES LIKE 'church_events'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Event table exists</p>\n";
        
        // Test event table structure
        $sql = "DESCRIBE church_events";
        $query = $dbh->prepare($sql);
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_COLUMN);
        
        $required_columns = ['id', 'event_name', 'start_date', 'branch_id'];
        $missing_columns = [];
        
        foreach($required_columns as $column) {
            if(!in_array($column, $columns)) {
                $missing_columns[] = $column;
            }
        }
        
        if(empty($missing_columns)) {
            echo "<p style='color: green;'>✓ Event table has required columns</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Event table missing columns: " . implode(', ', $missing_columns) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Event table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Event management test failed: " . $e->getMessage() . "</p>\n";
}

// Test 5: Donation Tracking Module
echo "<h2>Test 5: Donation Tracking Module</h2>\n";
try {
    // Check if donation table exists
    $sql = "SHOW TABLES LIKE 'donations'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Donation table exists</p>\n";
        
        // Test donation table structure
        $sql = "DESCRIBE donations";
        $query = $dbh->prepare($sql);
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_COLUMN);
        
        $required_columns = ['id', 'donor_name', 'amount', 'donation_date', 'branch_id'];
        $missing_columns = [];
        
        foreach($required_columns as $column) {
            if(!in_array($column, $columns)) {
                $missing_columns[] = $column;
            }
        }
        
        if(empty($missing_columns)) {
            echo "<p style='color: green;'>✓ Donation table has required columns</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Donation table missing columns: " . implode(', ', $missing_columns) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Donation table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Donation tracking test failed: " . $e->getMessage() . "</p>\n";
}

// Test 6: Resource Management Module
echo "<h2>Test 6: Resource Management Module</h2>\n";
try {
    // Check if equipment table exists
    $sql = "SHOW TABLES LIKE 'church_equipment'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Equipment table exists</p>\n";
        
        // Test equipment table structure
        $sql = "DESCRIBE church_equipment";
        $query = $dbh->prepare($sql);
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_COLUMN);
        
        $required_columns = ['id', 'item_name', 'category', 'branch_id'];
        $missing_columns = [];
        
        foreach($required_columns as $column) {
            if(!in_array($column, $columns)) {
                $missing_columns[] = $column;
            }
        }
        
        if(empty($missing_columns)) {
            echo "<p style='color: green;'>✓ Equipment table has required columns</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Equipment table missing columns: " . implode(', ', $missing_columns) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Equipment table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Resource management test failed: " . $e->getMessage() . "</p>\n";
}

// Test 7: Branch Management Module
echo "<h2>Test 7: Branch Management Module</h2>\n";
try {
    // Check if branches table exists
    $sql = "SHOW TABLES LIKE 'branches'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Branches table exists</p>\n";
        
        // Test branches table structure
        $sql = "DESCRIBE branches";
        $query = $dbh->prepare($sql);
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_COLUMN);
        
        $required_columns = ['id', 'branch_name', 'branch_code', 'is_headquarters'];
        $missing_columns = [];
        
        foreach($required_columns as $column) {
            if(!in_array($column, $columns)) {
                $missing_columns[] = $column;
            }
        }
        
        if(empty($missing_columns)) {
            echo "<p style='color: green;'>✓ Branches table has required columns</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Branches table missing columns: " . implode(', ', $missing_columns) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Branches table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Branch management test failed: " . $e->getMessage() . "</p>\n";
}

// Test 8: Communication Module
echo "<h2>Test 8: Communication Module</h2>\n";
try {
    // Check if messages table exists
    $sql = "SHOW TABLES LIKE 'messages'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Messages table exists</p>\n";
        
        // Check if message recipients table exists
        $sql = "SHOW TABLES LIKE 'message_recipients'";
        $query = $dbh->prepare($sql);
        $query->execute();
        
        if($query->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Message recipients table exists</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Message recipients table does not exist</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Messages table does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Communication module test failed: " . $e->getMessage() . "</p>\n";
}

// Test 9: Data Synchronization Module
echo "<h2>Test 9: Data Synchronization Module</h2>\n";
try {
    // Check if sync API file exists
    $sync_api_path = '../sync/sync_api.php';
    if(file_exists($sync_api_path)) {
        echo "<p style='color: green;'>✓ Sync API file exists</p>\n";
    } else {
        echo "<p style='color: red;'>✗ Sync API file does not exist</p>\n";
    }
    
    // Check if data sync file exists
    $data_sync_path = '../sync/data_sync.php';
    if(file_exists($data_sync_path)) {
        echo "<p style='color: green;'>✓ Data sync file exists</p>\n";
    } else {
        echo "<p style='color: red;'>✗ Data sync file does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Data synchronization test failed: " . $e->getMessage() . "</p>\n";
}

// Test 10: Reporting Module
echo "<h2>Test 10: Reporting Module</h2>\n";
try {
    // Check if reports directory exists
    $reports_dir = '../reports';
    if(is_dir($reports_dir)) {
        echo "<p style='color: green;'>✓ Reports directory exists</p>\n";
        
        // Check if report files exist
        $report_files = [
            'dashboard.php',
            'member_reports.php',
            'financial_reports.php',
            'event_reports.php',
            'resource_reports.php'
        ];
        
        $missing_files = [];
        foreach($report_files as $file) {
            if(!file_exists($reports_dir . '/' . $file)) {
                $missing_files[] = $file;
            }
        }
        
        if(empty($missing_files)) {
            echo "<p style='color: green;'>✓ All report files exist</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Missing report files: " . implode(', ', $missing_files) . "</p>\n";
        }
    } else {
        echo "<p style='color: red;'>✗ Reports directory does not exist</p>\n";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Reporting module test failed: " . $e->getMessage() . "</p>\n";
}

echo "<h2>Integration Test Summary</h2>\n";
echo "<p>All tests completed. Please review the results above.</p>\n";
echo "<p><a href='../main_dashboard.php'>Return to Dashboard</a></p>\n";
?>