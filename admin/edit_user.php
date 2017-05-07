<?php
    $title = "Manage Users";
    include('../includes/header.php');
    require_once('../includes/mysqli_connect.php');
    require_once('../includes/functions.php');
    include('../includes/sidebar-admin.php');
    // Check to see if has admin access
    admin_access();  
?>
<?php 
	//VALIDATE thông số post lên:
	//nếu có $_GET['uid'] thì giải quyết.
	if(isset($_GET['uid'])&&filter_var($_GET['uid'],FILTER_VALIDATE_INT,array('min_range'=>1))){
		$uid=$_GET['uid'];
		//nếu ấn submit rồi thì giải quyết:
		if(isset($_POST['submit'])){
			// $message="<p class='success'> đã nhấn submit</p>";
			$errors=array();
			$trimed=array_map('trim',$_POST);
			//kiểm tra first_name:
			if(preg_match('/^[\w]{2,10}$/i',$trimed['first_name'])){
				$fn=$trimed['first_name'];
			}else{
				$errors[]='first_name';
			}
			//kiểm tra last_name
			if(preg_match('/^[\w]{2,10}$/i',$trimed['last_name'])){
				$ln=$trimed['last_name'];
			}else{
				$errors[]='last_name';
			}
			//kiểm tra email:
			if(isset($trimed['email'])&&filter_var($trimed['email'],FILTER_VALIDATE_EMAIL)){
				$e=$trimed['email'];
			}else{
				$errors[]='email';
			}
			//kiểm tra user level
			if(isset($trimed['user_level'])&&filter_var($trimed['user_level'],FILTER_VALIDATE_INT,array('min_range'=>1))){
				$ul=$trimed['user_level'];
			}else{
				$errors[]='user_level';
			}
			
			if(empty($errors)){
				//thực hiện kiểm tra email xem có bị trùng hay không?
				 // $message="<p class='success'> không có lỗi</p>";
				$q="SELECT user_id FROM users WHERE email=? AND user_id !=?";
				if($stmt=mysqli_prepare($conn,$q)){
					// $message="<p class='success'> chạy đến đây là đúng</p>";
					mysqli_stmt_bind_param($stmt,'si',$e,$uid);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 0) {
						//trường hợp email không bị trùng:
						$query="UPDATE users SET 
                                    first_name = ?, 
                                    last_name = ?, email = ?, 
                                    user_level =? 
                                    WHERE user_id = ? LIMIT 1";
					if($upd_stmt=mysqli_prepare($conn,$query)){
						mysqli_stmt_bind_param($upd_stmt,'sssii',$fn,$ln,$e,$ul,$uid);
						mysqli_stmt_execute($upd_stmt);
						if(mysqli_stmt_affected_rows($upd_stmt)){
							$message="<p class='success'>Đã cập nhật thành công</p>";
						}else{
							$message="<p class='warning'> không thể cập nhật thành công</p>";
						}
					}
					}
				}//END IF CHECK EMAIL ISSET
			}//END IF CHECK ERRORS
		}//END IF CHECK SUBMIT
	}//END MAIN IF
	else{
		redirect_to('admin/manage_user.php');
	}
 ?>
<?php 
	//lấy ra thông tin user để sử dụng bên dưới
	if($user=fetch_user($_GET['uid'])){
 ?>
<div id="content">
    <h2>Edit user: <?php echo $user['first_name'] ." ". $user['last_name'];?> </h2>
    <!-- có tin nhắn gì thì in ra màn hình -->
    <?php if(isset($message)) {echo $message;}?>
<!-- FORM -->
<form action="" method="post">        
<fieldset>
    <legend>User Info</legend>
    <div>
        <label for="first-name">First Name
            <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='warning'>hãy điền tên vào</p>";?>
        </label> 
        <input type="text" name="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name']); ?>" size="20" maxlength="40" tabindex='1' />
    </div>
    <!-- .............................................. -->
    <div>
        <label for="last-name">Last Name
            <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='warning'>Please enter your last name.</p>";?>
        </label> 
        <input type="text" name="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name']); ?>" size="20" maxlength="40" tabindex='1' />
    </div>
	<!-- .............................................. -->
    <div>
        <label for="email">Email
        <?php if(isset($errors) && in_array('email',$errors)) echo "<p class='warning'>Please enter a valid email.</p>";?>
        </label> 
        <input type="text" name="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="40" tabindex='3' />
    </div>
	<!-- .............................................. -->
    <div>
        <label for="User Level">User Level:
            <?php if(isset($errors) && in_array('user level',$errors)) echo "<p class='warning'>hãy lựa chọn mức User</p>";?>
        </label>
        <select name="user_level">
        <?php
            // Set up array for roles
            $roles = array(1 => 'Registered Member', 2 => 'Moderator', 3 => 'Super Mod', 4 => 'Admin');
            foreach ($roles as $key => $role) {
                echo "<option value='{$key}'";
                    if($key == $user['user_level']) {echo "selected='selected'";}
                echo ">".$role."</option>";
            }
        ?>
        </select>
    </div>
</fieldset>

<div><input type="submit" name="submit" value="Save Changes" /></div>
<?php 
	} else {
	    echo "<p class='error'>No user found.</p>";
	} 
?>
</div><!--end content-->

    
<?php 
    include('../includes/footer.php'); 
?>