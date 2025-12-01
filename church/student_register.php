<?php
include('includes/checklogin.php');
check_login();
if(!empty($_POST["code"])) {
  $bidname= $_POST["code"];
  
  $sql ="SELECT Code FROM tblbid WHERE Code=:bidname";
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
      $sql="insert into tblbid(Name,Code,Sex,Age,Occupation,Status,Birthdate,Country,District,Parish,Village,Email,Phone,Registeredby,lastname,Photo,Marital)values(:name,:code,:sex,:age,:occupation,:status,:birthdate,:country,:district,:parish,:village,:email,:phone,:regname,:lastname,:image,:marital)";
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
                 <div class="row">
                  <div class="col-md-12">

                    <h2 class="page-title">Registration </h2>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="panel panel-primary">
                          <div class="panel-heading">Fill all Info</div>
                          <div class="panel-body">
                            <form method="post" action="" class="form-horizontal">

                              <div class="form-group">
                                <label class="col-sm-4 control-label"><h4 style="color: green" align="left">Room Related info </h4> </label>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Room no. </label>
                                <div class="col-sm-8">
                                  <select name="room" id="room"class="form-control"  onChange="getSeater(this.value);" onBlur="checkAvailability()" required> 
                                    <option value="">Select Room</option>
                                    <?php $query ="SELECT * FROM rooms";
                                    $stmt2 = $mysqli->prepare($query);
                                    $stmt2->execute();
                                    $res=$stmt2->get_result();
                                    while($row=$res->fetch_object())
                                    {
                                      ?>
                                      <option value="<?php echo $row->room_no;?>"> <?php echo $row->room_no;?></option>
                                    <?php } ?>
                                  </select> 
                                  <span id="room-availability-status" style="font-size:12px;"></span>

                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-sm-2 control-label">Seater</label>
                                <div class="col-sm-8">
                                  <input type="text" name="seater" id="seater"  class="form-control" readonly="true"  >
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Fees Per Month</label>
                                <div class="col-sm-8">
                                  <input type="text" name="fpm" id="fpm"  class="form-control" readonly="true">
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Food Status</label>
                                <div class="col-sm-8">
                                  <input type="radio" value="0" name="foodstatus" checked="checked"> Without Food
                                  <input type="radio" value="1" name="foodstatus"> With Food(Rs 2000.00 Per Month Extra)
                                </div>
                              </div>  

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Stay From</label>
                                <div class="col-sm-8">
                                  <input type="date" name="stayf" id="stayf"  class="form-control" >
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">Duration</label>
                                <div class="col-sm-8">
                                  <select name="duration" id="duration" class="form-control">
                                    <option value="">Select Duration in Month</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                  </select>
                                </div>
                              </div>


                              <div class="form-group">
                                <label class="col-sm-2 control-label"><h4 style="color: green" align="left">Personal info </h4> </label>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 control-label">course </label>
                                <div class="col-sm-8">
                                  <select name="course" id="course" class="form-control" required> 
                                    <option value="">Select Course</option>
                                    <?php $query ="SELECT * FROM courses";
                                    $stmt2 = $mysqli->prepare($query);
                                    $stmt2->execute();
                                    $res=$stmt2->get_result();
                                    while($row=$res->fetch_object())
                                    {
                                      ?>
                                      <option value="<?php echo $row->course_fn;?>"><?php echo $row->course_fn;?>&nbsp;&nbsp;(<?php echo $row->course_sn;?>)</option>
                                      <?php 
                                    } ?>
                                  </select> </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Registration No : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="regno" id="regno"  class="form-control" required="required"  onBlur="checkRegnoAvailability()">
                                    <span id="user-reg-availability" style="font-size:12px;"></span>
                                  </div>
                                </div>


                                <div class="form-group">
                                  <label class="col-sm-2 control-label">First Name : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="fname" id="fname"  class="form-control" required="required" >
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Middle Name : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="mname" id="mname"  class="form-control">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Last Name : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="lname" id="lname"  class="form-control" required="required">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Gender : </label>
                                  <div class="col-sm-8">
                                    <select name="gender" class="form-control" required="required">
                                      <option value="">Select Gender</option>
                                      <option value="male">Male</option>
                                      <option value="female">Female</option>
                                      <option value="others">Others</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Contact No : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="contact" id="contact"  class="form-control" required="required" >
                                  </div>
                                </div>


                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Email id : </label>
                                  <div class="col-sm-8">
                                    <input type="email" name="email" id="email"  class="form-control" onBlur="checkAvailability()" required="required">
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Emergency Contact: </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="econtact" id="econtact"  class="form-control" required="required">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Guardian  Name : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="gname" id="gname"  class="form-control" required="required">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Guardian  Relation : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="grelation" id="grelation"  class="form-control" required="required">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Guardian Contact no : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="gcontact" id="gcontact"  class="form-control" required="required">
                                  </div>
                                </div>  

                                <div class="form-group">
                                  <label class="col-sm-3 control-label"><h4 style="color: green" align="left">Correspondense Address </h4> </label>
                                </div>


                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Address : </label>
                                  <div class="col-sm-8">
                                    <textarea  rows="5" name="address"  id="address" class="form-control" required="required"></textarea>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">City : </label>
                                  <div class="col-sm-8">
                                    <input type="text" name="city" id="city"  class="form-control" required="required">
                                  </div>
                                </div>  

                                <div class="form-group">
                                  <label class="col-sm-2 control-label">State </label>
                                  <div class="col-sm-8">
                                    <select name="state" id="state"class="form-control" required> 
                                      <option value="">Select State</option>
                                      <?php $query ="SELECT * FROM states";
                                      $stmt2 = $mysqli->prepare($query);
                                      $stmt2->execute();
                                      $res=$stmt2->get_result();
                                      while($row=$res->fetch_object())
                                      {
                                        ?>
                                        <option value="<?php echo $row->State;?>"><?php echo $row->State;?></option>
                                        <?php 
                                      } ?>
                                    </select> </div>
                                  </div>              

                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Pincode : </label>
                                    <div class="col-sm-8">
                                      <input type="text" name="pincode" id="pincode"  class="form-control" required="required">
                                    </div>
                                  </div>  

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label"><h4 style="color: green" align="left">Permanent Address </h4> </label>
                                  </div>


                                  <div class="form-group">
                                    <label class="col-sm-5 control-label">Permanent Address same as Correspondense address : </label>
                                    <div class="col-sm-4">
                                      <input type="checkbox" name="adcheck" value="1"/>
                                    </div>
                                  </div>


                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Address : </label>
                                    <div class="col-sm-8">
                                      <textarea  rows="5" name="paddress"  id="paddress" class="form-control" required="required"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">City : </label>
                                    <div class="col-sm-8">
                                      <input type="text" name="pcity" id="pcity"  class="form-control" required="required">
                                    </div>
                                  </div>  

                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">State </label>
                                    <div class="col-sm-8">
                                      <select name="pstate" id="pstate"class="form-control" required> 
                                        <option value="">Select State</option>
                                        <?php $query ="SELECT * FROM states";
                                        $stmt2 = $mysqli->prepare($query);
                                        $stmt2->execute();
                                        $res=$stmt2->get_result();
                                        while($row=$res->fetch_object())
                                        {
                                          ?>
                                          <option value="<?php echo $row->State;?>"><?php echo $row->State;?></option>
                                          <?php 
                                        } ?>
                                      </select> </div>
                                    </div>              

                                    <div class="form-group">
                                      <label class="col-sm-2 control-label">Pincode : </label>
                                      <div class="col-sm-8">
                                        <input type="text" name="ppincode" id="ppincode"  class="form-control" required="required">
                                      </div>
                                    </div>  


                                    <div class="col-sm-6 col-sm-offset-4">
                                      <button class="btn btn-default" type="submit">Cancel</button>
                                      <input type="submit" name="submit" Value="Register" class="btn btn-primary">
                                    </div>
                                  </form>

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
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