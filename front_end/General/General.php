<?php
function CreateDateSelect($id, $date, $additional = "") {
	$select = "<select name=\"day" . $id . "\" id=\"day" . $id . "\"" . $additional . " style=\"width:40px;\">\n";
	for ($i = 1; $i < 32; $i ++) {
		$selected = "";
		if ($i == date("d",$date))
			$selected = " selected=\"selected\"";
		$select .= "<option value=\"" . $i . "\"" . $selected . ">" . $i . "</option>\n";
	}
	$select .= "</select>\n";
	$select .= " <select name=\"month" . $id . "\" id=\"month" . $id . "\"" . $additional . " style=\"width:40px;\">\n";
	for ($i = 1; $i < 13; $i ++) {
		$selected = "";
		if ($i == date("m",$date))
			$selected = " selected=\"selected\"";
		$select .= "<option value=\"" . $i . "\"" . $selected . ">" . $i . "</option>\n";
	}
	$select .= "</select>\n";
	$select .= " <select name=\"year" . $id . "\" id=\"year" . $id . "\"" . $additional . " style=\"width:60px;\">\n";
	for ($i = 2008; $i < (date("Y") + 3); $i ++) {
		$selected = "";
		if ($i == date("Y",$date))
			$selected = " selected=\"selected\"";
		$select .= "<option value=\"" . $i . "\"" . $selected . ">" . $i . "</option>\n";
	}
	$select .= "</select>\n";
	return $select;
}


 function findexts ($filename) 
 { 
	 $filename = strtolower($filename); 
	 $exts = explode('.', $filename); 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
 } 











































function listDatabases()
{
	$dbs = array();
	$excl = array( "\$dbname");//"information_schema","mysql","phpmyadmin","webauth");
	$res = mysql_query("SHOW DATABASES");
	
	while ($row = mysql_fetch_assoc($res)) {
	    if(!in_array($row['Database'],$excl))
	    {
	    	if(!count($dbs))
	    	{
	    		$dbs['NULL']='Select';
	    	}
	    	else 
	    	{
	    		$dbs[$row['Database']]=$row['Database'];
	    	}
	    }
	}
	return $dbs;	
}

function tableList($db, $DBCon)
{
	$tableList = array();
	
	$tables = mysql_list_tables($db, $DBCon);
	
	for($i=0; $i < mysql_num_rows($tables); $i++)
	{
	  if(!count($tableList))
      {
    	$tableList['NULL']='Select';
      }
	  $tableList[mysql_tablename($tables, $i)]=mysql_tablename($tables, $i);
	}
	return $tableList; 	
}

function userLogin()
{
	$html = "";
	$html .= '

	<div id="registration">
	 <h2>User Login</h2>
	
	 <form id="RegisterUserForm" action="#" method="get">
	 	<fieldset>
	 	<!--
	         <p>
	            <label for="name">Name</label>
	            <input id="name" name="name" type="text" class="text" value="" />
	         </p>
	        
	         <p>
	            <label for="tel">Phone Number</label>
	            <input id="tel" name="tel" type="tel" class="text" value="" />
	         </p>
	        -->
	         <p>
	            <label for="email">Email</label>
	            <input id="email" name="email" type="email" class="text" value="" />
	         </p>
	        
	         <p>
	            <label for="password">Password</label>
	            <input id="password" name="password" class="text" type="password" />
	         </p>
	        <!--
	         <p><input id="acceptTerms" name="acceptTerms" type="checkbox" />
	            <label for="acceptTerms">
	                I agree to the <a href="">Terms and Conditions</a> and <a href="">Privacy Policy</a>
	            </label>
	         </p>
	        -->
	         <p>
	            <button id="registerNew" type="button" onclick="javascript:userLogin();">Login</button>
	         </p>
	 	</fieldset>
	
	 </form>
	</div>
';
	echo $html;	
}

function stockView()
{
	//Stock Administration;
	$content .= "<h3>Stock Administration</h3>";
	//$content .= "<input type='hidden' name='Client' id='Client' value='$client'>";
	$content .= "<table width=100% class='reference'><tbody>";	
	
	$stock = getStock();
	
	//print "<pre> Here: ";
	//print_r($stock);
	//print "</pre>";
	
	$content .= '<tr><th>Stock_Description</th><th>Stock_Code</th><th>Economic_Batch_Quantity</th><th>Lead_Days</th><th>Buying_Rule</th><th>Dock_to_Stock</th><th>Stock_on_hold</th><th>Safety_Stock_Quantity</th><th>Maximum_Quantity</th><th>Minimum_Quantity</th></tr>';
	
/*
	    [0] => stdClass Object
        (
            [Stock_ID] => 1
            [Stock_Code] => DRV  1090  030  00
            [Stock_Description] => WASSARA DRILL TUBE 10-3005
            [Economic_Batch_Quantity] => 25
            [Lead_Days] => 0
            [Buying_Rule] => A
            [Dock_to_Stock] => 21
            [Stock_on_hold] => 0
            [Safety_Stock_Quantity] => 100
            [Maximum_Quantity] => 400
            [Minimum_Quantity] => 50
        )
        */
		
	if($stock[0])
	{
		while($row = mysql_fetch_object($stock[1]))
		{
			$content .= '<tr><td><a class="decor" href="javascript:editStock('.$row->Stock_ID.');">'.$row->Stock_Description.'</a></td><td>'.$row->Stock_Code.'</td><td>'.$row->Economic_Batch_Quantity.'</td><td>'.$row->Lead_Days.'</td><td>'.$row->Buying_Rule.'</td><td>'.$row->Dock_to_Stock.'</td><td>'.$row->Stock_on_hold.'</td><td>'.$row->Safety_Stock_Quantity.'</td><td>'.$row->Maximum_Quantity.'</td><td>'.$row->Minimum_Quantity.'</td>';
			$content .= '</tr>';
		}			
	}
	else 
	{
		echo "No Stock Records";
	}

	$content .= '</tbody></table>';
	
	print_r($content);		
}

function getStock()
{
   	$query = "SELECT * FROM stock ";
	
   	$tableDB = mysql_query($query)
		or die(mysql_error().$query);
		
	$stock = array();
	
	if(mysql_num_rows($tableDB))
	{
		$stock[0]=1;
		$stock[1]=$tableDB;
	}
	else 
	{
		$stock[0]=0;		
	}
	
	return $stock;
}












   
    function userDetail($DBCon)
   {
		if($_SESSION['User'])
		{
	   		//session_start();
	   		$_SESSION['User_Role'] = getUserRole($DBCon);
//print "<pre>";
	//print_r($_SESSION);
	
	//print "</pre>";   		
	   		$_SESSION['User_Site'] = getUserSite($DBCon);		
		}

   }
   
   function checkUser($DBCon, $user, $pass)
   {
   		$query = "SELECT User_ID FROM user WHERE Username = '".$user."' AND Password = '".$pass."'  ";
		$tableDB = mysql_query($query,$DBCon)
			or die(mysql_error().$query);
		
		//session_start();
			
		if(mysql_num_rows($tableDB) > 0)
		{
			$row = mysql_fetch_object($tableDB);
			$_SESSION['User']=$row->User_ID;
		}
		else 
		{
			$_SESSION['User']=0;
		}

   }  

   function getUserRole($DBCon)
   {
   		$query = "SELECT Role_ID FROM user_role WHERE User_ID = " . $_SESSION['User'];
		$tableDB = mysql_query($query,$DBCon)
			or die(mysql_error().$query);

		$User_IDs = array();
		
		if(mysql_num_rows($tableDB) > 0)
		{
			if($row = mysql_fetch_object($tableDB))
			{
				$User_IDs[]=$row->Role_ID; 
			}
		}
		else 
		{
			$User_IDs[]=0; 
		}
		
		return $User_IDs[0];
   }
   
   function getUserSite($DBCon)
   {
   		$query = "SELECT Site_ID FROM user_site WHERE User_ID = " . $_SESSION['User'];
		$tableDB = mysql_query($query,$DBCon)
			or die(mysql_error().$query);

		$Site_IDs = array();
		
		if(mysql_num_rows($tableDB) > 0)
		{
			if($row = mysql_fetch_object($tableDB))
			{
				$Site_IDs[]=$row->Site_ID; 
			}
		}
		else 
		{
			$Site_IDs[]=0; 
		}
		
		return $Site_IDs[0];
   }  

   function Process($request) 
   {   
   		if($request['ws'] == '00') 
   		{
   			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2'],
				'var3'=>NULL,
				'var4'=>NULL,
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
   		} 
   		
   		if($request['ws'] == '01')
   		{
			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2'],
				'var3'=>$request['var3'],
				'var4'=>$request['var4'],
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
   		}
   		
      	if($request['ws'] == '02')
   		{
			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2'],
				'var3'=>$request['var3'],
				'var4'=>$request['var4'],
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
   		}  

      	if($request['ws'] == '03')
   		{
			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2'],
				'var3'=>$request['var3'],
				'var4'=>$request['var4'],
				'var5'=>$request['var5'],
				'var6'=>$request['var6'],
				'var7'=>$request['var7'],
   				'var8'=>$request['var8']
			);
   		}   

      	if($request['ws'] == '04')
   		{
			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2'],
				'var3'=>$request['var3']
			);
   		}   

      	if($request['ws'] == '05')
   		{
			$data = array
   			(
				'ws'=>$request['ws'],
				'var1'=>$request['var1'],
				'var2'=>$request['var2']
			);
   		}    
   		
   		if(isset($data))
   		{
			$cRequest = new SoapClient
			(
				null, array 
				(
					'location' => "http://localhost/back_end/process.php",
					'uri'      => "urn://tyler/req",
					'trace'    => 1 
				)
			);
			
			$result= $cRequest->__soapCall
			(
				"Request",array($data)
			);
			
			if($result != '' )
			{				  
		        $content =  $result;	
			}
			else 
			{ 
				 $content  = "No Matching Data was Found Or Something is missing in the request...";
			}
			
			return $content;
   		}
   }   

 