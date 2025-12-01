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
                  <h5 class="modal-title">Bid reports</h5>                    
                </div>
                <div class="card-body">
                  <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Between dates reports:</h4>
                    <form class="forms-sample"  method="post" name="bwdatesreport"  action="btndates_reports.php" enctype="multipart/form-data">
                      
                      <div class="form-group row col-md-6">
                        <label for="fromdate" class="col-sm-3 col-form-label">From Date</label>
                        <div class="col-sm-9">
                          <input type="date" class="form-control" name="fromdate"  id="fromdate" value="" required='true' >
                        </div>
                      </div>
                      <div class="form-group row col-md-6">
                        <label for="fromdate" class="col-sm-3 col-form-label">To Date</label>
                        <div class="col-sm-9">
                          <input type="date" class="form-control" name="todate" id="todate" value="" required='true'>
                        </div>
                      </div>
                      <button type="submit" name="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    </form>
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
    <!-- <script type="text/javascript">
      function myFunction(strid)
      {
       var prtContent = document.getElementById("print");
       window.print();
     }
   </script> -->
   
</body>
</html>