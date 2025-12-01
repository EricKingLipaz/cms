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
                  <h5 class="modal-title" style="float: left;">Offertories Money</h5>    
                  <div class="card-tools" style="float: right;">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#deposit" ><i class="fas fa-plus" ></i> New Offertory
                    </button>
                  </div>    
                </div>
                <!-- /.card-header -->
                <div class="modal fade" id="deposit">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Register New promise</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- <p>One fine body&hellip;</p> -->
                        <?php @include("newoffertory-form.php");?>
                      </div>
                      <!-- <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                      </div> -->
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-lg ">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Offertories Banking Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <!-- <p>One fine body&hellip;</p> -->
                        <?php @include("offertorypayment.php");?>
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <div class="card-body table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                   <thead>
                    <tr>
                      <th class="text-center">Mass Date</th>
                      <th class="text-center">Description</th>
                      <th class="text-center">Currency</th>
                      <th class="text-center">Offertory Amount</th>
                      <th class="text-center">Registered by</th>
                      <th class="text-center">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT * from tbloffertory";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);

                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $row)
                        {  ?>
                         <tr>
                          <td class="text-center" ><?php  echo htmlentities(date("d-m-Y", strtotime($row->date)));?></td>
                          <td class="text-left"><?php  echo htmlentities($row->description);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->currency);?></td> 
                          <td class="text-right"><?php echo htmlentities(number_format($row->offertoryamount, 0, '.', ','));?></td>
                          <td class="text-left"><?php  echo htmlentities($row->depositedby);?></td>
                          <?php 
                          if($row->status=='on'){
                            ?>

                            <td class="text-center"><button class="badge badge-primary btn-xs edit_data" id="<?php echo  ($row->id); ?>">Bank Now</button></td>
                            <?php
                          }else{?>
                            <td><label class="badge badge-success">Banked</label></td>
                            <?php
                          }
                        }
                      } ?>
                    </tr>
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
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data',function(){
      var edit_id=$(this).attr('id');
      $.ajax({
        url:"offertorypayment.php",
        type:"post",
        data:{edit_id:edit_id},
        success:function(data){
          $("#info_update").html(data);
          $("#editData").modal('show');
        }
      });
    });
  });
</script>
</body>
</html>
