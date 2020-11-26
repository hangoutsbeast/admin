<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
	<title>Admin Panel</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="main">
	<div class="login">
		<form class="form" method="post" onsubmit="return validation()">
		  <h2>ADMIN PANEL</h2>
		  <div class="form-group">
		    <label for="user">Email / User Name:</label>
		    <input type="text" name="user" class="form-control" id="user">
		    <div class="error error-user"></div>
		  </div>
		  <div class="form-group">
		    <label for="pwd">Password:</label>
		    <input type="password" name="password" class="form-control" id="pwd">
		    <div class="error error-pass"></div>
		  </div>
		  <div class="checkbox">
		    <label><input type="checkbox"> Remember me</label>
		  </div>
		  <div class="text-center mt-4">
		  	<button type="submit" name="login" id="login" class="btn btn-primary mt-4">Submit</button>
		  </div>
		  <input type="hidden" id="session" value="<?=(empty($_SESSION['admin'])?'':''.$_SESSION["admin"].'')?>" >	
		  <div class="alert text-center mt-4 alert-danger" style="display: none;margin-bottom: 0" role="alert"></div>
  		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		var ses = $('#session').val();
		if(!ses==''){
			window.location = 'home.php';
		}
	});
</script>
<script>
	function validation(){
		var specialChars = "<>!#$%^&*()_+[]{}?:;|'\"\\,./~`-=";
		var checkForSpecialChar = function(string){
		 for(i = 0; i < specialChars.length;i++){
		   if(string.indexOf(specialChars[i]) > -1){
		       return true
		    }
		 }
		 return false;
		}
		var user = document.getElementById('user');
		var pass = document.getElementById('pwd');
		if(user.value===''){
			$('.error-user').text('please enter the user & email..');
			$('.error').css('display','block');
			setTimeout(function(){
				$('.error').css('display','none');
			},3000)
			return false;
		} 
		if(pass.value===''){
			$('.error-pass').text('please enter the password..');
			$('.error').css('display','block');			
			setTimeout(function(){
				$('.error').css('display','none');
			},3000)			
			return false;
		}

		if(checkForSpecialChar(user.value)){
			$('.error-user').text('please enter correct user and email');	
			$('.error').css('display','block');	
		  return false;
		} else {
			$.ajax({
				url:'query.php',
				type:'post',
				data: {'user':user.value,'pass':pass.value,'login':'login'},
			}).done(function(response){
				var res = JSON.parse(response);
				if(res.error){
					$('.alert-danger').text(res.error);
					$('.alert-danger').css('display','block');
				}else{
					window.location = res.redirect;
				}
			});
			return false;
		}
	}
</script>
</body>
</html>