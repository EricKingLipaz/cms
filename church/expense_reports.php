<?php
include('includes/checklogin.php');
check_login();
//session_destroy($_SESSION['pdf']);
//session_unset($_SESSION['pdf']);
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
                <div class="modal-header ">
                  <div class="col-md-6">
                    <form method="post" >
                      <div class="input-group col-md-12">
                        <input type="text" class="form-control col-md-12" name="search2" placeholder="search by expense"  required>
                        <div class="input-group-append">
                          <button class="btn btn-sm btn-gradient-primary" type="submit" name="search" >Search</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-md-6">
                    <span style="float: right;">
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addsector" ><i class="fas fa-plus" ></i> Generate pdf
                    </button>
                   </span>  
                 </div>                 
               </div>
               <div class="card-body">

                <div class="table-responsive"  id="examp">

                  <table class="table align-items-center table-hover" >
                    <thead>
                      <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Expense Category</th>
                        <th class="text-center">Expense</th>
                        <th class="text-center">Reason</th>
                        <th class="text-right">Amount Spent</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(empty($_POST['search2'])){
                        $sql ="SELECT expence.*,expensecategory.* from expence join expensecategory on expence.category=expensecategory.id ORDER BY expence.id DESC";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        if($query->rowCount() > 0)
                        {
                          foreach($results as $row)
                          {          
                            ?>
                            <tr>
                              <td class=""><?php  echo htmlentities(date("d-m-Y", strtotime($row->date)));?></td>
                              <td class=""><?php  echo htmlentities($row->categoryname);?></td>
                              <td class=""><?php  echo htmlentities($row->expense);?></td>
                              <td class=""><?php  echo htmlentities($row->resoan);?></td>
                              <td class="text-right"><?php echo htmlentities(number_format($row->amount, 0, '.', ','));?></td>
                            </tr>
                            <?php 
                          }
                        }
                      } 
                      if(isset($_POST['search'])&& !empty($_POST['search2'])){
                        $search2 = $_POST['search2'];  
                        $sql ="SELECT expence.*,expensecategory.* from expence join expensecategory on expence.category=expensecategory.id where expence.expense='$search2'   ";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        if($query->rowCount() > 0)
                        {
                          foreach($results as $row)
                          {          
                            ?>
                            <tr>
                              <td class=""><?php  echo htmlentities(date("d-m-Y", strtotime($row->date)));?></td>
                              <td class=""><?php  echo htmlentities($row->categoryname);?></td>
                              <td class=""><?php  echo htmlentities($row->expense);?></td>
                              <td class=""><?php  echo htmlentities($row->resoan);?></td>
                              <td class="text-right"><?php echo htmlentities(number_format($row->amount, 0, '.', ','));?></td>
                            </tr>
                            <?php 
                          }
                        }
                      }
                      ?>
                    </tbody>
                  </table>
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
</div>
<!-- container-scroller -->
<?php @include("includes/foot.php");?>
<!-- End custom js for this page -->
</body>
</html>