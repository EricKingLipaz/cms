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

// Get date range for reports (default to last 30 days)
$end_date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime('-30 days'));

// Handle date range filter
if(isset($_POST['filter_reports'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
}

// Get resource statistics
$total_equipment = 0;
$working_equipment = 0;
$broken_equipment = 0;
$needs_repair_equipment = 0;
$disposed_equipment = 0;

// Total equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_equipment = $result->total;

// Working equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment WHERE status = 'working'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$working_equipment = $result->total;

// Broken equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment WHERE status = 'broken'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$broken_equipment = $result->total;

// Needs repair equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment WHERE status = 'needs_repair'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$needs_repair_equipment = $result->total;

// Disposed equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment WHERE status = 'disposed'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$disposed_equipment = $result->total;

// Equipment categories distribution
$equipment_categories = [];
$sql = "SELECT category, COUNT(*) as count 
        FROM church_equipment 
        GROUP BY category";
$query = $dbh -> prepare($sql);
$query->execute();
$equipment_categories = $query->fetchAll(PDO::FETCH_ASSOC);

// Branch-wise equipment distribution
$branch_equipment = [];
$sql = "SELECT b.branch_name, COUNT(e.id) as equipment_count 
        FROM branches b 
        LEFT JOIN church_equipment e ON b.id = e.branch_id 
        GROUP BY b.id, b.branch_name 
        ORDER BY equipment_count DESC";
$query = $dbh -> prepare($sql);
$query->execute();
$branch_equipment = $query->fetchAll(PDO::FETCH_ASSOC);

// Equipment value statistics
$total_equipment_value = 0;
$depreciated_value = 0;

$sql = "SELECT SUM(purchase_cost) as total_value, SUM(current_value) as depreciated_value FROM church_equipment";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_equipment_value = $result->total_value ? $result->total_value : 0;
$depreciated_value = $result->depreciated_value ? $result->depreciated_value : 0;

// Maintenance required equipment
$maintenance_equipment = [];
$sql = "SELECT item_name, category, last_maintenance_date, maintenance_schedule 
        FROM church_equipment 
        WHERE status = 'working' AND last_maintenance_date IS NOT NULL 
        ORDER BY last_maintenance_date ASC 
        LIMIT 10";
$query = $dbh -> prepare($sql);
$query->execute();
$maintenance_equipment = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Reports - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h1 class="text-2xl font-bold text-gray-800">Resource Reports</h1>
                    <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
                
                <!-- Date Range Filter -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form method="post" class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        </div>
                        <div>
                            <button type="submit" name="filter_reports" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filter Reports
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Resource Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Resource Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                    <i class="fas fa-boxes text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Equipment</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_equipment); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Working</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($working_equipment); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-500">
                                    <i class="fas fa-times-circle text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Broken</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($broken_equipment); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-orange-100 text-orange-500">
                                    <i class="fas fa-tools text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Needs Repair</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($needs_repair_equipment); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-gray-100 text-gray-500">
                                    <i class="fas fa-trash-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Disposed</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($disposed_equipment); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Financial Summary -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Financial Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                    <i class="fas fa-dollar-sign text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Equipment Value</h3>
                                    <p class="text-2xl font-bold">$<?php echo number_format($total_equipment_value, 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Current Value</h3>
                                    <p class="text-2xl font-bold">$<?php echo number_format($depreciated_value, 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-500">
                                    <i class="fas fa-percentage text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Depreciation</h3>
                                    <p class="text-2xl font-bold">
                                        <?php 
                                        echo $total_equipment_value > 0 ? 
                                            number_format((($total_equipment_value - $depreciated_value) / $total_equipment_value) * 100, 1) : '0.0'; 
                                        ?>%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Equipment Categories Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Equipment Categories Distribution</h3>
                        <canvas id="equipmentCategoryChart" height="300"></canvas>
                    </div>
                    
                    <!-- Equipment Status Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Equipment Status Distribution</h3>
                        <canvas id="equipmentStatusChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Branch-wise Equipment Distribution -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Branch-wise Equipment Distribution</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment Count</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $total_branch_equipment = array_sum(array_column($branch_equipment, 'equipment_count'));
                                foreach($branch_equipment as $branch): 
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $branch['branch_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['equipment_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        $percentage = $total_branch_equipment > 0 ? round(($branch['equipment_count']/$total_branch_equipment)*100, 1) : 0;
                                        echo $percentage . '%';
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Maintenance Required -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Maintenance Required</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Maintenance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Maintenance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($maintenance_equipment as $equipment): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $equipment['item_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $equipment['category']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $equipment['last_maintenance_date'] ? date('M j, Y', strtotime($equipment['last_maintenance_date'])) : 'N/A'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        if($equipment['last_maintenance_date'] && $equipment['maintenance_schedule']) {
                                            $next_maintenance = date('M j, Y', strtotime($equipment['last_maintenance_date'] . ' + ' . $equipment['maintenance_schedule']));
                                            echo $next_maintenance;
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Equipment Categories Chart
        const equipmentCategoryCtx = document.getElementById('equipmentCategoryChart').getContext('2d');
        const equipmentCategoryData = <?php echo json_encode(array_column($equipment_categories, 'category')); ?>;
        const equipmentCategoryCounts = <?php echo json_encode(array_column($equipment_categories, 'count')); ?>;
        
        const equipmentCategoryChart = new Chart(equipmentCategoryCtx, {
            type: 'pie',
            data: {
                labels: equipmentCategoryData,
                datasets: [{
                    data: equipmentCategoryCounts,
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Equipment Status Chart
        const equipmentStatusCtx = document.getElementById('equipmentStatusChart').getContext('2d');
        const equipmentStatusChart = new Chart(equipmentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Working', 'Broken', 'Needs Repair', 'Disposed'],
                datasets: [{
                    data: [<?php echo $working_equipment; ?>, <?php echo $broken_equipment; ?>, <?php echo $needs_repair_equipment; ?>, <?php echo $disposed_equipment; ?>],
                    backgroundColor: [
                        '#10b981',
                        '#ef4444',
                        '#f59e0b',
                        '#6b7280'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>