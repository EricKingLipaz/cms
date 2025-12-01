

<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{ 
  $eid= $_SESSION['paymentid'];
  $sql ="select * from tblloans where id = '$eid' ";
  $query = $dbh -> prepare($sql);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    { 
      $originalamount = $row->loanamount;
      $amount=$_POST['amount'];
      $paid=($originalamount-$amount);
    }
  }
  if($paid<0){
    echo '<script>alert("You are paying beyond ")</script>';
  }else{
    $eid= $_SESSION['paymentid'];
    $amount=$paid;
    $sql="update tblloans set loanamount=:amount where id=:eid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':amount',$amount,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("paid successfuly")</script>';
    echo "<script>window.location.href ='promises.php'</script>";
  }

}   

?>
<div class="modal-body">  
 <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">
  <?php
  $eid=$_POST['edit_id'];
  $usertype = $_SESSION['usertype'];
  $property = $_SESSION['property'];
  $sql="SELECT * from tblloans   where id=:eid ";                                    
  $query = $dbh -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);

  $cnt=1;
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    {
      $_SESSION['paymentid']=$row->id;
      ?>          

      <div class="row">
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">Christian Name</label>
          <input type="text" name="bankname" class="form-control" value="<?php echo $row->bankname; ?>"  id="bankname" readonly required>
        </div>
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">Balance Amount</label>
          <input type="text" name="bankaccount" class="form-control" value="<?php echo $row->loanamount; ?>"  id="bankaccount" readonly required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">Promised Date</label>
          <input type="text" name="amount" class="form-control" value="<?php  echo htmlentities(date("d-m-Y", strtotime($row->date)));?>"  id="amount" readonly >
        </div>
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">currency</label>
          <input type="text" name="duration" class="form-control" value="<?php echo $row->currency; ?>"  id="duration" readonly >
        </div>
      </div>
      <div class="row">
        <div class="form-group offset-md-6 col-md-6 ">
          <label for="exampleInputPassword1">Paid amount</label>
          <input type="text" name="amount" class="form-control"  id="amount"  >
        </div>
      </div>
      <?php 
    }
  } ?>
  <input type="submit" name="insert" id="insert" value="Pay" class="btn btn-success" />  
</form>  
</div>