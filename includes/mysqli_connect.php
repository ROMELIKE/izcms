<?php 
	// kết nối với csdl
	$conn= mysqli_connect('localhost','root','','izcms');
	//nếu kết nối không thành công thì báo lỗi ra trình duyệt
	if(!$conn){
		trigger_error("không thể kết nối dtb".mysqli_connect_error());
	}else{
		//đặt phương thức kết nối là utf8
		mysqli_set_charset($conn,'unicode');
	}
 ?>