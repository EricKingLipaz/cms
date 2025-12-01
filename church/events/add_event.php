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
    $event_name=$_POST['event_name'];
    $event_description=$_POST['event_description'];
    $event_type=$_POST['event_type'];
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $location=$_POST['location'];
    $max_attendees=$_POST['max_attendees'];
    $registration_required=isset($_POST['registration_required']) ? 1 : 0;
    
    // Set branch based on user permissions
    if($is_super_admin && isset($_POST['branch'])) {
        $branch_id = $_POST['branch'];
    } else {
        $branch_id = $user_branch_id;
    }
    
    // Insert event data
    $sql="INSERT INTO church_events(event_name, event_description, branch_id, event_type, start_date, end_date, location, organizer, max_attendees, registration_required) VALUES(:event_name, :event_description, :branch_id, :event_type, :start_date, :end_date, :location, :organizer, :max_attendees, :registration_required)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':event_name',$event_name,PDO::PARAM_STR);
    $query->bindParam(':event_description',$event_description,PDO::PARAM_STR);
    $query->bindParam(':branch_id',$branch_id,PDO::PARAM_STR);
    $query->bindParam(':event_type',$event_type,PDO::PARAM_STR);
    $query->bindParam(':start_date',$start_date,PDO::PARAM_STR);
    $query->bindParam(':end_date',$end_date,PDO::PARAM_STR);
    $query->bindParam(':location',$location,PDO::PARAM_STR);
    $query->bindParam(':organizer',$aid,PDO::PARAM_STR);
    $query->bindParam(':max_attendees',$max_attendees,PDO::PARAM_STR);
    $query->bindParam(':registration_required',$registration_required,PDO::PARAM_STR);
    
    if($query->execute()) {
        echo '<script>alert("Event has been added successfully")</script>';
        echo "<script>window.location.href ='event_list.php'</script>";
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
    <title>Add Event - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Add New Event</h1>
                    <a href="event_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Events
                    </a>
                </div>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Event Name</label>
                                    <input type="text" name="event_name" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Event Description</label>
                                    <textarea name="event_description" rows="3"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Event Type</label>
                                    <select name="event_type" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Event Type</option>
                                        <option value="Regular Service">Regular Service</option>
                                        <option value="Special Service">Special Service</option>
                                        <option value="Camp">Camp</option>
                                        <option value="Study Group">Study Group</option>
                                        <option value="Conference">Conference</option>
                                        <option value="Outreach">Outreach</option>
                                        <option value="Other">Other</option>
                                    </select>
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
                            </div>
                        </div>
                        
                        <!-- Event Schedule -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Event Schedule</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                                    <input type="datetime-local" name="start_date" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                    <input type="datetime-local" name="end_date"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Maximum Attendees</label>
                                    <input type="number" name="max_attendees" min="1"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="registration_required" name="registration_required" type="checkbox"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="registration_required" class="ml-2 block text-sm text-gray-700">
                                        Registration Required
                                    </label>
                                </div>
                                
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-blue-800 mb-2">Event Organizer</h3>
                                    <p class="text-sm text-blue-700">
                                        This event will be organized by <?php echo $_SESSION['names']; ?>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>