
<?php
include('../includes/header.php');
include('../includes/functions.php');
include('../includes/sidebar-admin.php');
include('../includes/mysqli_connect.php')
?>
<?php
admin_access();
//kiểm tra giá trị của pid từ biết $_GET
if(isset($_GET['pid']) && filter_var($_GET['pid'],FILTER_VALIDATE_INT,array('min_range'=>1))){
//nếu tồn tại pid và pid đúng định dạng rồi thì thực hiện validate...
$pid=$_GET['pid'];
//sử lý form add category bên dưới:
    $errors = array() ;/* đặt mảng errors là 1 mảng trống*/
    if($_SERVER['REQUEST_METHOD']=='POST'){
    // khai báo 1 biến mảng "Lỗi"
        $errors=array();/*rỗng chưa có gì*/
        /*---------------------------------------------*/
    // kiểm tra dữ liệu nhập vào phần page name:
        if(empty($_POST['page_name'])){
            $errors[]='page_name';
        }else{
            $page_name=mysqli_real_escape_string($conn,strip_tags($_POST['page_name']));
        };
    // END kiểm tra page_name

        /*---------------------------------------------*/
    // kiểm tra category
        if(isset($_POST['category']) && filter_var($_POST['category'],FILTER_VALIDATE_INT,array('min_range'=>1))){
            $cat_id=$_POST['category'];
        }
        else{
            $errors[]='category';
        };
    // END kiểm tra category

        /*---------------------------------------------*/
    // kiểm tra position
        if(isset($_POST['position']) && filter_var($_POST['position'],FILTER_VALIDATE_INT,array('min_range'=>1))){
            $position=$_POST['position'];
        }
        else{
            $errors[]='position';
        };
    // END kiểm tra position
        /*---------------------------------------------*/

    // kiểm tra content
        if (empty($_POST['content'])) {
            $errors[]='content';
        }else{
            $content=mysqli_real_escape_string($conn,$_POST['content']);
        }
    // END kiểm tra content

        /*NẾU không có lỗi nào thì insert vào CSDL*/
        if(empty($errors)){
        //thực hiện UPDATE
            $q="UPDATE pages SET ";
            $q.=" page_name='{$page_name}', ";
            $q.=" cat_id={$cat_id}, ";
            $q.=" position={$position}, ";
            $q.=" content='{$content}', ";
            $q.=" user_id= 1, ";
            $q.=" post_on= NOW(), ";
            $q.=" cat_id={$cat_id} ";
            $q.=" WHERE page_id={$pid}  LIMIT 1";


            $r=mysqli_query($conn,$q);
            confirm_query($r,$q);

            if(mysqli_affected_rows($conn)==1){
                $messages='<p class="success">Page đã được sửa thành công</p>';
            }else{
                $messages='<p class="warning">Page không sửa thêm vào</p>';
            }
        }else{
            $messages='<p class="warning">hãy điền đầy đủ các trường</p>';
        }

    }/*END MAIN IF*/
}else{
    //nếu không tồn tại pid , thì chuyển hướng tới trang admin
    header('location: view_page.php');
}

?>
<div id="content">
    <?php
        //kéo page trong CSDL để hiển thị ra màn hình trùng id thì show ra, user muốn sửa cái gì thì sửa
    $q="SELECT * FROM pages WHERE page_id={$pid}";
    $r=mysqli_query($conn,$q);
    if(mysqli_num_rows($r)==1){
            //nếu có bài viết trả về
        $page=mysqli_fetch_assoc($r);
    }else{
            //nếu không có bài viết trả về
        $messages="<p class='warning'>bài viết không tồn tại</p>";
    }
    ?>
    <!-- form thêm categories -->
    <?php if(!empty($messages))echo $messages; ?>
    <!-- Form đăng kí pages -->
    <form action="" id="edit_page" method="post">

        <fieldset>
            <legend>Edit Pages</legend>
            <div>
                <label for="page">Page Name: <?php if(isset($page['page_name'])){echo $page['page_name'] ;} ?><span class="required">*</span>
                    <?php
                    if(isset($errors)&&in_array('page_name', $errors)){
                        echo '<p class="warning">Hãy điền đầy đủ page_name</p>';
                    }
                    ?>
                </label>
                <input type="text" name="page_name" id="page_name" value="<?php if(isset($page['page_name']))echo $page['page_name'] ?>" size="20" maxlength="80" tabindex="1" placeholder="thêm page name"/>
            </div>
            <div>
                <label for="category">All categories: <span class="required">*</span>
                    <?php
                    if(isset($errors)&&in_array('category', $errors)){
                        echo '<p class="warning">Hãy chọn một category</p>';
                    }
                    ?>
                </label>
                <select name="category" id="">
                    <option value="">Chọn 1 chuyên mục</option>
                    <?php
                    $q='SELECT cat_id , cat_name FROM categories ORDER BY position ASC';
                    $r=mysqli_query($conn,$q);
                    //kiểm tra dữ SELECT nếu số dòng lớn hơn 0
                    if(mysqli_num_rows($r)>0){
                        while($cats =mysqli_fetch_array($r,MYSQLI_NUM)){
                            echo "<option value='{$cats[0]}'";
                                if(isset($page['cat_id'])&&$page['cat_id']==$cats[0]) echo"selected='selected'";
                            echo ">".$cats[1]."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="position">position <span class="required">*</span>
                    <?php
                    if(isset($errors)&&in_array('position', $errors)){
                        echo '<p class="warning">Hãy chọn 1 position</p>';
                    }
                    ?>
                </label>
                <select name="position" id="">

                    <?php
                    $q="SELECT count(page_id)AS count FROM pages";
                    $r=mysqli_query($conn,$q);

                    if(mysqli_num_rows($r)==1){
                        list($num)=mysqli_fetch_array($r,MYSQLI_NUM);
                        for($i=1;$i<$num+1;$i++){
                            echo "<option value='{$i}'";

                                if(isset($page['position'])&&$page['position']==$i) echo"selected='selected'";
                            echo ">".$i."</option>";
                        }
                    }
                    ?>

                </select>
            </div>
            <div>
                <label for="page-content">Page content: <span class="required">*</span>
                    <?php
                    if(isset($errors)&&in_array('content', $errors)){
                        echo '<p class="warning">Hãy thêm vào content</p>';
                    }
                    ?>
                </label>
                <textarea name="content" id="" cols="50" rows="20"><?php 
                if(isset($page['content'])){echo htmlentities($page['content'],ENT_COMPAT,'UTF-8');}
                 ?></textarea>
            </div>
            <p>
                <input type="submit" name="submit" value="Save">
            </p>
        </fieldset>
    </form>

</div>
<!--end content-->
<?php
include('../includes/footer.php');
?>
<!--end aside-->



