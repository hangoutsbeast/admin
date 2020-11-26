<?php require('include/header.php');?>
<style type="text/css">
  #submenu2{display: block !important}

</style>

<article class="content">
    <div class="content_top"><span><?php if($_GET['media_type']=='image'){echo "Add Images ";} elseif($_GET['media_type']=='video'){ echo " Add Videos ";} elseif($_GET['media_type']=='category'){echo "Category";}?></span></div>
    <?php if($_GET['status']=='error'){ ?>
    <div class="error">Unable to edit..</div>
    <?php }elseif($_GET['status']=='success'){ ?>
    <div class="success">Added successfully</div>
    <?php } ?>
    <div class="media">
    	<div class="media-form">
    		<?php if($_GET['media_type']=='image'){ ?>
<form action="query.php" enctype="multipart/form-data" method="post">
<table class="table" cellpadding="10" bgcolor="#ccc" width="100%" >
	<tr><td colspan="2" align="center"><h2>Add Image</h2></td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="image-name" required=""></td>
	</tr>
	<tr>
		<td>Media:</td>
		<td><input class="cte" type="file" name="post-image"></td>
	</tr>
	<tr>
		<td>Category:</td>
		<td>
			<select class="cte" name="Category">

								<?php $sl = $conn->query("select * from gallery group by Gallery");
								if($sl->num_rows > 0){
					while($select = $sl->fetch_assoc()){
				 ?>
				<option  value="<?=strtolower($select['Gallery']) ?>"><?=$select['Gallery']?></option>
				<?php }} else { ?>
				<option  value="uncategorized">Uncategorized</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="image"></td></tr>
</table>
</form>
<?php } elseif($_GET['media_type']=='video'){ ?>
<form action="query.php" enctype="multipart/form-data" method="post">
<table class="table" cellpadding="10" bgcolor="#ccc" width="100%" >
	<tr><td colspan="2" align="center"><h2>Add video</h2></td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="video-name" required=""></td>
	</tr>
	<tr>
		<td>Media:</td>
		<td><input class="cte" type="text" name="video-link" required=""></td>
		<input type="hidden" name="type" value="1">
	</tr>
	<tr>
		<td>Category:</td>
		<td>
			<select class="cte" name="Category">
				
								<?php $sl = $conn->query("select * from gallery group by Gallery");
								if($sl->num_rows > 0){
					while($select = $sl->fetch_assoc()){
				 ?>
				<option  value="<?=strtolower($select['Gallery']) ?>"><?=$select['Gallery']?></option>
				<?php }} else { ?>
				<option  value="uncategorized">Uncategorized</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="video"></td></tr>
</table>
</form>
<?php } elseif($_GET['media_type']=='category'){ ?>

<form action="query.php" enctype="multipart/form-data" method="post">
<table class="table" cellpadding="10" bgcolor="#ccc" width="100%" >
	<tr><td colspan="2" align="center"><h2>Add Image</h2></td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="cat-name"></td>
	</tr>
	<tr>
		<td colspan="2"><div class="media-cat">
			<ul>
	<?php $al = $conn->query("select * from gallery ");
      		if($al->num_rows > 0){
      		while($media = $al->fetch_assoc()){
      	 ?>
      	<li>
          <?php if($media['media_type']=='0') {?>
          <img src="upload/<?=$media['media']?>" height="150px" width="150px"><br>
          <div class="ceh"><span><input type="checkbox" name="media_id[]" value="<?=$media['id']?>"></span> <span><?=$media['name']?></span></div>
        <?php } elseif($media['media_type']=='1') {?>
          <iframe width="150" height="150" src="<?=$media['media']?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><br>
          <div class="ceh"><span><input type="checkbox" name="media_id[]" value="<?=$media['id']?>"></span> <span><?=$media['name']?></span></div>
        <?php } ?>
        </li>
      	<?php } }else { ?>
      		<li class="error">No Record Found</li>
       <?php  } ?>
				
			</ul>
		</div></td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="categoryim"></td></tr>
</table>
</form>

<?php }  ?>

</div>
</div>
</article>
<?php require('include/footer.php') ?>