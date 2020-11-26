<?php require('include/header.php');?>
<?php
function hi($string)
{
	$slug = trim(str_replace('https://www.youtube.com/embed/','https://youtu.be/',$string));
	return $slug;
}

 ?>
<style type="text/css">
  #submenu2{display: block !important}

</style>

<article class="content">
    <div class="content_top"><span><?php if($_GET['media_type']=='image'){echo "Edit Images ";} elseif($_GET['media_type']=='video'){ echo " Edit Videos ";} elseif($_GET['media_type']=='category'){echo "Category";}?></span></div>

    <?php if($_GET['status']=='error'){ ?>
    <div class="error">Unable to edit..</div>
    <?php }elseif($_GET['status']=='success'){ ?>
    <div class="success">Updated successfully</div>
    <?php } ?>
    <div class="media">
    	<div class="media-form">
    		<?php if($_GET['media_type']=='image'){ ?>
    		<?php $em = $conn->query("select * from gallery where id ='".$_GET['edit']."' ");
    			$editimage = $em->fetch_assoc();
    		 ?>
<form action="query.php" enctype="multipart/form-data" method="post">
<table class="table" cellpadding="10" bgcolor="#ccc" width="100%" >
	<tr><td colspan="2" align="center"><h2>Edit Image</h2></td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="image-name" value="<?=$editimage['name']?>"></td>
	</tr>
	<tr>
		<td>Media:</td>
		<td><input class="cte" type="file" name="post-image"><span><input type="hidden" name="im-id" value="<?=$editimage['id']?>"></span> <span><?=$editimage['media']?></span></td>
	</tr>
	<tr>
		<td>Category:</td>
		<td>
			<select class="cte" name="Category">
				<option  value="uncategorized">Uncategorized</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="edit-im"></td></tr>
</table>
</form>
<?php } elseif($_GET['media_type']=='video'){ ?>
    		<?php $ev = $conn->query("select * from gallery where id ='".$_GET['edit']."' ");
    			$editvideo = $ev->fetch_assoc();
    		 ?>
<form action="query.php" enctype="multipart/form-data" method="post">
<table class="table" cellpadding="10" bgcolor="#ccc" width="100%" >
	<tr><td colspan="2" align="center"><h2>Edit Video</h2></td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="video-name" value="<?=$editvideo['name']?>"></td>
	</tr>
	<tr>
		<td>Media:</td>
		<td><input class="cte" type="text" name="video-link" value="<?=hi($editvideo['media'])?>"></td>
		<input type="hidden" name="type" value="1">
		<input type="hidden" name="vd-id" value="<?=$editvideo['id']?>">
	</tr>
	<tr>
		<td>Category:</td>
		<td>
			<select class="cte" name="Category">
				
				<?php $sl = $conn->query("select * from gallery where media_type = '1'");
					while($select = $sl->fetch_assoc()){
				 ?>
				<option  value="<?=strtolower($select['Gallery']) ?>"><?=$select['Gallery']?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="edit-video"></td></tr>
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
          <img src="img/<?=$media['media']?>" height="150px" width="150px"><br>
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
	<tr><td colspan="2" align="center"><input type="submit" name="category"></td></tr>
</table>
</form>

<?php }  ?>

</div>
</div>
</article>
<?php require('include/footer.php') ?>