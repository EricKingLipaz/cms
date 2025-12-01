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
    $subject=$_POST['subject'];
    $message_body=$_POST['message_body'];
    $recipient_type=$_POST['recipient_type'];
    $priority=$_POST['priority'];
    
    $recipient_branch_id = null;
    $recipient_member_id = null;
    $recipient_admin_id = null;
    
    // Set recipient based on type
    switch($recipient_type) {
        case 'all':
            // No specific recipient needed
            break;
        case 'branch':
            if($is_super_admin && isset($_POST['branch_id'])) {
                $recipient_branch_id = $_POST['branch_id'];
            } else {
                $recipient_branch_id = $user_branch_id;
            }
            break;
        case 'individual':
            if(isset($_POST['member_id'])) {
                $recipient_member_id = $_POST['member_id'];
            }
            break;
    }
    
    // Insert message data
    $sql="INSERT INTO messages(sender_id, recipient_type, recipient_branch_id, recipient_member_id, recipient_admin_id, subject, message_body, priority) VALUES(:sender_id, :recipient_type, :recipient_branch_id, :recipient_member_id, :recipient_admin_id, :subject, :message_body, :priority)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':sender_id',$aid,PDO::PARAM_STR);
    $query->bindParam(':recipient_type',$recipient_type,PDO::PARAM_STR);
    $query->bindParam(':recipient_branch_id',$recipient_branch_id,PDO::PARAM_STR);
    $query->bindParam(':recipient_member_id',$recipient_member_id,PDO::PARAM_STR);
    $query->bindParam(':recipient_admin_id',$recipient_admin_id,PDO::PARAM_STR);
    $query->bindParam(':subject',$subject,PDO::PARAM_STR);
    $query->bindParam(':message_body',$message_body,PDO::PARAM_STR);
    $query->bindParam(':priority',$priority,PDO::PARAM_STR);
    
    if($query->execute()) {
        $message_id = $dbh->lastInsertId();
        
        // Insert message recipients based on recipient type
        switch($recipient_type) {
            case 'all':
                // Add all members and admins as recipients
                // For admins
                $sql_admins = "INSERT INTO message_recipients (message_id, recipient_admin_id) 
                              SELECT :message_id, ID FROM tbladmin";
                $query_admins = $dbh->prepare($sql_admins);
                $query_admins->bindParam(':message_id', $message_id, PDO::PARAM_STR);
                $query_admins->execute();
                
                // For members
                $sql_members = "INSERT INTO message_recipients (message_id, recipient_member_id) 
                               SELECT :message_id, ID FROM tblchristian";
                $query_members = $dbh->prepare($sql_members);
                $query_members->bindParam(':message_id', $message_id, PDO::PARAM_STR);
                $query_members->execute();
                break;
                
            case 'branch':
                // Add all members and admins in the selected branch as recipients
                // For admins in branch
                $sql_admins = "INSERT INTO message_recipients (message_id, recipient_admin_id) 
                              SELECT :message_id, ID FROM tbladmin WHERE branch_id = :branch_id";
                $query_admins = $dbh->prepare($sql_admins);
                $query_admins->bindParam(':message_id', $message_id, PDO::PARAM_STR);
                $query_admins->bindParam(':branch_id', $recipient_branch_id, PDO::PARAM_STR);
                $query_admins->execute();
                
                // For members in branch
                $sql_members = "INSERT INTO message_recipients (message_id, recipient_member_id) 
                               SELECT :message_id, ID FROM tblchristian WHERE branch_id = :branch_id";
                $query_members = $dbh->prepare($sql_members);
                $query_members->bindParam(':message_id', $message_id, PDO::PARAM_STR);
                $query_members->bindParam(':branch_id', $recipient_branch_id, PDO::PARAM_STR);
                $query_members->execute();
                break;
                
            case 'individual':
                // Add specific member as recipient
                if($recipient_member_id) {
                    $sql_recipient = "INSERT INTO message_recipients (message_id, recipient_member_id) 
                                     VALUES (:message_id, :recipient_member_id)";
                    $query_recipient = $dbh->prepare($sql_recipient);
                    $query_recipient->bindParam(':message_id', $message_id, PDO::PARAM_STR);
                    $query_recipient->bindParam(':recipient_member_id', $recipient_member_id, PDO::PARAM_STR);
                    $query_recipient->execute();
                }
                break;
        }
        
        echo '<script>alert("Message has been sent successfully")</script>';
        echo "<script>window.location.href ='message_list.php'</script>";
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
    <title>Compose Message - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Compose Message</h1>
                    <a href="message_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Messages
                    </a>
                </div>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Message Header -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Message Details</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                                    <input type="text" name="subject" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                                    <select name="priority" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="normal">Normal</option>
                                        <option value="high">High</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Recipient Type</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center">
                                            <input id="recipient_all" name="recipient_type" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" value="all" checked>
                                            <label for="recipient_all" class="ml-3 block text-sm font-medium text-gray-700">All Members & Admins</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="recipient_branch" name="recipient_type" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" value="branch">
                                            <label for="recipient_branch" class="ml-3 block text-sm font-medium text-gray-700">
                                                <?php echo $is_super_admin ? 'Specific Branch' : 'My Branch (' . $_SESSION['branch_name'] . ')'; ?>
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="recipient_individual" name="recipient_type" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" value="individual">
                                            <label for="recipient_individual" class="ml-3 block text-sm font-medium text-gray-700">Individual Member</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($is_super_admin) { ?>
                                <div id="branch_selection" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700">Select Branch</label>
                                    <select name="branch_id"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Branch</option>
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
                                <?php } ?>
                                
                                <div id="member_selection" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700">Select Member</label>
                                    <select name="member_id"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Member</option>
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
                        
                        <!-- Message Body -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Message Content</h2>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea name="message_body" rows="10" required
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recipientAllRadio = document.getElementById('recipient_all');
            const recipientBranchRadio = document.getElementById('recipient_branch');
            const recipientIndividualRadio = document.getElementById('recipient_individual');
            const branchSelection = document.getElementById('branch_selection');
            const memberSelection = document.getElementById('member_selection');
            
            recipientAllRadio.addEventListener('change', function() {
                if(this.checked) {
                    branchSelection.classList.add('hidden');
                    memberSelection.classList.add('hidden');
                }
            });
            
            recipientBranchRadio.addEventListener('change', function() {
                if(this.checked) {
                    branchSelection.classList.remove('hidden');
                    memberSelection.classList.add('hidden');
                }
            });
            
            recipientIndividualRadio.addEventListener('change', function() {
                if(this.checked) {
                    branchSelection.classList.add('hidden');
                    memberSelection.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>