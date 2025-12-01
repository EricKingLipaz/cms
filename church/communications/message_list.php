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

// Handle message deletion
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    
    // Only allow deletion if user is the sender of this message
    $sql_check="SELECT sender_id FROM messages WHERE id=:rid";
    $query_check=$dbh->prepare($sql_check);
    $query_check->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query_check->execute();
    $result_check=$query_check->fetch(PDO::FETCH_OBJ);
    
    if($result_check && $result_check->sender_id == $aid) {
        // Delete message recipients first
        $sql_del_recipients="DELETE FROM message_recipients WHERE message_id=:rid";
        $query_del_recipients=$dbh->prepare($sql_del_recipients);
        $query_del_recipients->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query_del_recipients->execute();
        
        // Delete the message
        $sql="DELETE FROM messages WHERE id=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
        
        echo "<script>alert('Message deleted successfully');</script>"; 
        echo "<script>window.location.href = 'message_list.php'</script>";
    } else {
        echo "<script>alert('You do not have permission to delete this message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Communication Center</h1>
                    <a href="compose_message.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Compose Message
                    </a>
                </div>
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-8">
                        <a href="#" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Inbox
                        </a>
                        <a href="sent_messages.php" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Sent
                        </a>
                    </nav>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sender</label>
                            <select id="senderFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Senders</option>
                                <?php
                                $sql = "SELECT DISTINCT m.sender_id, a.FirstName, a.LastName FROM messages m JOIN tbladmin a ON m.sender_id = a.ID ORDER BY a.FirstName, a.LastName";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) {
                                        echo '<option value="'.$row->sender_id.'">'.$row->FirstName.' '.$row->LastName.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select id="priorityFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Priorities</option>
                                <option value="high">High</option>
                                <option value="normal">Normal</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Range</label>
                            <select id="dateFilter" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="searchInput" class="focus:ring-primary focus:border-primary block w-full pr-10 py-2 border-gray-300 rounded-md" placeholder="Search messages...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="messagesTable">
                            <?php
                            // Build SQL query to get messages for this user
                            $sql = "SELECT m.*, a.FirstName, a.LastName, mr.read_status FROM messages m 
                                    JOIN tbladmin a ON m.sender_id = a.ID 
                                    LEFT JOIN message_recipients mr ON m.id = mr.message_id AND mr.recipient_admin_id = :user_id
                                    WHERE m.recipient_type = 'all' 
                                    OR (m.recipient_type = 'branch' AND m.recipient_branch_id = :branch_id)
                                    OR (m.recipient_type = 'individual' AND m.recipient_member_id IN (
                                        SELECT ID FROM tblchristian WHERE branch_id = :branch_id2
                                    ))
                                    OR m.recipient_admin_id = :user_id2
                                    ORDER BY m.send_date DESC";
                            
                            $query = $dbh -> prepare($sql);
                            $query->bindParam(':user_id', $aid, PDO::PARAM_INT);
                            $query->bindParam(':branch_id', $user_branch_id, PDO::PARAM_INT);
                            $query->bindParam(':branch_id2', $user_branch_id, PDO::PARAM_INT);
                            $query->bindParam(':user_id2', $aid, PDO::PARAM_INT);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0) {
                                foreach($results as $row) {
                                    ?>
                                    <tr class="<?php echo $row->read_status == 0 ? 'bg-blue-50' : ''; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo $row->FirstName;?> <?php echo $row->LastName;?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo $row->subject;?></div>
                                            <div class="text-sm text-gray-500"><?php echo substr($row->message_body, 0, 50);?><?php if(strlen($row->message_body) > 50) echo '...';?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y g:i A', strtotime($row->send_date));?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                            $priority_class = '';
                                            switch($row->priority) {
                                                case 'high': $priority_class = 'bg-red-100 text-red-800'; break;
                                                case 'normal': $priority_class = 'bg-blue-100 text-blue-800'; break;
                                                case 'low': $priority_class = 'bg-green-100 text-green-800'; break;
                                                default: $priority_class = 'bg-gray-100 text-gray-800';
                                            }
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $priority_class; ?>">
                                                <?php echo ucfirst($row->priority);?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($row->read_status == 0) { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Unread
                                                </span>
                                            <?php } else { ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Read
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="view_message.php?viewid=<?php echo $row->id;?>" class="text-primary hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="message_list.php?delid=<?php echo $row->id;?>" onclick="return confirm('Do you really want to delete this message?');" class="text-red-600 hover:text-red-900">
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
                                        No messages found
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
            $('#senderFilter, #priorityFilter, #dateFilter, #searchInput').on('change keyup', function() {
                var sender = $('#senderFilter').val();
                var priority = $('#priorityFilter').val();
                var date = $('#dateFilter').val();
                var search = $('#searchInput').val().toLowerCase();
                
                $('#messagesTable tr').each(function() {
                    var row = $(this);
                    var senderText = row.find('td:eq(0)').text().toLowerCase();
                    var priorityText = row.find('td:eq(3) span').text().toLowerCase();
                    var searchText = row.text().toLowerCase();
                    
                    var show = true;
                    
                    if(sender && senderText.indexOf(sender) === -1) show = false;
                    if(priority && priorityText.indexOf(priority) === -1) show = false;
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