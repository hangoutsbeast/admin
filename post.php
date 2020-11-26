<?php require('include/header.php') ?>
	<article class="content">
		<div class="content_top"><span><?=(empty($_GET['id'])?"New ":"Edit ")?> <?php if(@$_GET['post_type']=='page'){echo "Page";} elseif(@$_GET['post_type']=='post'){ echo "Post";}?></span></div>
		<div class="cont">
			<?php if(!empty($_GET['id'])){
			$data = editpost($_GET['post_type'],$_GET['id'],$conn); } ?>
		<div class="left-article">
		
			<input class="post form-control mb-3" type="text" value="<?=$data['title']?>" id="Title" name="Title">
			
			<textarea class="post " id="editor1" name="editor1"><?=$data['details']?></textarea>
			<script>
                CKEDITOR.replace( 'editor1' );
            </script>

            <input type="hidden" id="type" name="type" value="<?=($_GET['post_type']=='post'?'0':'1')?>">	
            <input type="button" id="<?=(!empty($_GET['id'])?"editpost":"addpost")?>" value="<?=(!empty($_GET['id'])?"Edit":"Add")?> Post" class="mt-4 btn btn-success">
        </div>   
		<div class="right-article">
			<div class="publish">
				<p class="accordion">Publish</p>
				<div class="panel">
				  <ul>
				  	<li>
				  		<i class="fa fa-map-pin" aria-hidden="true"></i> Status : <?=(empty($data['post_status'])?"Draft":"Publish")?>
				  		<input type="hidden" id="post_status" name="post_status" value="<?=(empty($data['post_status'])?"Draft":"Publish")?>">
				  	</li>
				  	<li>
				  		<i class="fa fa-calendar" aria-hidden="true"></i> Published On : <?=(empty($data['u_date'])?date("Y-m-d"):date("Y-m-d",strtotime($data["u_date"])))?>
				  		<input type="hidden" id="c_date" name="c_date" value="<?=date("Y-m-d H:i:s")?>">
				  	</li>
				  </ul>
				</div>
			</div>
			<div class="publish">
				<p class="accordion">Category</p>
					<div class="panel">
						<div class="cat">
							<p>All Categories</p>
							<select name="Category" id="Category" class="form-control">
								<option value="0">Uncategorized</option>
							</select>
						</div>
						<div class="cat_in">
						  	<input type="text" name="cate" class="form-control">
						</div>
						<input type="button" id="pcreate" class="btn btn-primary" value="Create">
					</div>

			</div>
			<?php if($_GET['post_type']=='post'){ ?>
			<div class="publish">
				<p class="accordion">Featured Images</p>
				<div class="panel">
					<div id="demo"></div>
					<input type="hidden" name="image" id="image" value="">
					<button  data-toggle="modal" data-target="#myModal" class="btn btn-primary" type="button">Add Featured Image</button>	
					<div class="modal" id="myModal">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <!-- Modal body -->
					      <div class="modal-body">
					        <ul class="nav flex-row">
							    <li class="nav-item">
							      <a class="nav-link active" data-toggle="tab" href="#select">Select Images</a>
							    </li>
							    <li class="nav-item">
							      <a class="nav-link" data-toggle="tab" href="#upload">Upload Images</a>
							    </li>						    
						  	</ul>
						  	<div class="tab-content">
						  		<div id="upload" class="container tab-pane">
						  			<div class="img-upload">
						  				<span class="error upload-error"></span>	
						  				<input type="file" name="post-image" id="image-upload" class="form-control">
						  				<input type="button" id="img-upload" class="btn btn-primary" value="Upload">
						  			</div>
						  		</div>
						  		<div id="select" class="container tab-pane active">
						  			<ul class="images">			  				
						  			</ul>
						  			<input type="button" id="img-select" class="btn mt-3 btn-primary" value="Select">
						  		</div>
						  	</div>
					      </div>
					    </div>
					  </div>
					</div>				
				</div>
			</div>
			<?php } ?>
		</div>
		</div>
	</article>
<?php if(!empty($_GET['id'])){ if($data['cat_id']){ ?>
	<script>
		var catid = <?php echo $data['cat_id'] ?>;
		var img = <?php echo $data['gal_id'] ?>;
		$.post("query.php",{imgid:img},function(data,status){
			if(data!==''){
				$('#demo').append(data).siblings().css('display','none');
			}
		});
	</script>
<?php } } ?>
<?php require('include/footer.php') ?>