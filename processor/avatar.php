<?php 
session_start();
include('../includes/mysqli_connect.php');
include('../includes/functions.php');?>
<?php
	if(isset($_POST['upload'])) {
		if(isset($_FILES['image'])) {
			//tạo array trống cho biến errors
				$errors=array();
				//kiểm tra xem ảnh có đúng định dạng hay không?
				$allowed=array('image/jpeg','image/jpg','image/png','image/x-png');
				if(in_array(strtolower($_FILES['image']['type']), $allowed)){
					//nếu có trong định dạng cho phép, tách lấy phần mở rộng
					// $ext=end(explode('.',$_FILES['image']['name']));
					// $renamed=uniqid(rand(),true).'.'.$ext;
					if(!move_uploaded_file($_FILES['image']['tmp_name'],"../uploads/images/".$_FILES['image']['name'])){
						$message='wrong-server';
					}else{
						echo "thành công";
					}
				}else{
					//nếu sai định dạng
					$message='wrong-format';
				}
		} // END isset $_FILES

		 // Check for an error
    if($_FILES['image']['error'] > 0) {
        $message = "<p class='warning'>không thể upload được file vì: <strong>";
        // Print the message based on the error
        switch ($_FILES['image']['error']) {
            case 1:
                $message .= "chưa hỗ trợ upload trong file php.ini";
                break;
            case 2:
                $message .= "vượt quá giới hạn cho phép";
                break;
            case 3:
                $message .= "Lỗi chế độ phân quyền";
                break;
            case 4:
                $message .= "Không tìm thấy file upload";
                break;
            case 6:
                $message.= "Không tìm thấy thư mục tạm thời";
                break;
            case 7:
                $message.= "Không thể ghi vào đĩa";
                break;
            case 8:
                $message .= "File tải lên đã bị dừng";
                break;
            default:
                $message.= "Đã xảy ra lỗi hệ thống";
                break;
        } // END of switch
        $message .= "</strong></p>";
    } // END of error IF

    // Xoa file da duoc upload va ton tai trong thu muc tam
    if(isset($_FILES['image']['tmp_name']) && is_file($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
    	unlink($_FILES['image']['tmp_name']);
    }

	} // END main if

	if(empty($errors)) {
		// Update cSDL
		$q = "UPDATE users SET avatar = '{$_FILES['image']['name']}' WHERE user_id = {$_SESSION['uid']} LIMIT 1";
		$r = mysqli_query($conn, $q); confirm_query($r, $q);

		if(mysqli_affected_rows($conn) > 0) {
			// Update thanh cong, chuyen huong nguoi dung ve trang edit_profile
			redirect_to('edit_profile.php');
		}
	}
	//report_error($errors);
	if(!empty($message)) echo $message; 
?>


<!-- quy trình UPload file (theo IZ)
	1.kiểm tra đã ấn submit chưa?
		1.1: kiểm tra đã isset ảnh chưa?
			1.1.1: nếu ảnh thuộc định dạng thì move file upload
			1.1.2: hiển thị nếu xảy ra lỗi.
		1.2: kiểm tra xem có lỗi trong $_FILES không?
		1.3: Xóa đường dẫn tạm thời đi
		1.4: nếu không có lỗi gì thì thực hiện update CSDL
		
 -->










