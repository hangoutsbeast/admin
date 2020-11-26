 <?php
require ('include/connection.php');

if(isset($_POST['forget'])){
	extract($_POST);
$user = $_POST['fmail'];
	
	$run = $conn->query("select * from registration where name = '$fmail'");
	if($run->num_rows>0){
		$value = $run->fetch_assoc();

		$to = 'designtoonsvishal@gmail.com';
		$subject = 'Enquiry';
		$from = 'www.unmuktuddan.org';
		$headers  = 'MIME-Version: 3.2.2' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from."\r\n".
		    'Reply-To: '.$from."\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		$message = "<p>Your admin password is</p><h1 style='padding:20px; color:#f00'>".$value['password']."</h1>

		";    
		if(mail($to, $subject, $message, $headers)){
			   $msg = 'Your mail has been sent successfully.';
			} else{
			    $msg = 'Unable to send email. Please try again.';
			}

			}else{
				echo "unable to send";
			}
	

}

  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<body>
	<div class="forget">
		<form method="post" action="forget_password.php">

			<?php if($msg){ ?>
			<div class="error"><?=$msg?></div>
			<?php } ?>
		<h3>Enter your user name</h3>
		<input type="text" name="fmail">
		<input type="submit" name="forget">
		</form>
	</div>
</body>
</html>