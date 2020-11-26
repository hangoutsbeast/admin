<?php

function getuser($user,$conn,$col){
	$stmt = $conn->query("select email,Name from admin where username='$user'");
	$get = $stmt->fetch_assoc();
	echo $get[$col];
}

function getpost($type,$conn){
	$post = ($type=='post'?'0':'1');
	$post_arr = array();
	$stmt = $conn->query("select * from post where type='$post'");
	while($get = $stmt->fetch_assoc()){
		array_push($post_arr, $get);
	}
	$count =  count($post_arr);
	$post_html ='<tbody>';
	$j = 1;
	for ($i=0; $i < $count; $i++) { 
		$post_html .= "<tr>";
		$post_html .= "<td>".$post_arr[$i]['title']."</td>";
		$post_html .= "<td><a class='popup' data-name='post' data-type='select' data-pk='".$post_arr[$i]['id']."' >".$post_arr[$i]['post_status']."</a></td>";
		$post_html .= "<td>".date("Y/m/d",strtotime($post_arr[$i]['c_date']))."</td>";
		$post_html .= '<td><span data-post-replace="'.$post_arr[$i]['id'].'" class="fa fa-pencil"></span>&nbsp;&nbsp;
              <span data-post-trash="'.$post_arr[$i]['id'].'" class="text-right fa fa-trash"></span>
            </div></td>';
		$post_html .= "</tr>";
		$j++;
	}
	return $post_html .= '</tbody>';
}

function pcount($type,$conn){
	$post = ($type=='post'?'0':'1');
	$stmt = $conn->query("select * from post where type='$post'");
	echo $stmt->num_rows;
}

function editpost($type,$id,$conn){
	$post = ($type=='post'?'0':'1');
	$stmt = $conn->query("select * from post where type='$post' and id='$id'");
	if($stmt->num_rows > 0){
		$get = $stmt->fetch_assoc();
	}
	$post_rel = $conn->query("select * from post_rel where post_id='$id'");
	if($post_rel->num_rows > 0){
		$post_get = $post_rel->fetch_assoc();
		return array_merge($get,$post_get);
	}else{
		return $get;
	}
	
}
function image($conn,$id){
	$stmt = $conn->query("select path from gallery where id='$id'");
	$image = $stmt->fetch_assoc(); 
	return $image['path'];
}

function catname($conn,$id){
	$stmt = $conn->query("select cat_name from category where cat_id='$id'");
	if($stmt->num_rows > 0){
		$cat = $stmt->fetch_assoc(); 	
		return $cat['cat_name'];
	}else{
		return "Uncategorized";
	}

}