<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate a logged-in user
$_SESSION['odmsaid'] = 1; // Simulate user ID
$_SESSION['permission'] = 'Superuser'; // Simulate super admin
$_SESSION['branch_id'] = 1; // Simulate branch ID

// Database connection
include('includes/dbconnection.php');

// Get user information
$aid=$_SESSION['odmsaid'];
$is_super_admin = true; // Force super admin
$user_branch_id = 1; // Force branch ID

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Complete Database Test - Church Management System</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
</head>
<body class='bg-gray-100'>
    <div class='max-w-6xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Complete Database Test</h1>";

// Test database query for members
try {
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Members Data</h2>";
    
    $sql = "SELECT c.*, b.branch_name FROM tblchristian c LEFT JOIN branches b ON c.branch_id = b.id ORDER BY c.CreationDate DESC";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Name</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Email</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Status</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
        
        foreach($results as $row) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm font-medium text-gray-900'>" . $row->Name . " " . $row->lastname . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm text-gray-900'>" . $row->Email . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->branch_name . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-" . ($row->Status=="Baptised" ? "green" : "yellow") . "-100 text-" . ($row->Status=="Baptised" ? "green" : "yellow") . "-800'>
                            " . $row->Status . "
                        </span>
                    </td>
                  </tr>";
        }
        
        echo "</tbody>
              </table>
            </div>";
    } else {
        echo "<p class='text-gray-500'>No members found</p>";
    }
    
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

// Test database query for events
try {
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Events Data</h2>";
    
    $sql = "SELECT e.*, b.branch_name FROM church_events e LEFT JOIN branches b ON e.branch_id = b.id ORDER BY e.start_date DESC";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Event</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Date</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
        
        foreach($results as $row) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm font-medium text-gray-900'>" . $row->event_name . "</div>
                        <div class='text-sm text-gray-500'>" . $row->event_description . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->start_date . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->branch_name . "
                    </td>
                  </tr>";
        }
        
        echo "</tbody>
              </table>
            </div>";
    } else {
        echo "<p class='text-gray-500'>No events found</p>";
    }
    
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

// Test database query for donations
try {
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Donations Data</h2>";
    
    $sql = "SELECT d.*, b.branch_name FROM donations d LEFT JOIN branches b ON d.branch_id = b.id ORDER BY d.donation_date DESC";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Donor</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Amount</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Date</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
        
        foreach($results as $row) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm font-medium text-gray-900'>" . $row->donor_name . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        $" . $row->amount . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->donation_date . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->branch_name . "
                    </td>
                  </tr>";
        }
        
        echo "</tbody>
              </table>
            </div>";
    } else {
        echo "<p class='text-gray-500'>No donations found</p>";
    }
    
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

// Test database query for equipment
try {
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Equipment Data</h2>";
    
    $sql = "SELECT e.*, b.branch_name FROM church_equipment e LEFT JOIN branches b ON e.branch_id = b.id ORDER BY e.created_at DESC";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Equipment</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Code</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
        
        foreach($results as $row) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm font-medium text-gray-900'>" . $row->item_name . "</div>
                        <div class='text-sm text-gray-500'>" . $row->description . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->item_code . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->branch_name . "
                    </td>
                  </tr>";
        }
        
        echo "</tbody>
              </table>
            </div>";
    } else {
        echo "<p class='text-gray-500'>No equipment found</p>";
    }
    
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

// Test database query for branches
try {
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Branches Data</h2>";
    
    $sql = "SELECT * FROM branches ORDER BY branch_name";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo "<div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch Name</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Location</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Status</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
        
        foreach($results as $row) {
            echo "<tr>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <div class='text-sm font-medium text-gray-900'>" . $row->branch_name . "</div>
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                        " . $row->location . "
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap'>
                        <span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-" . ($row->status=="active" ? "green" : "red") . "-100 text-" . ($row->status=="active" ? "green" : "red") . "-800'>
                            " . $row->status . "
                        </span>
                    </td>
                  </tr>";
        }
        
        echo "</tbody>
              </table>
            </div>";
    } else {
        echo "<p class='text-gray-500'>No branches found</p>";
    }
    
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

echo "</div>
</body>
</html>";
?>