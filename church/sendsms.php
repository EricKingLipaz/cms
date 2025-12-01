<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="card-body">
  <?php
  if(isset($_GET["sendsms"])){
    $msg = $_GET["msg"];
    $sender = $_GET["sender"];
    $telform = $_GET["tel"];
  }
  ?>
  <form class="form-horizontal" action="confirm_sms.php?tel=<?php echo $telform; ?>&msg=<?php echo $msg; ?>&sender=<?php echo $sender; ?>" role="form" method="GET">
    <?php
    $eid=$_POST['edit_id'];
    $sql="SELECT * from tblloans where id = '$eid' ";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
      foreach($results as $row)
      {
        ?>
        <div class="control-group">
          <label class="control-label" for="inputEmail">Contact</label>
          <input class="form-control"  type="text" name="tel" class="span5" required value="<?php echo( "+1".$row->phone); ?> " readonly='' />
        </div>
        <?php 
      }
    } ?>
    <div class="control-group"> 
      <label for="description">SMS Description/Body</label>
      <textarea class="form-control" name="msg" required rows="5" placeholder="Enter SMS Description"></textarea>
    </div>
    <div class="control-group">
      <label class="control-label" for="inputEmail">SENDER</label>
      <input class="form-control" type="text" name="sender" required value="
      <?php
      $aid=$_POST['edit_id'];
      $sql="SELECT * from tblloans where id='$aid'";
      $query = $dbh -> prepare($sql);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);

      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        {
          echo ("+1".$row->phone);
        }
      }
      ?>" readonly=''/>
    </div>
    <br/>  
    <div class="control-group" style="float: right;">
     <button type="submit" name="sendsms" class="btn btn-success"><i class="icon-plus-sign icon-large"></i>&nbsp;Send Sms</button>
     <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
   </div>
 </form>
</div>
<!-- /.card-body -->
