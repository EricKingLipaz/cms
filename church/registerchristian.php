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
                  <h5 class="modal-title" style="float: left;">Christian register</h5>    
                  <div class="card-tools" style="float: right;">
                   <a href="newchristian.php"> <button type="button" class="btn btn-sm btn-success" ><i class="fas fa-plus" ></i> Register Christian
                   </button></a>
                 </div>                
               </div>
               
               <!-- /.card-header -->
               
               <!--  start  modal -->
               <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Christian details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update4">
                     <?php @include("edit_christian.php");?>
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
            <!--  start  modal -->
            <div id="editData5" class="modal fade">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">View christian details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" id="info_update5">
                   <?php @include("view_christian.php");?>
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
                  <th>Christian Names</th>
                  <th class="text-center">Code</th>
                  <th class="text-center">Country</th>
                  <th>Village</th>
                  <th class="text-center">Contact</th>
                  <th class="text-center">Registered By</th>
                  <th class=" Text-center" style="width: 15%;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $companyname=$_SESSION['companyname'];
                $sql="SELECT tblchristian.ID,tblchristian.Name,tblchristian.Code,tblchristian.Country,tblchristian.Village,tblchristian.District,tblchristian.Phone,tblchristian.Photo,tblchristian.Registeredby from tblchristian  ORDER BY ID DESC";
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
                      <td>
                        <img src="christianimages/<?php  echo $row->Photo;?>" class="mr-2" alt="image"><a href="#"class=" edit_data5" id="<?php echo  ($row->ID); ?>" ><?php  echo htmlentities($row->Name);?></a>
                      </td>
                      <td class="text-center"><?php  echo htmlentities($row->Code);?></td>
                      <td class="text-center"><?php  echo htmlentities($row->Country);?></td>
                      <td class=""><?php  echo htmlentities($row->Village);?></td>
                      <td class=""><?php  echo htmlentities($row->Phone);?></td>
                      <td class=""><?php  echo htmlentities($row->Registeredby);?></td>
                      <td class=" text-center"><a href="#"  class=" edit_data4" id="<?php echo  ($row->ID); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a>
                        <a href="#"  class=" edit_data5" id="<?php echo  ($row->ID); ?>" title="click to view">&nbsp;<i class="mdi mdi-eye" aria-hidden="true"></i></a>
                      </td>
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
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_christian.php",
        type:"post",
        data:{edit_id4:edit_id4},
        success:function(data){
          $("#info_update4").html(data);
          $("#editData4").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_christian.php",
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