<?php 
include('includes/dbconnection.php');
if(isset($_POST['submitexpensename']))
{
 $aid=$_SESSION['odmsaid'];
 $sql="SELECT * from  tbladmin where ID=:aid";
 $query = $dbh -> prepare($sql);
 $query->bindParam(':aid',$aid,PDO::PARAM_STR);
 $query->execute();
 $results=$query->fetchAll(PDO::FETCH_OBJ);
 if($query->rowCount() > 0)
 {
  foreach($results as $row)
  { 
    $user=$row->FirstName;
  }}
  $usertype=$user;
  $expense_name=$_POST['expense_name'];
  $expense_category=$_POST['expense_category'];
  $sql="insert into expensename(categoryname,expensename,registeredby)values(:expense_category,:expense_name,:usertype)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':expense_name',$expense_name,PDO::PARAM_STR);
  $query->bindParam(':usertype',$usertype,PDO::PARAM_STR);
  $query->bindParam(':expense_category',$expense_category,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("successfuly has been added.")</script>';
    echo "<script>window.location.href ='manage_sector.php'</script>";
  }
  else
  {
    echo '<script>alert("Something went wrong. Please try again")</script>';
  }
}
?>



<form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">
  <div class="card-body">
    <div class="row">
      <div class="form-group col-md-12 ">
        <label for="exampleInputEmail1">Expense category </label>
        <select name="expense_category" class="form-control" required>
          <option value="">Select expense category</option>
          <?php
          $sql="SELECT * from  expensecategory";
          $query = $dbh -> prepare($sql);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);
          $cnt=1;
          if($query->rowCount() > 0)
          {
            foreach($results as $row)
              {               ?> 

                <option value="<?php  echo $row->id;?>"><?php  echo $row->categoryname;?></option>
              <?php }} ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-12 ">
            <label for="exampleInputEmail1">Expense name <span style="font-size: 12px; color: grey;">(Expenditure line)</span> </label>
            <input type="text" name="expense_name" class="form-control" id="exampleInputEmail1"value=" "  required>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="modal-footer text-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" name="submitexpensename" class="btn btn-primary">Submit</button>
      </div>
    </form>
