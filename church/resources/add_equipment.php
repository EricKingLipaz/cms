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

// Handle form submission
if(isset($_POST['submit'])) {
    $item_name=$_POST['item_name'];
    $category=$_POST['category'];
    $description=$_POST['description'];
    $purchase_date=$_POST['purchase_date'];
    $purchase_cost=$_POST['purchase_cost'];
    $current_value=$_POST['current_value'];
    $location=$_POST['location'];
    $maintenance_schedule=$_POST['maintenance_schedule'];
    $assigned_to=$_POST['assigned_to'] ?? null;
    
    // Set branch based on user permissions
    if($is_super_admin && isset($_POST['branch'])) {
        $branch_id = $_POST['branch'];
    } else {
        $branch_id = $user_branch_id;
    }
    
    // Generate item code
    $item_code = "EQ" . rand(1000, 9999);
    
    // Insert equipment data
    $sql="INSERT INTO church_equipment(item_name, item_code, category, description, purchase_date, purchase_cost, current_value, branch_id, location, maintenance_schedule, assigned_to) VALUES(:item_name, :item_code, :category, :description, :purchase_date, :purchase_cost, :current_value, :branch_id, :location, :maintenance_schedule, :assigned_to)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':item_name',$item_name,PDO::PARAM_STR);
    $query->bindParam(':item_code',$item_code,PDO::PARAM_STR);
    $query->bindParam(':category',$category,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->bindParam(':purchase_date',$purchase_date,PDO::PARAM_STR);
    $query->bindParam(':purchase_cost',$purchase_cost,PDO::PARAM_STR);
    $query->bindParam(':current_value',$current_value,PDO::PARAM_STR);
    $query->bindParam(':branch_id',$branch_id,PDO::PARAM_STR);
    $query->bindParam(':location',$location,PDO::PARAM_STR);
    $query->bindParam(':maintenance_schedule',$maintenance_schedule,PDO::PARAM_STR);
    $query->bindParam(':assigned_to',$assigned_to,PDO::PARAM_STR);
    
    if($query->execute()) {
        echo '<script>alert("Equipment has been added successfully")</script>';
        echo "<script>window.location.href ='equipment_list.php'</script>";
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
    <title>Add Equipment - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Add New Equipment</h1>
                    <a href="equipment_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Equipment
                    </a>
                </div>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Equipment Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Equipment Information</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Item Name</label>
                                    <input type="text" name="item_name" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select name="category" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Category</option>
                                        <option value="Musical Instrument">Musical Instrument</option>
                                        <option value="Audio Equipment">Audio Equipment</option>
                                        <option value="AV Equipment">AV Equipment</option>
                                        <option value="Furniture">Furniture</option>
                                        <option value="Vehicle">Vehicle</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" rows="3"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" name="location"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <?php if($is_super_admin) { ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Branch</label>
                                    <select name="branch" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Branch</option>
                                        <?php
                                        $sql = "SELECT * FROM branches WHERE status='active' ORDER BY branch_name";
                                        $query = $dbh -> prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        if($query->rowCount() > 0) {
                                            foreach($results as $row) {
                                                echo '<option value="'.$row->id.'" '.($row->id == $user_branch_id ? 'selected' : '').'>'.$row->branch_name.'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php } ?>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Assign to Member (Optional)</label>
                                    <select name="assigned_to"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Not Assigned</option>
                                        <?php
                                        $sql = "SELECT ID, Name, lastname FROM tblchristian";
                                        if(!$is_super_admin) {
                                            $sql .= " WHERE branch_id = :branch_id";
                                        }
                                        $sql .= " ORDER BY Name, lastname";
                                        
                                        $query = $dbh -> prepare($sql);
                                        if(!$is_super_admin) {
                                            $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                                        }
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        if($query->rowCount() > 0) {
                                            foreach($results as $row) {
                                                echo '<option value="'.$row->ID.'">'.$row->Name.' '.$row->lastname.'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial & Maintenance Details -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Financial & Maintenance</h2>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Purchase Date</label>
                                        <input type="date" name="purchase_date"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Purchase Cost ($)</label>
                                        <input type="number" name="purchase_cost" step="0.01" min="0"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Value ($)</label>
                                    <input type="number" name="current_value" step="0.01" min="0"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Maintenance Schedule</label>
                                    <textarea name="maintenance_schedule" rows="2"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                                </div>
                                
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-blue-800 mb-2">Equipment Code</h3>
                                    <p class="text-sm text-blue-700">
                                        A unique equipment code will be automatically generated when you save this item.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Equipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>