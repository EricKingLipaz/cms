<?php 
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
$msg = $_GET["msg"];
$sender = $_GET["sender"];
$telform = $_GET["tel"];
echo '<script>alert("sent successfuly")</script>';
echo "<script>window.location.href ='sendsmslist.php'</script>";
?>
