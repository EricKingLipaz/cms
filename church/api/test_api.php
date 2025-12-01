<?php
// Simple API test endpoint
header('Content-Type: application/json');

include('../includes/dbconnection.php');

$response = array();

try {
    // Test database connection
    $stmt = $dbh->query("SELECT 1");
    
    // Get total members
    $members_stmt = $dbh->query("SELECT COUNT(*) as count FROM tblchristian");
    $members_result = $members_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get total branches
    $branches_stmt = $dbh->query("SELECT COUNT(*) as count FROM branches");
    $branches_result = $branches_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get total donations
    $donations_stmt = $dbh->query("SELECT COUNT(*) as count FROM donations");
    $donations_result = $donations_stmt->fetch(PDO::FETCH_ASSOC);
    
    $response = array(
        'status' => 'success',
        'message' => 'API connection successful',
        'data' => array(
            'total_members' => $members_result['count'],
            'total_branches' => $branches_result['count'],
            'total_donations' => $donations_result['count'],
            'timestamp' => date('Y-m-d H:i:s')
        )
    );
} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'message' => 'API connection failed: ' . $e->getMessage(),
        'data' => null
    );
}

echo json_encode($response);
?>