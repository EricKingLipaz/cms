<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/checklogin.php');
check_login();

// Get user information
$aid=$_SESSION['odmsaid'];
include('includes/dbconnection.php');
$sql="SELECT * from tbladmin where ID=:aid";
$query = $dbh -> prepare($sql);
$query->bindParam(':aid',$aid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
  foreach($results as $row)
  {  
    $admin_name = $row->FirstName . ' ' . $row->LastName;
    $admin_role = $row->AdminName;
    $branch_id = $row->branch_id;
  }
} else {
  $admin_name = "Unknown User";
  $admin_role = "";
  $branch_id = null;
}

// Get branch information
$branch_name = "Unknown Branch";
if ($branch_id) {
  $branch_sql = "SELECT * from branches where id=:branch_id";
  $branch_query = $dbh -> prepare($branch_sql);
  $branch_query->bindParam(':branch_id',$branch_id,PDO::PARAM_STR);
  $branch_query->execute();
  $branch_results=$branch_query->fetchAll(PDO::FETCH_OBJ);
  if($branch_query->rowCount() > 0)
  {
    foreach($branch_results as $branch_row)
    {  
      $branch_name = $branch_row->branch_name;
    }
  }
}

// Get statistics with error handling
try {
  // Total members
  $members_sql = "SELECT COUNT(*) as count FROM tblchristian";
  if (!is_super_admin() && $branch_id) {
    $members_sql .= " WHERE branch_id = :branch_id";
  }
  $members_query = $dbh->prepare($members_sql);
  if (!is_super_admin() && $branch_id) {
    $members_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
  }
  $members_query->execute();
  $members_result = $members_query->fetch(PDO::FETCH_ASSOC);
  $total_members = $members_result['count'];

  // Total donations (this month)
  $donations_sql = "SELECT SUM(amount) as total FROM donations WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
  if (!is_super_admin() && $branch_id) {
    $donations_sql .= " AND branch_id = :branch_id";
  }
  $donations_query = $dbh->prepare($donations_sql);
  if (!is_super_admin() && $branch_id) {
    $donations_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
  }
  $donations_query->execute();
  $donations_result = $donations_query->fetch(PDO::FETCH_ASSOC);
  $total_donations = $donations_result['total'] ? number_format($donations_result['total'], 2) : "0.00";

  // Upcoming events
  $events_sql = "SELECT COUNT(*) as count FROM church_events WHERE event_date >= CURDATE()";
  if (!is_super_admin() && $branch_id) {
    $events_sql .= " AND branch_id = :branch_id";
  }
  $events_query = $dbh->prepare($events_sql);
  if (!is_super_admin() && $branch_id) {
    $events_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
  }
  $events_query->execute();
  $events_result = $events_query->fetch(PDO::FETCH_ASSOC);
  $upcoming_events = $events_result['count'];

  // Total branches
  $branches_sql = "SELECT COUNT(*) as count FROM branches";
  $branches_query = $dbh->prepare($branches_sql);
  $branches_query->execute();
  $branches_result = $branches_query->fetch(PDO::FETCH_ASSOC);
  $total_branches = $branches_result['count'];
} catch (Exception $e) {
  // Set default values if there's an error
  $total_members = 0;
  $total_donations = "0.00";
  $upcoming_events = 0;
  $total_branches = 0;
  echo "<!-- Database error: " . $e->getMessage() . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Church Management System - Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
          <div class="relative">
            <div class="flex items-center space-x-2 text-gray-700">
              <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-semibold">
                <?php echo substr($admin_name, 0, 1); ?>
              </div>
              <span><?php echo $admin_name; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Welcome, <?php echo $admin_name; ?>!</h1>
      <p class="text-gray-600 mt-2">
        <?php if(is_super_admin()): ?>
          Super Admin Dashboard - Managing all church branches
        <?php else: ?>
          Admin Dashboard - <?php echo $branch_name; ?> Branch
        <?php endif; ?>
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Members -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-primary">
            <i class="fas fa-users text-xl"></i>
          </div>
          <div class="ml-4">
            <h2 class="text-gray-500 text-sm font-medium">Total Members</h2>
            <p class="text-2xl font-bold"><?php echo number_format($total_members); ?></p>
          </div>
        </div>
      </div>

      <!-- Total Donations -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 text-secondary">
            <i class="fas fa-hand-holding-usd text-xl"></i>
          </div>
          <div class="ml-4">
            <h2 class="text-gray-500 text-sm font-medium">Donations (This Month)</h2>
            <p class="text-2xl font-bold">$<?php echo $total_donations; ?></p>
          </div>
        </div>
      </div>

      <!-- Upcoming Events -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-purple-100 text-accent">
            <i class="fas fa-calendar-alt text-xl"></i>
          </div>
          <div class="ml-4">
            <h2 class="text-gray-500 text-sm font-medium">Upcoming Events</h2>
            <p class="text-2xl font-bold"><?php echo $upcoming_events; ?></p>
          </div>
        </div>
      </div>

      <!-- Branches -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
            <i class="fas fa-church text-xl"></i>
          </div>
          <div class="ml-4">
            <h2 class="text-gray-500 text-sm font-medium">Church Branches</h2>
            <p class="text-2xl font-bold"><?php echo $total_branches; ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modules Grid -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Church Management Modules</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Members -->
        <a href="members/member_list.php" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300">
          <div class="p-3 bg-blue-100 rounded-full text-primary">
            <i class="fas fa-users text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Members</span>
        </a>

        <!-- Events -->
        <a href="events/event_list.php" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300">
          <div class="p-3 bg-purple-100 rounded-full text-purple-500">
            <i class="fas fa-calendar-alt text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Events</span>
        </a>

        <!-- Donations -->
        <a href="donations/donation_list.php" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300">
          <div class="p-3 bg-green-100 rounded-full text-green-500">
            <i class="fas fa-donate text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Donations</span>
        </a>

        <!-- Resources -->
        <a href="resources/equipment_list.php" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-300">
          <div class="p-3 bg-yellow-100 rounded-full text-yellow-500">
            <i class="fas fa-boxes text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Resources</span>
        </a>

        <!-- Worship Teams -->
        <a href="#" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition duration-300">
          <div class="p-3 bg-pink-100 rounded-full text-pink-500">
            <i class="fas fa-music text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Worship Teams</span>
        </a>

        <!-- Reports -->
        <a href="reports/dashboard.php" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300">
          <div class="p-3 bg-indigo-100 rounded-full text-indigo-500">
            <i class="fas fa-chart-bar text-xl"></i>
          </div>
          <span class="mt-2 text-sm font-medium text-gray-700">Reports</span>
        </a>
      </div>
    </div>
  </div>
</body>
</html>