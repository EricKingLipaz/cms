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

// Get financial statistics
$total_donations = 0;
$total_donation_amount = 0;
$donation_types = [];
$monthly_donations = [];

// Total donations and amount
$sql = "SELECT COUNT(*) as total, SUM(amount) as total_amount FROM donations WHERE donation_date BETWEEN :start AND :end";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_donations = $result->total;
$total_donation_amount = $result->total_amount ? $result->total_amount : 0;

// Donation types distribution
$sql = "SELECT donation_type, COUNT(*) as count, SUM(amount) as total_amount 
        FROM donations 
        WHERE donation_date BETWEEN :start AND :end
        GROUP BY donation_type";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$donation_types = $query->fetchAll(PDO::FETCH_ASSOC);

// Monthly donations for the last 12 months
for($i = 11; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    
    $sql = "SELECT SUM(amount) as total FROM donations WHERE donation_date BETWEEN :start AND :end";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':start', $month_start, PDO::PARAM_STR);
    $query->bindParam(':end', $month_end, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $monthly_donations[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'amount' => $result->total ? $result->total : 0
    ];
}

// Payment methods distribution
$payment_methods = [];
$sql = "SELECT payment_method, COUNT(*) as count, SUM(amount) as total_amount 
        FROM donations 
        WHERE donation_date BETWEEN :start AND :end
        GROUP BY payment_method";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$payment_methods = $query->fetchAll(PDO::FETCH_ASSOC);

// Top donors
$top_donors = [];
$sql = "SELECT donor_name, SUM(amount) as total_amount, COUNT(*) as donation_count 
        FROM donations 
        WHERE donation_date BETWEEN :start AND :end
        GROUP BY donor_name 
        ORDER BY total_amount DESC 
        LIMIT 10";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$top_donors = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Financial Reports</h1>
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
                
                <!-- Financial Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Financial Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-donate text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Donations</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_donations); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-dollar-sign text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Amount</h3>
                                    <p class="text-2xl font-bold">$<?php echo number_format($total_donation_amount, 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                    <i class="fas fa-calculator text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Average Donation</h3>
                                    <p class="text-2xl font-bold">$<?php echo $total_donations > 0 ? number_format($total_donation_amount/$total_donations, 2) : '0.00'; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Monthly Average</h3>
                                    <p class="text-2xl font-bold">$<?php echo count($monthly_donations) > 0 ? number_format(array_sum(array_column($monthly_donations, 'amount'))/count($monthly_donations), 2) : '0.00'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Donation Types Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Donation Types Distribution</h3>
                        <canvas id="donationTypeChart" height="300"></canvas>
                    </div>
                    
                    <!-- Monthly Donations Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Monthly Donations (Last 12 Months)</h3>
                        <canvas id="monthlyDonationChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Payment Methods</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Donations</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $total_payment_amount = array_sum(array_column($payment_methods, 'total_amount'));
                                foreach($payment_methods as $method): 
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $method['payment_method']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($method['count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo number_format($method['total_amount'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        $percentage = $total_payment_amount > 0 ? round(($method['total_amount']/$total_payment_amount)*100, 1) : 0;
                                        echo $percentage . '%';
                                        ?>
                                    </td>
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
        // Donation Types Chart
        const donationTypeCtx = document.getElementById('donationTypeChart').getContext('2d');
        const donationTypeData = <?php echo json_encode(array_column($donation_types, 'donation_type')); ?>;
        const donationTypeAmounts = <?php echo json_encode(array_column($donation_types, 'total_amount')); ?>;
        
        const donationTypeChart = new Chart(donationTypeCtx, {
            type: 'pie',
            data: {
                labels: donationTypeData,
                datasets: [{
                    data: donationTypeAmounts,
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
        
        // Monthly Donations Chart
        const monthlyDonationCtx = document.getElementById('monthlyDonationChart').getContext('2d');
        const monthlyDonationChart = new Chart(monthlyDonationCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($monthly_donations, 'month')); ?>,
                datasets: [{
                    label: 'Donation Amount ($)',
                    data: <?php echo json_encode(array_column($monthly_donations, 'amount')); ?>,
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