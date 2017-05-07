<?php 
require_once('recaptchalib.php');
    if(isset($_POST['submit'])){
        $errors=array();
        //validate name
        if(!empty($_POST['name'])){
            $name=mysqli_real_escape_string($conn,strip_tags($_POST['name']));
        }else{
            $errors[]='name';
        }
        //validate email
        if(isset($_POST['email'])&& filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $e=mysqli_real_escape_string($conn,strip_tags($_POST['email']));
        }else{
            $errors[]='email';
        }
        //validate comment
        if(!empty($_POST['comment'])){
            $comment=mysqli_real_escape_string($conn,$_POST['comment']);
        }else{
            $errors[]='comment';
        }
        //validate capcha
        // if(isset($_POST['captcha'])&&trim($_POST['captcha'])!=$_SESSION['q']['answer']){
        //     $errors[]='wrong';
        // }
        /*sử lý captcha của google*/
         // $privatekey = "6Lf5eAkUAAAAAAit5b0jdiSuSAX7S0uXjtda1P5Q";
         //  $resp = recaptcha_check_answer ($privatekey,
         //                                $_SERVER["REMOTE_ADDR"],
         //                                $_POST["recaptcha_challenge_field"],
         //                                $_POST["recaptcha_response_field"]);

         //  if (!$resp->is_valid) {
         //    // What happens when the CAPTCHA was entered incorrectly
         //   $errors[]='captcha';
         //  }
/*nếu không có lỗi gì xảy ra thì thực hiện chèn vào*/
        if(empty($errors)){
            $q="INSERT INTO comments(page_id,author,email,comment,comment_date) VALUES({$pid},'{$name}','{$e}','{$comment}',NOW())";
            $r=mysqli_query($conn,$q);
            confirm_query($r,$q);
            if(mysqli_affected_rows($conn)==1){
                $messages="<p class='success'>comment được chấp nhận</p>";
            }else{
                $messages="<p class='errors'>lỗi : không thể comment</p>";
            }
        }else{
            $messages="<p class='error'>bạn đã để trống 1 trường</p>";
        }
    }
 ?>
<!-- hiển thị ra nội dung khách cmt, vào bài viết có (theo id) -->
<?php 
    $q=" SELECT comment_id, author, comment, DATE_FORMAT(comment_date,'%b %d,%y') AS date" ;
    $q.=" FROM comments WHERE page_id={$pid} ";
    $r=mysqli_query($conn,$q);
    confirm_query($r,$q);
    if(mysqli_num_rows($r)>0){
        echo "<ol id='disscuss'>";
        while ($cmt=mysqli_fetch_assoc($r)) {
            echo "<li class='comment-wrap'>
                <p class='author'>{$cmt['author']}</p>
                <p class='comment-sec'>{$cmt['comment']}</p>
                <p class='date'>{$cmt['date']}</p>";
            if(is_admin()){
                echo "<a class='remove' id='{$cmt['comment_id']}'>Delete</a>";
            }
            echo"</li>

            ";
        }echo "</ol>";
    }else{
        echo "<i class='success'>Hãy là người cmt đầu tiên</i>";
    }
 ?>
<!-- hiển thị ra nếu có thông báo lỗi, -->
 <?php 
    if(isset($messages)){
        echo $messages;
    }
  ?>
<form id="comment-form" action="" method="post">
    <fieldset>
    	<legend>Leave a comment</legend>
            <div>
            <label for="name">Name: <span class="required">*</span>
                <?php if(isset($errors)&& in_array('name', $errors)){
                    echo "<span class='warning'>hãy điền đầy đủ tên</span>";
                    } ?>
            </label>
            <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo htmlentities($_POST['name'],ENT_COMPAT,'UTf-8') ?>" size="20" maxlength="80" tabindex="1" />
        </div>
        <div>
            <label for="email">Email: <span class="required">*<span>
                 <?php if(isset($errors)&& in_array('email', $errors)){
                    echo "<span class='warning'>hãy điền đầy đủ email</span>";
                    } ?></label>
            <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']) ?>" size="20" maxlength="80" tabindex="2" />
        </div>
        <div>
            <label for="comment">Your Comment: <span class="required">*</span> <?php if(isset($errors)&& in_array('comment', $errors)){
                    echo "<span class='warning'>hãy điền đầy đủ comment</span>";
                    } ?></label>
            <div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?php if(isset($_POST['comment'])) echo htmlentities($_POST['comment']) ?></textarea></div>
        </div>
        <!-- ....................................... -->
        <!-- <div>
            <label for="captcha">Answer question:<?php echo captcha(); ?><span class="required">*</span>
             <?php if(isset($errors)&& in_array('wrong', $errors)){
                        echo "<span class='warning'>hãy điền đầy đủ captcha</span>";
                        } ?>
            </label>
                <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="10" tabindex="4" />
        </div> -->
        <!-- ....................................... -->
        <!-- <div>
            <label>điền vào ô recaptcha
<?php if(isset($errors)&& in_array('captcha', $errors)){
                    echo "<span class='warning'>hãy điền đầy đủ captcha</span>";
                    } ?>
            </label>
            <?php 
            $publickey = "6Lf5eAkUAAAAAJUft4jmMUh4zIOrjUns9ykya95z"; // you got this from the signup page
  echo recaptcha_get_html($publickey); ?>
        </div> -->
    </fieldset>
    <div><input type="submit" name="submit" value="Post Comment" /></div>
</form>