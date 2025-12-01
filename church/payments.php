
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['save']))
{
  $compname=$_SESSION['company_name'];
  $paidamount=$_POST['paidamount'];
  $transaction=$_POST['transaction'];
  $paymentdate=$_POST['paymentdate'];
  $attachment=$_FILES["attachment"]["name"];
  move_uploaded_file($_FILES["attachment"]["tmp_name"],"companyimages/payments/".$_FILES["attachment"]["name"]);
  $sql="insert into tblpayments(companyname,paidamount,transaction,attachment,paiddate)values(:compname,:paidamount,:transaction,:attachment,:paymentdate)";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':compname', $compname, PDO::PARAM_STR);
  $query->bindParam(':paidamount',$paidamount,PDO::PARAM_STR);
  $query->bindParam(':transaction',$transaction,PDO::PARAM_STR);
  $query->bindParam(':paymentdate',$paymentdate,PDO::PARAM_STR);
  $query->bindParam(':attachment',$attachment,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if($LastInsertId)
  {
     echo '<script>alert("Paid successfuly")</script>';
  }
  else
  {
    echo "<script>alert('Something went wrong. Please try again');</script>";

  }
 
}
?>
<div class="card-body">

 <div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form class="forms-sample" method="post" enctype="multipart/form-data">
          <?php
          $_SESSION['company_name']=$_POST['edit_id'];
          ?>        
          <div class="form-group">
            <label for="exampleInputUsername1">Amount paid </label>
            <input type="text" class="form-control" id="paidamount" name="paidamount" placeholder="Paid amount"  >
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Transaction Number</label>
            <input type="text" class="form-control" id="transaction" name="transaction" placeholder="Transaction number">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Payment date</label>
            <input type="date" class="form-control" id="paymentdate" name="paymentdate" placeholder="">
          </div>

          <div class="form-group">
            <label>File upload</label>
            <input type="file"  name="attachment"  class="file-upload-default">
            <div class="input-group col-xs-12">
              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
              <span class="input-group-append">
                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
              </span>
            </div>
          </div>
          <button type="submit" class="btn btn-gradient-primary mr-2" name="save">Submit</button>
          <button class="btn btn-light">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

</div>
<script src="assets/js/file-upload.js"></script>