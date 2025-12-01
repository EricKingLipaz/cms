

<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
  $depositedby= $_SESSION['names'];
  $amount=$_POST['amount'];
  $number=$_POST['refnumber'];
  $date=$_POST['date'];
  $description=$_POST['description'];
  $method=$_POST['method'];
  $currency=$_POST['currency'];
  $sql="insert into banks(currency,description,transaction_amount,transaction_ref,transaction_date,transaction_method,deposited_by)values(:currency,:description,:amount,:number,:date,:method,:depositedby)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':amount',$amount,PDO::PARAM_STR);
  $query->bindParam(':number',$number,PDO::PARAM_STR);
  $query->bindParam(':method',$method,PDO::PARAM_STR);
  $query->bindParam(':date',$date,PDO::PARAM_STR);
  $query->bindParam(':description',$description,PDO::PARAM_STR);
  $query->bindParam(':currency',$currency,PDO::PARAM_STR);
  $query->bindParam(':depositedby',$depositedby,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) {
    $desc=$_POST['description'];
    $amount = $_POST['amount'];
    $refnumber = mt_rand(100000000, 999999999);
    $sql2="insert into general_jaunal(description,credit,ref_no)values(:desc,:amount,:ref)";
    $query=$dbh->prepare($sql2);
    $query->bindParam(':desc',$desc,PDO::PARAM_STR);
    $query->bindParam(':ref',$refnumber,PDO::PARAM_STR);
    $query->bindParam(':amount',$amount,PDO::PARAM_STR);
    $query->execute();
    
    $currency=$_POST['currency'];
    $sql ="select amount as spent from deposit where  currency = '$currency' ";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    foreach($results as $row)
    { 
      $originalaccount = ($row->spent);
      $currency=$_POST['currency'];
      $amount=$_POST['amount'];
      $newaccount = ($originalaccount+$amount);
      $sql="update  deposit set amount = '$newaccount' where  currency = '$currency'";
      $query=$dbh->prepare($sql);
      $query->execute();

      $eid=$_SESSION['offertoryid'];
      $sql="update  tbloffertory set status='off' where id='$eid'";
      $query = $dbh->prepare($sql);
      $query->execute();
      echo '<script>alert("deposited successfully.")</script>';
      echo "<script>window.location.href ='offertory.php'</script>";
    }
  }
  else
  {
   echo '<script>alert("Something Went Wrong. Please try again")</script>';
 }
}
?>
<div class="modal-body">  
 <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">
  <?php
  $eid=$_POST['edit_id'];
  $sql="SELECT * from tbloffertory   where id=:eid ";                                    
  $query = $dbh -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  $cnt=1;
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    {
      $_SESSION['offertoryid']=$row->id;
      ?>          

      <div class="row">
        <div class="form-group col-md-4 ">
          <label for="exampleInputPassword1">Currency</label>
          <input type="text" name="currency" class="form-control" value="<?php echo $row->currency; ?>"  id="currency" readonly required>
        </div>
        <div class="form-group col-md-4 ">
          <label for="exampleInputPassword1">Transaction Amount</label>
          <input type="text" name="amount" class="form-control" value="<?php echo $row->offertoryamount; ?>"  id="amount" readonly required>
        </div>
        <div class="form-group col-md-4">
          <label>Transaction Method</label>
          <select class="form-control select2" name="method" style="width: 100%;">
            <option value="Cash" selected="selected">Cash</option>
            <option value="Cheque">Cheque</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">Transaction Date</label>
          <input type="date" name="date" class="form-control"  id="amount"  >
        </div>
        <div class="form-group col-md-6 ">
          <label for="exampleInputPassword1">Reference Number</label>
          <input type="text" name="refnumber" class="form-control" placeholder="Enter Reference No"  id="refnumber"  >
        </div>
      </div>
      <div class="row">
       <div class="form-group col-md-12">
        <label for="exampleInputPassword1">Description</label>
        <textarea class="form-control" name="description" rows="5" readonly><?php echo $row->description; ?></textarea>
      </div>
    </div>
    <?php 
  }
} ?>
<input type="submit" name="insert" id="insert" value="Bank" class="btn btn-success" />  
</form>  
</div>