<?php
error_reporting(E_ALL);
//function Request($request, $var1=NULL, $var2=NULL, $var3=NULL, $var4=NULL, $var5= NULL, $var6=NULL, $var7=NULL){
function Request($request){
	print "<pre>";
print_r($request);die;
$host = "localhost";
$username = "";
$password = "";
$db = "";

$DBCon = mysql_connect($host, $username, $password);
mysql_select_db($db,$DBCon);

set_time_limit(0);	

 if($request['ws'] == '00')
 {
 	$content = userLogin($request);
 }
 elseif ($request['ws'] == '01')
 {
 	$content = urls($request);
 }
 elseif ($request['ws'] == '02')
 {
 	$content = promotionRequest($request);
 }  
 elseif ($request['ws'] == '03')
 {
 	$content = staff($request);
 }  
 elseif ($request['ws'] == '04')
 {
 	$content = report();
 }   
 elseif ($request['ws'] == '05')
 {
 	$content = userDash($request);
 }  
 else 
 {
 	$content = 'Unknown request!';
 }
  
 return $content; 
}

$server = new SoapServer(null,
                         array('uri' => "urn://tyler/res"));
$server->addFunction('Request');
//$server->addFunction('shout');
//$server->addFunction(array('Request', 'shout'));
//$server->addFunction(SOAP_FUNCTIONS_ALL);
$server->handle(); 

//FUNCTIONS

function userLogin($ar)
{
	extract($ar);

	$query = "SELECT user.User_ID, user_role.Role, user.Username, user.Password, user_detail.Name, user_detail.Surname, user_detail.Mobile FROM user, user_detail, user_role WHERE user_detail.User_ID = user.User_ID AND user_role.User_Role_ID = user.User_Role_ID AND user.Username = '".$var1."' AND user.Password = '".$var2."'  ";
		$tableDB = mysql_query($query)
			or die($er = mysql_error().$query);
			
	$result = new stdClass();
					
	if($row = mysql_fetch_object($tableDB))
	{
		$result->Status = true;
		$result->User_ID = $row->User_ID;
		$result->Role = $row->Role;
		$result->Username = $row->Username;
		$result->Password = $row->Password;
		$result->Name = $row->Name . ' ' . $row->Surname;
		$result->Mobile = $row->Mobile;
	}
	else 
	{
		$result->Status = false;
		$result->Error = 'No Matching Record Found';
	}
	
	
				
	return $result; 
}

/** 
 * Url List with Edit and Delete options ...
 * @return All Url Rows ...
 */
function urls($ar)
{
	extract($ar);

	if($var1 == 'urls')
	{
		return callUrls();
	}
	
	if($var1 == 'editUrl' || $var1 === 'newUrl')
	{
		/**
		 * Capture New Url Details and Fill Details for Edit ...
		 * @param $rowID for Editing / String 'newUrl' for Capture Form ...
		 * @return Capture Form for New Url Data / Url Data for Editing ...
		 */
		
		
		if($var1 === 'newUrl')
		{
			$row = 0;
		}
		else 
		{
			$row = urlData($var2);
		}
		
		$result = new stdClass();

		$content = '<fieldset>';
		if($row)
		{
			$content .= '<legend><b>Edit Url</b></legend>';
			$content .= '<table>';
				$content .= '<tr><td></td><td><input type="hidden" name="rowID" id="rowID" value="'.$row->Url_ID.'" class=""></td></tr>';
				$content .= '<tr><td>Url:</td><td><input type="text" name="url" id="url" size="100" value="'.$row->Url.'" class=""></td></tr>';
				$content .= '<tr><td>Name:</td><td><input type="text" name="name" id="name" size="100" value="'.$row->Name.'" class=""></td></tr>';
				$content .= '<tr><td></td><td><input type="button" value="Update" onclick="requestContent('."'urls'".','."'updateUrl'".','.$row->Url_ID.');" class=""></td></tr>';
			$content .= '</table></fieldset>';

			$result->Status = true;
			$result->Data = $content;
		}
		else 
		{
			$content .= '<legend><b>New Url</b></legend>';
			$content .= '<table>';
				$content .= '<tr><td></td><td><input type="hidden" name="rowID" id="rowID" value="saveUrl" class=""></td></tr>';
				$content .= '<tr><td>Url:</td><td><input type="text" size="100" name="url" id="url" class=""></td></tr>';
				$content .= '<tr><td>Name:</td><td><input type="text" size="100" name="name" id="name" class=""></td></tr>';
				$content .= '<tr><td></td><td><input type="button" value="Save" onclick="requestContent('."'urls'".','."'saveUrl'".','."'saveUrl'".');" class=""></td></tr>';
			$content .= '</table></fieldset>';	

			$result->Status = true;
			$result->Data = $content;
		}
	}

	if($var1 == 'saveUrl')
	{
		return saveUrl($var3, $var4);
	}

	if($var1 == 'updateUrl')
	{
		return updateUrl($var2, $var3, $var4);
	}
	
	
	
	return $result; 
}

	function promotionRequest($ar)
	{
		extract($ar);
	
		if($var1 == 'urlStaff')
		{
			
		}
		
		if($var3 === 'list')
		{
			//if($this->_Category === 'promotionStaff')
				//$this->_promotionStaff();
				
			if($var1 == 'urlStaff')	
			    return urlStaff($var2);
		}

		if($var3 === 'add')
		{
			//if($this->_Category === 'promotionStaff')
				//$this->_promotionAddStaff();
				
			if($var1 == 'urlStaff')
				return urlAddStaff($ar);				
		}	

		if($var3 === 'delete')
		{
			//if($this->_Category === 'promotionStaff')
				//$this->_promotionDeleteStaff();
				
			if($var1 == 'urlStaff')			
				return urlDeleteStaff($ar); 
		}		
		
	}

	function urlStaff($var2)
	{
		$searchArray = array(1=>'Add Staff',2=>'Delete Staff');
		$content = '<fieldset>';
		$content .= '<legend><b>Add / Delete Staff</b></legend>';
		$content .= '<table>';
		$content .= '<tr><td>Url:</td><td><input type="text" name="name" id="name" value="'.getUrlName($var2).'" disabled="disabled" class=""></td></tr>';
		
		
		$onChangeLink = NULL; //"verifyValue(this.options[selectedIndex].value,"."'storeSearch'".")";
		$pullDownResource = new PullDown();
		$pullDown = $pullDownResource->CreatePullDown('action', $searchArray, $selected = 1, $select = 1, $onChange = $onChangeLink);
		$content .= '<tr><td>Action:</td><td>'.$pullDown.'</td></tr>';
		
		$staff = allStaff();
		
		$onChangeLink2 = NULL;
		$pullDownResource2 = new PullDown();
		$pullDown2 = $pullDownResource2->CreatePullDown('rowID', $staff, $selected = NULL, $select = 1, $onChange = $onChangeLink2);
		$content .= '<tr><td>Staff:</td><td>'.$pullDown2.'</td></tr>';		
		
		$content .= '<tr><td></td><td><input type="button" value="Submit" onclick="promotionRequest('."'promotionRequest'".','."'urlStaff'".','.$var2.','."'request'".','."'request'".');" class=""></td></tr>';
		$content .= '</table></fieldset><br />';
		
		$result = new stdClass();
		$result->Status = true;
		$aar = $content.'|'. getUrlStaff($var2);
		$result->Data = $aar;
		
		return $result;
		//print_r($content.'|'. getUrlStaff($var2));
	}
	
	function allStaff()
	{		
		$query = "SELECT  user_detail.User_ID, user_detail.Name, user_detail.Surname, user_detail.Mobile FROM user_detail,user WHERE user_detail.User_ID = user.User_ID AND user.User_Role_ID = 1 ";
	
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		$content = array();
		
		while($row = mysql_fetch_object($tableDB))
		{	
			 $content[$row->User_ID]=$row->Name . ' ' . $row->Surname;
		}
		return $content; 
	}
	
	function getUrlStaff($var2)
	{
		$query = "SELECT  user_detail.* FROM user_detail, staff_url WHERE staff_url.Url_ID = " . $var2. " AND staff_url.User_ID = user_detail.User_ID ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
		
		if(mysql_num_rows($tableDB) > 0)
		{
			$content = "<table width=100% class='reference'><tbody>";
			$content .= '<tr><th>Name</th><th>Surname</th><th>Mobile</th><th colspan=1></th></tr>';		
			
			while($row = mysql_fetch_object($tableDB))
			{
				$content .= '<tr><td>'.$row->Name.'</td><td>'.$row->Surname.'</td><td>'.$row->Mobile.'</td><td><a href="javascript:void(0)" onclick="promotionRequest('."'promotionRequest'".','."'urlStaff'".','.$var2.','."'delete'".','.$row->User_ID.');" class="">Delete</a></td></tr>';
			}	
			$content .= "</tbody></table><br />";
			return $content;
		}
		else
		{
			return ' ';
		}	
	}

	/**
    * Get Store Name ...
    * @return string Store Name ...
    */ 
   function getUrlName($var2)
   {
	   $query = "SELECT  Name FROM url WHERE Url_ID = " . $var2;	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);

		if($row = mysql_fetch_object($tableDB))
		{
			return $row->Name;
		}
   }	

	/**
	 * Get Url Data to Edit ...
	 * @param Url rowID (Url_ID) ...
	 * @return Url data...
	 */
	function urlData($Url_ID)
	{
		$query = "SELECT * FROM url WHERE Url_ID = '".$Url_ID."' LIMIT 1 ";	
		
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);	
			
		if($row = mysql_fetch_object($tableDB))	
		{
			return $row;
		}	
	}
	
	/** 
	 * Url List with Edit and Delete options ...
	 * @return All Url Rows ...
	 */
	function callUrls()
	{
		$query = "SELECT  * FROM url ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		$result = new stdClass();
			
		$content = "<table width=100% class='reference'><tbody>";
		$content .= '<tr><th>Url</th><th>Name</th><th colspan=2></th></tr>';
	
		if(mysql_num_rows($tableDB) > 0)
		{
			while($row = mysql_fetch_object($tableDB))
			{
				$content .= '<tr><td>'.$row->Url.'</td><td>'.$row->Name.'</td><td><a href="javascript:void(0)" onclick="requestContent('."'urls'".','."'editUrl'".','.$row->Url_ID.');">Edit</a></td><td><a href="javascript:void(0)" onclick="promotionRequest('."'promotionRequest'".','."'urlStaff'".','.$row->Url_ID.','."'list'".','."'0'".');">Staff</a></td></tr>';
			}	
			$content .= "</tbody></table><br />";
			
			$result->Status = true;
			$result->Data = $content;
			
			return $result;
		}
		else {
			$content .= "</tbody></table><br />";
			
			$result->Status = false;
			$result->Data = $content;
		}
		return $result;
	}	
	
	/**
	 * Insert New Store Data ...
	 */
	function saveUrl($var3, $var4)
	{
		$query = "INSERT INTO url (Url, Name) VALUES('".$var4."','".$var3."') ";	
		
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		return callUrls();		
	}	

		/**
	 * Update Edited Url Data
	 * @param Url Data Array
	 * @return none / Just triggers
	 */
	function updateUrl($var2, $var3, $var4)
	{
		$query = "UPDATE url SET Url = '".$var4."', Name = '".$var3."' WHERE Url_ID = '".$var2."' ";	
		
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
						
		return callUrls();			
	}
	
	function report()
	{
		return callReport();
	}

	
	function userDash($ar)
	{
		extract($ar);
		
		$result = new stdClass();
				
		$query = "SELECT  url.Url, url.Name FROM staff_url, url WHERE staff_url.User_ID = $var2 AND url.Url_ID = staff_url.Url_ID ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
	
		//$roles = $this->_roleList(); 
		
		//<input type="button" value="View Report" onclick="OpenInNewTab('.$page_id.');" class="">
			
		//$content = "<table width=100% class='reference'><tbody>";
		$content = "<table class='reference'><tbody>";
		//$content .= '<tr><th>Link</th><th></th></tr>';
		$content .= '<tr><th>Link</th></tr>';
		
		while($row = mysql_fetch_object($tableDB))
		{ //
			//$content .= '<tr><td>'.$row->Name.'</td><td><input type="button" value="Go" onclick="OpenInNewTab('.$row->Url.');" class=""></td></tr>';
			$content .= '<tr><td><a href="'.$row->Url.'" target="_blank">'.$row->Name.'</a></td></tr>';
		}	
		$content .= "</tbody></table><br />";
		
		$result->Status = true;
		$result->Data = $content;
		return $result;		
	}
		
	/** 
	 * Staff Url List  ...
	 * @return All Url Rows assigned to each staff...
	 */
	function callReport()
	{
		
		 //:: staff_url ::-> User_ID	Url_ID	Date	Staff_Url_ID 
		
		//:: user_detail ::-> User_Detail_ID	User_ID	Name	Surname	Mobile
		
		//:: url ::-> Url_ID	Url	Name
		
		$query = "SELECT  user_detail.Mobile AS Position, url.Name AS Url, CONCAT_WS(' ', user_detail.Name, user_detail.Surname) AS Staff  
		FROM staff_url, url, user_detail  
		WHERE user_detail.User_ID = staff_url.User_ID AND url.Url_ID = staff_url.Url_ID ORDER BY CONCAT_WS(' ', user_detail.Name, user_detail.Surname)";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		$result = new stdClass();
			
		$content = "<table width=100% class='reference'><tbody>";
		$content .= '<tr><th>Staff</th><th>Position</th><th>Url</th></tr>';
	
		if(mysql_num_rows($tableDB) > 0)
		{
			while($row = mysql_fetch_object($tableDB))
			{
				$content .= '<tr><td>'.$row->Staff.'</td><td>'.$row->Position.'</td><td>'.$row->Url.'</td></tr>';
			}	
			$content .= "</tbody></table><br />";
			
			$result->Status = true;
			$result->Data = $content;
			return $result;
		}
		else {
			$content .= "</tbody></table><br />";
			
			$result->Status = false;
			$result->Data = $content;
			return $result;
		}
	}	
	
	function staff($ar)
	{
		extract($ar);
	
		if($var1 == 'staff')
		{
			return callStaff();
		}
		
		if($var1 == 'newStaff')
		{
			return staffForm($var1, $var2);
		}	

		if($var1 == 'editStaff')
		{
			return staffForm($var1, $var2);
		}	

		
		if($var1 == 'updateStaff')
		{
			return updateStaff($ar);
		}
			
		if($var1 == 'saveStaff')
		{
			return saveStaff($ar);
		}	
		
		if($var1 == 'deleteStaff')
		{
			return deleteStaff($ar);
		}		
		
	}
	
	/**
	 * Insert New Staff Data ...
	 */
	function saveStaff($ar)
	{
		extract($ar);
		/*
		 * 
		 Array
		(
		    [0] => staff
		    [1] => saveStaff
		    [2] => saveStaff
		    [3] => USERNAME
		    [4] => PASS
		    [5] => 2
		    [6] => NAME
		    [7] => SURNAME
		    [8] => POSITION
		)* 
		 */
		// User_ID 	Username 	Password 	User_Role_ID 
		$query = "INSERT INTO user(Username,Password,User_Role_ID) VALUES('".$var3."','".$var4."','".$var5."') ";	

		$tableDB = mysql_query($query)
			or die(mysql_error().$query);

		if($tableDB)
		{
			$idDB = mysql_query("SELECT LAST_INSERT_ID() FROM user")
					or die(mysql_error());
				if ($id = mysql_fetch_array($idDB))
					$_USER_ID = $id[0];	
		}		
			
			
		// User_Detail_ID 	User_ID 	Name 	Surname 	Mobile 
		$query = "INSERT INTO user_detail(User_ID,Name,Surname,Mobile) VALUES('".$_USER_ID."','".$var6."','".$var7."','".$var8."') ";	

		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
			
			
		return callStaff();		
	}	
	
	/**
	 * Update Edited Staff Data
	 * @return none / Just triggers all Staff Listing....
	 */
	function updateStaff($ar)
	{
		/*
		Array
		(
		    [0] => staff
		    [1] => updateStaff
		    [2] => 2
		    [3] => admin
		    [4] => test
		    [5] => 2
		)
*/
		extract($ar);
		
		// User_Detail_ID 	User_ID 	Name 	Surname 	Mobile 
		$query = "UPDATE user_detail SET Name = '".$var6."', Surname = '".$var7."', Mobile = '".$var8."'  WHERE user_detail.User_ID = '".$var2."' ";	

		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		// User_ID 	Username 	Password 	User_Role_ID 
		$query = "UPDATE user SET Username = '".$var3."', Password = '".$var4."', User_Role_ID = '".$var5."'  WHERE user.User_ID = '".$var2."' ";	

		$tableDB = mysql_query($query)
			or die(mysql_error().$query);			
						
		return callStaff();	
	}	

		/** 
	 * Staff List with Edit and Delete options ...
	 * @return All Staff Rows ...
	 */
	function callStaff()
	{
		$selection = '';
		
		$query = "SELECT  user_detail.*,user.User_Role_ID, user.Username, user.Password FROM user_detail, user WHERE  user_detail.User_ID = user.User_ID AND user.User_ID != 4 ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			 
		$roles = array(1=>'User',2=>'Admin');
			
		$content = "<table width=100% class='reference'><tbody>";
		$content .= '<tr><th>Staff ID</th><th>Name</th><th>Surname</th><th>Position</th><th>Role</th><th>Username</th><th>Password</th><th colspan=2></th></tr>';		
		
		while($row = mysql_fetch_object($tableDB))
		{ 
			$content .= '<tr><td>'.$row->User_ID.'</td><td>'.$row->Name.'</td><td>'.$row->Surname.'</td><td>'.$row->Mobile.'</td><td>'.$roles[$row->User_Role_ID].'</td><td>'.$row->Username.'</td><td>'.$row->Password.'</td><td><a href="javascript:void(0)" onclick="requestContent('."'staff'".','."'editStaff'".','.$row->User_ID.');">Edit</a></td><td>
			
			<!--<font color="gray">Delete</font>-->
			<a href="javascript:void(0)" onclick="requestContent('."'staff'".','."'deleteStaff'".','.$row->User_ID.');">Delete</a>
			
			</td></tr>';
		}	
		$content .= "</tbody></table><br />";
		
		$result = new stdClass();
		$result->Status = true;
		$result->Data = $content; //$selection.'|'.$content;
			
		return $result;
		
	}
	
	/**
	 * Get Staff Data to Edit ...
	 * @param Staff rowID (User_ID) ...
	 * @return Staff data...
	 */
	function staffData($var2)
	{
		
		$query = "SELECT  user_detail.*,user.User_Role_ID, user.Username, user.Password FROM user_detail, user WHERE  user.User_ID =  $var2 AND user_detail.User_ID = user.User_ID ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
		$result = new stdClass();;
			
		if($row = mysql_fetch_object($tableDB))	
		{
			$result->Username = $row->Username;
			$result->Password = $row->Password;
			$result->User_Role_ID = $row->User_Role_ID;
			$result->Name = $row->Name;
			$result->Surname = $row->Surname;
			$result->Mobile = $row->Mobile;			
		}	
		return $result;
	}	
	
	/**
    * Get all Roles ...
    * @return All Roles ...
    */
   function roles()
   {
      	$query = "SELECT * FROM user_role ";
		$tableDB = mysql_query($query)
			or die(mysql_error().$query);	
			
		$roles = array();
		
		while($row = mysql_fetch_object($tableDB))
		{
			$roles[$row->User_Role_ID]=$row->Role;
		}
		return $roles;	
   }	

	/**
	 * Capture New Staff Details and Fill Details for Edit ...
	 * @param $rowID for Editing / String 'newStaff' for Capture Form ...
	 * @return Capture Form for New Staff Data / Staff Data for Editing ...
	 */
	function staffForm($var1, $var2)
	{
		$roles = roles();
				
		if($var1  === 'newStaff')
		{
			$row = 0;
		}
		else 
		{
			$row = 1;
			$staffRow = staffData($var2);
		}

		$content = '<fieldset>';
		if($row)
		{
			$content .= '<legend><b>Edit Staff</b></legend>';
			$content .= '<table>';
				$content .= '<tr><td></td><td><input type="hidden" name="rowID" id="rowID" value="'.$var2.'" class=""></td></tr>';
				$content .= '<tr><td>Name:</td><td><input type="text" name="name" id="name" value="'.$staffRow->Name.'" class=""></td></tr>';
				$content .= '<tr><td>Surname:</td><td><input type="text" name="surname" id="surname" value="'.$staffRow->Surname.'" class=""></td></tr>';
				//$content .= '<tr><td>Mobile:</td><td><input type="text" name="mobile" id="mobile" value="'.$this->_mobileFormat('save',$this->getMobile()).'" onkeyup="verifyValue(this.id,'."'number'".');" class=""></td></tr>';
				//$content .= '<tr><td>Mobile:</td><td><input type="text" name="mobile" id="mobile" value="'.$staffRow->Mobile.'" onkeyup="verifyValue(this.id,'."'number'".');" class=""></td></tr>';
				$content .= '<tr><td>Position:</td><td><input type="text" name="mobile" id="mobile" value="'.$staffRow->Mobile.'" class=""></td></tr>';
				
				$onChangeLink = NULL;
				$pullDownResource = new PullDown();
				$pullDown = $pullDownResource->CreatePullDown("Role", $roles, $staffRow->User_Role_ID, $select = 1, $onChange = $onChangeLink); 	 				
				
				$content .= '<tr><td>Role:</td><td>'.$pullDown.'</td></tr>';
				$content .= '<tr><td>Username:</td><td><input type="text" name="username" id="username" value="'.$staffRow->Username.'" class=""></td></tr>';
				$content .= '<tr><td>Password:</td><td><input type="text" name="password" id="password" value="'.$staffRow->Password.'" class=""></td></tr>';
				$content .= '<tr><td></td><td><input type="button" value="Update" onclick="requestContent('."'staff'".','."'updateStaff'".','.$var2.');" class=""></td></tr>';
			$content .= '</table></fieldset>';		
		}
		else 
		{
			$content .= '<legend><b>New Staff</b></legend>';
			$content .= '<table>';
				$content .= '<tr><td></td><td><input type="hidden" name="rowID" id="rowID" value="saveStaff" class=""></td></tr>';

				$content .= '<tr><td>Name:</td><td><input type="text" name="name" id="name" class=""></td></tr>';
				$content .= '<tr><td>Surname:</td><td><input type="text" name="surname" id="surname" class=""></td></tr>';
				$content .= '<tr><td>Position:</td><td><input type="text" name="mobile" id="mobile" class=""></td></tr>';
				
				$onChangeLink = NULL;
				$pullDownResource = new PullDown();
				$pullDown = $pullDownResource->CreatePullDown("Role", $roles, $selected = NULL, $select = 1, $onChange = $onChangeLink); 	 				
				
				$content .= '<tr><td>Role:</td><td>'.$pullDown.'</td></tr>';
				$content .= '<tr><td>Username:</td><td><input type="text" name="username" id="username" onchange="verifyValue(this.id,'."'username'".');" class=""><span id="checkUsername"></span></td></tr>';
				$content .= '<tr><td>Password:</td><td><input type="text" name="password" id="password"  class=""></td></tr>';				
				
				
				$content .= '<tr><td></td><td><input type="button" value="Save" onclick="requestContent('."'staff'".','."'saveStaff'".','."'saveStaff'".');" class=""></td></tr>';
			$content .= '</table></fieldset>';		
		}
		
		$result = new stdClass();
		$result->Status = true;
		$result->Data = $content; 
			
		return $result;	
	}	
	
	function urlAddStaff($ar)
	{
		extract($ar);
		//Staff_Store_ID 	User_ID 	Store_ID 	Date 
	   $query = "SELECT * FROM staff_url WHERE Url_ID = ".$var2." AND User_ID = ". $var4 . " LIMIT 1 ";	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
	   if(!$row = mysql_fetch_object($tableDB))
	   {
		   $query = "INSERT INTO staff_url (Url_ID, User_ID, Date) VALUES(".$var2.",".$var4.",'".time()."')";	
			
		   $tableDB = mysql_query($query)
				or die(mysql_error().$query);	   	
	   }
	   return urlStaff($var2);
	} 	
	
	function urlDeleteStaff($ar)
	{
		extract($ar);
		
		//Staff_Store_ID 	User_ID 	Store_ID 	Date 
	   $query = "SELECT * FROM staff_url WHERE Url_ID = ".$var2." AND User_ID = ". $var4 . " LIMIT 1 ";	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
	   if($row = mysql_fetch_object($tableDB))
	   {
		   $query = "DELETE FROM staff_url WHERE Url_ID = ".$var2." AND User_ID = ".$var4;	
			
		   $tableDB = mysql_query($query)
				or die(mysql_error().$query);	   	
	   }
	   
	   return urlStaff($var2);
	}	
	
	function deleteStaff($ar)
	{
		extract($ar);
		delete_staff_urls($var2);
		delete_staff_user($var2);
		delete_staff_user_details($var2);
		return callStaff();
	}
	
	function delete_staff_urls($userID)
	{
	   $query = "SELECT * FROM staff_url WHERE User_ID = ". $userID . " LIMIT 1 ";	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
	   if($row = mysql_fetch_object($tableDB))
	   {
		   $query = "DELETE FROM staff_url WHERE User_ID = ".$userID;	
			
		   $tableDB = mysql_query($query)
				or die(mysql_error().$query);	   	
	   }		
	}
	
	function delete_staff_user($userID)
	{
	   $query = "SELECT * FROM user WHERE User_ID = ". $userID . " LIMIT 1 ";	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
	   if($row = mysql_fetch_object($tableDB))
	   {
		   $query = "DELETE FROM user WHERE User_ID = ".$userID;	
			
		   $tableDB = mysql_query($query)
				or die(mysql_error().$query);	   	
	   }		
	}
	
	function delete_staff_user_details($userID)
	{
	   $query = "SELECT * FROM user_detail WHERE User_ID = ". $userID . " LIMIT 1 ";	
		
	   $tableDB = mysql_query($query)
			or die(mysql_error().$query);
			
	   if($row = mysql_fetch_object($tableDB))
	   {
		   $query = "DELETE FROM user_detail WHERE User_ID = ".$userID;	
			
		   $tableDB = mysql_query($query)
				or die(mysql_error().$query);	   	
	   }			
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	/////classes
	
	class PullDown 
{
    public function __construct() 
    {
   		//$this->setClientID($client);
    }
   
	public function CreatePullDown($name, $array, $selected = NULL, $select = 0, $onChange = NULL) 
	{		
		if($onChange != NULL)
		{
			$pullDown = "<select name=\"" . $name . "\" id=\"" . $name . "\"  onchange=\"".$onChange."\">\n";
		}
		else 
		{
			$pullDown = "<select name=\"" . $name . "\" id=\"" . $name . "\">\n";
		}
		
		
		if($select)
		{
			$pullDown .= '<option value="0">Select</option>';
		}
		foreach ($array as $key => $value) {
			$optionValue = $key;
			$text = $value;
			if (is_array($value)) {
				$optionValue = $value[0] . "";
				$text = $value[1];
			}
			$select = "";
			if ($selected == $optionValue) {
				$select = " selected=\"selected\"";
			}
			$pullDown .= "<option value=\"" . $optionValue . "\"" . $select . ">" . $text . "</option>\n";
		}
		$pullDown .= "</select>\n";
		return $pullDown;
	}

   public function __destruct() 
   {
   	
   }
}
?>
