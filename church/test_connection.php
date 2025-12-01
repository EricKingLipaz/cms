<?php
// Test database connection and authentication
include('includes/dbconnection.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Connection Test</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Database Connection Test</h1>";

try {
    // Test PDO connection
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM tbladmin");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✓ PDO Connection Successful</p>
            <p>Found {$result['count']} admin users in the database.</p>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✗ PDO Connection Failed</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

try {
    // Test MySQLi connection
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM tbladmin");
    $row = mysqli_fetch_assoc($result);
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✓ MySQLi Connection Successful</p>
            <p>Found {$row['count']} admin users in the database.</p>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✗ MySQLi Connection Failed</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

// Test if we can retrieve admin users
try {
    $stmt = $dbh->query("SELECT ID, UserName, FirstName, LastName, Email FROM tbladmin LIMIT 5");
    echo "<div class='bg-white shadow rounded-lg p-6 mb-4'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Admin Users</h2>
            <div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>ID</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Username</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Name</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Email</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['ID']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['UserName']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['FirstName']} {$row['LastName']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['Email']}</td>
              </tr>";
    }
    
    echo "      </tbody>
                </table>
            </div>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✗ Failed to retrieve admin users</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

// Test branches table
try {
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM branches");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✓ Branches Table Access Successful</p>
            <p>Found {$result['count']} branches in the database.</p>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>
            <p class='font-bold'>✗ Branches Table Access Failed</p>
            <p>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "<div class='text-center mt-8'>
        <a href='index.php' class='bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4'>
            <i class='fas fa-sign-in-alt mr-2'></i>Login
        </a>
        <a href='landing.php' class='bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded'>
            <i class='fas fa-home mr-2'></i>Home
        </a>
      </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js'></script>
</body>
</html>";
?>