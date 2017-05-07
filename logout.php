<?php
$title="Logout";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
// include('includes/sidebar-a.php');
?>
<?php 
	if(!isset($_SESSION['first_name'])){
		header('location:index.php');
	}else{
		$_SESSION=array();
		session_destroy();
		setcookie(session_name(),'',time()-36000);
		$message="<span class='success'>Bạn đã đăng xuất thành công</span>";
	}

 ?>
<div id="content">
	<?php if(isset($message)){echo $message;} ?>
 </div><!--end content-->   
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

