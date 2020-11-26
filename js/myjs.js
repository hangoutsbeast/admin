

$('#pcreate').click(function(){
	var arr = {};
	var catname = $("input[name='cate']").val();
	if(catname==''){
		$("input[name='cate']").attr('placeholder','please enter category name');
	}else{
		arr['cate'] = catname;
		arr['type'] = 'post-cat';
		arr['Create'] = 'Create';
		$.ajax({
			url:'query.php',
			type:'post',
			data:arr
		}).done(function(response){
			var res = JSON.parse(response);
			$('#Category').append(res.value);
			$("input[name='cate']").val('');
			alert(res.msg);
		});
	}
});

$('#img-gallery').change(function(){
	var file = $(this)[0].files;
	var ext = ["image/jpeg","image/png","image/jpg","image/gif"];
	var fd = new FormData(); 
	if(file.length <= 0){
		$('.upload-error').text("Please select file");
		setTimeout(function(){
			$('.upload-error').text('');
		},3000)
		return false;
	}
	if($.inArray(file[0].type,ext)=="-1"){
		$('.upload-error').text("This file is not supported");
		setTimeout(function(){
			$('.upload-error').text('');
		},3000)		
		return false;
	}
	fd.append('galimg',file[0]);	
	if($(this).length > 0){
		$.ajax({
			url:'query.php',
			type:'post',
			data:fd,
       		contentType: false,
        	processData: false
		}).done(function(response){
			console.log(response);
			var result = JSON.parse(response);
			if(result.success){
				$('.mediacat .row').append(result.success);
				location.reload();
			}else{
				alert(result.error);
			}
		});
	}
})

var table = '#mytable';
$('.crow').on('change',function(){
	$('.pagination').html('');
	var trnum = 0;
	var maxRow = parseInt($(this).val());
	var totalRows = $(table+' tbody tr').length;
	$(table+' tr:gt(0) ').each(function(){
		trnum++;
		if(trnum > maxRow){
			$(this).hide();
		}
		if(trnum <= maxRow){
			$(this).show();
		}
	});

	if(totalRows >maxRow){
		var pagenum = Math.ceil(totalRows/maxRow);
		for(var  i=1;i<=pagenum;){
			$('.pagination').append('<li class="page-item" data-page="'+i+'"><span><a class="page-link">'+ i++ +'</a></span></li>').show();
		}
	}
	$('.pagination li:first-child').addClass('active');
	$('.pagination li').on('click',function(){
		var pageNum = $(this).attr('data-page');
		var trIndex = 0;
		$('.pagination li').removeClass('active');
		$(this).addClass('active');
		$(table+' tr:gt(0) ').each(function(){
			trIndex++;
			if(trIndex > (maxRow*pageNum) || trIndex <= ((maxRow*pageNum)-maxRow)){
				$(this).hide();
			}else{
				$(this).show();
			}
		});
	});
});
$(function(){
	$.fn.editable.defaults.mode = 'inline';
	$('.popup').editable({
	    //type: 'select',
	    prepend:'Select Status',
	    source:[{value:'Draft', text:'Draft'},{value:'Publish', text:'Publish'}],
	    url: 'query.php',
	    type:'POST',
	    success:function(response, newValue) {
	    	var result = JSON.parse(response);
	    	if(result.success){
        	  alert(result.success);
	    	}else{
	    	  alert(result.error);
	    	}
    	}
	});
	$.get("query.php?page=pages",function(data,status){
		var datalist = JSON.parse(data);
		var htmldata = '';
		for(var count = 0; count < datalist.length; count++){
			htmldata += '<option value="'+datalist[count].title+'">';
		}
		$('#menus').append(htmldata);
	});
	$('table tr:eq(0)').prepend('<th>ID</th>');
	var id = 0;
	$('table tr:gt(0)').each(function(){
		id++;
		$(this).prepend('<td>'+ id + '</td>');
	});
	if(catid){
		$.post("query.php",{cat:'cat',id:catid},function(data,status){
			$('#Category').append(data);
		});			
	}else{
		$.post("query.php",{cat:'cat'},function(data,status){
			$('#Category').append(data);
		});			
	}
	$.get("query.php?img=gallery",function(data,status){
		$('ul.images').html(data);
	});
})

$(document.body).on('click','.thumbnail',function(){
	$(this).toggleClass('active').siblings().removeClass('active');
});

$(document.body).on('click','span[data-post-replace]',function(){
	var postid ="&id="+$(this)[0].dataset.postReplace;
	window.location.href = "post.php"+location.search+postid;
	
});

$(document.body).on('click','.fa-window-close',function(){
	$('.featured').remove();
	$('button[data-toggle="modal"]').css('display','block');
});

$('button[data-target]').on('click',function(){
	var toggle = $(this)[0].dataset.target;
	$(toggle).toggle();
});

$('#img-select').on('click',function(){
	var id = $('#thumbnail.active').attr('data-id');
	$.post("query.php",{imgid:id},function(data,status){
		if(status=='success'){
			$('#myModal').modal('hide');
			$('#demo').append(data).siblings().css('display','none');
		}
	})
});

$(document.body).on('click','img[data-cat]',function(){
	var link = "?idd="+$(this)[0].dataset.cat;
	window.location.href= link;
});
$(document.body).on('click','img[data-del]',function(){
	var link = $(this)[0].dataset.del;
	//$(this)[0].parant.remove();
	if (confirm("Are You Sure To Delete This Category")) {
		$.post("query.php",{del:link},function(data,status){
			if(status=='success'){
				$('.alert').text(data);
				$('.alert').show(500);
				$('.alert').addClass('alert-success');
				setTimeout(function(){
				$('.alert').hide(500);
				},6000)
			}
		});
		$(this).parents()[1].remove();
	}
});

$(document.body).on('click','span[data-trash]',function(){
	var del = $(this)[0].dataset.trash;
	if (confirm("Are You Sure To Delete This Item")) {
		$(this).parentsUntil('.row').hide();
		$.post("query.php",{imgdel:del},function(data,status){
			if(status=='success'){
				//console.log(data);
				$('.alert').text(data).addClass('alert-success').show(500);
				setTimeout(function(){
				$('.alert').hide(500);
				},3000);
			}
		});		
	}
});

$('#img-upload').on('click',function(){
	var img = $('#image-upload')[0].files;
	var ext = ["image/jpeg","image/png","image/jpg","image/gif"];
	var fd = new FormData(); 
	if(img.length <= 0){
		$('.upload-error').text("Please select file");
		setTimeout(function(){
			$('.upload-error').text('');
		},3000)
		return false;
	}
	if($.inArray(img[0].type,ext)=="-1"){
		$('.upload-error').text("This file is not supported");
		setTimeout(function(){
			$('.upload-error').text('');
		},3000)		
		return false;
	}
	fd.append('Imagep',img[0]);
	$.ajax({
		url:'query.php',
		type:'post',
		data:fd,
        contentType: false,
        processData: false,		
	}).done(function(response){
		//console.log(response);
		var result = JSON.parse(response);
		if(result.success){
			$('#myModal').modal('hide');
			$('#image').append(result.id);
			$('#demo').append(result.success).siblings().hide();
		}else{
			$('#demo').append(result.error);
			$('#demo').addClass('upload-error');
		}
	})
})

$('span[data-post-trash]').click(function(){
	var post_trash = $(this)[0].dataset.postTrash;
	$.post('query.php',{postTrash:post_trash},function(data,status){
		if(status=='success'){
			alert("post removed successfully..");
		}
	});
	$(this).parentsUntil('tbody').hide();
});

$('#category-form').submit(function(){
	$(this).children()[2].value=$('#cattable').children()[1].lastElementChild.firstChild.innerText;
	if($(this).children()[1].value==""){
		$(this).children()[1].setAttribute('placeholder','Please Enter Category Name');
		return false;
	}
	$.ajax({
		url:'query.php',
		type:'post',
		data:$(this).serialize()
	}).done(function(response){
		var result = JSON.parse(response);
		if (result.success){
			$('#cattable').append(result.value);
			$('.edit_cat .alert').addClass('alert-success').text(result.success).show(500);
		}else{
			$('.edit_cat .alert').addClass('alert-danger').text(result.error).show(500);
			setTimeout(function(){
				$('.edit_cat .alert').hide(500);
				$('.edit_cat .alert').removeClass('alert-danger');
			},6000)
		}
	});
	return false
});



$(document.body).on('click','#myPopup',function(){
	var main = $(this)[0];
	var dataset = main.dataset;
	$.ajax({
		url:'query.php',
		type:'post',
		data:{dataset:dataset}
	}).done(function(response){
		console.log(response);
		var data = JSON.parse(response);
		if(data.name){
			$(main).parentsUntil('td').text(data.name);
			$(this)[0].attr('visibility','hidden');
			//location.reload();
			alert(data.msg);
		}else{
			alert(data.msg);
		}
	});
})

$('#addpost').click(function(){
	var image = '';
	var title = $('#Title')[0];	
	var detail = CKEDITOR.instances['editor1'].getData();
	var type = $('#type')[0];
	var pstatus = $('#post_status')[0];
	var date = $('#c_date')[0];
	var category = $('#Category')[0];
	if($('input[name="im"]').length > 0){
		image = $('input[name="im"]')[0];
	}
	var post = '';
	if(type.value==='0'){
		post = {'addpost':'post','title':title.value,'detail':detail,'type':type.value,'pstatus':pstatus.value,'date':date.value,'pcategory':category.value,'image':image.value}
	}else{
		post = {'addpost':'post','title':title.value,'detail':detail,'type':type.value,'pstatus':pstatus.value,'date':date.value,'pcategory':category.value}
	}

	if(title.value===''){
		title.setAttribute('placeholder','Please Enter Title Name..');
		title.setAttribute('class','border border-danger post form-control mb-3');
		return false;
	}

	$.post('query.php',post,function(data,status){
		if(status=='success'){
			var msg = JSON.parse(data);
			if(msg.success){
				alert("Success : " + msg.success);
				location.reload();
			}else{
				alert("Error : " + msg.error);
			}
		}
	});
	return false;
})
$('#editpost').click(function(){
	var id = window.location.href.split('&')[1].split('=')[1];
	var image = '';
	var title = $('#Title')[0];
	var detail = CKEDITOR.instances['editor1'].getData();
	var category = $('#Category')[0];
	if($('input[name="im"]').length > 0){
		image = $('input[name="im"]')[0];
	}else{
		image = '0';
	}
	var post = '';
	if(type.value==='0'){
		post = {'editpost':'post','id':id,'title':title.value,'detail':detail,'pcategory':category.value,'image':image.value}
	}else{
		post = {'editpost':'post','id':id,'title':title.value,'detail':detail,'pcategory':category.value}
	}
	if(title.value===''){
		title.setAttribute('placeholder','Please Enter Title Name..');
		title.setAttribute('class','border border-danger post form-control mb-3');
		return false;
	}
	$.post('query.php',post,function(data,status){
		console.log(data);
		if(status=='success'){
			var msg = JSON.parse(data);
			if(msg.success){
				alert("Success : " + msg.success);
				location.reload();
			}else{
				alert("Error : " + msg.error);
			}
		}
	});
	return false;	
});