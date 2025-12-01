<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/checklogin.php');
//check_login(); // Skip login check for debugging

echo "<h2>Dashboard Debug</h2>";

// Initialize variables
$admin_name = "Debug User";
$is_super_admin = true;
$branch_name = "Main Branch";
$total_members = 100;
$total_donations = "5000.00";
$upcoming_events = 5;
$total_branches = 3;
$activities = array(
    array(
        'icon' => 'fa-user-plus',
        'icon_bg' => 'bg-blue-100',
        'icon_color' => 'text-blue-500',
        'title' => 'New member registered',
        'description' => 'John Doe joined the church',
        'time' => 'Today'
    ),
    array(
        'icon' => 'fa-donate',
        'icon_bg' => 'bg-green-100',
        'icon_color' => 'text-green-500',
        'title' => 'Donation received',
        'description' => '$100.00 donation from Jane Smith',
        'time' => 'Yesterday'
    )
);

echo "<p>Variables initialized successfully</p>";
echo "<ul>";
echo "<li>Admin Name: $admin_name</li>";
echo "<li>Is Super Admin: " . ($is_super_admin ? 'Yes' : 'No') . "</li>";
echo "<li>Branch Name: $branch_name</li>";
echo "<li>Total Members: $total_members</li>";
echo "<li>Total Donations: $total_donations</li>";
echo "<li>Upcoming Events: $upcoming_events</li>";
echo "<li>Total Branches: $total_branches</li>";
echo "</ul>";

echo "<h3>Activities Array:</h3>";
echo "<pre>";
print_r($activities);
echo "</pre>";

echo "<h3>Rendering Modules Section:</h3>";

// Test rendering the modules section
ob_start();
?>
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
<?php
$modules_content = ob_get_clean();
echo "<p>Modules section rendered successfully</p>";

// Display the modules content
echo $modules_content;

echo "<p><a href='main_dashboard.php'>→ Main Dashboard</a></p>";
?>