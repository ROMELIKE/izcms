
<?php
include('../includes/header.php');
include('../includes/functions.php');
include('../includes/sidebar-admin.php');
include('../includes/mysqli_connect.php')
?>
<?php 
admin_access();
	//sử lý form add category bên dưới:
$errors = array() ;/* đặt mảng errors là 1 mảng trống*/
if($_SERVER['REQUEST_METHOD']=='POST'){
		// nếu nhập category rồi
	if(!empty($_POST['category'])){
		/*sử dụng mysqli_real_escape_string để tránh SQL injection (hủy đi các dấu '')*/
		/*sử dụng hàm strip_tags để tránh XSS*/
		$cat_name=mysqli_real_escape_string($conn, strip_tags($_POST['category']));
	}
		// nếu chưa nhập ctgory
	else{
		$errors[]='category';
	}

		// kiểm tra nhập với poisiton
	if(isset($_POST['position'])){
		$position=$_POST['position'];
	}
	else{
		$errors[]='position';
	}
		// nếu không có bất cứ lỗi gì xảy ra thì chen vào CSDL
	if(empty($errors)){
		$q="INSERT INTO categories (user_id,cat_name,position) VALUES ('','{$cat_name}',$position)";
		$r=mysqli_query($conn,$q) or die("query{$q} \n<br> mysql error" .mysql_error($conn));

		//kiểm tra kết quả xem đã insert vào được chưa
		if(mysqli_affected_rows($conn)==1){
			echo"<p class='success'>chuyên mục đã được thêm thành công</p>";
		}else{
			echo "<p class='warning'>không thể thêm chuyên mục</p>";
		}
	}
	else{
		echo '<p class="warning">hãy nhập đủ các trường</p>';
	}


}

?>
<div id="content">
	<!-- form thêm categories -->
	<form action="" method="post" id="add_cat">
		<fieldset>
			<legend>Add categories</legend>
			<div>
				<label for="category">Category name: <span class="required">*</span></label>
				<input type="text" name="category" id="category" value="<?php if(isset($_POST['category']))echo strip_tags($_POST['category']) ;?>" size="20" maxlength="80" tabindex="1">
				<?php if(isset($errors)&& in_array('category', $errors))
				{echo '<p class="warning">Bạn chưa điền category</p>';} ?>
			</div>
			<div>
				<label for="position">position: <span class="required">*</span></label>
				<select name="position" id="" tabindex="2">
				<!-- luôn hiện ra số vị trí để người dùng tiện sử dụng, bằng cách đếm số các bản ghi -->
					<?php 
						$q="SELECT count(cat_id)AS count FROM categories";
						$r=mysqli_query($conn,$q);

						if(mysqli_num_rows($r)==1){
							list($num)=mysqli_fetch_array($r,MYSQLI_NUM);
							for($i=1;$i<$num+1;$i++){
								echo "<option value='{$i}'>".$i."</option>";
							}
						}
					 ?>
				</select>
			</div>
		</fieldset>
		<p><input type="submit" name="submit" value="Add category" ></p>
	</form>

</div>
<!--end content-->
<?php
include('../includes/footer.php');
?>    
<!--end aside-->



