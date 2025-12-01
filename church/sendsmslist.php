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
            <div class="col-md-6 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <?php
                  $sql ="SELECT id from tblloans ";
                  $query = $dbh -> prepare($sql);
                  $query->execute();
                  $results=$query->fetchAll(PDO::FETCH_OBJ);
                  $count=$query->rowCount();
                  ?>
                  <h2 class=""><?php echo htmlentities($count);?></h2>
                  <h4 class="card-text">Contacts</h4>
                </div>
              </div>
            </div>
            <div class="col-md-6 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h2 class="">1000</h2>
                  <h4 class="card-text">Balance SMS</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">Send SMS</h5>    
                  <div class="card-tools" style="float: right;">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sendsms" ><i class="mdi mdi-send" ></i>&nbsp; SMS All
                    </button>
                  </div>    
                </div>
                <!-- /.card-header -->
                <div class="modal fade" id="sendsms">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Send SMS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form role="form" action="sendmessage.php?tel=<?php echo $telform; ?>&msg=<?php echo $msg; ?>&sender=<?php echo $sender; ?>"   method="GET">
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group col-md-6">
                                <label for="recepeinetcategory">Contact(s)</label>
                                <input class="form-control" type="text" name="tel" value="
                                <?php
                                $sql="SELECT * from tblloans";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);

                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  {
                                    echo ("+1".$row->phone).",";
                                  }
                                }
                                ?>" readonly=''/>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="recepeinetcategory">Sender</label>
                                <input class="form-control" type="text" name="sender" required value="
                                <?php
                                $aid=$_SESSION['odmsaid'];
                                $sql="SELECT * from tbladmin where ID='$aid'";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);

                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  {
                                    echo ("+1".$row->MobileNumber);
                                  }
                                }
                                ?>" readonly=''/>
                              </div>

                            </div>
                            <div class="form-group col-md-12">
                              <label for="description">SMS Description/Body</label>
                              <textarea class="form-control" name="msg" required rows="5" placeholder="Enter SMS Description"></textarea>
                            </div>
                          </div>

                          <!-- /.card-body -->
                          <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="sendsms" class="btn btn-primary">Submit</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog ">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Send SMS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <!-- <p>One fine body&hellip;</p> -->
                        <?php @include("sendsms.php");?>
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
                      <th class="text-center">Promised Date</th>
                      <th class="text-center">Christian Name</th>
                      <th class="text-center">Phone</th>
                      <th class="text-center">Currency</th>
                      <th class="text-center">Promised Amount</th>
                      <th class="text-center">Balance</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT * from tblloans";
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
                          <td><?php  echo htmlentities($row->bankname);?></td>
                          <td class="text-left">0<?php  echo htmlentities($row->phone);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->currency);?></td> 
                          <td class="text-right"><?php echo htmlentities(number_format($row->promisedamount, 0, '.', ','));?></td>
                          <td class="text-right"><?php echo htmlentities(number_format($row->loanamount, 0, '.', ','));?></td>
                          <td class="text-center"><button class="btn btn-primary btn-xs edit_data" id="<?php echo  ($row->id); ?>">Send SMS</button></td>
                          <?php 
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
        url:"sendsms.php",
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
