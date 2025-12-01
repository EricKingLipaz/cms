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

// Handle branch deletion
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    
    // Prevent deletion of headquarters
    $sql_check="SELECT is_headquarters FROM branches WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && $result_check->is_headquarters == 1) {
        echo "<script>alert('Cannot delete headquarters branch');</script>";
    } else {
        $sql="DELETE FROM branches WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Branch deleted successfully');</script>";
    }
    echo "<script>window.location.href = 'branch_list.php'</script>";
}

// Handle branch status change
if(isset($_GET['statusid']) && isset($_GET['status'])) {
    $rid=intval($_GET['statusid']);
    $status=$_GET['status'];
    
    // Prevent changing status of headquarters
    $sql_check="SELECT is_headquarters FROM branches WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && $result_check->is_headquarters == 1) {
        echo "<script>alert('Cannot change status of headquarters branch');</script>";
    } else {
        $sql="UPDATE branches SET status=:status WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Branch status updated successfully');</script>";
    }
    echo "<script>window.location.href = 'branch_list.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Management - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <h1 class="text-2xl font-bold text-gray-800">Branch Management</h1>
                    <a href="add_branch.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Branch
                    </a>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="statusFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="locationFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Locations</option>
                                <?php
                                $sql = "SELECT DISTINCT location FROM branches ORDER BY location";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        echo '<option value="'.$row->location.'">'.$row->location.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="searchInput" class="focus:ring-primary focus:border-primary block w-full pr-10 py-2 border-gray-300 rounded-md" placeholder="Search branches...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Branch Summary -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-blue-800">Total Branches</div>
                        <div class="text-2xl font-bold text-blue-900">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM branches";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo number_format($result->total ?? 0);
                            ?>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-green-800">Active Branches</div>
                        <div class="text-2xl font-bold text-green-900">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM branches WHERE status = 'active'";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo number_format($result->total ?? 0);
                            ?>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-yellow-800">Headquarters</div>
                        <div class="text-2xl font-bold text-yellow-900">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM branches WHERE is_headquarters = 1";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo number_format($result->total ?? 0);
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Branches Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Established</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="branchesTable">
                            <?php
                            // Build SQL query
                            $sql = "SELECT * FROM branches ORDER BY is_headquarters DESC, branch_name";
                            
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $row) {
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo $row->branch_name;?></div>
                                            <div class="text-sm text-gray-500"><?php echo $row->branch_code;?></div>
                                            <?php if($row->is_headquarters == 1) { ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Headquarters
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->location;?>
                                            <div class="text-sm text-gray-500"><?php echo $row->address;?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->phone;?>
                                            <div class="text-sm text-gray-500"><?php echo $row->email;?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y', strtotime($row->established_date));?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($row->status=="active") { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            <?php } else { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="view_branch.php?viewid=<?php echo $row->id;?>" class="text-primary hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit_branch.php?editid=<?php echo $row->id;?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <?php if($row->status=="active" && $row->is_headquarters != 1) { ?>
                                                <a href="branch_list.php?statusid=<?php echo $row->id;?>&status=inactive" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    <i class="fas fa-times-circle"></i> Deactivate
                                                </a>
                                            <?php } else if($row->status=="inactive" && $row->is_headquarters != 1) { ?>
                                                <a href="branch_list.php?statusid=<?php echo $row->id;?>&status=active" class="text-green-600 hover:text-green-900 mr-3">
                                                    <i class="fas fa-check-circle"></i> Activate
                                                </a>
                                            <?php } ?>
                                            <?php if($row->is_headquarters != 1) { ?>
                                                <a href="branch_list.php?delid=<?php echo $row->id;?>" onclick="return confirm('Do you really want to delete this branch? This will remove all associated data.');" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php 
                                    $cnt++;
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No branches found
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Filter functionality
            $('#statusFilter, #locationFilter, #searchInput').on('change keyup', function() {
                var status = $('#statusFilter').val();
                var location = $('#locationFilter').val();
                var search = $('#searchInput').val().toLowerCase();
                
                $('#branchesTable tr').each(function() {
                    var row = $(this);
                    var statusText = row.find('td:eq(4) span').text().toLowerCase();
                    var locationText = row.find('td:eq(1)').text().toLowerCase();
                    var searchText = row.text().toLowerCase();
                    
                    var show = true;
                    
                    if(status && statusText.indexOf(status) === -1) show = false;
                    if(location && locationText.indexOf(location) === -1) show = false;
                    if(search && searchText.indexOf(search) === -1) show = false;
                    
                    if(show) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>
</body>
</html>