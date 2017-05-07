<?php
$title="register";
include_once('includes/functions.php');
include_once('includes/mysqli_connect.php');
include_once('includes/header.php');
// include_once('includes/sidebar-a.php');
?>
<?php 
//trường hợp: chưa tồn tại session:
if(!isset($_SESSION['uid'])){
	//sử lý php
	if(isset($_POST['submit'])){
		$errors=array();
		//validate email
		if(isset($_POST['email'])&&filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			$e=mysqli_escape_string($conn,$_POST['email']);
		}else{
			$errors[]='email';
		}
		//validate pass:
		if(isset($_POST['password'])&&preg_match('/^[\w]{4,20}$/',$_POST['password'])){
			$p=mysqli_escape_string($conn,$_POST['password']);
		}else{
			$errors[]='password';
		}
		//nếu không có lỗi nào cả:
		if(empty($errors)){
			//Trường hợp: không có lỗi nào
			$q="SELECT user_id, first_name, last_name, user_level FROM users WHERE (email='{$e}' AND pass='{$p}') AND active IS NULL LIMIT 1";
			$r=mysqli_query($conn,$q);
			confirm_query($r,$q);
			if(mysqli_num_rows($r)==1){
				//Trường hợp: tìm thấy user
				$user=mysqli_fetch_assoc($r);
				//thêm các session
				$_SESSION['uid']=$user['user_id'];
				$_SESSION['first_name']=$user['first_name'];
				$_SESSION['last_name']=$user['last_name'];
				$_SESSION['user_level']=$user['user_level'];
				header('location: index.php');
			}else{
				//Trường hợp: không tìn thấy user
				$message="<p class='warning'>Email hoặc mật khẩu không đúng</p>";
			}
		}//Trường hợp: có lỗi nào
		else{
			$message="<p class='warning'>Đăng nhập thất bại, hãy thử lại</p>";
		}
	}
}else{
	//trường hợp:nếu đã tồn tại session.
	redirect_to();
}
 ?>
<div id="content">
  <h2>Login</h2>
    <?php if(!empty($message)) echo $message; ?>
    <form id="login" action="" method="post">
        <fieldset>
        	<legend>Login</legend>
            	<div>
                    <label for="email">Email:
                        <?php if(isset($errors) && in_array('email',$errors)) echo "<span class='warning'>Please enter your email.</span>";?>
                    </label>
                    <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']);} ?>" size="20" maxlength="80" tabindex="1" />
                </div>
                <div>
                    <label for="pass">Password:
                        <?php if(isset($errors) && in_array('password',$errors)) echo "<span class='warning'>Please enter your password.</span>";?>
                    </label>
             <input type="password" name="password" id="pass" value="" size="20" maxlength="20" tabindex="2" />
                </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Login" /></div>
    </form>
    <p><a href="retrieve_password.php">Forgot password?</a></p>
 </div><!--end content-->   
<?php
include_once('includes/sidebar-b.php');
include_once('includes/footer.php');
?>    
<!--end aside-->

