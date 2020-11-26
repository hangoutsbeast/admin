<?php require('include/header.php');?>

  <article class="content">
    <div class="mediacat">
      <div class="p-grid">
        <div class="name content_top">Media <button class="btn btn-primary" data-target="#drop-container" >Add New</button></div>
        <div class="media-cat text-right">
          <select name="medselect" id="medselect">
            <option value="all">All Media</option>
            <option value="image">Image</option>
            <option value="video">Video</option>
          </select>
        </div>
      </div>
      <div id="drop-container" class="mb-5">
        <div class="drop-area-text">
          <div class="upload-error"></div>
          <input type="file" name="media" id="img-gallery" class="form-control">
        </div>
      </div>
      <div class="row">
        <?php 
        $stmt = $conn->query("select * from gallery");
        while($result = $stmt->fetch_assoc()){
         ?>
        <div class="col-md-3 mb-3">
          <img src="<?=$result['path']?>" class="img-responsive" alt="<?=$result['name']?>" title="<?=$result['name']?>">
          <div class="caption">
            <div class="p-grid">
              <span data-replace="<?=$result['id']?>" class="fa fa-pencil"></span>
              <span data-trash="<?=$result['id']?>" class="text-right fa fa-trash"></span>
            </div>
          </div>
        </div>
      <?php  } ?>
      </div>
    </div>
    <div class="alert media-alert"></div>
  </article>
<?php require('include/footer.php') ?>