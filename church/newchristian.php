<?php
include('includes/checklogin.php');
check_login();
if(!empty($_POST["code"])) {
  $bidname= $_POST["code"];
  
  $sql ="SELECT Code FROM tblchristian WHERE Code=:bidname";
  $query= $dbh -> prepare($sql);
  $query-> bindParam(':bidname', $bidname, PDO::PARAM_STR);
  $query-> execute();
  $results = $query -> fetchAll(PDO::FETCH_OBJ);
  $cnt=1;
  if($query -> rowCount() > 0)
  {
    echo "<script>alert('Code already registered.');</script>";
  } else{
    if(isset($_POST['submit']))
    {
      $aid=$_SESSION['odmsaid'];
      $sql="SELECT * from  tbladmin where ID=:aid";
      $query = $dbh -> prepare($sql);
      $query->bindParam(':aid',$aid,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
          $reg=$row->FirstName;
          $reg2=$row->LastName;
        }
      }
      $regname=$reg;
      $lastname=$reg2;
      $name=$_POST['name'];
      $code=$_POST['code'];
      $sex=$_POST['sex'];
      $age=$_POST['age'];
      $occupation=$_POST['occupation'];
      $status=$_POST['status'];
      $phone=$_POST['phone'];
      $birthdate=$_POST['birthdate'];
      $country=$_POST['country'];
      $district=$_POST['district'];
      $parish=$_POST['parish'];
      $village=$_POST['village'];
      $email=$_POST['email'];
      $marital=$_POST['marital'];
      $image=$_FILES["productimage1"]["name"];
      move_uploaded_file($_FILES["productimage1"]["tmp_name"],"christianimages/".$_FILES["productimage1"]["name"]);
      $sql="insert into tblchristian(Name,Code,Sex,Age,Occupation,Status,Birthdate,Country,District,Parish,Village,Email,Phone,Registeredby,lastname,Photo,Marital)values(:name,:code,:sex,:age,:occupation,:status,:birthdate,:country,:district,:parish,:village,:email,:phone,:regname,:lastname,:image,:marital)";
      $query=$dbh->prepare($sql);
      $query->bindParam(':name',$name,PDO::PARAM_STR);
      $query->bindParam(':code',$code,PDO::PARAM_STR);
      $query->bindParam(':sex',$sex,PDO::PARAM_STR);
      $query->bindParam(':age',$age,PDO::PARAM_STR);
      $query->bindParam(':occupation',$occupation,PDO::PARAM_STR);
      $query->bindParam(':status',$status,PDO::PARAM_STR);
      $query->bindParam(':birthdate',$birthdate,PDO::PARAM_STR);
      $query->bindParam(':phone',$phone,PDO::PARAM_STR);
      $query->bindParam(':country',$country,PDO::PARAM_STR);
      $query->bindParam(':district',$district,PDO::PARAM_STR);
      $query->bindParam(':parish',$parish,PDO::PARAM_STR);
      $query->bindParam(':village',$village,PDO::PARAM_STR);
      $query->bindParam(':email',$email,PDO::PARAM_STR);
      $query->bindParam(':marital',$marital,PDO::PARAM_STR);
      $query->bindParam(':regname',$regname,PDO::PARAM_STR);
      $query->bindParam(':lastname',$lastname,PDO::PARAM_STR);
      $query->bindParam(':image',$image,PDO::PARAM_STR);
      $query->execute();
      $LastInsertId=$dbh->lastInsertId();
      if ($LastInsertId>0) 
      {
        echo '<script>alert("Registered successfully")</script>';
        echo "<script>window.location.href ='newchristian.php'</script>";
      }
      else
      {
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
      }
    }
  }
}
?>
<script>
  function checkAvailability() 
  {
    $("#loaderIcon").show();
    jQuery.ajax(
    {
      url: "check_availability.php",
      data:'Code='+$("#code").val(),
      type: "POST",
      success:function(data)
      {
        $("#user-availability-status").html(data);
        $("#loaderIcon").hide();
      },
      error:function (){}
    });
  }
</script>

<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <?php @include("includes/header.php");?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <?php @include("includes/sidebar.php");?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">Christian  registration information</h5>
                </div>

                <div class="card-body">
                  <form class="form-sample"  method="post" enctype="multipart/form-data">

                    <div class="row">
                      <div class="form-group col-md-6">
                        <label class="col-sm-12 pl-0 pr-0">Christian Name</label>
                        <div class="col-sm-12 pl-0 pr-0">
                          <input type="text" name="name" id="name" placeholder="Enter Names" class="form-control" required />
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label class="col-sm-12 ">Unique code</label>
                        <div class="col-sm-12 ">
                          <input type="text" name="code" id="code" onBlur="checkAvailability()"    placeholder="Enter code" class="form-control" required />
                          <span id="user-availability-status" style="font-size:12px;"></span> 
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="form-group col-md-4">
                        <label class="col-sm-12 pl-0 pr-0">Sex</label>
                        <div class="col-sm-12 pl-0 pr-0">
                          <select name="sex" class="form-control" required>
                            <option value="">Select sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group col-md-4 pl-md-0">
                        <label class="col-sm-12 pl-0 pr-0">Age</label>
                        <div class="col-sm-12 pl-0 pr-0">
                         <input type="text" name="age" id="age"   placeholder="Enter age" class="form-control" required />
                       </div>
                     </div>                      

                     <div class="form-group col-md-4 pl-md-0">
                      <label class="col-sm-12 pl-0 pr-0">Occupation</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <select name="occupation" class="form-control" required>
                          <option value="">Select occupation</option>
                          <option value="Doctor">Doctor</option>
                          <option value="Lawyer">Lawyer</option>
                          <option value="Engineer">Engineer</option>
                          <option value="Bussiness man">Bussiness man</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">  
                    <div class="form-group col-md-4 pl-md-0">
                      <label class="col-sm-12 pl-0 pr-0 ">Attach Christian Photo</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <input type="file" name="productimage1" class="file-upload-default">
                        <div class="input-group ">
                          <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                          </span>
                        </div>
                      </div>
                    </div>    
                    <div class="form-group col-md-2 pl-md-0 ">
                      <label class="col-sm-12 pl-0 pr-0">Baptised status</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <select name="status" class="form-control" required>
                          <option value="">Select status</option>
                          <option value="Baptised">Baptised</option>
                          <option value="Not-Baptised">Not-Baptised</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-2 pl-md-0 ">
                      <label class="col-sm-12 pl-0 pr-0">Marital status</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <select name="marital" class="form-control" required>
                          <option value="">Select status</option>
                          <option value="Married">Married</option>
                          <option value="Single">Single</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group col-md-4 pl-md-0">
                      <label class="col-sm-12 pl-0 pr-0">Date of Birth</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <input type="date" name="birthdate" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <p class="card-title"> Address Information </p>
                  <p class="card-description">In this section, enter information of the christian's address. </p>
                  <div class="row mt-5">

                    <div class="form-group col-md-4 ">
                      <label class="col-sm-12 pl-0 pr-0 ">Country</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <select name="country" class="form-control" required>
                          <option value="">Select country</option>
                          <option value="Uganda">Uganda</option>
                          <option value="Denmark">Denmark</option>
                          <option value="Canada">Canada</option>
                          <option value="England">England</option>
                          <option value="Spain">Spain</option>
                          <option value="USA">USA</option>
                          <option value="United Kingdom">United Kingdom</option>
                          <option value="India">India</option>
                          <option value="South Africa">South Africa</option>
                          <option value="Austraria">Austraria</option>
                        </select>
                      </div>
                    </div>


                    <div class="form-group col-md-4 pl-md-0">
                      <label class="col-sm-12 pl-0 pr-0 ">District</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <input type="text" name="district" placeholder="Enter district" class="form-control" required>
                      </div>
                    </div>

                    <div class="form-group col-md-4 pl-md-0">
                      <label class="col-sm-12 pl-0 pr-0 ">Parish</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <input type="text" name="parish" placeholder="Enter parish" class="form-control" >
                      </div>
                    </div>
                  </div>
                  <div class="row">
                   <div class="form-group col-md-4 pl-md-0">
                    <label class="col-sm-12 pl-0 pr-0">Village</label>
                    <div class="col-sm-12 pl-0 pr-0">
                      <input type="text" name="village" placeholder="Enter village" class="form-control" >
                    </div>
                  </div>
                  <div class="form-group col-md-4">
                    <label class="col-sm-12 pl-0 pr-0 ">Email</label>
                    <div class="col-sm-12 pl-0 pr-0">
                      <input type="form-control"   name="email"  placeholder="Enter email" class="form-control" >
                    </div>
                  </div>
                  <div class="form-group col-md-4 pl-md-0">
                    <label class="col-sm-12 pl-0 pr-0">Phone</label>
                    <div class="col-sm-12 pl-0 pr-0">
                      <input type="text" name="phone" placeholder="Enter phone" class="form-control"  required>
                    </div>
                  </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: right;">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:../../partials/_footer.html -->
    <?php @include("includes/footer.php");?>
    <!-- partial -->
  </div>
  <!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
<?php @include("includes/foot.php");?>
</body>
</html>