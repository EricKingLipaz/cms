<?php
include('includes/checklogin.php');
check_login();
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
                  <h5 class="modal-title" style="float: left;">General Ledger</h5>    
                  <div class="card-tools" style="float: right;">
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addsector" ><i class="fas fa-plus" ></i> Generate pdf
                    </button>
                  </div>    
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                   <thead>
                    <tr>
                      <th class="text-center">Date </th>
                      <th class="text-center">Description</th>
                      <th class="text-center">Ref No.</th>
                      <th class="text-center">Debit</th>
                      <th class="text-center">Credit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql ="SELECT * from general_jaunal  ";    
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $row)
                        { ?>
                         <tr>
                          <td class="font-w600"><?php  echo htmlentities(date("d-m-Y", strtotime($row->date)));?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->description);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->ref_no);?></td>
                          <td class="text-right"><?php echo htmlentities(number_format($row->debit, 0, '.', ','));?></td>
                          <td class="text-right"><?php echo htmlentities(number_format($row->credit, 0, '.', ','));?></td>
                        </tr>
                        <?php
                      }
                    }?>
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
