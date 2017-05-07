 <div id="footer">
        <ul class="footer-links">
            <?php 
             if(isset($_SESSION['user_level'])) {
                 // Neu co SESSION
                 switch($_SESSION['user_level']) {
                     case 0: // Registered users access
                     echo "
                         <li><a href='".BASE_URL."edit_profile.php'>Cá nhân</a></li>
                         <li><a href='".BASE_URL."change_password.php'>Thay đổi password</a></li>
                         <li><a href='#'>Đăng xuất</a></li>
                         <li><a href='".BASE_URL."logout.php'>Log Out</a></li>
                     ";
                     break;
                     
                     case 2: // Admin access
                     echo "
                         <li><a href='".BASE_URL."edit_profile.php'>Cá nhân</a></li>
                         <li><a href='".BASE_URL."change_password.php'>Thay đổi password</a></li>
                         <li><a href='#'>Thông báo</a></li>
                         <li><a href='".BASE_URL."admin/admin.php'>Quản lý (admin)</a></li>
                         <li><a href='".BASE_URL."logout.php'>Đăng xuất</a></li>
                     ";
                     break;
                     
                     default:
                     echo "
                         <li><a href='".BASE_URL."register.php'>Đăng ký</a></li>
                         <li><a href='".BASE_URL."login.php'>Đăng nhập</a></li>
                     ";
                     break;
                     
                 }
                 
             } else {
                 // Neu khong co $_SESSION
                 echo "
                         <li><a href='index.php'>Trang chủ</a></li>
                         <li><a href='register.php'>Đăng ký</a></li>
                         <li><a href='login.php'>Đăng nhập</a></li>
                     ";
             }
            ?>
        </ul>
    </div>
    <!--end footer-->
</div>
<!-- end content-container-->
</div>
<!--end container-->
</body>

</html>