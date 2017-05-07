 <?php
$title="register";
include('../includes/functions.php');
include('../includes/mysqli_connect.php');
include('../includes/header.php');
// include('../includes/sidebar-a.php');
// include('../class.smtp.php');
// include('../class.phpmailer.php'); 
?>
<div id="content">
	<?php 
	if(isset($_GET['x'],$_GET['y'])&&filter_var($_GET['x'],FILTER_VALIDATE_EMAIL)&&strlen($_GET['y'])==32){
		$e=mysqli_real_escape_string($conn,$_GET['x']);
		$a=mysqli_real_escape_string($conn,$_GET['y']);
		$q="UPDATE users SET active=NULL WHERE email='{$e}' AND active='{$a}' LIMIT 1";
		$r=mysqli_query($conn,$q);
		confirm_query($r,$q);
		if(mysqli_affected_rows($conn)==1){
			$message="<p class='success'>Bạn đã kích hoạt thành công tài khoản.<br><button><a href='".BASE_URL."login.php'>Đăng nhập</a></button></p>";
		}else{
			$message="<p class='warning'>Kích hoạt không thành công, vui lòng thử lại</p>";
		}
	}else{
		redirect_to('index.php');
	}
 ?>
 <?php 
 	if(isset($message)){
 		echo "{$message}";
 	}
  ?>
</div>

<?php
include('../includes/sidebar-b.php');
include('../includes/footer.php');
?>    
<!--end aside-->

