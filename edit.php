<?php require('include/header.php'); ?>
	<article class="content">
		<div class="content_top"><span><?=@$_GET['post_type']?>s</span> <a href="post.php?post_type=<?=@$_GET['post_type']?>" class="btn btn-primary">Add <?=@$_GET['post_type']?></a></div>
		<div class="p-grid">
			<div class="count">All Count (<?=pcount($_GET['post_type'],$conn) ?>)</div>
			<div class="text-right">
				<span>Number of Row :</span>
				<select id="crow" class="crow">
					<option value="500">all</option>
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
				</select>
			</div>
		</div>
		<table id="mytable" class="table table-bordered" width="100%" border="1"  align="center">
			<thead>
				<th>Title</th>
				<th>Post Status</th>
				<th>Date</th>
				<th>Edit</th>
			</thead>
			<?php echo getpost($_GET['post_type'],$conn) ?>
		</table>
		<div class="pagination-container">
			<nav>
				<ul class="pagination"></ul>
			</nav>			
		</div>
	</article>

<?php require('include/footer.php') ?>