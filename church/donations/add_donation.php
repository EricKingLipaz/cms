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
    $donor_name=$_POST['donor_name'];
    $donor_email=$_POST['donor_email'];
    $donor_phone=$_POST['donor_phone'];
    $donor_member_id=$_POST['donor_member_id'] ?? null;
    $amount=$_POST['amount'];
    $currency=$_POST['currency'];
    $donation_type=$_POST['donation_type'];
    $payment_method=$_POST['payment_method'];
    $donation_date=$_POST['donation_date'];
    $notes=$_POST['notes'];
    
    // Set branch based on user permissions
    if($is_super_admin && isset($_POST['branch'])) {
        $branch_id = $_POST['branch'];
    } else {
        $branch_id = $user_branch_id;
    }
    
    // Generate receipt number
    $receipt_number = "R" . date('Ymd') . rand(1000, 9999);
    
    // Insert donation data
    $sql="INSERT INTO donations(donor_name, donor_email, donor_phone, donor_member_id, branch_id, amount, currency, donation_type, payment_method, donation_date, receipt_number, notes, created_by) VALUES(:donor_name, :donor_email, :donor_phone, :donor_member_id, :branch_id, :amount, :currency, :donation_type, :payment_method, :donation_date, :receipt_number, :notes, :created_by)";
    
    $query=$dbh->prepare($sql);
    $query->bindParam(':donor_name',$donor_name,PDO::PARAM_STR);
    $query->bindParam(':donor_email',$donor_email,PDO::PARAM_STR);
    $query->bindParam(':donor_phone',$donor_phone,PDO::PARAM_STR);
    $query->bindParam(':donor_member_id',$donor_member_id,PDO::PARAM_STR);
    $query->bindParam(':branch_id',$branch_id,PDO::PARAM_STR);
    $query->bindParam(':amount',$amount,PDO::PARAM_STR);
    $query->bindParam(':currency',$currency,PDO::PARAM_STR);
    $query->bindParam(':donation_type',$donation_type,PDO::PARAM_STR);
    $query->bindParam(':payment_method',$payment_method,PDO::PARAM_STR);
    $query->bindParam(':donation_date',$donation_date,PDO::PARAM_STR);
    $query->bindParam(':receipt_number',$receipt_number,PDO::PARAM_STR);
    $query->bindParam(':notes',$notes,PDO::PARAM_STR);
    $query->bindParam(':created_by',$aid,PDO::PARAM_STR);
    
    if($query->execute()) {
        echo '<script>alert("Donation has been added successfully")</script>';
        echo "<script>window.location.href ='donation_list.php'</script>";
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
    <title>Add Donation - Church Management System</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Add New Donation</h1>
                    <a href="donation_list.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Donations
                    </a>
                </div>
                
                <form method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Donor Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Donor Information</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Donor Type</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center">
                                            <input id="new_donor" name="donor_type" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" value="new" checked>
                                            <label for="new_donor" class="ml-3 block text-sm font-medium text-gray-700">New Donor</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="member_donor" name="donor_type" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300" value="member">
                                            <label for="member_donor" class="ml-3 block text-sm font-medium text-gray-700">Church Member</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="new_donor_fields">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Donor Name</label>
                                        <input type="text" name="donor_name"
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" name="donor_email"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                                            <input type="tel" name="donor_phone"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="member_donor_fields" class="hidden">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Select Church Member</label>
                                        <select name="donor_member_id"
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
                        </div>
                        
                        <!-- Donation Details -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Donation Details</h2>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" name="amount" step="0.01" min="0.01" required
                                                class="focus:ring-primary focus:border-primary block w-full pl-7 pr-12 py-2 border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Currency</label>
                                        <select name="currency" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                            <option value="USD">USD (US Dollar)</option>
                                            <option value="EUR">EUR (Euro)</option>
                                            <option value="GBP">GBP (British Pound)</option>
                                            <option value="KES">KES (Kenyan Shilling)</option>
                                            <option value="UGX">UGX (Ugandan Shilling)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Donation Type</label>
                                        <select name="donation_type" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                            <option value="">Select Type</option>
                                            <option value="tithe">Tithe</option>
                                            <option value="offering">Offering</option>
                                            <option value="donation">Donation</option>
                                            <option value="pledge">Pledge</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                        <select name="payment_method" required
                                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2">
                                            <option value="">Select Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="check">Check</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="mobile_money">Mobile Money</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Donation Date</label>
                                    <input type="date" name="donation_date" value="<?php echo date('Y-m-d'); ?>" required
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
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" rows="2"
                                        class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2"></textarea>
                                </div>
                                
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h3 class="text-md font-medium text-green-800 mb-2">Receipt Information</h3>
                                    <p class="text-sm text-green-700">
                                        A receipt will be automatically generated with a unique number for this donation.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" name="submit"
                            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newDonorRadio = document.getElementById('new_donor');
            const memberDonorRadio = document.getElementById('member_donor');
            const newDonorFields = document.getElementById('new_donor_fields');
            const memberDonorFields = document.getElementById('member_donor_fields');
            
            newDonorRadio.addEventListener('change', function() {
                if(this.checked) {
                    newDonorFields.classList.remove('hidden');
                    memberDonorFields.classList.add('hidden');
                }
            });
            
            memberDonorRadio.addEventListener('change', function() {
                if(this.checked) {
                    memberDonorFields.classList.remove('hidden');
                    newDonorFields.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>