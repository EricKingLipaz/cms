<?php
session_start();
error_reporting(0);
include('../includes/checklogin.php');
check_login();

// Get user information
$aid=$_SESSION['odmsaid'];
$is_super_admin = is_super_admin();

// Database connection
include('../includes/dbconnection.php');

// Only super admins can access this page
if(!$is_super_admin) {
    echo "<script>alert('You do not have permission to access this page');</script>";
    echo "<script>window.location.href = '../main_dashboard.php'</script>";
    exit;
}

// Handle form submission
if(isset($_POST['submit'])) {
    $branch_name=$_POST['branch_name'];
    $location=$_POST['location'];
    $address=$_POST['address'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $pastor_name=$_POST['pastor_name'];
    $established_date=$_POST['established_date'];
    $is_headquarters=isset($_POST['is_headquarters']) ? 1 : 0;
    
    // Generate branch code
    $branch_code = strtoupper(substr($branch_name, 0, 2)) . rand(100, 999);
    
    // If this is set as headquarters, unset any existing headquarters
    if($is_headquarters == 1) {
        $sql_unset = "UPDATE branches SET is_headquarters = 0";
        $query_unset = $dbh->prepare($sql_unset);
        $query_unset->execute();
    }
    
    // Insert branch data
    $sql="INSERT INTO branches(branch_name, branch_code, location, address, phone, email, pastor_name, established_date, is_headquarters) VALUES(:branch_name, :branch_code, :location, :address, :phone, :email, :pastor_name, :established_date, :is_headquarters)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':branch_name',$branch_name,PDO::PARAM_STR);
    $query->bindParam(':branch_code',$branch_code,PDO::PARAM_STR);
    $query->bindParam(':location',$location,PDO::PARAM_STR);
    $query->bindParam(':address',$address,PDO::PARAM_STR);
    $query->bindParam(':phone',$phone,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':pastor_name',$pastor_name,PDO::PARAM_STR);
    $query->bindParam(':established_date',$established_date,PDO::PARAM_STR);
    $query->bindParam(':is_headquarters',$is_headquarters,PDO::PARAM_STR);
    
    if($query->execute()) {
        echo '<script>alert("Branch has been added successfully")</script>';
        echo "<script>window.location.href ='branch_list.php'</script>";
    } else {
        echo '<script>alert("Something went wrong. Please try again")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Branch - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <h1 class="text-2xl font-bold text-gray-800">Add New Branch</h1>
                    <a href="branch_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Branches
                    </a>
                </div>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Branch Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Branch Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Branch Name</label>
                                        <input type="text" name="branch_name" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Location</label>
                                        <input type="text" name="location" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Address</label>
                                        <textarea name="address" rows="2" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Established Date</label>
                                        <input type="date" name="established_date" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pastor Name</label>
                                        <input type="text" name="pastor_name"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" name="phone"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" name="email"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="is_headquarters" name="is_headquarters" type="checkbox"
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label for="is_headquarters" class="ml-2 block text-sm text-gray-700">
                                            Set as Headquarters
                                        </label>
                                    </div>
                                    
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <h3 class="text-md font-medium text-blue-800 mb-2">Branch Code</h3>
                                        <p class="text-sm text-blue-700">
                                            A unique branch code will be automatically generated when you save this branch.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Branch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>