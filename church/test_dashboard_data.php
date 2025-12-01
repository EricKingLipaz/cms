<?php
session_start();
error_reporting(0);
include('includes/checklogin.php');
// check_login(); // Not checking login for this test

include('includes/dbconnection.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Data Test</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Dashboard Data Test</h1>";

// Test 1: Database connection
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>1. Database Connection</h2>";

try {
    $stmt = $dbh->query("SELECT 1");
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded'>
            <p class='font-bold'>✓ Database Connection Successful</p>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>
            <p class='font-bold'>✗ Database Connection Failed</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "</div>";

// Test 2: Check required tables
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>2. Required Tables</h2>";

$tables = array('tbladmin', 'tblchristian', 'branches', 'church_events', 'donations', 'church_equipment');
foreach ($tables as $table) {
    try {
        $stmt = $dbh->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-2'>
                <p class='font-bold'>✓ $table exists with {$result['count']} records</p>
              </div>";
    } catch (Exception $e) {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-2'>
                <p class='font-bold'>✗ $table access failed</p>
                <p>Error: " . $e->getMessage() . "</p>
              </div>";
    }
}

echo "</div>";

// Test 3: Check admin users
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>3. Admin Users</h2>";

try {
    $stmt = $dbh->query("SELECT ID, UserName, FirstName, LastName, Email, AdminName FROM tbladmin");
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full divide-y divide-gray-200'>
                <thead class='bg-gray-50'>
                    <tr>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>ID</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Username</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Name</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Email</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Role</th>
                    </tr>
                </thead>
                <tbody class='bg-white divide-y divide-gray-200'>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['ID']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['UserName']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['FirstName']} {$row['LastName']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['Email']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['AdminName']}</td>
              </tr>";
    }
    
    echo "      </tbody>
            </table>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>
            <p class='font-bold'>✗ Failed to retrieve admin users</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "</div>";

// Test 4: Check branches
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>4. Branches</h2>";

try {
    $stmt = $dbh->query("SELECT id, branch_name, location, is_headquarters FROM branches");
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full divide-y divide-gray-200'>
                <thead class='bg-gray-50'>
                    <tr>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>ID</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch Name</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Location</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Headquarters</th>
                    </tr>
                </thead>
                <tbody class='bg-white divide-y divide-gray-200'>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $is_headquarters = $row['is_headquarters'] ? 'Yes' : 'No';
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['id']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['branch_name']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['location']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>$is_headquarters</td>
              </tr>";
    }
    
    echo "      </tbody>
            </table>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>
            <p class='font-bold'>✗ Failed to retrieve branches</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "</div>";

// Test 5: Check members
echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
        <h2 class='text-xl font-bold text-gray-800 mb-4'>5. Church Members</h2>";

try {
    $stmt = $dbh->query("SELECT ID, Name, lastname, branch_id FROM tblchristian LIMIT 10");
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full divide-y divide-gray-200'>
                <thead class='bg-gray-50'>
                    <tr>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>ID</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Name</th>
                        <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Branch ID</th>
                    </tr>
                </thead>
                <tbody class='bg-white divide-y divide-gray-200'>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['ID']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['Name']} {$row['lastname']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['branch_id']}</td>
              </tr>";
    }
    
    echo "      </tbody>
            </table>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>
            <p class='font-bold'>✗ Failed to retrieve members</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "</div>";

echo "<div class='text-center mt-8'>
        <a href='main_dashboard.php' class='bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4'>
            <i class='fas fa-tachometer-alt mr-2'></i>Dashboard
        </a>
        <a href='index.php' class='bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded'>
            <i class='fas fa-sign-in-alt mr-2'></i>Login
        </a>
      </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js'></script>
</body>
</html>";
?>