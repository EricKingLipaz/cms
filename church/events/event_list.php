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

// Handle event deletion
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    
    // Only allow deletion if user has permission for this event's branch
    $sql_check="SELECT branch_id FROM church_events WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && ($is_super_admin || $user_branch_id == $result_check->branch_id)) {
        $sql="DELETE FROM church_events WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Event deleted successfully');</script>"; 
        echo "<script>window.location.href = 'event_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to delete this event');</script>";
    }
}

// Handle event status change
if(isset($_GET['statusid']) && isset($_GET['status'])) {
    $rid=intval($_GET['statusid']);
    $status=$_GET['status'];
    
    // Only allow status change if user has permission for this event's branch
    $sql_check="SELECT branch_id FROM church_events WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && ($is_super_admin || $user_branch_id == $result_check->branch_id)) {
        $sql="UPDATE church_events SET status=:status WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Event status updated successfully');</script>"; 
        echo "<script>window.location.href = 'event_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to update this event');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Event Management</h1>
                    <a href="add_event.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Event
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
                                <option value="planned">Planned</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Type</label>
                            <select id="typeFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Types</option>
                                <option value="Regular Service">Regular Service</option>
                                <option value="Special Service">Special Service</option>
                                <option value="Camp">Camp</option>
                                <option value="Study Group">Study Group</option>
                                <option value="Conference">Conference</option>
                                <option value="Outreach">Outreach</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="searchInput" class="focus:ring-primary focus:border-primary block w-full pr-10 py-2 border-gray-300 rounded-md" placeholder="Search events...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Events Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="eventsTable">
                            <?php
                            // Build SQL query based on user permissions
                            $sql = "SELECT e.*, b.branch_name FROM church_events e LEFT JOIN branches b ON e.branch_id = b.id";
                            
                            if(!$is_super_admin) {
                                $sql .= " WHERE e.branch_id = :branch_id";
                            }
                            
                            $sql .= " ORDER BY e.start_date DESC";
                            
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
                                            <div class="text-sm font-medium text-gray-900"><?php echo $row->event_name;?></div>
                                            <div class="text-sm text-gray-500"><?php echo substr($row->event_description, 0, 50);?><?php if(strlen($row->event_description) > 50) echo '...';?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo date('M j, Y g:i A', strtotime($row->start_date));?></div>
                                            <?php if($row->end_date) { ?>
                                            <div class="text-sm text-gray-500">to <?php echo date('M j, Y g:i A', strtotime($row->end_date));?></div>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->branch_name;?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row->event_type;?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                            $status_class = '';
                                            switch($row->status) {
                                                case 'planned': $status_class = 'bg-blue-100 text-blue-800'; break;
                                                case 'ongoing': $status_class = 'bg-yellow-100 text-yellow-800'; break;
                                                case 'completed': $status_class = 'bg-green-100 text-green-800'; break;
                                                case 'cancelled': $status_class = 'bg-red-100 text-red-800'; break;
                                                default: $status_class = 'bg-gray-100 text-gray-800';
                                            }
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                                <?php echo ucfirst($row->status);?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="view_event.php?viewid=<?php echo $row->id;?>" class="text-primary hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit_event.php?editid=<?php echo $row->id;?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <?php if($row->status != "cancelled") { ?>
                                                <a href="event_list.php?statusid=<?php echo $row->id;?>&status=cancelled" class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-times-circle"></i> Cancel
                                                </a>
                                            <?php } ?>
                                            <a href="event_list.php?delid=<?php echo $row->id;?>" onclick="return confirm('Do you really want to delete this event?');" class="text-red-600 hover:text-red-900">
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
                                        No events found
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
            $('#branchFilter, #statusFilter, #typeFilter, #searchInput').on('change keyup', function() {
                var branch = $('#branchFilter').val();
                var status = $('#statusFilter').val();
                var type = $('#typeFilter').val();
                var search = $('#searchInput').val().toLowerCase();
                
                $('#eventsTable tr').each(function() {
                    var row = $(this);
                    var branchText = row.find('td:eq(2)').text().toLowerCase();
                    var statusText = row.find('td:eq(4) span').text().toLowerCase();
                    var typeText = row.find('td:eq(3)').text().toLowerCase();
                    var searchText = row.text().toLowerCase();
                    
                    var show = true;
                    
                    if(branch && branchText.indexOf(branch) === -1) show = false;
                    if(status && statusText.indexOf(status) === -1) show = false;
                    if(type && typeText.indexOf(type) === -1) show = false;
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