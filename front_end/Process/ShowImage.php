<?php 
session_start();
include_once("../General/db.php");
include_once("../include.php");
ini_set("memory_limit","128M");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Call Image View</title>
</head>
<body>
<?php 
	$thisCall =  $_GET['call'];
	
	//echo '../Images/AuditImages/'.showImage($thisCall);
	
	//echo '<img src="'.showImage($thisCall).'">';
	
	echo '<img src="../Images/AuditImages/'.showImage($thisCall).'">';

	function showImage($thisCall)
	{
		$query = "SELECT Image FROM image_path WHERE Call_ID = '".$thisCall."' ";
		
		$tableDB = mysqli_query($query)
			or die(mysqli_error().$query);

		$result = '';

		if($row = mysqli_fetch_object($tableDB))
		{
			$result = $row->Image;
		}
		return $result;
	}
?>
</body>
</html>
