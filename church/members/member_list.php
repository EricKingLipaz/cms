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

// Handle member deletion
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    
    // Only allow deletion if user has permission for this member's branch
    $sql_check="SELECT branch_id FROM tblchristian WHERE ID=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && ($is_super_admin || $user_branch_id == $result_check->branch_id)) {
        $sql="DELETE FROM tblchristian WHERE ID=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Member deleted successfully');</script>"; 
        echo "<script>window.location.href = 'member_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to delete this member');</script>";
    }
}

// Handle member status change
if(isset($_GET['statusid']) && isset($_GET['status'])) {
    $rid=intval($_GET['statusid']);
    $status=$_GET['status'];
    
    // Only allow status change if user has permission for this member's branch
    $sql_check="SELECT branch_id FROM tblchristian WHERE ID=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && ($is_super_admin || $user_branch_id == $result_check->branch_id)) {
        $sql="UPDATE tblchristian SET Status=:status WHERE ID=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Member status updated successfully');</script>"; 
        echo "<script>window.location.href = 'member_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to update this member');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Member Management</h1>
                    <a href="add_member.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Member
                    </a>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Branch</label>
                            <select id="branchFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Branches</option>
                                <?php
                                $sql = "SELECT * FROM branches WHERE status='active' ORDER BY branch_name";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        echo '<option value="'.$row->id.'">'.$row->branch_name.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="statusFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="Baptised">Baptised</option>
                                <option value="Not-Baptised">Not-Baptised</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="genderFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Genders</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="searchInput" class="focus:ring-primary focus:border-primary block w-full pr-10 py-2 border-gray-300 rounded-md" placeholder="Search members...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Members Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="membersTable">
                            <?php
                            // Build SQL query based on user permissions
                            $sql = "SELECT c.*, b.branch_name FROM tblchristian c LEFT JOIN branches b ON c.branch_id = b.id";
                            
                            if(!$is_super_admin) {
                                $sql .= " WHERE c.branch_id = :branch_id";
                            }
                            
                            $sql .= " ORDER BY c.CreationDate DESC";
                            
                            $query = $dbh -> prepare($sql);
                            
                            if(!$is_super_admin) {
                                $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            }
                            
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $row) {
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <?php if($row->Photo=="avatar15.jpg") { ?>
                                                        <img class="h-10 w-10 rounded-full" src="../assets/img/avatars/avatar15.jpg" alt="">
                                                    <?php } else { ?>
                                                        <img class="h-10 w-10 rounded-full" src="../profileimages/<?php echo $row->Photo;?>" alt="">
                                                    <?php } ?>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo $row->Name;?> <?php echo $row->lastname;?></div>
                                                    <div class="text-sm text-gray-500"><?php echo $row->Sex;?>, Age: <?php echo $row->Age;?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo $row->Email;?></div>
                                            <div class="text-sm text-gray-500"><?php echo $row->Phone;?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->branch_name;?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($row->Status=="Baptised") { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Baptised
                                                </span>
                                            <?php } else { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Not-Baptised
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="view_member.php?viewid=<?php echo $row->ID;?>" class="text-primary hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit_member.php?editid=<?php echo $row->ID;?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <?php if($row->Status=="Baptised") { ?>
                                                <a href="member_list.php?statusid=<?php echo $row->ID;?>&status=Not-Baptised" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    <i class="fas fa-times-circle"></i> Mark Not-Baptised
                                                </a>
                                            <?php } else { ?>
                                                <a href="member_list.php?statusid=<?php echo $row->ID;?>&status=Baptised" class="text-green-600 hover:text-green-900 mr-3">
                                                    <i class="fas fa-check-circle"></i> Mark Baptised
                                                </a>
                                            <?php } ?>
                                            <a href="member_list.php?delid=<?php echo $row->ID;?>" onclick="return confirm('Do you really want to delete this member?');" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $cnt++;
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No members found
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
            $('#branchFilter, #statusFilter, #genderFilter, #searchInput').on('change keyup', function() {
                var branch = $('#branchFilter').val();
                var status = $('#statusFilter').val();
                var gender = $('#genderFilter').val();
                var search = $('#searchInput').val().toLowerCase();
                
                $('#membersTable tr').each(function() {
                    var row = $(this);
                    var branchText = row.find('td:eq(2)').text().toLowerCase();
                    var statusText = row.find('td:eq(3) span').text().toLowerCase();
                    var genderText = row.find('td:eq(0) div:eq(1)').text().toLowerCase();
                    var searchText = row.text().toLowerCase();
                    
                    var show = true;
                    
                    if(branch && branchText.indexOf(branch) === -1) show = false;
                    if(status && statusText.indexOf(status) === -1) show = false;
                    if(gender && genderText.indexOf(gender) === -1) show = false;
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