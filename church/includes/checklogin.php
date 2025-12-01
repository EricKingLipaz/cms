<?php
function check_login()
{
if(strlen($_SESSION['odmsaid'])==0)
	{
		$host=$_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra="index.php";		
		$_SESSION["id"]="";
		header("Location: http://$host$uri/$extra");
		exit();
	}
}

function is_super_admin()
{
    if(isset($_SESSION['permission']) && $_SESSION['permission'] == 'Superuser') {
        return true;
    }
    return false;
}

function is_branch_admin()
{
    if(isset($_SESSION['permission']) && $_SESSION['permission'] == 'Admin') {
        return true;
    }
    return false;
}

function get_user_branch()
{
    if(isset($_SESSION['branch_id'])) {
        return $_SESSION['branch_id'];
    }
    return null;
}

function can_access_branch($branch_id)
{
    // Super admins can access all branches
    if(is_super_admin()) {
        return true;
    }
    
    // Branch admins can only access their own branch
    if(is_branch_admin() && get_user_branch() == $branch_id) {
        return true;
    }
    
    return false;
}
?>