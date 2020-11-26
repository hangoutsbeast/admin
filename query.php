<?php
require('include/connection.php');

function slug($string)
{
	$slug = trim(str_replace(' ','-',strtolower($string)),'-');
	return $slug;
}

function video($string)
{
	$slug = trim(str_replace('https://youtu.be/','https://www.youtube.com/embed/',$string));
	return $slug;
}

function clean($string) {
   //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}

function short($value)
{
	$extract = explode(' ', strip_tags($value));
	$count = count($extract);
	$short = array();
	if($count < 50){
		return implode(' ', $extract);
	}else{
		for($i=1; $i<=50; $i++){
			array_push($short, $extract[$i]);
		}		
		return implode(' ', $short);
	}
}

/*---- login authentication  --------- */

ob_start();
session_start();

if(isset($_POST['pk'])){
	$stmt = $conn->query("update `".$_POST['name']."` SET `post_status`='".$_POST['value']."' where id='".$_POST['pk']."'");
	if($stmt){
		$cat[success] = "Status changed.";
		echo json_encode($cat);
	}else{
		$cat[error] = "there is some error in status changing.";
		echo json_encode($cat);
	}
}

if(isset($_GET['page'])){
	$output = array();
	$stmt = $conn->query("select * from post where type='0'");
	while ($row = $stmt->fetch_assoc()) {
		$output[] = $row;
	}
	echo json_encode($output);
}

if(isset($_POST['login']))
{
	extract($_POST);
	$User = $_POST['user'];
	$password = $_POST['pass'];
	$stmt = $conn->query("select username,password from admin where username='$User' and password='$password'");
	if($stmt->num_rows > 0){
		$_SESSION['admin'] = $User;
		$redirect[redirect]= 'home.php';
		echo json_encode($redirect);
	}else{
		$error[error]= 'wrong user id and password';
		echo json_encode($error);
	}

}


if(isset($_FILES['galimg'])){
	$file = $_FILES['galimg'];
	$filename = $_FILES['galimg']['name'];
	$filetmpname = $_FILES['galimg']['tmp_name'];
	$filesize = $_FILES['galimg']['size'];
	$fileerror = $_FILES['galimg']['error'];
	$filetype = $_FILES['galimg']['type'];
	$fileExt = explode('.', $filename);
	$fileactualExt = strtolower(end($fileExt));
	$allowed = array('jpg','jpeg','png','gif');
	$stmt = $conn->query("select * from gallery where name='".$filename."'");
	if($stmt->num_rows > 0){
		$image[success] = 'This image already exists';
		echo json_encode($image);
	}else{
	if(in_array($fileactualExt, $allowed)){
		if($fileerror===0){
			if($filesize < 1000000){
				$fileNameNew = uniqid('',true).".".$fileactualExt;
				$fileDestination = 'upload/'.$fileNameNew;
				move_uploaded_file($filetmpname, $fileDestination);
				$result = $conn->query("insert into gallery (name,media,type,path) values ('".$filename."','".$fileNameNew."','".$filetype."','".$fileDestination."')");
				$id = $conn->insert_id;
				$fe = $conn->query("select * from gallery where id = '$id'");
				$get = $fe->fetch_assoc();
				$image[success] = '<div class="col-md-3 mb-3">
				<img src="'.$get['path'].'" class="img-responsive" alt="'.$get['name'].'" title="'.$get['name'].'">
					<div class="caption">
            			<div class="p-grid">
              				<span data-replace="'.$get['id'].'" class="fa fa-pencil"></span>
              				<span data-trash="'.$get['id'].'" class="text-right fa fa-trash"></span>
            			</div>
          			</div>
				</div>';
				//$image[id] = $get['id'];
				echo json_encode($image);
			}
			else{
				$image[error] = "File is too Big";
				echo json_encode($image);
			}
		}
		else{
			$image[error] = "There was an error uploading your file!";
			echo json_encode($image);			
		}
	}
	else{
		$image[error] = "You cannot upload files of this type!";
		echo json_encode($image);					
	}
}
}

	



/*---- forget password  --------- */

if (isset($_POST['forget'])) {
	extract($_POST);
	$fmail = $_POST['fmail'];
	$run = $conn->query("select * from Registration where email = $fmail");
	$row = $run->fetch_object();
	$email = $run['email'];
	echo "$email";

}
/*---- delete category  --------- */	

if(isset($_POST['del'])){
	$stmt = $conn->query("delete from category where cat_id='".$_POST['del']."'");
	if($stmt){
		echo "Category Removed Successfully..";
	}
}

if(isset($_POST['imgdel'])){
	$stmt = $conn->query("delete from gallery where id='".$_POST['imgdel']."'");
	if($conn->affected_rows > 0){
		echo "Removed Successfully..";
	}else{
		echo "There is some error in removeing this item";
	}
}



/*---- add category  --------- */	

if(isset($_POST['category'])){
	$sno = (int)$_POST['catsno'];
	$sno++;
	$category = $_POST['category'];
	$stmt = $conn->query("select cat_name from category where cat_name='$category'");
	if($stmt->num_rows > 0){
		$cat[error] = "This Category Already Exists";		
		echo json_encode($cat);
	}else{
	$result = $conn->query("insert into category (cat_name,cat_slug,cat_date) values ('$category','".slug($category)."',now())");
	if($result){
		$cat[success] = "Category Added Successfully..";
		$cat[value] = "<tr><td>".$sno."</td><td>".$category."</td><td><img src='img/pencil.png' title='Edit' data-cat='".$conn->insert_id."'>&nbsp;<img src='img/cross.png' title='Delete' data-del='".$conn->insert_id."' ></td></tr>";
		echo json_encode($cat);
	}
	else
	{
		$cat[error] = "There Is Some Error in Adding Category";	
		echo json_encode($cat);
	}
	}
}

/*---- Update category  --------- */

if (isset($_POST['update_cat'])) {
	$cat_name = $_POST['cat_name'];
	$cat_slug = $_POST['cat_slug'];
	$data = $_POST['data'];
	$result = $conn->query("update category set cat_name ='$cat_name', cat_slug = '$cat_slug' where cat_id = '$data'");
	if($result){
		header("location:create_cat.php?idd=$data&success");
	}
	else
	{
		header("location:create_cat.php?idd=$data&error");	
	}
}


if (isset($_POST['postTrash'])){
	$trash_id = $_POST['postTrash'];
	$result = $conn->query("delete from post where id='$trash_id'");
	if($result){
		$stmt = $conn->query("delete from post_rel where post_id='$trash_id'");
		if($stmt){
			echo "Post removed successfully..";	
		}else{
			echo "There is some problem in deleting";
		}
	} else{
		echo "There is some problem in deleting";
	}
}



/*---- add category through post category  --------- */

if (isset($_POST['Create'])) {
	$cate = $_POST['cate'];
	$type = $_POST['type'];
	$result = $conn->query("insert into category (cat_id,cat_name,cat_slug,cat_type,cat_date) values ('','$cate','".slug($cate)."','$type',now())");
	if ($result) {
		$opt[value] = "<option value='".slug($cate)."'>".$cate."</option>";
		$opt[msg] = "Category added successfully";
		echo json_encode($opt);
	}
}

if(isset($_POST['dataset'])){
	$val = ($_POST['dataset']['value']=='Publish'?'Draft':'Publish');
	$status = $conn->query("update post set post_status='".$val."' where id='".$_POST['dataset']['id']."'");
	if($status){
		$posti[name] = $val;
		$posti[msg] = "post status changed";
		echo json_encode($posti);
	}else{
		$posti[error] = "there is some error in changing status";
		echo json_encode($posti);
	}
}

/*---- add Image through post category  --------- */
if (isset($_FILES['Imagep'])) {
	$file = $_FILES['Imagep'];
	$filename = $_FILES['Imagep']['name'];
	$filetmpname = $_FILES['Imagep']['tmp_name'];
	$filesize = $_FILES['Imagep']['size'];
	$fileerror = $_FILES['Imagep']['error'];
	$filetype = $_FILES['Imagep']['type'];
	$fileExt = explode('.', $filename);
	$fileactualExt = strtolower(end($fileExt));
	$allowed = array('jpg','jpeg','png','gif');
	$stmt = $conn->query("select * from gallery where name='".$filename."'");
	if($stmt->num_rows > 0){
		$pimg = $stmt->fetch_assoc();
		$image[success] = '<div class="featured"><i class="fa fa-window-close"></i><img src="'.$pimg['path'].'" height="150px" width="100%" ><input type="hidden" name="im" value="'.$pimg['id'].'"></div>';
		//$image[id] = $pimg['id'];
		echo json_encode($image);
	}else{
	if(in_array($fileactualExt, $allowed)){
		if($fileerror===0){
			if($filesize < 1000000){
				$fileNameNew = uniqid('',true).".".$fileactualExt;
				$fileDestination = 'upload/'.$fileNameNew;
				move_uploaded_file($filetmpname, $fileDestination);
				$result = $conn->query("insert into gallery (name,media,type,path) values ('".$filename."','$fileNameNew','$filetype','$fileDestination')");
				$id = $conn->insert_id;
				$fe = $conn->query("select * from gallery where id = '$id'");
				$get = $fe->fetch_assoc();
				$image[success] = '<div class="featured"><i class="fa fa-window-close"></i><img src="'.$get['path'].'" height="150px" width="100%" ><input type="hidden" name="im" value="'.$get['id'].'"></div>';
				//$image[id] = $get['id'];
				echo json_encode($image);
			}
			else{
				$image[error] = "File is too Big";
				echo json_encode($image);
			}
		}
		else{
			$image[error] = "There was an error uploading your file!";
			echo json_encode($image);			
		}
	}
	else{
		$image[error] = "You cannot upload files of this type!";
		echo json_encode($image);					
	}
}	
}

if(!empty($_GET['img']))
{
	$stmt =$conn->query( "select * from ".$_GET['img']);
	$img = '';
	while ($result = $stmt->fetch_assoc()) {
		$img .= '<li class="thumbnail" id="thumbnail" data-id='.$result['id'].'><img src="'.$result['path'].'"></li>';
	}
	echo $img;
}

if(isset($_POST['editpost'])){
	$post_id = $_POST['id'];
	$title = clean($_POST['title']);
	$slug = slug($title);
	$detail = htmlentities($_POST['detail']);
	$gal_id = (empty($_POST['image'])?'0':$_POST['image']);
	$category = $_POST['pcategory'];
	$short_de = trim(short(strip_tags($_POST['detail'])));
		$result = $conn->query("UPDATE post SET title='$title',details='$detail',post_slug='$slug',post_short='$short_de' where id='$post_id'");
		if($result){
			$get_rel = $conn->query("select * from post_rel where post_id='$post_id'");
			if($get_rel->num_rows > 0){
				$post_rel = $conn->query("UPDATE post_rel SET cat_id='$category',gal_id='$gal_id' where post_id='$post_id'");
				if($post_rel){
					$posti[success] = "Post updated successfully";
					echo json_encode($posti);
				}else{
					$posti[error] = "There is error in some details of post go check post field.";
					echo json_encode($posti);
				}				
			}else{
				$insert_rel = $conn->query("insert into post_rel (post_id,cat_id,gal_id) values ('$post_id','$category','$gal_id')");
				if($insert_rel){
					$posti[success] = "Post updated successfully";
					echo json_encode($posti);					
				}
			}

		}else{
			$posti[error] = "There is some error in updating post";
			echo json_encode($posti);
		}	
}

if(isset($_POST['imgid'])){
	$stmt = $conn->query("select * from gallery where id='".$_POST['imgid']."'");
	$result = $stmt->fetch_assoc();
	if($_POST['imgid']){
		echo $data = '<div class="featured"><i class="fa fa-window-close"></i><img src="'.$result['path'].'" height="150px" width="100%"><input type="hidden" name="im" value="'.$result['id'].'"></div>';		
	}
}

if(isset($_POST['cat'])){
	//print_r($_POST);
	$stmt = $conn->query("select * from category where cat_type='post-cat'");
	$data = '';
	while ($get = $stmt->fetch_assoc()) {
		if(count($_POST) > 1){
			$data .= '<option '.($_POST['id']===$get['cat_id']?"selected":"").' value="'.$get['cat_id'].'">'.$get['cat_name'].'</option>';
		}else{
			$data .= '<option  value="'.$get['cat_id'].'">'.$get['cat_name'].'</option>';
		}
	}
	echo $data;
}


if(isset($_POST['addpost'])){
	$title = clean($_POST['title']);
	$slug = slug($title);
	$detail = htmlentities($_POST['detail']);
	$post_type = $_POST['type'];
	$gal_id = (empty($_POST['image'])?'0':$_POST['image']);
	$post_status = $_POST['pstatus'];
	$c_date = $_POST['date']; 
	$category = $_POST['pcategory'];
	$short_de = short(strip_tags($_POST['detail']));
	$stmt = $conn->query("select title from post where title='".$title."' and type='".$post_type."'");
	if($stmt->num_rows > 0){
		$posti[error] = "Post is already added with this title name";
		echo json_encode($posti);
	}else{
		$result = $conn->query("insert into post (title,details,type,post_slug,post_short,post_type,c_date,post_status) values ('$title','$detail','$post_type','$slug','$short_de','post','$c_date','$post_status')");
		$post_id = $conn->insert_id;
		if(result){
			$post_rel = $conn->query("insert into post_rel (post_id,cat_id,gal_id,parent_id) values ('$post_id','$category','$gal_id','$parent_id')");
			if($post_rel){
				$status_p = $conn->query("update post set `post_status`='Publish' WHERE id='".$post_id."'");
				$posti[success] = "Post added successfully";
				echo json_encode($posti);
			}else{
				$posti[error] = "There is error in some details of post go check post field.";
				echo json_encode($posti);
			}
		}else{
			$posti[error] = "There is some error in adding post";
			echo json_encode($posti);
		}
	}
}



if (isset($_POST['Page'])) {
	$title = $_POST['Title'];
	$details = $_POST['editor1'];
	$type = $_POST['type'];
	$slug = $_POST['Category'];
	$img = $_POST['im'];
	$result = $conn->query("insert into post (id,title,details,type,cat_slug,title_slug,pro_img,c_date,u_date) values ('','$title','$details','$type','".slug($slug)."','".slug($title)."','$img',now(),now())");
	if($result){
		header("location:edit.php?post_type=page&status=success");
	}
	else{
		header("location:edit.php?post_type=page&status=error");
	}	
}





if (isset($_POST['video'])) {
	$name = $_POST['video-name'];
	$link = $_POST['video-link'];
	$type = $_POST['type'];
	$cat = $_POST['Category'];
	$result = $conn->query("insert into gallery (id,name,media,Gallery,media_type,date) values ('','$name','".video($link)."','$cat','$type',now())");
	if($result){
		header("location:add-image.php?media_type=video&status=success");
	}
	else{
		header("location:add-image.php?media_type=video&status=error");	
	}
	
}

if (isset($_POST['edit-im'])) {
	$name = $_POST['image-name'];
	$cat = $_POST['Category'];
	$im = $_POST['im-id'];
	$result = $conn->query("update gallery set name ='$name' ,Gallery ='$cat' where id=$im");
	if($result){
		if($_FILES['post-image']['name']){
			$file = $_FILES['post-image'];
			$filename = $_FILES['post-image']['name'];
			$filetmpname = $_FILES['post-image']['tmp_name'];
			$filesize = $_FILES['post-image']['size'];
			$fileerror = $_FILES['post-image']['error'];
			$filetype = $_FILES['post-image']['type'];
			$fileExt = explode('.', $filename);
			$fileactualExt = strtolower(end($fileExt));
			$exectname = str_replace($fileactualExt,'', $filename);
			$allowed = array('jpg','jpeg','png','gif');
			if(in_array($fileactualExt, $allowed)){
				if($fileerror===0){
					if($filesize < 1000000){
						$fileNameNew = uniqid('',true).".".$fileactualExt;
						$fileDestination = 'upload/'.$fileNameNew;
						move_uploaded_file($filetmpname, $fileDestination);
						$conn->query("update gallery set media='$fileNameNew' where id=$im");
					}
					else{
						echo "File is too Big";
					}
				}
				else{
					echo "There was an error uploading your file!";
				}
			}
			else{
				echo "You cannot upload files of this type!";
			}

		 	}
		header("location:edit-image.php?media_type=image&edit=$im&status=success");
	}
	else{
		header("location:edit-image.php?media_type=image&edit=$im&status=error");	
	}
	
}

if (isset($_POST['edit-video'])) {
	$name = $_POST['video-name'];
	$link = $_POST['video-link'];
	$type = $_POST['type'];
	$cat = $_POST['Category'];
	$vd = $_POST['vd-id'];
	$result = $conn->query("update gallery set name='$name',media='".video($link)."',Gallery='$cat',media_type='$type' where id=$vd");
	if($result){
		header("location:edit-image.php?media_type=video&edit=$vd&status=success");
	}
	else{
		header("location:edit-image.php?media_type=video&edit=$vd&status=error");	
	}
	
}




if (isset($_POST['categoryim'])) {
	$name = $_POST['cat-name'];
	$cat = count($_POST['media_id']);
	if($cat > 0){
		for($i=0;$i<$cat;$i++){

			$sql = 'update gallery SET `Gallery`="'.$name.'" where id ="'.$_POST['media_id'][$i].'";';
			 if (!$conn->multi_query($sql)) {
    			echo "Multi query failed: (" . $conn->errno . ") " . $conn->error;
				}
				else{
					header("location:add-image.php?media_type=category&status=success");
				}
		}
	}
	 
	
}

if(isset($_POST['addpages'])){
	$name = $_POST['get_pages'];
	$gp = $conn->query("select * from menumap where menu_name='$name'");
	if($gp->num_rows > 0){
		header("location:add-menu.php?status=already");	
	}
	else{
		$addpage = $conn->query("insert into menumap (id,menu_name,menu_slug,date) values ('','$name','".slug($name)."',now())");
	if($addpage){
		header("location:add-menu.php?status=success");
	}
	else{
		header("location:add-menu.php?status=error");	
	}
		
	}
}

if (isset($_POST['addsub'])) {
	$id = $_POST['menuid'];
	$name = $_POST['get_pagm'];
	$addmenu = $conn->query("insert into menumap (id,menu_name,menu_slug,sub_id,date) values ('','$name','".slug($name)."','$id',now())");
	if($addmenu){
		header("location:add-menu.php?status=success");
	}
	else{
		header("location:add-menu.php?status=error");	
	}

}

if (isset($_POST['catme'])) {
	$id = $_POST['menuid'];
	$name = $_POST['get_cat'];
	$addcat = $conn->query("insert into menumap (id,menu_name,menu_slug,sub_id,date) values ('','$name','".slug($name)."','$id',now())");
	if($addcat){
		header("location:add-menu.php?status=success");
	}
	else{
		header("location:add-menu.php?status=error");	
	}

}

if (isset($_POST['customlink'])) {
	$id = $_POST['menuid'];
	$name = $_POST['customl'];
	$customlm = $conn->query("insert into menumap (id,menu_name,menu_slug,sub_id,date) values ('','$name','".slug($name)."','$id',now())");

	if($customlm){
		header("location:add-menu.php?status=success");
	}
	else{
		header("location:add-menu.php?status=error");	
	}

}

if (isset($_POST['Page-edit'])) {
	$title = $_POST['Title'];
	$details = $_POST['editor1'];
	$slug = $_POST['Category'];
	$img = $_POST['im'];
	$id = $_POST['idi'];
	echo $slug;
	$result = $conn->query("Update post SET title = '$title',details = '$details', pro_img = '$img' where id ='$id'");
	if($result){
		if($title!=$slug){
			$cat = $conn->query("Update post SET cat_slug = '".slug($slug)."' where id ='$id'");
		}
		header("location:edit.php?post_type=page&status=success");
	}
	else{
		header("location:edit.php?post_type=page&status=error");
	}	
}

if (isset($_POST['Post-edit'])) {
	$title = $_POST['Title'];
	$details = $_POST['editor1'];
	$slug = $_POST['Category'];
	$cati = $_POST['cati'];
	$img = $_POST['im'];
	$id = $_POST['idi'];
	echo $slug;
	$result = $conn->query("Update post SET title = '$title',details = '$details', pro_img = '$img' where id ='$id'");
	if($result){
		if($cati!=$slug){
			$cat = $conn->query("Update post SET cat_slug = '".slug($slug)."' where id ='$id'");
		}
		header("location:edit.php?post_type=post&status=success");
	}
	else{
		header("location:edit.php?post_type=post&status=error");
	}	
}
