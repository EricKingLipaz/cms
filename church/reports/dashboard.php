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

// Get overall statistics
$total_members = 0;
$total_donations = 0;
$total_donation_amount = 0;
$total_events = 0;
$total_equipment = 0;

// Total members
$sql = "SELECT COUNT(*) as total FROM tblchristian";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_members = $result->total;

// Total donations and amount
$sql = "SELECT COUNT(*) as total, SUM(amount) as total_amount FROM donations";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_donations = $result->total;
$total_donation_amount = $result->total_amount ? $result->total_amount : 0;

// Total events
$sql = "SELECT COUNT(*) as total FROM church_events";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_events = $result->total;

// Total equipment
$sql = "SELECT COUNT(*) as total FROM church_equipment";
$query = $dbh -> prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_equipment = $result->total;

// Membership growth data (last 6 months)
$membership_data = [];
for($i = 5; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    
    $sql = "SELECT COUNT(*) as count FROM tblchristian WHERE CreationDate BETWEEN :start AND :end";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':start', $month_start, PDO::PARAM_STR);
    $query->bindParam(':end', $month_end, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $membership_data[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'count' => $result->count
    ];
}

// Donation trends (last 6 months)
$donation_data = [];
for($i = 5; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    
    $sql = "SELECT SUM(amount) as total FROM donations WHERE donation_date BETWEEN :start AND :end";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':start', $month_start, PDO::PARAM_STR);
    $query->bindParam(':end', $month_end, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $donation_data[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'amount' => $result->total ? $result->total : 0
    ];
}

// Branch-wise statistics
$branch_stats = [];
$sql = "SELECT b.branch_name, b.is_headquarters,
        (SELECT COUNT(*) FROM tblchristian c WHERE c.branch_id = b.id) as member_count,
        (SELECT COUNT(*) FROM donations d WHERE d.branch_id = b.id) as donation_count,
        (SELECT SUM(amount) FROM donations d WHERE d.branch_id = b.id) as donation_amount,
        (SELECT COUNT(*) FROM church_events e WHERE e.branch_id = b.id) as event_count
        FROM branches b ORDER BY b.is_headquarters DESC, b.branch_name";
$query = $dbh -> prepare($sql);
$query->execute();
$branch_stats = $query->fetchAll(PDO::FETCH_ASSOC);

// Top donors
$top_donors = [];
$sql = "SELECT donor_name, SUM(amount) as total_amount, COUNT(*) as donation_count 
        FROM donations 
        GROUP BY donor_name 
        ORDER BY total_amount DESC 
        LIMIT 10";
$query = $dbh -> prepare($sql);
$query->execute();
$top_donors = $query->fetchAll(PDO::FETCH_ASSOC);

// Recent activities
$recent_activities = [];
$sql = "SELECT 'member' as type, Name as name, CreationDate as date FROM tblchristian ORDER BY CreationDate DESC LIMIT 5
        UNION ALL
        SELECT 'donation' as type, donor_name as name, created_at as date FROM donations ORDER BY created_at DESC LIMIT 5
        UNION ALL
        SELECT 'event' as type, event_name as name, created_at as date FROM church_events ORDER BY created_at DESC LIMIT 5
        ORDER BY date DESC LIMIT 10";
$query = $dbh -> prepare($sql);
$query->execute();
$recent_activities = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics Dashboard</h1>
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
                
                <!-- Key Metrics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Key Metrics</h2>
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
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-hand-holding-usd text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Donations</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_donations); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                    <i class="fas fa-dollar-sign text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Donation Amount</h3>
                                    <p class="text-2xl font-bold">$<?php echo number_format($total_donation_amount, 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Events</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_events); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Membership Growth Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Membership Growth (Last 6 Months)</h3>
                        <canvas id="membershipChart" height="300"></canvas>
                    </div>
                    
                    <!-- Donation Trends Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Donation Trends (Last 6 Months)</h3>
                        <canvas id="donationChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Branch Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Branch Statistics</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donations</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($branch_stats as $branch): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $branch['branch_name']; ?></div>
                                        <?php if($branch['is_headquarters'] == 1): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Headquarters
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['member_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['donation_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($branch['donation_amount'] ? $branch['donation_amount'] : 0, 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['event_count']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Top Donors -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Donors</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donations</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Donations</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Donation</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($top_donors as $donor): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $donor['donor_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($donor['total_amount'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($donor['donation_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($donor['total_amount'] / $donor['donation_count'], 2); ?></td>
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
        // Membership Growth Chart
        const membershipCtx = document.getElementById('membershipChart').getContext('2d');
        const membershipChart = new Chart(membershipCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($membership_data, 'month')); ?>,
                datasets: [{
                    label: 'New Members',
                    data: <?php echo json_encode(array_column($membership_data, 'count')); ?>,
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
                            stepSize: 10
                        }
                    }
                }
            }
        });
        
        // Donation Trends Chart
        const donationCtx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(donationCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($donation_data, 'month')); ?>,
                datasets: [{
                    label: 'Donation Amount ($)',
                    data: <?php echo json_encode(array_column($donation_data, 'amount')); ?>,
                    backgroundColor: '#10b981',
                    borderColor: '#059669',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>