<?php
if($_POST)
{
		$to = "Annemarie.vz@gmx.com";
		$subject = "HTML email";
		
		$message = "
		<html>
		<head>
		<title>Notification</title>
		</head>
		<body>".
		"<p>" . $_POST['message'] . "</p>".
		
		"</html>
		";
		
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		// More headers
		$headers .= 'From: <'.$_POST['email'].'>' . "\r\n";
		
		if(mail($to,$subject,$message,$headers))
		{
			echo 'Your Request has been sent to the Administrator!';
		}	
}
else 
{
		echo "Request format unknown";
}
