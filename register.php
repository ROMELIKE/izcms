<?php
$title="register";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
include('includes/sidebar-a.php');
include('includes/class.smtp.php');
include "includes/class.phpmailer.php"; 

?>
<div id="content">
     <?php 
        //xử lý form
        if(isset($_POST['submit'])){
            $errors=array();
            //mặc định cho các trường nhập liệu là false
            $fn=$ln=$e=$p=FALSE;

        //kiểm tra định dạng tên nhập vào.
            if(preg_match('/^[\w\'.-]{2,20}$/',trim($_POST['first_name']))){
                //lưu $fn= giá trị vừa nhập sau khi đã loại bỏ dấu cách ở 2 đầu, và chống sql-injection
                $fn=mysqli_real_escape_string($conn,trim($_POST['first_name']));
            }else{
                $errors[]='first name';
            }
        //kiểm tra phần last-name y chang như vậy:
            if(preg_match('/^[\w\'.-]{2,20}$/',trim($_POST['last_name']))){
                $ln=mysqli_real_escape_string($conn,trim($_POST['last_name']));
            }else{
                $errors[]='last name';
            }
        //kiểm tra phần email
            if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                $e=mysqli_real_escape_string($conn,$_POST['email']);
            }else{
                $errors[]='email';
            }
        //kiểm tra phần password
            if(preg_match('/^[\w\'.-]{4,20}$/',trim($_POST['password1']))){
                //nếu vượt qua biểu thức chính quy,kiểm tra tiếp xem có khớp nhau k?
                if($_POST['password1']==$_POST['password2']){
                    $p=mysqli_real_escape_string($conn,trim($_POST['password1']));
                }else{
                    $errors[]='no match';
                }

            }else{
                $errors[]='password';
            }
        //nếu không có lỗi nào:
            if(empty($errors)){
               $q="SELECT user_id FROM users WHERE email='{$e}'";
               $r=mysqli_query($conn,$q);
               confirm_query($r,$q);
               if(mysqli_num_rows($r)==0){
                    //trường hợp: email không bị trùng
                     /* => tạo ra đoạn mã active ngẫu nhiên*/
                    $a=md5(uniqid(rand(),true));
                    /* => thêm vào CSDL*/
                    $q="INSERT INTO users (first_name,last_name,email,pass,active,registration_date) VALUES('{$fn}','{$ln}','{$e}','{$p}','{$a}',NOW())";
                    $r=mysqli_query($conn,$q);
                    confirm_query($r,$q);
                    // --------------------------
                    if(mysqli_affected_rows($conn)==1){
                        //Trường hợp: thêm vào thành công => gửi mail thông báo active
                       //khai báo các tham số cần thiết để gửi mail
                       $nTo = $fn; //Ten nguoi nhan
                       $mTo = $e;   //dia chi nhan mail
                       $title = 'Kích hoạt tài khoản'; 
                       $body="cảm ơn <b>{$nTo}</b> <b>{$ln}</b> đã đăng kí, 1 active code đã được gửi tới email, vào lấy cmm đi \n\n";
                       $body .= BASE_URL."admin/activate.php?x=".urldecode($e)."&y={$a}";
                       $diachicc='';
                       //sử dụng hàm sendMail để gửi.
                       if($suc=sendMail($title, $body, $nTo, $mTo,$diachicc)){
                        $message="<p class='success'>Bạn đã đăng ký thành công, hãy xác nhận trong Email.</p>";
                       }else{
                            $message="<p class='warning'>Không thể gửi Mail</p>";
                       }

                    }/*Trường hợp: không thể them vào trong CSDL => thông báo ra ngoài*/
                    else{
                        $message="<p class='warning'>đăng ký thất bại,lỗi hệ thống</p>";
                    }
               }//trường hợp: email bị trùng
               else{
                $message="<p class='warning'>địa chỉ mail đã bị trùng, hãy lựa chọn địa chỉ khác</p>";
               }
            }/*trường hợp: vẫn còn lỗi(để trống trường)*/
            else{
                $message="<p class='warning'>Hãy điền đầy đủ các trường</p>";
            }
        }/*END MAIN IF*/
     ?> 
     <!-- phần hiển thị form -->
     
     <h2 class="text-uppercase">Register</h2>
     <?php if(!empty($message)){echo $message;} ?>
     <form action="register.php" method="post">
         <fieldset>
                <legend>Register</legend>
                 <!-- ................................... -->
                 <div>
                     <label for="First Name">First Name <span class="required">*</span>
                    <?php 
                        if(isset($errors)&&in_array('first name', $errors)){
                            echo "<span class='warning'>dmm nhập họ ngu VKL</span>";
                        }
                     ?>
                     </label> 
                   <input type="text" name="first_name" size="20" maxlength="20" value="<?php if(isset($_POST['first_name'])){echo "{$_POST['first_name']}";} ?>" tabindex='1' />
                 </div>
                  <!-- ................................... -->
                 <div>
                     <label for="Last Name">Last Name <span class="required">*</span>
                     <?php 
                        if(isset($errors)&&in_array('last name', $errors)){
                             echo "<span class='warning'>dmm nhập tên ngu</span>";
                        }
                     ?></label> 
                   <input type="text" name="last_name" size="20" maxlength="40" value="<?php if(isset($_POST['last_name'])){echo "{$_POST['last_name']}";} ?>" tabindex='2' />
                 </div>
                  <!-- ................................... -->
                 <div>
                     <label for="email">Email <span class="required">*</span>
                     <?php 
                        if(isset($errors)&&in_array('email', $errors)){
                             echo "<span class='warning'>dmm nhập email ngu</span>";
                        }
                     ?></label> 
                   <input type="text" name="email" id="email" size="20" maxlength="80" value="<?php if(isset($_POST['email'])){echo "{$_POST['email']}";} ?>" tabindex='3' />
                   <span id="available"></span>
                 </div>
                  <!-- ................................... -->
                 <div>
                     <label for="password">Password <span class="required">*</span>
                     <?php 
                        if(isset($errors)&&in_array('password', $errors)){
                            echo "<span class='warning'>dmm nhập pass ngu,sai định dạng rồi</span>";
                        }
                     ?></label> 
                   <input type="password" name="password1" size="20" maxlength="20" value="" tabindex='4' id='pass1' />
                 </div>
                 <!-- ................................... -->
                 <div>
                     <label for="email">Confirm Password <span class="required">*</span> 
                     <?php 
                        if(isset($errors)&&in_array('no match', $errors)){
                            echo "<span class='warning'>mật khẩu đéo khớp nhé</span>";
                        }
                      ?>
                     </label> 
                     
                   <input type="password" id='pass2' name="password2" size="20" maxlength="20" value="" tabindex='5' />
                   <span id="pass-errors"></span>
                 </div>
                  <!-- ................................... -->
         </fieldset>
         <p><input type="submit" name="submit" value="Register" /></p>
     </form>

</div>
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

