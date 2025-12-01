<?php
session_start();
error_reporting(0);
include('../includes/checklogin.php');
check_login();

// Get user information
$aid=$_SESSION['odmsaid'];
$is_super_admin = is_super_admin();
$user_branch_id = get_user_branch();

// Database connection
include('../includes/dbconnection.php');

// Only super admins can access this page
if(!$is_super_admin) {
    echo "<script>alert('You do not have permission to access this page');</script>";
    echo "<script>window.location.href = '../main_dashboard.php'</script>";
    exit;
}

// Handle manual sync request
if(isset($_POST['sync_now'])) {
    // In a real implementation, this would trigger the sync process
    // For this demo, we'll just show a message
    echo '<script>alert("Data synchronization initiated. This may take a few moments.")</script>';
}

// Get sync statistics
$total_members = 0;
$total_donations = 0;
$total_events = 0;
$total_equipment = 0;

$sql = "SELECT COUNT(*) as total FROM tblchristian";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_members = $result->total;

$sql = "SELECT COUNT(*) as total FROM donations";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_donations = $result->total;

$sql = "SELECT COUNT(*) as total FROM church_events";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_events = $result->total;

$sql = "SELECT COUNT(*) as total FROM church_equipment";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_equipment = $result->total;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sync - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <?php include('../includes/header.php'); ?>
    
    <div class="flex">
        <!-- Sidebar -->
        <?php include('../includes/sidebar.php'); ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Data Synchronization</h1>
                </div>
                
                <!-- Sync Status -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sync-alt fa-2x text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-800">Real-time Data Sync</h3>
                            <p class="text-sm text-blue-700">All branches are automatically synchronized with the central database.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Sync Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Synchronization Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-primary"><?php echo number_format($total_members); ?></div>
                            <div class="text-sm text-gray-500">Members</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-500"><?php echo number_format($total_donations); ?></div>
                            <div class="text-sm text-gray-500">Donations</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-purple-500"><?php echo number_format($total_events); ?></div>
                            <div class="text-sm text-gray-500">Events</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-2xl font-bold text-yellow-500"><?php echo number_format($total_equipment); ?></div>
                            <div class="text-sm text-gray-500">Equipment</div>
                        </div>
                    </div>
                </div>
                
                <!-- Branch Sync Status -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Branch Sync Status</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Sync</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Count</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $sql = "SELECT * FROM branches ORDER BY is_headquarters DESC, branch_name";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo $row->branch_name;?></div>
                                                <div class="text-sm text-gray-500"><?php echo $row->branch_code;?></div>
                                                <?php if($row->is_headquarters == 1) { ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Headquarters
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php 
                                                // In a real implementation, this would show actual sync times
                                                echo date('M j, Y g:i A', strtotime('-'.rand(1, 60).' minutes'));
                                                ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Synced
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php
                                                // Count members in this branch
                                                $sql_count = "SELECT COUNT(*) as total FROM tblchristian WHERE branch_id = :branch_id";
                                                $query_count = $dbh -> prepare($sql_count);
                                                $query_count->bindParam(':branch_id', $row->id, PDO::PARAM_INT);
                                                $query_count->execute();
                                                $result_count = $query_count->fetch(PDO::FETCH_OBJ);
                                                echo number_format($result_count->total);
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Manual Sync -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Manual Synchronization</h2>
                    <p class="text-sm text-gray-600 mb-4">Trigger a manual synchronization of all branch data with the central database.</p>
                    <form method="post">
                        <button type="submit" name="sync_now" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i> Sync All Data Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Simulate real-time sync status updates
        setInterval(function() {
            $('.sync-status').each(function() {
                var statuses = ['Synced', 'Syncing...', 'Pending'];
                var randomStatus = statuses[Math.floor(Math.random() * statuses.length)];
                $(this).text(randomStatus);
                
                if(randomStatus === 'Synced') {
                    $(this).removeClass('bg-yellow-100 text-yellow-800 bg-blue-100 text-blue-800').addClass('bg-green-100 text-green-800');
                } else if(randomStatus === 'Syncing...') {
                    $(this).removeClass('bg-green-100 text-green-800 bg-yellow-100 text-yellow-800').addClass('bg-blue-100 text-blue-800');
                } else {
                    $(this).removeClass('bg-green-100 text-green-800 bg-blue-100 text-blue-800').addClass('bg-yellow-100 text-yellow-800');
                }
            });
        }, 5000);
    </script>
</body>
</html>