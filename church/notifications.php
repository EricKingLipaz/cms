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
                  <h5 class="modal-title" style="float: left;">Notifications</h5> 
                </div>
                <!-- /.card-header -->
                <!--  start  modal -->
                <div id="editData5" class="modal fade">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Notification details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update5">
                        <?php @include("view_notification.php");?>
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                    <thead>
                      <tr>
                        <th class="text-center">No</th>
                        <th>Nature</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">New Balance</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql="SELECT * from tblnotification  ORDER BY id DESC";
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
                            <td class=""><a href="#"class=" edit_data5" id="<?php echo  ($row->id); ?>" ><?php  echo htmlentities($row->nature);?></a></td>
                            <td class="text-right"><?php echo htmlentities(number_format($row->amount, 0, '.', ','));?></td>
                            <td class="text-right"><?php echo htmlentities(number_format($row->total, 0, '.', ','));?></td>
                            <?php
                            if($row->status=='on'){?>
                              <td><label class="badge badge-warning">New</label></td>
                              <?php
                            }else{?>
                              <td><label class="badge badge-success">Viewed</label></td>
                              <?php
                            }
                            ?>
                            <td class="text-center"><?php  echo htmlentities($row->creationdate);?></td>
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
  <script type="text/javascript">
    $(document).ready(function(){
      $(document).on('click','.edit_data5',function(){
        var edit_id5=$(this).attr('id');
        $.ajax({
          url:"view_notification.php",
          type:"post",
          data:{edit_id5:edit_id5},
          success:function(data){
            $("#info_update5").html(data);
            $("#editData5").modal('show');
          }
        });
      });
    });
  </script>
</body>
</html>