<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(isset($_POST['login'])) {
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    
    $sql ="SELECT a.*, b.branch_name FROM tbladmin a LEFT JOIN branches b ON a.branch_id = b.id WHERE a.UserName=:username and a.Password=:password";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['odmsaid']=$result->ID;
            $_SESSION['login']=$result->UserName;
            $_SESSION['names']=$result->FirstName . ' ' . $result->LastName;
            $_SESSION['permission']=$result->AdminName;
            $_SESSION['branch_id']=$result->branch_id;
            $_SESSION['branch_name']=$result->branch_name;
            $get=$result->Status;
        }
        
        $aa= $_SESSION['odmsaid'];
        $sql="SELECT * from tbladmin where ID=:aa";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':aa',$aa,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0) {
            foreach($results as $row) {            
                if($row->Status=="1") { 
                    echo "<script type='text/javascript'> document.location ='../main_dashboard.php'; </script>";        
                } else { 
                    echo "<script>
                    alert('Your account was disabled. Contact Admin');document.location ='login.php';
                    </script>";
                }
            } 
        } 
    } else{
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary text-white py-6 px-8 text-center">
            <h1 class="text-2xl font-bold">Church Management System</h1>
            <p class="mt-2">Login to your account</p>
        </div>
        
        <div class="py-8 px-8">
            <form method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" id="username" required
                            class="focus:ring-primary focus:border-primary block w-full pl-10 py-3 border-gray-300 rounded-md"
                            placeholder="Enter your username">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="focus:ring-primary focus:border-primary block w-full pl-10 py-3 border-gray-300 rounded-md"
                            placeholder="Enter your password">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-blue-700">
                            Forgot your password?
                        </a>
                    </div>
                </div>
                
                <div>
                    <button type="submit" name="login"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            Church Management System
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 py-4 px-8 text-center">
            <p class="text-xs text-gray-500">
                &copy; 2023 Church Management System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>