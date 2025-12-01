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

// Get member statistics
$total_members = 0;
$male_members = 0;
$female_members = 0;
$active_members = 0;
$inactive_members = 0;

// Total members
$sql = "SELECT COUNT(*) as total FROM tblchristian";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_members = $result->total;

// Male members
$sql = "SELECT COUNT(*) as total FROM tblchristian WHERE Sex = 'Male'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$male_members = $result->total;

// Female members
$sql = "SELECT COUNT(*) as total FROM tblchristian WHERE Sex = 'Female'";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$female_members = $result->total;

// Age distribution
$age_groups = [
    '18-25' => 0,
    '26-35' => 0,
    '36-50' => 0,
    '51-65' => 0,
    '65+' => 0
];

foreach($age_groups as $group => $count) {
    list($min, $max) = explode('-', $group);
    if($group == '65+') {
        $sql = "SELECT COUNT(*) as count FROM tblchristian WHERE Age >= 65";
        $query = $dbh -> prepare($sql);
    } else {
        $sql = "SELECT COUNT(*) as count FROM tblchristian WHERE Age BETWEEN :min AND :max";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':min', $min, PDO::PARAM_INT);
        $query->bindParam(':max', $max, PDO::PARAM_INT);
    }
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $age_groups[$group] = $result->count;
}

// Branch-wise member distribution
$branch_members = [];
$sql = "SELECT b.branch_name, COUNT(c.ID) as member_count 
        FROM branches b 
        LEFT JOIN tblchristian c ON b.id = c.branch_id 
        GROUP BY b.id, b.branch_name 
        ORDER BY member_count DESC";
$query = $dbh -> prepare($sql);
$query->execute();
$branch_members = $query->fetchAll(PDO::FETCH_ASSOC);

// Membership growth by month
$membership_growth = [];
for($i = 11; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    
    $sql = "SELECT COUNT(*) as count FROM tblchristian WHERE CreationDate BETWEEN :start AND :end";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':start', $month_start, PDO::PARAM_STR);
    $query->bindParam(':end', $month_end, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $membership_growth[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'count' => $result->count
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Reports - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Member Reports</h1>
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
                
                <!-- Member Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Member Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-primary">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Members</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_members); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-primary">
                                    <i class="fas fa-male text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Male Members</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($male_members); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-pink-100 text-pink-500">
                                    <i class="fas fa-female text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Female Members</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($female_members); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-venus-mars text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Gender Ratio</h3>
                                    <p class="text-2xl font-bold"><?php echo $total_members > 0 ? round(($male_members/$total_members)*100) : 0; ?>:<?php echo $total_members > 0 ? round(($female_members/$total_members)*100) : 0; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Age Distribution Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Age Distribution</h3>
                        <canvas id="ageChart" height="300"></canvas>
                    </div>
                    
                    <!-- Membership Growth Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Membership Growth (Last 12 Months)</h3>
                        <canvas id="membershipChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Branch-wise Member Distribution -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Branch-wise Member Distribution</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $total_branch_members = array_sum(array_column($branch_members, 'member_count'));
                                foreach($branch_members as $branch): 
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $branch['branch_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['member_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        $percentage = $total_branch_members > 0 ? round(($branch['member_count']/$total_branch_members)*100, 1) : 0;
                                        echo $percentage . '%';
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
        // Age Distribution Chart
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        const ageChart = new Chart(ageCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($age_groups)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($age_groups)); ?>,
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
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
        
        // Membership Growth Chart
        const membershipCtx = document.getElementById('membershipChart').getContext('2d');
        const membershipChart = new Chart(membershipCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($membership_growth, 'month')); ?>,
                datasets: [{
                    label: 'New Members',
                    data: <?php echo json_encode(array_column($membership_growth, 'count')); ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>