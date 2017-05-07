<?php
include('../includes/header.php');
include('../includes/sidebar-admin.php');
include('../includes/functions.php');
include('../includes/mysqli_connect.php')
?>
<div id="content">
<?php 
admin_access();
    //kiểm tra xem GET-cid và GET-cat name (trên link) đã tồn tại hay chưa? và xem GET-cid có phải là dạng số nguyên, chạy từ 1 hay không??
    if(isset($_GET['pid'], $_GET['pn'])&&filter_var(
        $_GET['pid'],FILTER_VALIDATE_INT,array('min_range'=>1))){
        //nếu đúng=> thực hiện gán biến và XÓA RECORD
        $pid=$_GET['pid'];
        $page_name=$_GET['pn'];
       
            //sử lý form:
        //nếu đã nhấn submit
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //thì kiểm tra lựa chọn của radio..
            //nếu chọn 'yes':
            if(isset($_POST['delete'])&&($_POST['delete']=='yes')){
                //thì thực hiện viết câu lệnh delete record trùng id với cid

                //chạy câu lệnh
                $q="DELETE FROM pages WHERE page_id = {$pid} LIMIT 1";
                $r=mysqli_query($conn,$q);
                confirm_query($r,$q);
                //kiểm tra trạng thái sau khi chạy lệnh để đua ra thông báo:
                if(mysqli_affected_rows($conn)==1){
                    //hiện ra thông báo xóa thành công
                    $messages= "<p class='success'>Bạn đã xóa thành công page</p>";
                }else{
                    //nếu xảy ra lỗi nào đó, mà không thể xóa
                     $messages= "<p class='warning'>Không thể xóa page, đã xảy ra lỗi</p>";
                }
            }
            //nếu chọn 'no':
            else{
                $messages= "<p class='success'>Bạn đã không xóa page lựa chọn</p>";
            }
        }
    }//nếu pid và page name (trên link) không tồn tại, hoặc không đúng định dạng, chuyển hướng người dùng về trang viewcategory
    else{
        header ('location: view_page.php');
    }
 ?>
	<h2>Delete page: <?php if(isset($page_name))echo $page_name?></h2>
    <form action="" method="post">
    <!-- nếu biến message có thông báo gì thì hiện ra -->
    <?php if(!empty($messages))echo $messages; ?>
        <fieldset>
            <legend>Delete page</legend>
            <div>
                <input type="radio" name="delete" value="no" checked="checked">NO
                <input type="radio" name="delete" value="yes">YES
            </div>
            <div>
                <input type="submit" name="submit" value="delete" onclick="return confirm('Bạn có chắc chắn muốn thực hiện tiếp không??');">
            </div>
        </fieldset>
    </form>
</div><!--end content-->
<?php
include('../includes/sidebar-b.php');
include('../includes/footer.php');
?>    
<!--end aside-->



