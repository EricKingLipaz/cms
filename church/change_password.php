<?php
session_start();
error_reporting(0);
include('includes/checklogin.php');
check_login();

include('includes/dbconnection.php');

if(isset($_POST['submit']))
{
    $aid=$_SESSION['odmsaid'];
    $currentpassword=md5($_POST['currentpassword']);
    $newpassword=md5($_POST['newpassword']);
    $sql ="SELECT ID FROM tbladmin WHERE ID=:aid and Password=:currentpassword";
    $query= $dbh -> prepare($sql);
    $query->bindParam(':aid',$aid,PDO::PARAM_STR);
    $query->bindParam(':currentpassword',$currentpassword,PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);

    if($query -> rowCount() > 0)
    {
        $con="update tbladmin set Password=:newpassword where ID=:aid";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1-> bindParam(':aid',$aid, PDO::PARAM_STR);
        $chngpwd1-> bindParam(':newpassword',$newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();

        echo '<script>alert("Your password has been changed successfully")</script>';
    } else {
        echo '<script>alert("Your current password is wrong")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Church Management System</title>
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
                                <?php 
                                $aid=$_SESSION['odmsaid'];
                                $sql="SELECT FirstName, LastName from tbladmin where ID=:aid";
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':aid',$aid,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        echo substr($row->FirstName . ' ' . $row->LastName, 0, 1);
                                    }
                                }
                                ?>
                            </div>
                            <span>
                                <?php 
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        echo $row->FirstName . ' ' . $row->LastName;
                                    }
                                }
                                ?>
                            </span>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h1>
            
            <div class="max-w-md">
                <form method="post" class="space-y-6">
                    <div>
                        <label for="currentpassword" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="currentpassword" id="currentpassword" required class="focus:ring-primary focus:border-primary block w-full pl-10 py-3 border-gray-300 rounded-md">
                        </div>
                    </div>
                    
                    <div>
                        <label for="newpassword" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="newpassword" id="newpassword" required class="focus:ring-primary focus:border-primary block w-full pl-10 py-3 border-gray-300 rounded-md">
                        </div>
                    </div>
                    
                    <div>
                        <label for="confirmpassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="confirmpassword" id="confirmpassword" required class="focus:ring-primary focus:border-primary block w-full pl-10 py-3 border-gray-300 rounded-md">
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <a href="main_dashboard.php" class="text-primary hover:text-blue-700">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                        <button name="submit" type="submit" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Change Password
                        </button>
                    </div>
                </form>
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
        
        // Password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('confirmpassword').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirm password do not match!');
                return false;
            }
        });
    </script>
</body>
</html>