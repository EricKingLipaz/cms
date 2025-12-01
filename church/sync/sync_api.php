<?php
// This is a simplified API endpoint for data synchronization
// In a production environment, this would be more robust with authentication and security measures

header('Content-Type: application/json');

// Database connection
include('../includes/dbconnection.php');

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch($method) {
    case 'GET':
        handleGetRequest();
        break;
    case 'POST':
        handlePostRequest();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetRequest() {
    global $dbh;
    
    // Get parameters
    $branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : null;
    $last_sync = isset($_GET['last_sync']) ? $_GET['last_sync'] : null;
    $data_type = isset($_GET['data_type']) ? $_GET['data_type'] : null;
    
    if(!$branch_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Branch ID is required']);
        return;
    }
    
    $response = [];
    
    // Get members
    if(!$data_type || $data_type == 'members') {
        $sql = "SELECT * FROM tblchristian WHERE branch_id = :branch_id";
        if($last_sync) {
            $sql .= " AND updated_at > :last_sync";
        }
        $sql .= " ORDER BY updated_at DESC";
        
        $query = $dbh -> prepare($sql);
        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
        if($last_sync) {
            $query->bindParam(':last_sync', $last_sync, PDO::PARAM_STR);
        }
        $query->execute();
        $response['members'] = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get events
    if(!$data_type || $data_type == 'events') {
        $sql = "SELECT * FROM church_events WHERE branch_id = :branch_id";
        if($last_sync) {
            $sql .= " AND updated_at > :last_sync";
        }
        $sql .= " ORDER BY updated_at DESC";
        
        $query = $dbh -> prepare($sql);
        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
        if($last_sync) {
            $query->bindParam(':last_sync', $last_sync, PDO::PARAM_STR);
        }
        $query->execute();
        $response['events'] = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get donations
    if(!$data_type || $data_type == 'donations') {
        $sql = "SELECT * FROM donations WHERE branch_id = :branch_id";
        if($last_sync) {
            $sql .= " AND updated_at > :last_sync";
        }
        $sql .= " ORDER BY updated_at DESC";
        
        $query = $dbh -> prepare($sql);
        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
        if($last_sync) {
            $query->bindParam(':last_sync', $last_sync, PDO::PARAM_STR);
        }
        $query->execute();
        $response['donations'] = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get equipment
    if(!$data_type || $data_type == 'equipment') {
        $sql = "SELECT * FROM church_equipment WHERE branch_id = :branch_id";
        if($last_sync) {
            $sql .= " AND updated_at > :last_sync";
        }
        $sql .= " ORDER BY updated_at DESC";
        
        $query = $dbh -> prepare($sql);
        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
        if($last_sync) {
            $query->bindParam(':last_sync', $last_sync, PDO::PARAM_STR);
        }
        $query->execute();
        $response['equipment'] = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode($response);
}

function handlePostRequest() {
    global $dbh;
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if(!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }
    
    $branch_id = isset($input['branch_id']) ? intval($input['branch_id']) : null;
    $data_type = isset($input['data_type']) ? $input['data_type'] : null;
    $data = isset($input['data']) ? $input['data'] : null;
    
    if(!$branch_id || !$data_type || !$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters']);
        return;
    }
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        switch($data_type) {
            case 'members':
                // Handle member data
                foreach($data as $member) {
                    if(isset($member['ID'])) {
                        // Update existing member
                        $sql = "UPDATE tblchristian SET 
                                Name = :name, 
                                lastname = :lastname, 
                                Age = :age, 
                                Sex = :sex, 
                                Email = :email, 
                                Phone = :phone, 
                                updated_at = NOW() 
                                WHERE ID = :id AND branch_id = :branch_id";
                        
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':name', $member['Name'], PDO::PARAM_STR);
                        $query->bindParam(':lastname', $member['lastname'], PDO::PARAM_STR);
                        $query->bindParam(':age', $member['Age'], PDO::PARAM_INT);
                        $query->bindParam(':sex', $member['Sex'], PDO::PARAM_STR);
                        $query->bindParam(':email', $member['Email'], PDO::PARAM_STR);
                        $query->bindParam(':phone', $member['Phone'], PDO::PARAM_STR);
                        $query->bindParam(':id', $member['ID'], PDO::PARAM_INT);
                        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
                        $query->execute();
                    } else {
                        // Insert new member
                        $sql = "INSERT INTO tblchristian (Name, lastname, Age, Sex, Email, Phone, branch_id, created_at, updated_at) 
                                VALUES (:name, :lastname, :age, :sex, :email, :phone, :branch_id, NOW(), NOW())";
                        
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':name', $member['Name'], PDO::PARAM_STR);
                        $query->bindParam(':lastname', $member['lastname'], PDO::PARAM_STR);
                        $query->bindParam(':age', $member['Age'], PDO::PARAM_INT);
                        $query->bindParam(':sex', $member['Sex'], PDO::PARAM_STR);
                        $query->bindParam(':email', $member['Email'], PDO::PARAM_STR);
                        $query->bindParam(':phone', $member['Phone'], PDO::PARAM_STR);
                        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
                        $query->execute();
                    }
                }
                $response['success'] = true;
                $response['message'] = 'Members synchronized successfully';
                break;
                
            case 'events':
                // Handle event data
                foreach($data as $event) {
                    if(isset($event['id'])) {
                        // Update existing event
                        $sql = "UPDATE church_events SET 
                                event_name = :event_name, 
                                start_date = :start_date, 
                                end_date = :end_date, 
                                updated_at = NOW() 
                                WHERE id = :id AND branch_id = :branch_id";
                        
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':event_name', $event['event_name'], PDO::PARAM_STR);
                        $query->bindParam(':start_date', $event['start_date'], PDO::PARAM_STR);
                        $query->bindParam(':end_date', $event['end_date'], PDO::PARAM_STR);
                        $query->bindParam(':id', $event['id'], PDO::PARAM_INT);
                        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
                        $query->execute();
                    } else {
                        // Insert new event
                        $sql = "INSERT INTO church_events (event_name, start_date, end_date, branch_id, created_at, updated_at) 
                                VALUES (:event_name, :start_date, :end_date, :branch_id, NOW(), NOW())";
                        
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':event_name', $event['event_name'], PDO::PARAM_STR);
                        $query->bindParam(':start_date', $event['start_date'], PDO::PARAM_STR);
                        $query->bindParam(':end_date', $event['end_date'], PDO::PARAM_STR);
                        $query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
                        $query->execute();
                    }
                }
                $response['success'] = true;
                $response['message'] = 'Events synchronized successfully';
                break;
                
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Unsupported data type']);
                return;
        }
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        return;
    }
    
    echo json_encode($response);
}
?>