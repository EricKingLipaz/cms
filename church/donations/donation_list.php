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

// Handle donation deletion
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    
    // Only allow deletion if user has permission for this donation's branch
    $sql_check="SELECT branch_id FROM donations WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && ($is_super_admin || $user_branch_id == $result_check->branch_id)) {
        $sql="DELETE FROM donations WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Donation deleted successfully');</script>"; 
        echo "<script>window.location.href = 'donation_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to delete this donation');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Management - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Donation Management</h1>
                    <a href="add_donation.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Donation
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
                            <label class="block text-sm font-medium text-gray-700">Donation Type</label>
                            <select id="typeFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Types</option>
                                <option value="tithe">Tithe</option>
                                <option value="offering">Offering</option>
                                <option value="donation">Donation</option>
                                <option value="pledge">Pledge</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Range</label>
                            <select id="dateFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="searchInput" class="focus:ring-primary focus:border-primary block w-full pr-10 py-2 border-gray-300 rounded-md" placeholder="Search donations...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Donations Summary -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-blue-800">Today's Donations</div>
                        <div class="text-2xl font-bold text-blue-900">
                            <?php
                            $sql = "SELECT SUM(amount) as total FROM donations WHERE DATE(donation_date) = CURDATE()";
                            if(!$is_super_admin) {
                                $sql .= " AND branch_id = :branch_id";
                            }
                            $query = $dbh -> prepare($sql);
                            if(!$is_super_admin) {
                                $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            }
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo '$' . number_format($result->total ?? 0, 2);
                            ?>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-green-800">This Month</div>
                        <div class="text-2xl font-bold text-green-900">
                            <?php
                            $sql = "SELECT SUM(amount) as total FROM donations WHERE MONTH(donation_date) = MONTH(CURDATE()) AND YEAR(donation_date) = YEAR(CURDATE())";
                            if(!$is_super_admin) {
                                $sql .= " AND branch_id = :branch_id";
                            }
                            $query = $dbh -> prepare($sql);
                            if(!$is_super_admin) {
                                $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            }
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo '$' . number_format($result->total ?? 0, 2);
                            ?>
                        </div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-purple-800">This Year</div>
                        <div class="text-2xl font-bold text-purple-900">
                            <?php
                            $sql = "SELECT SUM(amount) as total FROM donations WHERE YEAR(donation_date) = YEAR(CURDATE())";
                            if(!$is_super_admin) {
                                $sql .= " AND branch_id = :branch_id";
                            }
                            $query = $dbh -> prepare($sql);
                            if(!$is_super_admin) {
                                $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            }
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo '$' . number_format($result->total ?? 0, 2);
                            ?>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-yellow-800">Total Donations</div>
                        <div class="text-2xl font-bold text-yellow-900">
                            <?php
                            $sql = "SELECT SUM(amount) as total FROM donations";
                            if(!$is_super_admin) {
                                $sql .= " WHERE branch_id = :branch_id";
                            }
                            $query = $dbh -> prepare($sql);
                            if(!$is_super_admin) {
                                $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            }
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            echo '$' . number_format($result->total ?? 0, 2);
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Donations Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="donationsTable">
                            <?php
                            // Build SQL query based on user permissions
                            $sql = "SELECT d.*, b.branch_name, c.Name as member_name, c.lastname as member_lastname FROM donations d LEFT JOIN branches b ON d.branch_id = b.id LEFT JOIN tblchristian c ON d.donor_member_id = c.ID";
                            
                            if(!$is_super_admin) {
                                $sql .= " WHERE d.branch_id = :branch_id";
                            }
                            
                            $sql .= " ORDER BY d.donation_date DESC, d.created_at DESC";
                            
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
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php 
                                                if($row->donor_member_id) {
                                                    echo $row->member_name . ' ' . $row->member_lastname;
                                                } else {
                                                    echo $row->donor_name;
                                                }
                                                ?>
                                            </div>
                                            <?php if($row->donor_email || $row->donor_phone) { ?>
                                            <div class="text-sm text-gray-500">
                                                <?php echo $row->donor_email ? $row->donor_email : $row->donor_phone; ?>
                                            </div>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">$<?php echo number_format($row->amount, 2);?></div>
                                            <div class="text-sm text-gray-500"><?php echo $row->currency;?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->branch_name;?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?php echo ucfirst($row->donation_type);?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y', strtotime($row->donation_date));?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="view_donation.php?viewid=<?php echo $row->id;?>" class="text-primary hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit_donation.php?editid=<?php echo $row->id;?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="donation_list.php?delid=<?php echo $row->id;?>" onclick="return confirm('Do you really want to delete this donation?');" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $cnt++;
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No donations found
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
            $('#branchFilter, #typeFilter, #dateFilter, #searchInput').on('change keyup', function() {
                var branch = $('#branchFilter').val();
                var type = $('#typeFilter').val();
                var date = $('#dateFilter').val();
                var search = $('#searchInput').val().toLowerCase();
                
                $('#donationsTable tr').each(function() {
                    var row = $(this);
                    var branchText = row.find('td:eq(2)').text().toLowerCase();
                    var typeText = row.find('td:eq(3) span').text().toLowerCase();
                    var dateText = row.find('td:eq(4)').text().toLowerCase();
                    var searchText = row.text().toLowerCase();
                    
                    var show = true;
                    
                    if(branch && branchText.indexOf(branch) === -1) show = false;
                    if(type && typeText.indexOf(type) === -1) show = false;
                    if(search && searchText.indexOf(search) === -1) show = false;
                    
                    // Date filtering would require more complex logic
                    // For simplicity, we'll just show/hide based on other filters
                    
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