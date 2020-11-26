<?php require('include/header.php');?>

  <article class="content">
    <div class="content_top"><span>Add Menu</span></div>
    <div class="menuadd">
    <div class="row">
      <div class="col-md-4">
        <div id="accordion">
          <div class="card">
            <div class="card-header">
              <a class="card-link" data-toggle="collapse" href="#post">
                Pages
              </a>
            </div>
            <div id="post" class="collapse show" data-parent="#accordion">
              <div class="card-body">
                <input list="menus" class="form-control" name="menu" id="menu">
                  <datalist id="menus">
                  </datalist>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <a class="card-link" data-toggle="collapse" href="#category">
                Category
              </a>
            </div>
            <div id="category" class="collapse show" data-parent="#accordion">
              <div class="card-body">
                  <input list="menus" class="form-control" name="menu" id="menu">
                  <datalist id="menus">
                  </datalist>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <a class="card-link" data-toggle="collapse" href="#custom">
                Custom
              </a>
            </div>
            <div id="custom" class="collapse show" data-parent="#accordion">
              <div class="card-body">
                  <input list="menus" class="form-control" name="menu" id="menu">
                  <datalist id="menus">
                  </datalist>
              </div>
            </div>
          </div>                              
        </div>
      </div>
      <div class="col-md-8">
        <h4>Menu</h4>
        <div class="showpage">
          <ul>
         <?php $menu = $conn->query("select * from menumap where sub_id='0' ");
            while($setmenu = $menu->fetch_assoc()){
           ?>
            <li><span><?=$setmenu['menu_name']?></span><span style="float: right;"><a href="add-menu.php?idt=<?=$setmenu['id']?>"><img src="img/cross.png"></a></span>
              <ul>
            <?php $menui = $conn->query("select * from menumap where sub_id='".$setmenu['id']."' ");
              while($setmenui = $menui->fetch_assoc()){
             ?>

                <li><span><?=$setmenui['menu_name']?></span><span style="float: right;"><a href="add-menu.php?idt=<?=$setmenui['id']?>"><img src="img/cross.png"></a></span></li>
                <?php } ?>
              </ul>
            </li>
           <?php } ?> 
          </ul>
        </div>
      </div>
    </div>
    </div>
  </article>
<?php require('include/footer.php') ?>