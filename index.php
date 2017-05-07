<?php
include_once('includes/header.php');
include_once('includes/functions.php');
include_once('includes/mysqli_connect.php');
include_once('includes/sidebar-a.php');

?>

<div id="content">
<?php 
    if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $cid = $_GET['cid'];
        $q= " SELECT p.page_name, p.page_id, p.content, ";
        $q.= " DATE_FORMAT(p.post_on,'%b %d, %y') AS date, ";
        $q.= "  CONCAT_WS(' ',u.first_name,u.last_name) AS name, u.user_id ";
        $q.= " FROM pages AS p ";
        $q.= " INNER JOIN users AS u ";
        $q .= " USING (user_id) ";//lưu ý hay thiếu
        $q.= " WHERE p.cat_id={$cid} ";
        $q.= " ORDER BY date ASC LIMIT 0, 10 ";
        $r=mysqli_query($conn,$q);
        confirm_query($r,$q); 
        if(mysqli_num_rows($r)>0){
               //nếu có bài viết thì hiển thị
            while ($pages=mysqli_fetch_array($r,MYSQLI_ASSOC)) {
                    echo"
                        <div class='post'>
                            <h2><a href='single.php?pid={$pages['page_id']}'>{$pages['page_name']}</a></h2>
                            <p>".the_excerpt($pages['content'])." ... <a href='single.php?pid={$pages['page_id']}'><strong>Read more</strong> </a></p>
                            <p class'meta'><strong>Posted by: </strong><a href='author.php?aid={$pages['user_id']}'>{$pages['name']}</a><strong> on:</strong> {$pages['date']}</p>
                        </div>
                    ";
            }//END WHILE
        }else{
            echo "<p>không có post nào trong category này</p>";
        }

    }elseif(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $pid = $_GET['pid'];
            $q="SELECT p.page_name , p.content, 
                       DATE_FORMAT(post_on,'%b %d,%y') AS date, 
                       CONCAT_WS(' ',u.first_name,u.last_name) AS name, 
                       u.user_id, COUNT(c.comment_id) AS count 
                       FROM users AS u 
                       INNER JOIN pages AS p USING(user_id ) 
                       LEFT JOIN comments AS c ON p.page_id=c.page_id 
                       WHERE p.page_id={$pid} 
                       GROUP BY page_name 
                       ORDER BY date ASC";
            $r=mysqli_query($conn,$q);
            confirm_query($r,$q);
            if(mysqli_num_rows($r)>0){
                while($page=mysqli_fetch_assoc($r)){
                     echo"
                        <div class='post'>
                            <h2><a href='single.php?pid={$pid}'>{$page['page_name']}</a></h2>
                            <p class='comments'><a href='single.php?pid={$pid}#disscuss'>{$page['count']}</a></p>
                            <p>".the_excerpt($page['content'])." ... <a href='single.php?pid={$pid}'><strong>Read more</strong> </a></p>
                            <p class'meta'><strong>Posted by: </strong><a href='author.php?aid={$page['user_id']}'>{$page['name']}</a><strong> on:</strong> {$page['date']}</p>
                        </div>
                    ";
                }//END WHILE
            }else{
                echo "<p class='warning'> không có bài viết nào như vậy </p>";
            }
}else{
 ?><!-- nếu không còn gì để hiển thị thì hiển thị đoan chào mừng này -->
    <h2>Welcome To izCMS</h2>
    <div>
        <p>
            Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
        </p>

        <p>
            Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
        </p>

        <p>
            Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
        </p>
    </div>
    <?php } ?>
</div>
<!--end content-->
<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

