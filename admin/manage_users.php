<?php
include('../includes/header.php');
include('../includes/sidebar-admin.php');
require_once('../includes/functions.php');
require_once('../includes/mysqli_connect.php')
?>
<?php admin_access(); ?>
<div id="contents">
	<h2>Manage Pages</h2>
    <table>
    	<thead>
    		<tr>
    			<th><a href="manage_users.php?sort=fn">First Name</a></th>
    			<th><a href="manage_users.php?sort=ln">Last Name</a></th>
    			<th><a href="manage_users.php?sort=em">Email</a></th>
                <th>User Level</th>
                <th>Edit</th>
                <th>Delete</th>
    		</tr>
    	</thead>
    	<tbody>
    	<?php 
            //validate $_GET['sort'], và gán giá trị.
            $sort=(isset($_GET['sort']))?$_GET['sort']:'fn';
    		$order_by=sort_by($sort);
    	 ?>
    	<!-- hiển thị dữ liệu của user -->
    	<?php 
    		$users=fetch_all_user($order_by);
                foreach ($users as $user) {
                    echo"
                        <tr>
                            <td>{$user['first_name']}</td>
                            <td>{$user['last_name']}</td>
                            <td>{$user['email']}</td>
                            <td>{$user['user_level']}</td>
                            <td><a class='edit' href='edit_user.php?uid={$user['user_id']}'>Edit</a></td>
                            <td><a class='delete' href='delete_user.php?uid={$user['user_id']}&fn={$user['first_name']}'>Delete</a></td>
                        </tr>
                    ";
                }//END foreach;
    	 ?>
    	</tbody>
    </table>
</div><!--end content-->
<?php
// include('../includes/sidebar-b.php');
include('../includes/footer.php');
?>    
<!--end aside-->



