<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    $eib= $_SESSION['editbid'];
    $name=$_POST['name'];
    $sex=$_POST['sex'];
    $age=$_POST['age'];
    $phone=$_POST['phone'];
    $birthdate=$_POST['birthdate'];
    $country=$_POST['country'];
    $district=$_POST['district'];
    $parish=$_POST['parish'];
    $village=$_POST['village'];
    $email=$_POST['email'];
    $sql4="update tblchristian set Name=:name,Sex=:sex,Age=:age,Birthdate=:birthdate,Phone=:phone,Country=:country,District=:district,Parish=:parish,Village=:village,Email=:email where ID=:eib";
    $query=$dbh->prepare($sql4);
    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':sex',$sex,PDO::PARAM_STR);
    $query->bindParam(':age',$age,PDO::PARAM_STR);
    $query->bindParam(':birthdate',$birthdate,PDO::PARAM_STR);
    $query->bindParam(':phone',$phone,PDO::PARAM_STR);
    $query->bindParam(':country',$country,PDO::PARAM_STR);
    $query->bindParam(':district',$district,PDO::PARAM_STR);
    $query->bindParam(':parish',$parish,PDO::PARAM_STR);
    $query->bindParam(':village',$village,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':eib',$eib,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute())
    {
        echo '<script>alert("updated successfuly")</script>';
    }else{
        echo '<script>alert("update failed! try again later")</script>';
    }
}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id4'];
    $sql2="SELECT tblchristian.ID,tblchristian.Name,tblchristian.Sex,tblchristian.Age,tblchristian.Occupation,tblchristian.Status,tblchristian.Birthdate,tblchristian.Country,tblchristian.Phone,tblchristian.Email,tblchristian.Village,tblchristian.District,tblchristian.Parish,tblchristian.Photo from tblchristian   where tblchristian.ID=:eid";
    $query2 = $dbh -> prepare($sql2);
    $query2-> bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();
    $results=$query2->fetchAll(PDO::FETCH_OBJ);
    if($query2->rowCount() > 0)
    {
        foreach($results as $row)
        {
            $_SESSION['editbid']=$row->ID;
            ?>
            <form class="form-sample"  method="post" enctype="multipart/form-data">
               <div class="row">
                <div class="col-md-4">
                    <div class="control-group col-md-12">
                        <label class="control-label" for="basicinput">Christian Image</label>
                        <div class="controls">
                            <img src="christianimages/<?php  echo $row->Photo;?>" width="150" height="150">
                        </div>
                    </div>  
                </div>
                <div class="col-md-8">
                    <div class="row ">
                        <div class="form-group col-md-6">
                            <label class="col-sm-12 ">Name</label>
                            <div class="col-sm-12 ">
                                <input type="text" name="name" id="name" class="form-control" value="<?php  echo $row->Name;?>" required />
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-sm-12 ">Age</label>
                            <div class="col-sm-12 ">
                             <input type="text" name="age" id="age" class="form-control" value="<?php  echo $row->Age;?>" required />
                         </div>
                     </div>
                     <div class="form-group col-md-6 ">
                        <label class="col-sm-12 ">Date Of Birth</label>
                        <div class="col-sm-12 ">
                            <input type="date" name="birthdate" value="<?php  echo $row->Birthdate;?>" class="form-control" required>
                        </div>
                    </div>  
                    <div class="form-group col-md-6 ">
                        <label class="col-sm-12 ">Sex</label>
                        <div class="col-sm-12 ">
                            <select name="sex" class="form-control" required>
                                <option value="<?php  echo $row->Sex;?>"><?php  echo $row->Sex;?></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <hr>
        <p class="card-title"> Address Information </p>
        <div class="row mt-5">
            <div class="form-group col-md-4 ">
                <label class="col-sm-12 pl-0 pr-0 ">Country</label>
                <div class="col-sm-12 pl-0 pr-0">
                   <select name="country" class="form-control" required>
                    <option value="<?php  echo $row->Country;?>"><?php  echo $row->Country;?></option>
                    <option value="Uganda">Uganda</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Canada">Canada</option>
                    <option value="England">England</option>
                    <option value="Spain">Spain</option>
                    <option value="USA">USA</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="India">India</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Austraria">Austraria</option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-4 pl-md-0">
            <label class="col-sm-12 pl-0 pr-0 ">District</label>
            <div class="col-sm-12 pl-0 pr-0">
                <input type="text" name="district" value="<?php  echo $row->District;?>"  class="form-control" required>
            </div>
        </div>

        <div class="form-group col-md-4 pl-md-0">
            <label class="col-sm-12 pl-0 pr-0 ">Parish</label>
            <div class="col-sm-12 pl-0 pr-0">
                <input type="text" name="parish" value="<?php  echo $row->Parish;?>"  class="form-control" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label class="col-sm-12 pl-0 pr-0 ">Village</label>
            <div class="col-sm-12 pl-0 pr-0">
                <input type="form-control"   name="village" value="<?php  echo $row->Village;?>"  class="form-control" >
            </div>
        </div>
        <div class="form-group col-md-4 pl-md-0">
            <label class="col-sm-12 pl-0 pr-0">Phone</label>
            <div class="col-sm-12 pl-0 pr-0">
                <input type="text" name="phone" value="<?php  echo $row->Phone;?>"  class="form-control"  required>
            </div>
        </div>
        <div class="form-group col-md-4 pl-md-0">
            <label class="col-sm-12 pl-0 pr-0">Email</label>
            <div class="col-sm-12 pl-0 pr-0">
                <input type="text" name="email" value="<?php  echo $row->Email;?>"  class="form-control" >
            </div>
        </div>
    </div>
    <button type="submit" name="insert" class="btn btn-primary btn-fw mr-2" style="float: right;">Update</button>
</form>
<?php 
}
} ?>
</div>