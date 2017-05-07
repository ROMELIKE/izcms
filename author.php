<?php
$title="author";
include('includes/functions.php');
include('includes/mysqli_connect.php');
include('includes/header.php');
include('includes/sidebar-a.php');
?>
<div id="content">
<?php 
    //phân trang 
        //tìm total_record
        $q="SELECT COUNT(p.page_id) AS total FROM pages AS p INNER JOIN users AS u USING(user_id) WHERE user_id={$_GET['aid']}";
        $r=mysqli_query($conn,$q);
        confirm_query($r,$q);
        if(mysqli_num_rows($r)>0){
            $row=mysqli_fetch_assoc($r);
                $total_record=$row['total'];
        }
        //tìm limit
        $limit=10;
        //tìm current_page
        $current_page=isset($_GET['page'])&& filter_var($_GET['page'],FILTER_VALIDATE_INT,array('min_range'=>1))?$_GET['page']:1;
   
        //tìm total_page
        $total_page=ceil($total_record/$limit);
        //giới hạn cho current page
        if($current_page>$total_page){
            $current_page=$total_page;
        }elseif($current_page<1){
            $current_page=1;
        }
        //tìm start
        $start= ($current_page-1)*$limit;
        


 ?>
    <?php 
    // phần sử lý code php
        if($aid=validate_id($_GET['aid'])){
            //nếu author id tồn tại,thì sẽ truy vấn CSDL
            $q=" SELECT p.page_id,p.page_name,p.content,u.user_id," ;
            $q.=" DATE_FORMAT(p.post_on,'%b %d,%y') AS date ,";
            $q.=" CONCAT_WS(' ',u.first_name,u.last_name) AS name ";
            $q.=" FROM pages AS p ";
            $q.=" INNER JOIN users AS u ";
            $q.=" USING (user_id) ";
            $q.=" WHERE user_id={$aid} ";
            $q.=" ORDER BY date ASC ";
            $q.=" LIMIT {$start}, {$limit} ";
            $r=mysqli_query($conn,$q);
            confirm_query($r,$q);
            //phần hiển thị tin tức
            if(mysqli_num_rows($r)>0){
                while ($author=mysqli_fetch_assoc($r)) {
                     echo"
                        <div class='post'>
                            <h2><a href='single.php?pid={$author['page_id']}'>{$author['page_name']}</a></h2>
                            <p>".the_excerpt($author['content'])." ... <a href='single.php?pid={$author['page_id']}'><strong>Read more</strong> </a></p>
                            <p class'meta'><strong>Posted by: </strong><a href='author.php?aid={$author['user_id']}'>{$author['name']}</a><strong> on:</strong> {$author['date']}</p>
                        </div>
                    ";
                }//END while
                //hiển thị nút PREV
                echo "<ul class='pagination'>";
                    echo "<li><a href='author.php?aid={$aid}&&page=1'>FIRST</a></li>";
                    if($current_page>1 && $total_page>1){
                        echo"<li><a href='author.php?aid={$aid}&&page=".($current_page-1)."'>PREV</a></li>";
                    }
                    //hiển thị nút ở giữa
                    for($i=1;$i<=$total_page;$i++){
                        if($i==$current_page){
                            echo "<li class='active'><span>{$i}</span></li>";
                        }
                        else{
                            echo"<li><a href='author.php?aid={$aid}&&page={$i}'><span>{$i}</span></a></li>";
                        }
                    }
                    //hiển thị nút NEXT
                    if($current_page<$total_page && $total_page>1){
                        echo"<li><a href='author.php?aid={$aid}&&page=".($current_page+1)."'>NEXT</a></li>";
                    }
                    echo "<li><a href='author.php?aid={$aid}&&page={$total_page}'>LAST</a></li>";
                echo"</ul>";
            }// nếu không xuất hiện dòng mới thì...
            //hiển thị phân trang.
        }else{
            /*nếu tác giả không tồn tại thì báo lỗi*/
            echo "<p class='warning'>tác giả không tồn tại</p>";
        }
     ?>
</div>
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

