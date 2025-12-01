<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="card-body">
  <h3><?php  echo $_POST['edit_id'];?></h3>
  <?php
  $eid=$_POST['edit_id5'];
  $sql="SELECT * from tblnotification   where id=:eid";
  $query = $dbh -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    {
      ?>
      <div>
        <p><h6 class="preview-subject font-weight-normal mb-1"> Your bank account was <?php  echo $row->nature;?> by <?php echo htmlentities(number_format($row->amount, 0, '.', ','));?>. Your new balance is <?php echo htmlentities(number_format($row->total, 0, '.', ','));?></h6></p>
        <p class="text-gray ellipsis mb-0"> on <?php  echo $row->creationdate;?> </p>
      </div>
      <?php
    }
  } 
  $eid=$_POST['edit_id5'];
  $sql="update  tblnotification set status='off' where id='$eid'";
  $query = $dbh->prepare($sql);
  $query->execute();?>
</div>