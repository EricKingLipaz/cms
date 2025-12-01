<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
  if(isset($_POST['submit']))
  {
    $depositedby= $_SESSION['names'];
    $amount=$_POST['amount'];
    $promisedamount=$_POST['amount'];
    $bankname=$_POST['bankname'];
    $date=$_POST['date'];
    $description=$_POST['description'];
    $currency=$_POST['currency'];
    $phoneno=$_POST['phoneno'];

    $sql="insert into tblloans(currency,loanamount,promisedamount,loandescription,date,bankname,depositedby,phone)values(:currency,:amount,:promisedamount,:description,:date,:bankname,:depositedby,:phoneno)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':amount',$amount,PDO::PARAM_STR);
    $query->bindParam(':promisedamount',$promisedamount,PDO::PARAM_STR);
    $query->bindParam(':bankname',$bankname,PDO::PARAM_STR);
    $query->bindParam(':date',$date,PDO::PARAM_STR);
    $query->bindParam(':phoneno',$phoneno,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->bindParam(':currency',$currency,PDO::PARAM_STR);
    $query->bindParam(':depositedby',$depositedby,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
      echo '<script>alert("added successfully.")</script>';
      echo "<script>window.location.href ='promises.php'</script>";
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
      <div class="col-md-4">
        <div class="form-group">
          <label>Currency</label>
          <select class="form-control select2" name="currency" style="width: 100%;" required>
            <option value="USD"selected="selected">USD</option>
            <option value="UGX" >UGX</option>
            
            
          </select>
        </div>
        <!-- /.form-group -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Promised Amount</label>
          <input class="form-control" type="text" name="amount" placeholder="amount" required>
        </div>
      </div>   
      <div class="col-md-4">
        <div class="form-group">
          <label>Promised Date</label>
          <input type="date" name="date" class="form-control " />
        </div>        
      </div>
      <!-- /.col -->
    </div><!-- ./row -->
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <div class="form-group">
          <label>Christian Names</label>
          <input class="form-control" type="text" name="bankname" placeholder="Enter Names" required>
        </div>        
      </div>
       <div class="col-md-4">
        <div class="form-group">
          <label>Phone Number</label>
          <input class="form-control" type="text" name="phoneno" placeholder="Enter phone" required>
        </div>        
      </div>
    </div><!-- ./row -->
    <div class="row">
      <div class="form-group col-md-12">
        <label for="feDescription">Description</label>
        <textarea class="form-control" name="description" rows="5" required></textarea>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="modal-footer text-right">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>