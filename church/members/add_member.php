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
    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $age=$_POST['age'];
    $sex=$_POST['sex'];
    $occupation=$_POST['occupation'];
    $status=$_POST['status'];
    $country=$_POST['country'];
    $parish=$_POST['parish'];
    $village=$_POST['village'];
    $district=$_POST['district'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $marital=$_POST['marital'];
    $birthdate=$_POST['birthdate'];
    
    // Set branch based on user permissions
    if($is_super_admin && isset($_POST['branch'])) {
        $branch_id = $_POST['branch'];
    } else {
        $branch_id = $user_branch_id;
    }
    
    // Generate member code
    $code = "C" . rand(1000, 9999);
    
    // Handle photo upload
    $photo = "avatar15.jpg"; // Default photo
    if(isset($_FILES["photo"]["name"]) && $_FILES["photo"]["name"] != '') {
        $target_dir = "../profileimages/";
        $file_name = basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Check if image file is actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $file_name;
            }
        }
    }
    
    // Insert member data
    $sql="INSERT INTO tblchristian(Name, lastname, Code, Age, Sex, Occupation, Status, Country, Parish, Village, District, Email, Phone, Photo, Marital, Registeredby, Birthdate, branch_id) VALUES(:fname, :lname, :code, :age, :sex, :occupation, :status, :country, :parish, :village, :district, :email, :phone, :photo, :marital, :registeredby, :birthdate, :branch_id)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':lname',$lname,PDO::PARAM_STR);
    $query->bindParam(':code',$code,PDO::PARAM_STR);
    $query->bindParam(':age',$age,PDO::PARAM_STR);
    $query->bindParam(':sex',$sex,PDO::PARAM_STR);
    $query->bindParam(':occupation',$occupation,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':country',$country,PDO::PARAM_STR);
    $query->bindParam(':parish',$parish,PDO::PARAM_STR);
    $query->bindParam(':village',$village,PDO::PARAM_STR);
    $query->bindParam(':district',$district,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':phone',$phone,PDO::PARAM_STR);
    $query->bindParam(':photo',$photo,PDO::PARAM_STR);
    $query->bindParam(':marital',$marital,PDO::PARAM_STR);
    $query->bindParam(':registeredby',$aid,PDO::PARAM_STR);
    $query->bindParam(':birthdate',$birthdate,PDO::PARAM_STR);
    $query->bindParam(':branch_id',$branch_id,PDO::PARAM_STR);
    
    if($query->execute()) {
        echo '<script>alert("Member has been added successfully")</script>';
        echo "<script>window.location.href ='member_list.php'</script>";
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
    <title>Add Member - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Add New Member</h1>
                    <a href="member_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Members
                    </a>
                </div>
                
                <form method="post" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="fname" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" name="lname" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Age</label>
                                    <input type="number" name="age" min="1" max="120" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select name="sex" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                                    <input type="date" name="birthdate" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Marital Status</label>
                                    <select name="marital" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Occupation</label>
                                    <input type="text" name="occupation"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Member Status</label>
                                    <select name="status" required
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        <option value="">Select Status</option>
                                        <option value="Baptised">Baptised</option>
                                        <option value="Not-Baptised">Not-Baptised</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" name="phone"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Country</label>
                                    <input type="text" name="country"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="district"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Parish</label>
                                    <input type="text" name="parish"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Village</label>
                                    <input type="text" name="village"
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
                                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                                    <input type="file" name="photo" accept="image/*"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>