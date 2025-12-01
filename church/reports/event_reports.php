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

// Get event statistics
$total_events = 0;
$upcoming_events = 0;
$completed_events = 0;
$cancelled_events = 0;

// Total events
$sql = "SELECT COUNT(*) as total FROM church_events WHERE start_date BETWEEN :start AND :end";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$total_events = $result->total;

// Upcoming events
$sql = "SELECT COUNT(*) as total FROM church_events WHERE status = 'planned' AND start_date BETWEEN :start AND :end";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$upcoming_events = $result->total;

// Completed events
$sql = "SELECT COUNT(*) as total FROM church_events WHERE status = 'completed' AND start_date BETWEEN :start AND :end";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$completed_events = $result->total;

// Cancelled events
$sql = "SELECT COUNT(*) as total FROM church_events WHERE status = 'cancelled' AND start_date BETWEEN :start AND :end";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$cancelled_events = $result->total;

// Event types distribution
$event_types = [];
$sql = "SELECT event_type, COUNT(*) as count 
        FROM church_events 
        WHERE start_date BETWEEN :start AND :end
        GROUP BY event_type";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$event_types = $query->fetchAll(PDO::FETCH_ASSOC);

// Monthly events for the last 12 months
$monthly_events = [];
for($i = 11; $i >= 0; $i--) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    
    $sql = "SELECT COUNT(*) as total FROM church_events WHERE start_date BETWEEN :start AND :end";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':start', $month_start, PDO::PARAM_STR);
    $query->bindParam(':end', $month_end, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $monthly_events[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'count' => $result->total
    ];
}

// Branch-wise event distribution
$branch_events = [];
$sql = "SELECT b.branch_name, COUNT(e.id) as event_count 
        FROM branches b 
        LEFT JOIN church_events e ON b.id = e.branch_id AND e.start_date BETWEEN :start AND :end
        GROUP BY b.id, b.branch_name 
        ORDER BY event_count DESC";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$branch_events = $query->fetchAll(PDO::FETCH_ASSOC);

// Popular events (most registrations)
$popular_events = [];
$sql = "SELECT e.event_name, COUNT(r.id) as registration_count
        FROM church_events e
        LEFT JOIN event_registrations r ON e.id = r.event_id
        WHERE e.start_date BETWEEN :start AND :end
        GROUP BY e.id, e.event_name
        ORDER BY registration_count DESC
        LIMIT 10";
$query = $dbh -> prepare($sql);
$query->bindParam(':start', $start_date, PDO::PARAM_STR);
$query->bindParam(':end', $end_date, PDO::PARAM_STR);
$query->execute();
$popular_events = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reports - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Event Reports</h1>
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
                
                <!-- Event Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Event Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                    <i class="fas fa-calendar-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Total Events</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($total_events); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                    <i class="fas fa-calendar-plus text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Upcoming Events</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($upcoming_events); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-500">
                                    <i class="fas fa-calendar-check text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Completed Events</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($completed_events); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-500">
                                    <i class="fas fa-calendar-times text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500">Cancelled Events</h3>
                                    <p class="text-2xl font-bold"><?php echo number_format($cancelled_events); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Event Types Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Event Types Distribution</h3>
                        <canvas id="eventTypeChart" height="300"></canvas>
                    </div>
                    
                    <!-- Monthly Events Chart -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Monthly Events (Last 12 Months)</h3>
                        <canvas id="monthlyEventChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Branch-wise Event Distribution -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Branch-wise Event Distribution</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $total_branch_events = array_sum(array_column($branch_events, 'event_count'));
                                foreach($branch_events as $branch): 
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $branch['branch_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($branch['event_count']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                        $percentage = $total_branch_events > 0 ? round(($branch['event_count']/$total_branch_events)*100, 1) : 0;
                                        echo $percentage . '%';
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Popular Events -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Popular Events</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($popular_events as $event): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $event['event_name']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($event['registration_count']); ?></td>
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
        // Event Types Chart
        const eventTypeCtx = document.getElementById('eventTypeChart').getContext('2d');
        const eventTypeData = <?php echo json_encode(array_column($event_types, 'event_type')); ?>;
        const eventTypeCounts = <?php echo json_encode(array_column($event_types, 'count')); ?>;
        
        const eventTypeChart = new Chart(eventTypeCtx, {
            type: 'doughnut',
            data: {
                labels: eventTypeData,
                datasets: [{
                    data: eventTypeCounts,
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
        
        // Monthly Events Chart
        const monthlyEventCtx = document.getElementById('monthlyEventChart').getContext('2d');
        const monthlyEventChart = new Chart(monthlyEventCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthly_events, 'month')); ?>,
                datasets: [{
                    label: 'Number of Events',
                    data: <?php echo json_encode(array_column($monthly_events, 'count')); ?>,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
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
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>