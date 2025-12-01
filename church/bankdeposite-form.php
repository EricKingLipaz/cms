<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['deposit-submit']))
{
  $depositedby= $_SESSION['names'];
  $amount=$_POST['amount'];
  $number=$_POST['number'];
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
    echo '<script>alert("deposited successfully.")</script>';
    echo "<script>window.location.href ='bankdeposit.php'</script>";
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
    }
  }
  else
  {
   echo '<script>alert("Something Went Wrong. Please try again")</script>';
 }
}
?>

<form role="form" id=" " method="post" enctype="multipart/form-data">
  <div class="card-body">
    <div class="row">
      <div class=" col-md-4">
        <div class="form-group">
          <label>Currency</label>
          <select class="form-control select2" name="currency" style="width: 100%;">
            <option value="USD"selected="selected">USD</option>
            <option value="UGX" >UGX</option>
            
            
          </select>
        </div>
        <!-- /.form-group -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Transaction Date</label>
          <input type="date" name="date" class="form-control " />
        </div>       
      </div>
      <!-- /.col -->
      <div class="col-md-4 col-xs-4">
        <div class="form-group">
          <label>Transaction Method</label>
          <select class="form-control select2" name="method" style="width: 100%;">
            <option value="Cash" selected="selected">Cash</option>
            <option value="Cheque">Cheque</option>
          </select>
        </div>
        <!-- /.form-group -->
      </div>
    </div><!-- ./row -->
    <div class="row">
      <div class="offset-md-4 col-md-4 col-xs-8">
        <div class="form-group">
          <label for="branch">Reference Number</label>
          <input name="number" type="text" class="form-control" id="ref" placeholder="Enter Reference No" required>
        </div>        
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Transaction Amount</label>
          <input class="form-control" type="text" name="amount" placeholder="Enter transaction Amount" required>
        </div>        
      </div>
      <!-- /.col -->
    </div><!-- ./row -->
    <div class="row">
      <div class="form-group col-md-12 col-sm-12">
        <label for="feDescription">Description</label>
        <textarea class="form-control" name="description" rows="5" required></textarea>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="modal-footer text-right">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      <button type="submit" name="deposit-submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>