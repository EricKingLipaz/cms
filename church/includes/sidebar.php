 <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <?php
            $aid=$_SESSION['odmsaid'];
            $sql="SELECT * from  tbladmin where ID=:aid";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':aid',$aid,PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            { 
                foreach($results as $row)
                {
                    ?>
                    <a href="#" class="nav-link">
                        <div class="nav-profile-image">
                            <?php 
                            if($row->Photo=="avatar15.jpg")
                            { 
                                ?>
                                <img class="img-avatar" src="assets/img/avatars/avatar15.jpg" alt="">
                                <?php 
                            } else { 
                                ?>
                                <img class="img-avatar" src="profileimages/<?php  echo $row->Photo;?>" alt=""> 
                                <?php 
                            } ?>
                            <span class="login-status busy"></span>
                            <!--change to offline or busy as needed-->
                        </div>
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"><?php  echo $row->FirstName;?> <?php  echo $row->LastName;?></span>
                            <?php
                            $sql="SELECT * from  tblcompany";
                            $query = $dbh -> prepare($sql);
                            $query->bindParam(':aid',$companyname,PDO::PARAM_STR);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            if($query->rowCount() > 0)
                            {
                                foreach($results as $row)
                                {  
                                    ?>
                                    <span class="text-secondary text-small"><?php  echo $row->companyname;?></span>
                                    <?php
                                }
                            }?>
                        </div>
                    </a>
                    <?php 
                }
            } ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Christian management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="registerchristian.php">Manage christians</a></li>
                    <li class="nav-item"> <a class="nav-link" href="attendancy.php">Attendancy</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Finance management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic2">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="bankdeposit.php"> Bank Deposit</a></li>
                     <li class="nav-item"> <a class="nav-link" href="petty_cash.php">Bank Withdraw</a></li>
                    <li class="nav-item"> <a class="nav-link" href="promises.php"> Promises</a></li>
                    <li class="nav-item"> <a class="nav-link" href="add_expense.php">Expenses</a></li>
                    <li class="nav-item"> <a class="nav-link" href="offertory.php"> Offertories</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#companymanagement" aria-expanded="false" aria-controls="general-pages">
                <span class="menu-title">Church management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-bank menu-icon"></i>
            </a>
            <div class="collapse" id="companymanagement">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="churchprofile.php">Church profile </a></li>
                    
                    <li class="nav-item"> <a class="nav-link" href="store.php"> Manage store</a></li>

                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="notifications.php">
                <span class="menu-title">Notifications</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sms" aria-expanded="false" aria-controls="general-pages">
                <span class="menu-title">SMS</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-send menu-icon"></i>
            </a>
            <div class="collapse" id="sms">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="sendsmslist.php">Send SMS </a></li>
                    <!-- 
                    <li class="nav-item"> <a class="nav-link" href="#"> SMS Templates</a></li>
 -->
                </ul>
            </div>
        </li>
        <?php
        $aid=$_SESSION['odmsaid'];
        $sql="SELECT * from  tbladmin where ID=:aid";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':aid',$aid,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        $cnt=1;
        if($query->rowCount() > 0)
        {  
            foreach($results as $row)
            { 
                if($row->AdminName=="Admin"  )
                { 
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages">
                            <span class="menu-title">User management</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                        </a>
                        <div class="collapse" id="general-pages">

                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="userregister.php">Register user </a></li> <?php
                                $aid=$_SESSION['odmsaid'];
                                $sql="SELECT * from  tbladmin where ID=:aid";
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':aid',$aid,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0)
                                {  
                                    foreach($results as $row)
                                    { 
                                        if($row->AdminName=="Admin" )
                                        { 
                                            ?>
                                            <li class="nav-item"> <a class="nav-link" href="user_permission.php"> User permissions</a></li>


                                            <?php 
                                        } 
                                    }
                                } ?> 
                            </ul>

                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#configuration" aria-expanded="false" aria-controls="ui-basic">
                            <span class="menu-title">Settings</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-settings menu-icon"></i>
                        </a>
                        <div class="collapse" id="configuration">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="manage_expense.php">Expenses</a></li>
                                <!-- <li class="nav-item"> <a class="nav-link" href="#">Designation</a></li> -->
                            </ul>
                        </div>
                    </li>
                    <?php 
                } 
            }
        } ?> 
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-database menu-icon"></i>
            </a>
            <div class="collapse" id="reports">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="reports/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"> <a class="nav-link" href="reports/member_reports.php">Member Reports</a></li>
                    <li class="nav-item"> <a class="nav-link" href="reports/financial_reports.php">Financial Reports</a></li>
                    <li class="nav-item"> <a class="nav-link" href="reports/event_reports.php">Event Reports</a></li>
                    <li class="nav-item"> <a class="nav-link" href="reports/resource_reports.php">Resource Reports</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>