
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['withdraw']))
{
  $sql4 ="select * from deposit where  currency = 'UGX' ";
  $query = $dbh -> prepare($sql4);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    { 
      $originalaccount2 = ($row->amount);
      $amount2=$_POST['amount'];
      if($amount2 > $originalaccount2){
        echo "<script>alert('Your account balance is low');</script>"; 
      }else{
        $sql ="select balance as spent from petty_balance where  property = 'church' ";
        $query = $dbh -> prepare($sql);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0)
        {
          foreach($results as $row)
          { 
            $originalaccount = ($row->spent); 
            $amount=$_POST['amount'];
            $totalpettycash=($originalaccount+$amount);
            $sql="update  petty_balance set balance = '$totalpettycash' where property = 'church' ";
            $query=$dbh->prepare($sql);
            $query->execute();
            $LastInsertId=$dbh->lastInsertId();
          }
        }   
        $sql ="select * from deposit where  currency = 'UGX' ";
        $query = $dbh -> prepare($sql);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0)
        {
          foreach($results as $row)
          { 
            $originalaccount = ($row->amount);
            $currency= ($row->currency);
            $amount=$_POST['amount'];
            $newaccount = ($originalaccount-$amount);
            $property=$property2; 
            $sql="update  deposit set amount = '$newaccount' where currency = 'UGX'";
            $query=$dbh->prepare($sql);
            $query->execute();
          }
        } 
        $desc=$_POST['description']; 
        $amount=$_POST['amount'];
        $refnumber = mt_rand(100000000, 999999999);
        $sql2="insert into general_jaunal(description,debit,ref_no)values(:desc,:amount,:ref)";
        $query=$dbh->prepare($sql2);
        $query->bindParam(':desc',$desc,PDO::PARAM_STR);
        $query->bindParam(':ref',$refnumber,PDO::PARAM_STR);
        $query->bindParam(':amount',$amount,PDO::PARAM_STR);
        $query->execute();
        $desc='credit petty cash'; 
        $amount=$_POST['amount'];
        $refnumber2 = $refnumber;
        $sql2="insert into general_jaunal(description,credit,ref_no)values(:desc,:amount,:ref)";
        $query=$dbh->prepare($sql2);
        $query->bindParam(':desc',$desc,PDO::PARAM_STR);
        $query->bindParam(':ref',$refnumber2,PDO::PARAM_STR);
        $query->bindParam(':amount',$amount,PDO::PARAM_STR);
        $query->execute();
        $amount=$_POST['amount'];
        $method=$_POST['method'];
        $number=$_POST['number'];
        $description=$_POST['description'];
        $date=$_POST['date'];
        $withdrawn=$_SESSION['names'];;
        $sql="insert into petty_cash(date,ref_no,details,amount,initiatedby,method)values(:date,:number,:description,:amount,:withdrawn,:method)";
        $query=$dbh->prepare($sql);
        $query->bindParam(':description',$description,PDO::PARAM_STR);
        $query->bindParam(':method',$method,PDO::PARAM_STR);
        $query->bindParam(':amount',$amount,PDO::PARAM_STR);
        $query->bindParam(':date',$date,PDO::PARAM_STR);
        $query->bindParam(':number',$number,PDO::PARAM_STR);
        $query->bindParam(':withdrawn',$withdrawn,PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('withdrawn successfuly');</script>"; 
        echo "<script>window.location.href = 'bankdeposit.php'</script>";
      }
    }
  } 
}
?>


<div class="card-body">
  <!-- Date -->

  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">

    <div class="card-body">
     <div class="row">
       <div class="form-group col-md-6 ">
        <label for="exampleInputPassword1">Amount</label>
        <input type="text" name="amount" class="form-control" id="exampleInputPassword1" required>
      </div>
      <div class="form-group col-md-6 ">
        <label for="exampleInputEmail1">Description</label>
        <input type="text" name="description" class="form-control" id="exampleInputEmail1"required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 ">
        <div class="form-group">
          <label>Transaction Method</label>
          <select class="form-control select2" name="method" style="width: 100%;">
            <option value="Cash" selected="selected">Cash</option>
            <option value="Cheque">Cheque</option>
          </select>
        </div>
        <!-- /.form-group -->
      </div>
      <div class="col-md-6 ">
        <div class="form-group">
          <label for="branch">Reference Number</label>
          <input name="number" type="text" class="form-control" id="ref" placeholder="Enter Reference No" required>
        </div>        
      </div>
    </div>
    <div class="row"> 
      <div class="form-group offset-md-6 col-md-6">
        <label>Date:</label>
        <div class="input-group date" id="reservationdate" >
          <input type="date" name="date" class="form-control " />
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer" style="float: left;">
    <button type="submit" name="withdraw" class="btn btn-primary">withdraw</button>
  </div>
</form>       
</div>     
<!-- /.card-body -->
