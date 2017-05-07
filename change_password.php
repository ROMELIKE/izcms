<?php
$title="đổi mật khẩu";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
include('includes/sidebar-a.php');
?>
<?php 
//trường hợp: chưa đăng nhập thì không thể đổi pass.
	is_logged_in();
	if(isset($_POST['submit'])){
		//nếu đã nhấn submit thì bắt đầu sử lý form
		$errors=array();
		//kiểm tra password tồn tại và đúng định dạng hay không?
		if(isset($_POST['current_password'])&&preg_match('/^[\w\'.-]{4,20}$/',trim($_POST['current_password']))){
		//nếu vượt qua truy vấn, mật khẩu nhập vào có tồn tại trong CSDL hay không?
			$p=mysqli_real_escape_string($conn,trim($_POST['current_password']));
			$q="SELECT first_name FROM users WHERE pass='{$p}' AND user_id={$_SESSION['uid']}";
			$r=mysqli_query($conn,$q);
			confirm_query($r,$q);
		//nếu có giá trị trả về =1
			if(mysqli_num_rows($r)==1){
				//kiểm tra: newpassword mới tồn tại hay chưa:
				if(isset($_POST['password1'])&&preg_match('/^[\w\'.-]{4,20}$/',trim($_POST['password1']))){
					//sử lý:kiểm tra 2 pass có khớp nhau hay không?
					if($_POST['password1']==$_POST['password2']){
						//xử lý: UPDATE CSDL
						$newpass=mysqli_real_escape_string($conn,trim($_POST['password1']));
						$q="UPDATE users SET pass='{$newpass}' WHERE user_id={$_SESSION['uid']} LIMIT 1";
						$r=mysqli_query($conn,$q);
						confirm_query($r,$q);
						if(mysqli_affected_rows($conn)==1){
							$message="<p class='success'>Đã thay đổi mật khẩu thành công </p>";
						}else{
							$message="<p class='success'>Không thể thay đổi mật khẩu</p>";
						}
					}
					//nếu 2 pass mới không khớp:
					else{
						$errors[]='wrong-incorect-newpass-renewpass';
					}
				}//nếu newpassword chưa có hoặc không đúng:
				else{
					$errors[]='wrong-empty-newpass';
				}

			}else{
				//in ra thông báo: mật khẩu không chính xác
				$errors[]="wrong-incorect-CSDL";
			}
		}
		//nếu không đúng định dạng hiện thông báo?
		else{
			$errors[]="wrong-format";
		}
	}
 ?>
<div id="content">
<?php if(!empty($message)) echo $message; ?>
	<form action="" method="post">
		<fieldset>
			<legend>Change password</legend>
			<div>
				<label for="current password">Mật khẩu hiện tại
					<?php if(isset($errors) && in_array('wrong-incorect-CSDL',$errors)) echo "<span class='warning'>Mật khẩu không khớp.</span>";
						if(isset($errors) && in_array('wrong-format',$errors)) echo "<span class='warning'>Mật khẩu không đúng định dạng.</span>";
					?>
				</label>
				<input type="password" name="current_password" value="" size="20" maxlength="40" tabindex="1">
			</div>
			<div>
				<label for="new password">Mật khẩu hiện mới
					<?php if(isset($errors) && in_array('wrong-empty-newpass',$errors)) echo "<span class='warning'>Hãy điền mật khẩu mới, nhiều hơn 4 ký tự</span>";?>
				</label>
				<input type="password" name="password1" value="" size="20" maxlength="40" tabindex="2">
			</div>
			<div>
				<label for="confirm password">Nhập lại
					<?php if(isset($errors) && in_array('wrong-incorect-newpass-renewpass',$errors)) echo "<span class='warning'>2 mật khẩu không khớp nhau</span>";?>
				</label>
				<input type="password" name="password2" value="" size="20" maxlength="40" tabindex="3">
			</div>

		</fieldset>
		<div><input type="submit" name="submit" value="Thay đổi mật khẩu" tabindex="4"></div>
	</form>
</div><!--end content-->   
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

