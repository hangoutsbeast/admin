<?php
session_start();
require ('include/connection.php');
require ('include/function.php');
if($_SESSION['admin']=='')
{
header('location:index.php');
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMIN PANEL</title>
	<link rel="stylesheet" href="css/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<script src="ckeditor/ckeditor.js"></script>
	<script>
		var catid='';
	</script>
</head>
<body>
<section class="top-strip">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2><a href="home.php">ADMIN PANEL</a></h2>
			</div>
			<div class="col-md-6 text-right"> 
				<ul class="Social">
					<li><span><?=getuser($_SESSION['admin'],$conn,'Name')?></span></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
</section>
 <section class="container-fluid main-inner">
 	<div class="row">
	<article class="col-md-3 sidebar">
		<div class="profile">
			<img src="img/user.png" alt="" width="80px;">
			<h3><?=getuser($_SESSION['admin'],$conn,'Name')?></h3>
		</div>
	  <div id="accordion">
	    <div class="card">
	      <div class="card-header">
	        <a class="card-link" data-toggle="collapse" href="home.php">
	          Dashboard
	        </a>
	      </div>
	    </div>
	    <div class="card">
	      <div class="card-header">
	        <a class="collapsed card-link" data-toggle="collapse" href="#posts">
	        Posts
	      </a>
	      </div>
	      <div id="posts" class="collapse" data-parent="#accordion">
	        <div class="card-body">
	          <ul class="submenu">
					<li><a href="edit.php?post_type=post">All Posts</a></li>
					<li><a href="post.php?post_type=post">Add New</a></li>
					<li><a href="category.php">Category</a></li>
			  </ul>
	        </div>
	      </div>
	    </div>
	    <div class="card">
	      <div class="card-header">
	        <a class="collapsed card-link" data-toggle="collapse" href="#pages">
	          Pages
	        </a>
	      </div>
	      <div id="pages" class="collapse" data-parent="#accordion">
	        <div class="card-body">
	          <ul class="submenu">
					<li><a href="edit.php?post_type=page">All Pages</a></li>
					<li><a href="post.php?post_type=page">Add New</a></li>
			  </ul>	          
	        </div>
	      </div>
	    </div>
	    <div class="card">
	      <div class="card-header">
	        <a class="collapsed card-link" data-toggle="collapse" href="#media">
	          Media
	        </a>
	      </div>
	      <div id="media" class="collapse" data-parent="#accordion">
	        <div class="card-body">
				<ul class="submenu" id="submenu2">
					<li><a href="add-media.php">All Media</a></li>
					<li><a href="add-media.php?media_type=image">Photos</a></li>
					<li><a href="add-media.php?media_type=video">Videos</a></li>
				</ul>	          
	        </div>
	      </div>
	    </div>	
	    <div class="card">
	      <div class="card-header">
	        <a class="collapsed card-link" data-toggle="collapse" href="#menu">
	          Menus
	        </a>
	      </div>
	      <div id="menu" class="collapse" data-parent="#accordion">
	        <div class="card-body">
	          	<ul class="submenu" id="submenu4">
					<li><a href="add-menu.php">Add Menu</a></li>
				</ul>
	        </div>
	      </div>
	    </div>-->		    	        
	  </div>
	  <div class="side-bottom"></div>		
	</article>
	<article class="col-md-9"> 		
