<?php
include('includes/checklogin.php');
check_login();
?>
<script >
  function getSubcat(val) {
    $.ajax({
      type: "POST",
      url: "get_subcat.php",
      data:'cat_id='+val,
      success: function(data){
        $("#subcategory").html(data);
      }
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
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">Deposit Money</h5>    
                  <div class="card-tools" style="float: right;">
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#withdraw" ><i class="fas fa-minu" ></i> Withdraw money
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#deposit" ><i class="fas fa-plus" ></i> Deposit money
                    </button>
                  </div>    
                </div>
                <!-- /.card-header -->
                <div class="modal fade" id="deposit">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Deposited at Bank</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- <p>One fine body&hellip;</p> -->
                        <?php @include("bankdeposite-form.php");?>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <div class="modal fade" id="withdraw">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">withdraw money from Bank</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- <p>One fine body&hellip;</p> -->
                        <?php @include("withdraw-form.php");?>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div class="card-body table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                   <thead>
                    <tr>
                      <th class="text-center">Date</th>
                      <th class="text-center">Description</th>
                      <th class="text-center">Ref</th>
                      <th class="text-center">Type</th>
                      <th class="text-center">Currency</th>
                      <th class="text-center">Amount</th>
                      <th class="text-center">Deposited By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT * from banks";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);

                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $row)
                        {  ?>
                         <tr>

                          <td class="text-center" ><?php  echo htmlentities(date("d-m-Y", strtotime($row->transaction_date)));?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->description);?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->transaction_ref);?></td> 
                          <td class="font-w600"><?php  echo htmlentities($row->transaction_method);?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->currency);?></td>
                          <td class="text-right"><?php echo htmlentities(number_format($row->transaction_amount, 0, '.', ','));?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->deposited_by);?></td>
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
