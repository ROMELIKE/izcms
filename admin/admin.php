<?php
include('../includes/functions.php');
include('../includes/header.php');
include('../includes/sidebar-admin.php');
include('../includes/mysqli_connect.php')
?>
<?php 
	admin_access();
 ?>
<div id="content">

    <h2>Welcome To izCMS Admin panel</h2>
    <div>
        <p>
           chào mưng bạn đến với trang quản lý của izCMS. bạn có thể thêm , xóa và chỉnh sửa bài viết ở đây
        </p>
    </div>

</div>
<!--end content-->
<?php
include('../includes/sidebar-b.php');
include('../includes/footer.php');
?>    
<!--end aside-->



