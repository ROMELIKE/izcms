
<?php
include('../includes/header.php');
include('../includes/functions.php');
include('../includes/sidebar-admin.php');
include('../includes/mysqli_connect.php');
?>
<?php 
admin_access();
/*nếu có cid trên link và cid phải là dạng số INT, bắt đầu từ 1, thì lưu giá trị cid vào biến $cid, còn nếu không chuyển hướng trang tới admin.php*/
	if(isset($_GET['cid'])&& filter_var($_GET['cid'],FILTER_VALIDATE_INT,array('min_range'=>1))){
		$cid=$_GET['cid'];
	}
	else{
		redirect_to('/admin/admin.php');
	}

// -------------------------------------------------
	//sử lý form add category bên dưới:
$errors = array() ;/* đặt mảng errors là 1 mảng trống*/
if($_SERVER['REQUEST_METHOD']=='POST'){
// -------------------------------------------------
		// kiểm tra nhập category 
	if(!empty($_POST['category'])){
		$cat_name=mysqli_real_escape_string($conn, strip_tags($_POST['category']));
	}
		// nếu chưa nhập ctgory
	else{
		$errors[]='category';
	}
// -------------------------------------------------
		// kiểm tra nhập với poisiton
	if(isset($_POST['position'])){
		$position=$_POST['position'];
	}
	else{
		$errors[]='position';
	}
// -------------------------------------------------
		// nếu không có bất cứ lỗi gì xảy ra thì chen vào CSDL
	if(empty($errors)){
		$q="UPDATE categories SET cat_name='{$cat_name}',position=$position WHERE cat_id={$cid} LIMIT 1";
		$r=mysqli_query($conn,$q);
		confirm_query($r,$q);

		//kiểm tra kết quả xem đã insert vào được chưa
		if(mysqli_affected_rows($conn)==1){
			echo"<p class='success'>chuyên mục đã được chỉnh sửa thành công</p>";
		}else{
			echo "<p class='warning'>không thể chỉnh sửa chuyên mục</p>";
		}
	}
	else{
		echo '<p class="warning">hãy nhập đủ các trường</p>';
	}


}

?>
<!--...............................FORM SỬA............................. -->
<div id="content">
	<!-- form thêm categories -->

	<?php 
	//  lấy ra thông tin Category cần sửa
	 $q="SELECT cat_name,position FROM categories WHERE cat_id ={$cid}";
	 $r=mysqli_query($conn,$q);
	 confirm_query($r,$q);
	 if(mysqli_num_rows($r)==1){
	 	// nếu category tồn tại trong database, đưa vào cid , xuất dữ liệu ra ngoài
	 	list($cat_name,$position)=mysqli_fetch_array($r,MYSQL_NUM);
	 }else{
	 	//nếu cid không hợp lệ, sẽ không hiển thị category
	 	$messages="<p class='warning'>Category không tồn tại<p>";
	 }

	 ?>
	 <h2>Edit category:<?php if(isset($cat_name))echo $cat_name ?></h2>
	<form action="" method="post" id="edit_cat">
		<fieldset>
			<legend>Edit categories</legend>
			<div>
				<label for="category">Category name: <span class="required">*</span></label>
				<input type="text" name="category" id="category" value="<?php if(isset($cat_name))echo $cat_name;?>" size="20" maxlength="80" tabindex="1">
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
								echo "<option value='{$i}'";
									if(isset($position)&&($position==$i)) echo"selected='selected'";
								echo ">".$i."</option>";
							}
						}
					 ?>
				</select>
			</div>
		</fieldset>
		<p><input type="submit" name="submit" value="Edit category" ></p>
	</form>

</div>
<!--end content-->
<?php
include('../includes/sidebar-b.php');
include('../includes/footer.php');
?>    
<!--end aside-->



