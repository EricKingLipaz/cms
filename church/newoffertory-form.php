<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
  if(isset($_POST['submit']))
  {
    $depositedby= $_SESSION['names'];
    $amount=$_POST['amount'];
    $date=$_POST['date'];
    $description=$_POST['description'];
    $currency=$_POST['currency'];

    $sql="insert into tbloffertory(currency,offertoryamount,description,date,depositedby)values(:currency,:amount,:description,:date,:depositedby)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':amount',$amount,PDO::PARAM_STR);
    $query->bindParam(':date',$date,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->bindParam(':currency',$currency,PDO::PARAM_STR);
    $query->bindParam(':depositedby',$depositedby,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
      echo '<script>alert("added successfully.")</script>';
      echo "<script>window.location.href ='offertory.php'</script>";
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
            <option value="USD" selected="selected">USD</option>
            <option value="UGX">UGX</option>
            
          </select>
        </div>
        <!-- /.form-group -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Offertory Amount</label>
          <input class="form-control" type="text" name="amount" placeholder="amount" required>
        </div>
      </div>   
      <div class="col-md-4">
        <div class="form-group">
          <label>Mass Date</label>
          <input type="date" name="date" class="form-control " />
        </div>        
      </div>
      <!-- /.col -->
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