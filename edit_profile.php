<?php
$title="edit_profile";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
// include('includes/sidebar-a.php');
// kiểm tra đã login hay chưa?
	is_logged_in();
?>
<?php 
		if(isset($_POST['submit'])){
		$errors=array();
		//trim tất cả dữ liệu được post lên.
		$trimed=array_map('trim',$_POST);
		//bh tất cả post đều lưu trong biến $trimed[]
		//VALIDATE
		if(preg_match('/^[\w]{2,10}$/i',$trimed['first_name'])){
			$fn=$trimed['first_name'];
		}else{
			$errors[]='first_name';
		}
		if(preg_match('/^[\w]{2,10}$/i',$trimed['last_name'])){
			$ln=$trimed['last_name'];
		}else{
			$errors[]='last_name';
		}
		if(filter_var($trimed['email'],FILTER_VALIDATE_EMAIL)){
			$e=$trimed['email'];
		}else{
			$errors[]='email';
		}
		$web=(!empty($trimed['website']))?$trimed['website']:NULL;
		$yahoo=(!empty($trimed['yahoo']))?$trimed['yahoo']:NULL;
		$bio=(!empty($trimed['bio']))?$trimed['bio']:NULL;
		//kiểm tra xem có lỗi nào không?
		if(empty($errors)){
			$q="UPDATE users SET 
				first_name = ?,
				last_name= ?,
				email= ?,
				website= ?,
				yahoo= ?,
				bio= ?
				WHERE user_id= ?
				LIMIT 1;
			";
			$stmt=mysqli_prepare($conn,$q);
			mysqli_stmt_bind_param($stmt,'ssssssi',$fn,$ln,$e,$web,$yahoo,$bio,$_SESSION['uid']);
			mysqli_stmt_execute($stmt) or die("MySQL Erro: $q".mysqli_stmt_error($stmt));
			if(mysqli_stmt_affected_rows($stmt)>0){
				$message="<p class='success'>update thành công</p>";

			}else{
				$message="<p class='warning'>update không thành công, có lỗi hệ thống xảy ra</p>";
			}
		}else{
			echo "<pre>";
				print_r($errors);
			echo "</pre>";
		}
	}
 ?>
<div id="content">
<?php 
	echo(!empty($message))?$message:'';
 ?>
<h2>Chỉnh sửa cá nhân</h2>
<?php 
	//truy xuất CSDL:
	$user=fetch_user($_SESSION['uid']);
 ?>
	<form action="processor/avatar.php" enctype="multipart/form-data" method="post">
		<fieldset>
			<legend>Avatar</legend>
			<div>
				<img src="uploads/images/<?php echo is_null($user['avatar'])?'no_avatar.jpg' : $user['avatar']; ?>" class="avatar">
				<p>chọn 1 ảnh dạng jpg,png nhỏ hơn 512kb để thay thế</p>
				<input type="hidden" name="MAX_FILE_SIZE" value="524288">
				<input type="file" name="image">
				<p>
					<input type="submit" name="upload" value="change" class="change">
				</p>
			</div>
		</fieldset>
	</form>
	<!-- ................................................................ -->
	<form action="" method="post">        
	    <fieldset>
	        <legend>User Info</legend>
	        <div>
	            <label for="first-name">First Name
	                <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='warning'>Please enter your first name.</p>";?>
	            </label> 
	            <input type="text" name="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name']); ?>" size="20" maxlength="40" tabindex='1' />
	        </div>
	        
	        <div>
	            <label for="last-name">Last Name
	                <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='warning'>Please enter your last name.</p>";?>
	            </label> 
	            <input type="text" name="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name']); ?>" size="20" maxlength="40" tabindex='1' />
	        </div>
	  </fieldset>
	  <!-- ................................................................ -->
	  <fieldset>
	        <legend>Contact Info</legend>
	        <div>
	            <label for="email">Email
	            <?php if(isset($errors) && in_array('email',$errors)) echo "<p class='warning'>Please enter a valid email.</p>";?>
	            </label> 
	            <input type="text" name="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="40" tabindex='3' />
	        </div>
	        
	        <div>
	            <label for="website">Website</label> 
	            <input type="text" name="website" value="<?php echo (is_null($user['website'])) ? '' : strip_tags($user['website']); ?>" size="20" maxlength="40" tabindex='4' />
	        </div>
	        
	        <div>
	            <label for="yahoo">Yahoo Messenger</label> 
	            <input type="text" name="yahoo" value="<?php echo (is_null($user['yahoo'])) ? '' : strip_tags($user['yahoo']); ?>" size="20" maxlength="40" tabindex='5' />
	        </div>
	  </fieldset> 
	  <!-- ................................................................ -->
	  <fieldset>
	        <legend>About Yourself</legend>
	        <div>
	            <textarea cols="50" rows="20" name="bio"><?php echo (is_null($user['bio'])) ? '' : htmlentities($user['bio'], ENT_COMPAT, 'UTF-8'); ?></textarea>
	        </div>
	  </fieldset>   
	 <div><input type="submit" name="submit" value="Save Changes" /></div>
	</form>
	<!-- ................................................................ -->
 </div><!--end content-->   
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

