<?php 
session_start();
include_once("include.php");
//error_reporting(0); //SAC
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8"></meta>
<meta name="viewport" content="user-scalable=yes, initial-scale=1, minimum-scale=1, maximum-scale=1 width=device-width, height=device-height, target-densitydpi=device-dpi" ></meta>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="JS/Default.js" type="text/javascript"></script>
<title>Login</title>
<?php callCSS();
if(count($_POST))
{
	if($_POST['username'] != '')
	{
		if($_POST['password'] != '')
		{
			$data = array
   			(
				'ws'=>'00',
				'var1'=>$_POST['username'],
				'var2'=>$_POST['password'],
				'var3'=>NULL,
				'var4'=>NULL,
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
			
			$userLogin = Process($data);


			if($userLogin->Status)
			{
				$_SESSION['User']['UserID']=$userLogin->User_ID;
				$_SESSION['User']['Role'] = $userLogin->Role;
				$_SESSION['User']['Name'] = $userLogin->Name;
				$_SESSION['User']['Mobile'] = $userLogin->Mobile;
				$_SESSION['User']['Username'] = $userLogin->Username;
				$_SESSION['User']['Password'] = $userLogin->Password;
			}
		}
	}
}
if(isset($_SESSION['User']['Role']))
{
	if($_SESSION['User']['Role'] == 'Admin')
	{
		//echo '<tt><pre>'. var_export($_SESSION,true). '</pre></tt>';
		call_CSS_Admin();
	}
	else 
	{
		//echo '<tt><pre>'. var_export($_SESSION,true). '</pre></tt>';
		//echo "<br /> Call User layout";
		//session_destroy();
	}
}
?>
</head>
<body   id="Container">
<?php 

if(!isset($_SESSION['User']['UserID']))
{
	include_once("login.php");
	//session_destroy();
}
else 
{
	//echo '<tt><pre>'. var_export($_SESSION,true). '</pre></tt>';
	//
	if($_SESSION['User']['Role'] == 'Admin')
	{
		?>
		<table id="managersTable" cellpadding="0" cellspacing="0" border=0>
			<tr>
		    	<td style="padding-bottom:5px">
		        	<img src="Images/SACLogo.jpg" border="0" /> <!-- width="126.3" height="104.6"  -->
		        </td>
		    </tr>
		    <tr>
		    	<td>
					<ul id="nav">
						<li class="top"><a href="javascript:void(0)" class="top_link"><span>Home</span></a></li>
						<li class="top"><a href="javascript:void(0)" id="Urls" class="top_link"><span class="down">Urls</span></a>
							<ul class="sub">
								<li><a href="javascript:void(0)" onclick="requestContent('urls','urls','urls');">Urls</a></li>
								<li><a href="javascript:void(0)" onclick="requestContent('urls','newUrl','newUrl');">New Url</a></li>
							</ul>
						</li>					
						<li class="top"><a href="javascript:void(0)" id="staff" class="top_link"><span class="down">Staff</span></a>
							<ul class="sub">
								<li><a href="javascript:void(0)" onclick="requestContent('staff','staff','staff');">Staff</a></li>
								<li><a href="javascript:void(0)" onclick="requestContent('staff','newStaff','newStaff');">New Staff</a></li>
							</ul>						
						</li>	
						<li class="top"><a href="javascript:void(0)" id="staff" class="top_link"><span class="down">Reports</span></a>
							<ul class="sub">
								<li><a href="javascript:void(0)" onclick="requestContent('report','report','report');">Staff Urls</a></li>
							</ul>						
						</li>										
							 <li class="top"><a href="javascript:void(0)" onclick="requestContent('logout','logout','logout');" id="Logout" class="top_link"><span>Logout</span></a>
						</li>
					</ul>    	
				</td>
		    </tr>
		    <tr>
		    	<td id="spacer"><br /></td>
		    </tr>    
		    <tr>
		    	<td id="ProgressIndicator" class="hidden"></td>
		    </tr>
		    <tr>
		    	<td id="spacer"><br /></td>
		    </tr> 
		    <tr>
		    	<td id="Selection" class="hidden">
				</td>
		    </tr>        
		    <tr>
		    	<td id="Content" class=""> 
		    	   <?php //echo '<tt><pre>'. var_export($_SESSION,true). '</pre></tt>'; 
		    	     echo '<b>Welcome</b> ' . $_SESSION['User']['Name'];		    	   
		    	   ?>   	
				</td>
		    </tr>
		</table>
		<?php 
	}
	else 
	{
		//session_destroy();
			echo '<br /><center><img src="Images/SACLogo.PNG" width="86.3" height="64.6" border="0" /></center><br />';
			echo '<br /><center>Welcome <b>'. $_SESSION['User']['Name'] . '</b></center><br />';
			$data = array
   			(
				'ws'=>'05',
				'var1'=>'userDash',
				'var2'=>$_SESSION['User']['UserID']
			);

			$result = Process($data);

			print_r('<center>'.$result->Data.'</center>');
			//echo '<a href="#Foo" onclick="runMyFunction();">Do it!</a>';
			//
			//<br /><center>
			//<a href="javascript:void(0)" onclick="requestContent('logout','logout','logout');" id="Logout" class="top_link"><span>Logout</span></a></center>
			
					
	}

}
?>
</body>
</html>
