<?php
include('includes/dbconnection.php');

echo "<h2>Add Test Member</h2>";

try {
    // Check if branches exist
    $stmt = $dbh->query("SELECT id FROM branches LIMIT 1");
    $branch_id = null;
    if ($stmt->rowCount() > 0) {
        $branch_row = $stmt->fetch(PDO::FETCH_ASSOC);
        $branch_id = $branch_row['id'];
    }
    
    // Insert test member
    $sql = "INSERT INTO tblchristian(Name, lastname, Code, Age, Sex, Occupation, Status, Country, Parish, Village, District, Email, Phone, Photo, Marital, Registeredby, Birthdate, branch_id, CreationDate) 
            VALUES(:fname, :lname, :code, :age, :sex, :occupation, :status, :country, :parish, :village, :district, :email, :phone, :photo, :marital, :registeredby, :birthdate, :branch_id, NOW())";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query->bindParam(':code', $code, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_STR);
    $query->bindParam(':sex', $sex, PDO::PARAM_STR);
    $query->bindParam(':occupation', $occupation, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':parish', $parish, PDO::PARAM_STR);
    $query->bindParam(':village', $village, PDO::PARAM_STR);
    $query->bindParam(':district', $district, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
    $query->bindParam(':photo', $photo, PDO::PARAM_STR);
    $query->bindParam(':marital', $marital, PDO::PARAM_STR);
    $query->bindParam(':registeredby', $registeredby, PDO::PARAM_STR);
    $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    $query->bindParam(':branch_id', $branch_id, PDO::PARAM_STR);
    
    // Set values
    $fname = "Test";
    $lname = "Member";
    $code = "C" . rand(1000, 9999);
    $age = "30";
    $sex = "Male";
    $occupation = "Engineer";
    $status = "Baptised";
    $country = "USA";
    $parish = "Central Parish";
    $village = "Main Village";
    $district = "Central District";
    $email = "test@example.com";
    $phone = "1234567890";
    $photo = "avatar15.jpg";
    $marital = "Married";
    $registeredby = "1";
    $birthdate = "1993-01-01";
    
    if($query->execute()) {
        echo "<p style='color: green;'>✓ Test member added successfully</p>";
        
        // Check if member was added
        $stmt = $dbh->query("SELECT * FROM tblchristian WHERE Email='test@example.com'");
        if ($stmt->rowCount() > 0) {
            echo "<p>Member found in database:</p>";
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($member);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>✗ Member not found in database after insertion</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Failed to add test member</p>";
        print_r($query->errorInfo());
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='members/member_list.php'>→ Member List Page</a></p>";
echo "<p><a href='debug_members.php'>← Back to Member Debug</a></p>";
?>