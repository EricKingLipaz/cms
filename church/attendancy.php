<?php
include('includes/checklogin.php');
check_login();
if(isset($_POST['save']))
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
  $sex=$_POST['sex'];
  $age=$_POST['age'];
  $phone=$_POST['phone'];
  $date=$_POST['date'];
  $temperature=$_POST['temperature'];
  $district=$_POST['district'];
  $village=$_POST['village'];
  $sql="insert into tblattendancy(Name,Sex,Age,Cdate,Temperature,District,Village,Phone,Registeredby,lastname)values(:name,:sex,:age,:date,:temperature,:district,:village,:phone,:regname,:lastname)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':name',$name,PDO::PARAM_STR);
  $query->bindParam(':sex',$sex,PDO::PARAM_STR);
  $query->bindParam(':age',$age,PDO::PARAM_STR);
  $query->bindParam(':date',$date,PDO::PARAM_STR);
  $query->bindParam(':phone',$phone,PDO::PARAM_STR);
  $query->bindParam(':temperature',$temperature,PDO::PARAM_STR);
  $query->bindParam(':district',$district,PDO::PARAM_STR);
  $query->bindParam(':village',$village,PDO::PARAM_STR);
  $query->bindParam(':regname',$regname,PDO::PARAM_STR);
  $query->bindParam(':lastname',$lastname,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Registered successfully")</script>';
    echo "<script>window.location.href ='attendancy.php'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}
?>
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
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="modal-header">
                  <div class="col-md-6">
                    <form method="post" >
                      <div class="input-group col-md-12">
                        <input type="text" class="form-control col-md-12" name="search2" placeholder="search by code" aria-label="" aria-describedby="basic-addon2" required>
                        <div class="input-group-append">
                          <button class="btn btn-sm btn-gradient-primary" type="submit" name="search" >Search</button>
                        </div>
                      </div>
                    </form>
                  </div>           
                </div>
                <?php
                if(isset($_POST['search2'])&& !empty($_POST['search2'])){
                  $search2 = $_POST['search2']; 
                  $sql="SELECT tblchristian.ID,tblchristian.Name,tblchristian.Sex,tblchristian.Age,tblchristian.Occupation,tblchristian.District,tblchristian.Village,tblchristian.Phone from tblchristian where Code = '$search2'";
                  $query = $dbh -> prepare($sql);
                  $query->execute();
                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                  $cnt=1;
                  if($query->rowCount() > 0)
                  {
                    foreach($results as $row)
                    { 
                      ?>
                      <div class=" col-md-12 mt-4" style=" flex: 0 0 0%;">
                        <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                          <div class="row ">
                            <div class="form-group col-md-4 ">
                              <label for="exampleInputName1">Names</label>
                              <input type="text" name="name" class="form-control" value="<?php  echo $row->Name;?>" id="name"required>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="exampleInputName1">Sex</label>
                              <input type="text" name="sex" value="<?php  echo $row->Sex;?>" class="form-control" id="sex" required>
                            </div>

                            <div class="form-group col-md-4">
                              <label for="exampleInputName1">Age.</label>
                              <input type="text" name="age" value="<?php  echo $row->Age;?>" class="form-control" id="age"  required>
                            </div>
                          </div>
                          <div class="row ">
                            <div class="form-group col-md-4">
                              <label for="exampleInputName1">District</label>
                              <input type="text" name="district" value="<?php  echo $row->District;?>" class="form-control" id="district"required>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="exampleInputName1">Village</label>
                              <input type="text" name="village" value="<?php  echo $row->Village;?>" class="form-control" id="village" required>
                            </div>

                            <div class="form-group col-md-4">
                              <label for="exampleInputName1">Phone No.</label>
                              <input type="text" name="phone" class="form-control" value="<?php  echo $row->Phone;?>" id="phone"  required>
                            </div>
                          </div>
                          <div class="row ">
                            <div class="form-group col-md-3 offset-md-6">
                              <label for="exampleInputName1">Date</label>
                              <input type="text" name="date"  class="form-control" value="<?php echo date('Y-m-d ');?>" id="date" required>
                            </div>

                            <div class="form-group col-md-3">
                              <label for="exampleInputName1">Temperature.</label>
                              <input type="text" name="temperature" class="form-control" placeholder=" Enter temperature" id="temperature"  required>
                            </div>
                          </div>
                          
                          <button type="submit" style="float: right;" name="save" class="btn btn-primary btn-fw mr-2">Save</button>
                        </form>
                      </div>
                      <?php
                    }
                  }else{?>
                    <span style="color: red;"><h4>No record found</h4></span>
                    <?php
                  }
                }
                ?>
                <hr>
                <div class="card-body ">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead >
                      <tr>
                        <th class=" text-center" style="width: 3%" ></th>
                        <th class=" " >Names</th>
                        <th class="  text-center" >Sex</th>
                        <th class="  text-center"  >District</th>
                        <th class="text-center" >Village</th>
                        <th class="" >Temperature</th>
                        <th class="text-center" >Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql="SELECT * from tblattendancy  ORDER BY ID DESC";
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $cnt=1;
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        { 
                          ?>
                          <tr>
                            <td class="text-center"><?php echo htmlentities($cnt);?></td>
                            <td class=""  ><?php  echo htmlentities($row->Name);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->Sex);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->District);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->Village);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->Temperature);?><sup style="font-size: 15px">o</sup>c</td>
                            <td class="text-center"><?php  echo htmlentities(date("d-m-Y", strtotime($row->Cdate)));?></td>
                          </tr>
                          <?php 
                          $cnt=$cnt+1;
                        }
                      } ?>
                    </tbody>
                  </table>
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
  </div>
  <!-- container-scroller -->
  <?php @include("includes/foot.php");?>
  <!-- End custom js for this page -->

</body>
</html>