<?php 
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Promotions</title>
<script type="text/javascript" src="promo.js"></script> 
<link href="styles.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<table id="managersTable" cellpadding="0" cellspacing="0" border=1>
	<tr>
    	<td style="padding-bottom:5px">
        	<h2>CMS Builder 1.0</h2>
        </td>
    </tr>
    <tr>
    	<td style="background-color:#2C6700">
        	<table id="managersMenu" cellpadding="0" cellspacing="0">
            	<tr>
                	<td align="center">
                	<a href="javascript:adminProcedures();">Database Builder</a>
                    </td>	            	
                	<td align="center">
                	<a href="javascript:loadNewPromotion();">Page Builder</a>
                    </td>				
                	<td align="center">
                    	<a href="javascript:managePromotion();">Option</a>
                    </td>			
                	<td align="center">
                    	<a href="javascript:manageClients();">Option</a>
                    </td> 
                    <td align="center">
                    <a href="javascript:GetDownloads();">Option</a>
                    </td> 
                	<td align="center">
                    	<a href="javascript:manageClientsList();">Option</a>
                    </td> 
      				<td align="center">
                    	<a href="javascript:manageStores();">Option</a>
                    </td>                      
                    <td align="center">
                    	<a href="javascript:manageStaffInit();">Option</a>
                    </td>                                                       
                    <td align="center">
                    	<a href="logout.php">Logout</a>
                    </td>                                       
                </tr>
			</table>
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
    	<td id="Content" class="hidden">
		</td>
    </tr>
</table>
<?php 
	if(isset($_SESSION[redirectArray]))
	{
		?>
		<script>
				var action = "init";
				var client = <?php echo $_SESSION[redirectArray][client];?>;
				var promo = <?php echo $_SESSION[redirectArray][promo];?>;
				var questionnaire = <?php echo $_SESSION[redirectArray][questionnaire];?>;
				var array = [action,client,promo,questionnaire];
				sndReqGraphing(array);
		</script>
		<?php 
		unset($_SESSION[redirectArray]);
	}
?>
</body>
