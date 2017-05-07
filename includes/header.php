<?php 
 session_start();
 ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
	<title><?php echo(isset($title))? $title : "ROMECMS"; ?></title>
	<link rel='stylesheet' href='css/bootstrap.min.css' />
	<link rel='stylesheet' href='css/bootstrap-theme.min.css' />
	<link rel='stylesheet' href='css/style.css' />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src='js/tinymce/tinymce.js'></script>
	<script type="text/javascript" src="js/check_ajax.js"></script>
	<script type="text/javascript" src="js/delete_comment.js"></script>
	<script type="text/javascript">
		 tinymce.init({
		   selector: 'textarea',
		   height: 500,
		   theme: 'modern',
		   plugins: [
		     'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		     'searchreplace wordcount visualblocks visualchars code fullscreen',
		     'insertdatetime media nonbreaking save table contextmenu directionality',
		     'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
		   ],
		   toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		   toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
		   image_advtab: true,
		   templates: [
		     { title: 'Test template 1', content: 'Test 1' },
		     { title: 'Test template 2', content: 'Test 2' }
		   ],
		   content_css: [
		     '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
		     '//www.tinymce.com/css/codepen.min.css'
		   ]
		  });
	</script>
</head>

<body>
	<div id="container">
	<div id="header">
		<h1><a href="">ROMECMS</a></h1>
        <p class="slogan">The ROME Content Management System</p>
	</div>
	<div id="navigation">
		<ul>
	        <li><a href='index.php'>Home</a></li>
			<li><a href='#'>About</a></li>
			<li><a href='#'>Services</a></li>
			<li><a href='contact.php'>Contact us</a></li>
		</ul>
        
        <p class="greeting">Xin chào: <?php if(isset($_SESSION['first_name'],$_SESSION['last_name'])){echo "<b>".$_SESSION['last_name']." ".$_SESSION['first_name']."</b>";} else{echo "bạn hiền!";}?></p>
	</div><!-- end navigation-->
 