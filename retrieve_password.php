<?php
$title="quên mật khẩu";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
include('includes/sidebar-a.php');
include('includes/class.smtp.php');
include "includes/class.phpmailer.php"; 
?>
<?php 
	if(isset($_POST['submit'])){
		$errors=array();
		//Trường hợp: nhấn submit => sử lý forrm
		if(isset($_POST['email'])&&filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			//Trường hợp: email tồn tại
			$e=mysqli_real_escape_string($conn,$_POST['email']);
			$q="SELECT user_id,first_name FROM users WHERE email='{$e}'";
			$r=mysqli_query($conn,$q);
			confirm_query($r,$q);
			if(mysqli_num_rows($r)==1){
				$user=mysqli_fetch_assoc($r);
			}else{
				$errors[]='email-nomatch';
			}
			}else{
				//trường hợp email sai định dạng
				$errors[]="email";
			}
		//nếu không có lỗi nào
		if(empty($errors)){
			//=> tạo ra newpass: cắt từ ký tự thứ 3-10 của 1 chuỗi random, độc nhất đã được md5
			$newpass=substr(md5(uniqid(rand())),3,10);
			//update CSDL
			$q="UPDATE users SET pass='{$newpass}'where user_id={$user['user_id']} LIMIT 1";
			$r=mysqli_query($conn,$q);
			confirm_query($r,$q);
			if(mysqli_affected_rows($conn)==1){
				//gửi mail thông báo đã tạo mk mới
				$nTo = $user['first_name']; //Ten nguoi nhan
				$mTo = $e;   //dia chi nhan mail
				$title = 'quên mật khẩu'; 
				$body="Mật khẩu mới của bạn: <b>{$newpass}</b>";
				$diachicc='';
				//sử dụng hàm sendMail để gửi.
				if($suc=sendMail($title, $body, $nTo, $mTo,$diachicc)){
				 $message="<p class='success'>Mật khẩu mới của bạn đã được gửi tới email.</p>";
				}else{
				     $message="<p class='warning'>Không thể gửi Mail</p>";
				}
			}
			else{
				$message="<p class='warning>không thể tạo được mật khẩu mới</p>";
			}

		}
	}//END main if

 ?>
<div id="content">
<?php echo isset($message)?$message:""; ?>
	<h2>Retrieve Password</h2>
	<form action="" method="post" id="login">
		<fieldset>
			<legend>Retrieve Password</legend>
			<div>
				<label for="email">Email:
				<?php if(isset($errors)&&in_array('email', $errors)){echo"<span class='warning'>Hãy điền chính xác email đã đăng ký</span>";} 
					if(isset($errors)&&in_array('email-nomatch', $errors)){echo"<span class='warning'>Không có email nào như này cả</span>";} 
				?>
				</label>
				<input type="text" name="email" id="email" value="<?php echo isset($_POST['email'])?$_POST['email']:""; ?>" size=40>
			</div>
		</fieldset>
		<div><input type="submit" name="submit" value="Retrieve Password"></div>
	</form>
 </div><!--end content-->   
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

