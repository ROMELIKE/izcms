    <div id="content-container">
        <div id="section-navigation">
            <ul class="navi">
                <?php 

                //kiểm tra nếu tồn tại GET_cid và GET_cid dang số nguyên, thì gán cho biết cid=GET_cid
                if(isset($_GET['cid'])&& filter_var($_GET['cid'],FILTER_VALIDATE_INT,array('min_range'=>1))){
                    $cid=$_GET['cid'];
                    $pid=NULL;
                }elseif(isset($_GET['pid'])&& filter_var($_GET['pid'],FILTER_VALIDATE_INT,array('min_range'=>1)))
                {
                    $pid=$_GET['pid'];
                    $cid=NULL;
                }else{
                    $cid=NULL;
                    $pid=NULL;
                }

                /*câu lệnh truy xuất category*/
                //in ra tẩt các các category có trong web
                $q="SELECT cat_name , cat_id FROM categories ORDER BY position ASC";
                $r=mysqli_query($conn,$q);
                 confirm_query($r,$q);
                while ($cats=mysqli_fetch_array($r,MYSQLI_ASSOC)) {
                    echo"<li><a href='index.php?cid={$cats['cat_id']}'";
                        if($cats['cat_id']==$cid)echo'class="selected"';
                    echo ">".$cats['cat_name'].'</a>';

                        /*câu lệnh truy xuất pages*/
                        //in ra tất cả các page có trong từng category 1 (điều kiện trường cat_id của page phải == $cats['cat_id'] ở bên trên)
                        $q1="SELECT * FROM pages WHERE cat_id = {$cats['cat_id']} ORDER BY position ASC LIMIT 0,4";
                        $r1=mysqli_query($conn,$q1);
                            confirm_query($r,$q);
                            echo '<ul class="pages">';
                                    //lấy pages từ CSDL
                            while ($pages=mysqli_fetch_array($r1,MYSQLI_ASSOC)){
                                echo "<li><a href='index.php?pid={$pages['page_id']}'";
                                    if($pages['page_id']==$pid)echo"class=selected";
                                echo ">".$pages['page_name']."</a></li> </br>";
                            }

                        echo "</ul>";
                    echo "</li>";
                }

                ?>
            </ul>
        </div>
        <!--end section-navigation-->