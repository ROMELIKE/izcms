<?php
$title="Contact Us";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
include('includes/sidebar-a.php');
?>
<div id="content">
    <?php 
    //sử lý form
        if(isset($_POST['submit'])){
            $errors=array();
            //kiểm tra trường nhập tên:
             if(empty($_POST['name'])) {
                $errors[] = 'name';
            }
            
            // Kiem tra xem email co hop le
            if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $_POST['email'])) {
                $errors[] = 'email';
            }
            
            // Kiem tra noi dung tin nhan
            if(empty($_POST['comment'])) {
                $errors[] = 'comment';
            }

            //kiểm tra có lỗi hay không? nếu không có gửi email
            if(empty($errors)){
            	$body = "Name: {$_POST['name']} \n\n Comment:\n ". strip_tags($_POST['comment']);
            	$body=wordwrap($body,70);
            	if(mail('romelikeyou@gmail.com','thử nghiệm test',$body,'FROM:localhost@localhost')){
            		echo "<p class='success'>đã gửi được mail<p>";
            		//sau khi gửi xong, reset form
            		$_POST=array();
            	}else{
            		echo "<p class='warning'>meo mủng như lồn,đéo gửi được<p>";
            	}


            }else{
            	echo "<p class='warning'>nhập nhủng như cặc<p>";
            }
            
        }
     ?>
     <form id="contact" action="" method="post">
    <fieldset>
        <legend>Contact</legend>
        <!-- tên -->
            <div>
                <label for="Name">Your Name: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('name',$errors)) {
                            echo "<span class='warning'>Đéo nhập tên à ?</span>";
                            }
                    ?> 
                </label>
                <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="1" />
            </div>
            <!-- email -->
            <div>
                <label for="email">Email: <span class="required">*</span>
              <?php 
                        if(isset($errors) && in_array('email',$errors)) {
                            echo "<span class='warning'>Nhập mail ngu như chó !!!</span>";
                            }
                    ?> 
                </label>
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="2" />
            </div>
            <!-- tin nhắn -->
            <div>
                <label for="comment">Your Message: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('comment',$errors)) {
                            echo "<span class='warning'>Viết hộ 1 chữ vào.</span>";
                            }
                    ?> 
                </label>
                <div id="comment"><textarea name="comment" rows="10" cols="45" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
            </div>
    </fieldset>
    <div><input type="submit" name="submit" value="Send Email" /></div>
</form>
</div>
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

