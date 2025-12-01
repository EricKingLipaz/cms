<?php
session_start();
error_reporting(0);
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
    $username = $row->UserName;
    $email = $row->Email;
    $admin_role = $row->AdminName;
    $mobile = $row->MobileNumber;
    $photo = $row->Photo;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Church Management System</title>
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
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">User Profile</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Picture -->
        <div class="md:col-span-1">
          <div class="bg-gray-50 rounded-lg p-6 text-center">
            <div class="mx-auto w-32 h-32 rounded-full bg-primary flex items-center justify-center text-white text-4xl font-bold mb-4">
              <?php echo substr($admin_name, 0, 1); ?>
            </div>
            <h2 class="text-xl font-bold text-gray-800"><?php echo $admin_name; ?></h2>
            <p class="text-gray-600"><?php echo $admin_role; ?></p>
          </div>
        </div>
        
        <!-- Profile Details -->
        <div class="md:col-span-2">
          <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Profile Information</h3>
            
            <div class="space-y-4">
              <div class="flex">
                <div class="w-1/3 text-gray-600">Full Name</div>
                <div class="w-2/3 font-medium"><?php echo $admin_name; ?></div>
              </div>
              
              <div class="flex">
                <div class="w-1/3 text-gray-600">Username</div>
                <div class="w-2/3 font-medium"><?php echo $username; ?></div>
              </div>
              
              <div class="flex">
                <div class="w-1/3 text-gray-600">Email</div>
                <div class="w-2/3 font-medium"><?php echo $email; ?></div>
              </div>
              
              <div class="flex">
                <div class="w-1/3 text-gray-600">Mobile</div>
                <div class="w-2/3 font-medium"><?php echo $mobile; ?></div>
              </div>
              
              <div class="flex">
                <div class="w-1/3 text-gray-600">Role</div>
                <div class="w-2/3 font-medium">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    <?php echo $admin_role; ?>
                  </span>
                </div>
              </div>
            </div>
            
            <div class="mt-6">
              <a href="main_dashboard.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
              </a>
            </div>
          </div>
        </div>
      </div>
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