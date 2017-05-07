<?php
    $title = "Delete Users";
    include('../includes/header.php');
    require_once('../includes/mysqli_connect.php');
    require_once('../includes/functions.php');
    include('../includes/sidebar-admin.php');
    // Check to see if has admin access
    admin_access();  
?>
        <?php 
            if(isset($_GET['uid'])&&filter_var($_GET['uid'],FILTER_VALIDATE_INT,array('mỉn_range'=>1))){
                $uid=$_GET['uid'];
                if(isset($_POST['submit'])){
                    if(isset($_POST['delete'])&&$_POST['delete']=='yes'){
                        //thực hiện xóa user trong CSDL...
                        $q="DELETE FROM users WHERE user_id={$uid} LIMIT 1";
                        $r=mysqli_query($conn,$q);confirm_query($r,$q);
                        if(mysqli_affected_rows($conn)>0){
                            redirect_to('admin/manage_users.php');
                        }
                    }else{
                        $message="<p class='success'>bạn đã chọn không xóa <a href='manage_users.php'>quay lại</a></p>";
                    }
                }
            }else{
                //nếu uid không tồn tại, chuyển hướng về manage
                redirect_to('admin/manage_users.php');
            }
         ?>
    <div id="content">
    	<h2> Delete user</h2>
    	<?php if(!empty($message)) echo $message; ?>
    	   <form action="" method="post">
    	   <fieldset>
    			<legend>Delete user</legend>
    				<label for="delete">Are you sure?</label>
    				<div>
    					<input type="radio" name="delete" value="no" checked="checked" /> No
    					<input type="radio" name="delete" value="yes" /> Yes
    				</div>
    				<div><input type="submit" name="submit" value="Delete" /></div>
    		</fieldset>
	   </form>
    </div><!--end content-->

<?php include('../includes/footer.php'); ?>
