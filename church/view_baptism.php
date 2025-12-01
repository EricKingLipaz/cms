<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="card-body">
  <h3><?php  echo $_POST['edit_id'];?></h3>
 <?php
 $eid=$_POST['edit_id5'];
 $sql="SELECT * from tblbaptism   where ID=:eid";
 $query = $dbh -> prepare($sql);
 $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
 $query->execute();
 $results=$query->fetchAll(PDO::FETCH_OBJ);
 if($query->rowCount() > 0)
 {
  foreach($results as $row)
    {?>

      <h4 style="color: blue">Christian Information</h4>
      <table border="1" class="table table-bordered">
        <tr>
          <th>Names</th>
          <td><?php  echo $row->Name;?></td>
          <th >Code</th>
          <td><?php  echo $row->Code;?></td>
        </tr>
        <tr>
          <th>Status</th>
          <td><?php  echo $row->Status;?></td>
          <th >Sex</th>
          <td ><?php  echo $row->Sex;?></td>
        </tr>
        <tr>
          <th>Date Of Birth</th>
          <td><?php  echo $row->Birthdate;?></td>
          <th >Age</th>
          <td ><?php  echo $row->Age;?></td>
        </tr>
        <tr>
          <th >Occupation</th>
          <td><?php  echo $row->Occupation;?></td>
          <th>Registered By</th>
          <td ><?php  echo $row->Registeredby;?> <?php  echo $row->lastname;?></td>
        </tr>
        <tr>
          <th >Creation Date</th>
          <td><?php  echo $row->CreationDate;?></td>
        </tr>
      </table> 
      <h4 style="color: blue">Address Information</h4>
      <table border="1" class="table table-bordered">
       <tr>
        <th>Country</th>
        <td><?php  echo $row->Country;?></td>
        <th>District</th>
        <td><?php  echo $row->District;?></td>
      </tr>
      <tr>
        <th>Parish</th>
        <td><?php  echo $row->Parish;?> </td>
        <th>Village</th>
        <td><?php  echo $row->Village;?></td>

      </tr>
      <tr>
        <th>Email</th>
        <td><?php  echo $row->Email;?></td>
        <th>Contact</th>
        <td><?php  echo $row->Phone;?></td>
      </tr>
      <?php 
    }
  } ?>
</div>