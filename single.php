<?php
$title="single";
include('includes/functions.php');
include('includes/mysqli_connect.php');
if($pid=validate_id($_GET['pid'])) {
        $set= get_page_by_id($pid);
        $page_view=view_counter($pid);
        $posts=array();
        if(mysqli_num_rows($set)>0){
               //nếu có bài viết thì hiển thị
             $pages=mysqli_fetch_array($set,MYSQLI_ASSOC) ;
             $posts[]=array('page_name'=>$pages['page_name'],
                            'content'=>$pages['content'],
                            'author'=>$pages['name'],
                            'post-on'=>$pages['date'],
                            'aid'=>$pages['user_id']
                );
             $title=$pages['page_name'];                   
        }else{
            echo "<p>không có post nào trong category này</p>";
        }

    }else{
        header('location: index.php');
    }
include('includes/header.php');
include('includes/sidebar-a.php');

?>

<div id="content">
<?php foreach($posts as $post){
                    echo"
                        <div class='post'>
                            <h3>{$post['page_name']}</h3>
                            <p>".the_content($post['content'])." </p>
                            <p class'meta'><strong>Posted by: </strong><a href='author.php?aid={$post['aid']}'>{$post['author']}</a><strong> on:</strong> {$post['post-on']}
                            <strong>page view:</strong> {$page_view}
                            </p>
                        </div>
                    ";
}
                    
 ?>
    <?php 
    include('includes/comment_form.php');
 ?>
</div>
<!--end content-->

<?php
include('includes/sidebar-b.php');
include('includes/footer.php');
?>    
<!--end aside-->

