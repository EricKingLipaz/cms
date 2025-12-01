<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user is logged in
$is_logged_in = isset($_SESSION['odmsaid']) && strlen($_SESSION['odmsaid']) > 0;

// Get user info if logged in
$user_info = null;
if ($is_logged_in) {
    $aid=$_SESSION['odmsaid'];
    $sql="SELECT * from tbladmin where ID=:aid";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':aid',$aid,PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0) {
        $user_info = $results[0];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#10b981',
                        accent: '#8b5cf6',
                        dark: '#1e293b',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="main_dashboard.php" class="flex items-center py-4">
                            <span class="font-semibold text-gray-800 text-lg">Church Management System</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <?php if ($is_logged_in): ?>
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center space-x-2 text-gray-700 hover:text-primary transition duration-300">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-semibold">
                                <?php echo substr($user_info->FirstName . ' ' . $user_info->LastName, 0, 1); ?>
                            </div>
                            <span><?php echo $user_info->FirstName . ' ' . $user_info->LastName; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                            <a href="change_password.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="index.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">System Status</h1>
            <p class="text-gray-600 mt-2">Current status of the Church Management System</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Authentication Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full <?php echo $is_logged_in ? 'bg-green-100 text-green-500' : 'bg-yellow-100 text-yellow-500'; ?>">
                        <i class="fas <?php echo $is_logged_in ? 'fa-user-check' : 'fa-user-clock'; ?> text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-bold text-gray-800">Authentication</h2>
                        <p class="text-gray-600">
                            <?php echo $is_logged_in ? 'User logged in' : 'No active session'; ?>
                        </p>
                    </div>
                </div>
                <?php if ($is_logged_in): ?>
                <div class="mt-4 text-sm">
                    <p><span class="font-medium">User:</span> <?php echo $user_info->FirstName . ' ' . $user_info->LastName; ?></p>
                    <p><span class="font-medium">Role:</span> <?php echo $user_info->AdminName; ?></p>
                    <p><span class="font-medium">Email:</span> <?php echo $user_info->Email; ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Database Connection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <?php
                $db_status = false;
                $db_error = '';
                try {
                    $stmt = $dbh->query("SELECT 1");
                    $db_status = true;
                } catch (Exception $e) {
                    $db_error = $e->getMessage();
                }
                ?>
                <div class="flex items-center">
                    <div class="p-3 rounded-full <?php echo $db_status ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500'; ?>">
                        <i class="fas <?php echo $db_status ? 'fa-database' : 'fa-exclamation-triangle'; ?> text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-bold text-gray-800">Database Connection</h2>
                        <p class="text-gray-600">
                            <?php echo $db_status ? 'Connected successfully' : 'Connection failed'; ?>
                        </p>
                    </div>
                </div>
                <?php if (!$db_status): ?>
                <div class="mt-4 text-sm text-red-500">
                    <p>Error: <?php echo $db_error; ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Required Tables -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Required Tables</h2>
                <?php
                $tables = array('tbladmin', 'tblchristian', 'branches', 'church_events', 'donations', 'church_equipment');
                foreach ($tables as $table) {
                    $table_exists = false;
                    try {
                        $stmt = $dbh->query("SELECT COUNT(*) as count FROM $table");
                        $table_exists = true;
                    } catch (Exception $e) {
                        // Table doesn't exist
                    }
                    ?>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600"><?php echo $table; ?></span>
                        <span class="<?php echo $table_exists ? 'text-green-500' : 'text-red-500'; ?>">
                            <i class="fas <?php echo $table_exists ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">System Information</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">PHP Version:</span> <?php echo phpversion(); ?></p>
                    <p><span class="font-medium">Server Software:</span> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
                    <p><span class="font-medium">Server Time:</span> <?php echo date('Y-m-d H:i:s'); ?></p>
                    <p><span class="font-medium">Session Status:</span> <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive'; ?></p>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <?php if ($is_logged_in): ?>
            <a href="main_dashboard.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
            </a>
            <?php else: ?>
            <a href="index.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
            <?php endif; ?>
            <a href="test_dashboard_data.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-vial mr-2"></i>Advanced Test
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-500">
                &copy; 2023 Church Management System. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // User menu toggle
        document.getElementById('user-menu-btn').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuBtn = document.getElementById('user-menu-btn');
            
            if (!userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>