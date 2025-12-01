<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/checklogin.php');
check_login();

// Initialize variables to prevent undefined variable errors
$admin_name = "Unknown User";
$admin_role = "";
$branch_id = null;
$branch_name = "Unknown Branch";
$total_members = 0;
$total_donations = "0.00";
$upcoming_events = 0;
$total_branches = 0;
$activities = array();

// Get user information
if (isset($_SESSION['odmsaid'])) {
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
    }
}

// Get branch information
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

// Get statistics
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
    $donations_sql = "SELECT SUM(amount) as total FROM donations WHERE MONTH(donation_date) = MONTH(CURRENT_DATE()) AND YEAR(donation_date) = YEAR(CURRENT_DATE())";
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
    $events_sql = "SELECT COUNT(*) as count FROM church_events WHERE start_date >= CURDATE()";
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

    // Get recent activities
    $activities = array();

    // Recent members
    $recent_members_sql = "SELECT Name, lastname, CreationDate FROM tblchristian ORDER BY CreationDate DESC LIMIT 2";
    if (!is_super_admin() && $branch_id) {
      $recent_members_sql .= " WHERE branch_id = :branch_id";
    }
    $recent_members_query = $dbh->prepare($recent_members_sql);
    if (!is_super_admin() && $branch_id) {
      $recent_members_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
    }
    $recent_members_query->execute();
    while ($row = $recent_members_query->fetch(PDO::FETCH_ASSOC)) {
      $activities[] = array(
        'icon' => 'fa-user-plus',
        'icon_bg' => 'bg-blue-100',
        'icon_color' => 'text-blue-500',
        'title' => 'New member registered',
        'description' => $row['Name'] . ' ' . $row['lastname'] . ' joined the church',
        'time' => date('F j, Y', strtotime($row['CreationDate']))
      );
    }

    // Recent donations
    $recent_donations_sql = "SELECT d.donor_name, d.amount, d.donation_date FROM donations d ORDER BY d.donation_date DESC LIMIT 2";
    if (!is_super_admin() && $branch_id) {
      $recent_donations_sql .= " WHERE d.branch_id = :branch_id";
    }
    $recent_donations_query = $dbh->prepare($recent_donations_sql);
    if (!is_super_admin() && $branch_id) {
      $recent_donations_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
    }
    $recent_donations_query->execute();
    while ($row = $recent_donations_query->fetch(PDO::FETCH_ASSOC)) {
      $activities[] = array(
        'icon' => 'fa-donate',
        'icon_bg' => 'bg-green-100',
        'icon_color' => 'text-green-500',
        'title' => 'Donation received',
        'description' => '$' . number_format($row['amount'], 2) . ' donation from ' . $row['donor_name'],
        'time' => date('F j, Y', strtotime($row['donation_date']))
      );
    }

    // Recent events
    $recent_events_sql = "SELECT event_name, start_date FROM church_events WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 2";
    if (!is_super_admin() && $branch_id) {
      $recent_events_sql .= " WHERE branch_id = :branch_id";
    }
    $recent_events_query = $dbh->prepare($recent_events_sql);
    if (!is_super_admin() && $branch_id) {
      $recent_events_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
    }
    $recent_events_query->execute();
    while ($row = $recent_events_query->fetch(PDO::FETCH_ASSOC)) {
      $activities[] = array(
        'icon' => 'fa-calendar-check',
        'icon_bg' => 'bg-purple-100',
        'icon_color' => 'text-purple-500',
        'title' => 'Event scheduled',
        'description' => $row['event_name'] . ' planned for ' . date('F Y', strtotime($row['start_date'])),
        'time' => date('F j, Y', strtotime($row['start_date']))
      );
    }

    // Recent equipment
    $recent_equipment_sql = "SELECT item_name, status FROM church_equipment ORDER BY created_at DESC LIMIT 1";
    if (!is_super_admin() && $branch_id) {
      $recent_equipment_sql .= " WHERE branch_id = :branch_id";
    }
    $recent_equipment_query = $dbh->prepare($recent_equipment_sql);
    if (!is_super_admin() && $branch_id) {
      $recent_equipment_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
    }
    $recent_equipment_query->execute();
    while ($row = $recent_equipment_query->fetch(PDO::FETCH_ASSOC)) {
      $activities[] = array(
        'icon' => 'fa-tools',
        'icon_bg' => 'bg-yellow-100',
        'icon_color' => 'text-yellow-500',
        'title' => 'Equipment maintenance',
        'description' => $row['item_name'] . ' needs ' . strtolower($row['status']),
        'time' => 'Recently'
      );
    }
} catch (Exception $e) {
    // Handle database errors gracefully
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
          <div class="hidden md:flex items-center space-x-1">
            <a href="main_dashboard.php" class="py-4 px-2 text-primary border-b-2 border-primary font-semibold">Dashboard</a>
            <a href="members/member_list.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-primary transition duration-300">Members</a>
            <a href="events/event_list.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-primary transition duration-300">Events</a>
            <a href="donations/donation_list.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-primary transition duration-300">Donations</a>
            <a href="resources/equipment_list.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-primary transition duration-300">Resources</a>
          </div>
        </div>
        <div class="hidden md:flex items-center space-x-3">
          <div class="relative">
            <button class="text-gray-500 hover:text-primary transition duration-300">
              <i class="fas fa-bell text-xl"></i>
              <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">3</span>
            </button>
          </div>
          <div class="relative">
            <button id="user-menu-btn" class="flex items-center space-x-2 text-gray-700 hover:text-primary transition duration-300">
              <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-semibold">
                <?php echo substr($admin_name, 0, 1); ?>
              </div>
              <span><?php echo $admin_name; ?></span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
              <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
              <a href="change_password.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
              <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
            </div>
          </div>
        </div>
        <div class="md:hidden flex items-center">
          <button class="outline-none mobile-menu-button">
            <svg class="w-6 h-6 text-gray-500 hover:text-primary"
              x-show="!showMenu"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div class="hidden mobile-menu">
      <ul class="bg-white">
        <li><a href="main_dashboard.php" class="block text-sm px-2 py-4 text-primary font-semibold">Dashboard</a></li>
        <li><a href="members/member_list.php" class="block text-sm px-2 py-4 hover:bg-primary hover:text-white transition duration-300">Members</a></li>
        <li><a href="events/event_list.php" class="block text-sm px-2 py-4 hover:bg-primary hover:text-white transition duration-300">Events</a></li>
        <li><a href="donations/donation_list.php" class="block text-sm px-2 py-4 hover:bg-primary hover:text-white transition duration-300">Donations</a></li>
        <li><a href="resources/equipment_list.php" class="block text-sm px-2 py-4 hover:bg-primary hover:text-white transition duration-300">Resources</a></li>
      </ul>
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
        <div class="mt-4">
          <span class="text-green-500 text-sm font-medium"><i class="fas fa-arrow-up"></i> 12.5%</span>
          <span class="text-gray-500 text-sm ml-2">from last month</span>
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
        <div class="mt-4">
          <span class="text-green-500 text-sm font-medium"><i class="fas fa-arrow-up"></i> 8.3%</span>
          <span class="text-gray-500 text-sm ml-2">from last month</span>
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
        <div class="mt-4">
          <span class="text-blue-500 text-sm font-medium">3 this week</span>
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
        <div class="mt-4">
          <span class="text-gray-500 text-sm">
            <?php 
            if (is_super_admin()) {
              echo "1 Headquarters, " . ($total_branches - 1) . " Branches";
            } else {
              echo $branch_name;
            }
            ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Chart -->
      <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Membership Growth</h2>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
          <p class="text-gray-500">Membership Growth Chart (Placeholder)</p>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h2>
        <div class="space-y-4">
          <?php foreach ($activities as $activity): ?>
          <div class="flex items-start">
            <div class="p-2 <?php echo $activity['icon_bg']; ?> rounded-full">
              <i class="fas <?php echo $activity['icon']; ?> <?php echo $activity['icon_color']; ?>"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900"><?php echo $activity['title']; ?></p>
              <p class="text-sm text-gray-500"><?php echo $activity['description']; ?></p>
              <p class="text-xs text-gray-400"><?php echo $activity['time']; ?></p>
            </div>
          </div>
          <?php endforeach; ?>
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

    <!-- Branch Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Church Branches</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php
            // Fetch branches
            $branches_sql = "SELECT * FROM branches";
            if (!is_super_admin() && $branch_id) {
              $branches_sql .= " WHERE id = :branch_id";
            }
            $branches_sql .= " ORDER BY is_headquarters DESC, branch_name ASC";
            
            $branches_query = $dbh->prepare($branches_sql);
            if (!is_super_admin() && $branch_id) {
              $branches_query->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
            }
            $branches_query->execute();
            
            while ($branch = $branches_query->fetch(PDO::FETCH_OBJ)) {
              // Get member count for this branch
              $member_count = 0;
              try {
                $member_count_sql = "SELECT COUNT(*) as count FROM tblchristian WHERE branch_id = :branch_id";
                $member_count_query = $dbh->prepare($member_count_sql);
                $member_count_query->bindParam(':branch_id', $branch->id, PDO::PARAM_INT);
                $member_count_query->execute();
                $member_count_result = $member_count_query->fetch(PDO::FETCH_ASSOC);
                $member_count = $member_count_result['count'];
              } catch (Exception $e) {
                // Handle error silently
              }
            ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900"><?php echo $branch->branch_name; ?></div>
                    <div class="text-sm text-gray-500"><?php echo $branch->address; ?></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $branch->location; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($member_count); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="#" class="text-primary hover:text-blue-900">View</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex justify-center md:justify-start">
          <p class="text-center text-sm text-gray-500">
            &copy; 2023 Church Management System. All rights reserved.
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex justify-center md:justify-end space-x-6">
          <a href="#" class="text-gray-400 hover:text-gray-500">
            <span class="sr-only">Privacy Policy</span>
            Privacy
          </a>
          <a href="#" class="text-gray-400 hover:text-gray-500">
            <span class="sr-only">Terms</span>
            Terms
          </a>
          <a href="#" class="text-gray-400 hover:text-gray-500">
            <span class="sr-only">Support</span>
            Support
          </a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Mobile menu toggle
    document.querySelector('.mobile-menu-button').addEventListener('click', function() {
      document.querySelector('.mobile-menu').classList.toggle('hidden');
    });

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