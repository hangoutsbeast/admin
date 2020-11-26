<?php require('include/header.php');?>

	<article class="content">
		<div class="p-grid">
		<div class="add_category">	
		<form method="post" id="category-form" >
		<h3>Enter Category Name</h3>
		<input type="text" name="category" placeholder="" class="form-control" autofocus="autofocus">
		<input type="hidden" name="catsno">
		<input type="submit" class="mt-4 btn btn-primary" name="add_cat">
		</form>
		<div class="show_cat">
			<h4>List Category</h4>
			<table id="cattable" class="table table-striped table-bordered">
				<thead>
					<th>Name</th>
					<th>Edit</th>
				</thead>
				<?php
				 $value = $conn->query('select * from category where cat_type="post-cat"');
					if($value->num_rows > 0){
						while($row = $value->fetch_assoc()){
							?>
							<tr>
								<td><?php echo $row['cat_name']?></td>
								<td><img src="img/pencil.png" title="Edit" data-cat="<?php echo $row['cat_id']?>">&nbsp;<img src="img/cross.png" title="Delete" data-del="<?php echo $row['cat_id']?>" ></td>
							</tr>
				<?php
				 } } ?>	
			</table> 		
		</div>
		</div>
		<div class="edit_cat">
			<h3>Edit Category</h3>
			<div class="cati">
				<?php
				$id = $_GET['idd'];
				if($_GET['idd']){
				$get = $conn->query("select * from category where cat_id =$id");				 
				$row1 = $get->fetch_assoc();
				
				?>
				<form action="query.php" method="post">
					<div class="form-group">
						<input type="text" class="form-control" name="cat_name" value="<?php echo $row1['cat_name']?>" placeholder="Category Name" autofocus="autofocus">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="cat_slug" value="<?php echo $row1['cat_slug']?>" placeholder="Category link" autofocus="autofocus">
					</div>
					<input type="hidden" name="data" value="<?php echo $row1['cat_id'] ?>">
					<input type="submit" class="btn btn-primary" value="Update" name="update_cat">
				</form>
				<?php } else{
					echo "Nothing Selected For Edit";
				}
				?>
			</div>
			<div class="alert mt-5" style="display: none;" role="alert">
			</div>
		</div>
		</div>
	</article>
	
<?php require('include/footer.php') ?>