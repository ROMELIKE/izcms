<?php include('mysqli_connect.php');?>
<?php include('functions.php');?>
<?php
   if(isset($_POST['cmt_id'])&&filter_var($_POST['cmt_id'],FILTER_VALIDATE_INT)){
    $cmid=$_POST['cmt_id'];
    $q="DELETE FROM comments WHERE comment_id={$cmid} LIMIT 1";
    $r=mysqli_query($conn,$q);
    if(mysqli_affected_rows($conn)==1){
        echo "thành công";
    }else{
        echo"không thành công";
    }
   }else{
        echo "không có tham số: <b>\$_POST['cmt_id']</b> ";
   }
?>