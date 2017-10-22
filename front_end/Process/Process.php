<?php
session_start();
include_once("../General/db.php");
include_once("../include.php");
ini_set("memory_limit","128M");
//log
//report
if(count($_FILES))
{
		/*
		echo "<br /><br />";
		echo "<center><div>";
		echo '<img src="../Images/valueCheck.gif">';
		echo "<br /><br />";
		echo "</div></center>";
		
		$image = new ImageUpload($DBCon);
		$image->saveImage($action='Save',$User_ID=$_SESSION['User']['UserID'],$Call_ID=$_SESSION['Call'],$Call_Status_ID=$_SESSION['Status'],$imageObj=$_FILES);
		
		*/
		
		//echo getcwd();
		/*
		print "<pre>";
		print_r($_SESSION);
		print_r($_FILES);
		print_r($image);
		print "</pre>";
		*/
		

/*		
	Array
(
    [User] => Array
        (
            [UserID] => 1
            [Role] => User
            [Name] => John Doe
            [Mobile] => 0837651062
            [Username] => test
            [Password] => test
        )

    [Call] => 37
    [Status] => 13
)
Array
(
    [datafile] => Array
        (
            [name] => 100131_max.jpg
            [type] => image/jpeg
            [tmp_name] => C:\xampp\tmp\php9F0B.tmp
            [error] => 0
            [size] => 30556
        )

)
		*/


}
else
{
	$array = explode(",",$_GET["array"]);
	
//		print "<pre>";
//		print_r($array);
//		print_r($_SESSION);
//		print "</pre>";
//die;
		
	
	/**
	 * Request All Urls
	 */
	if($array[0] == 'urls')
	{
		//$url = new Url($DBCon,$array[1],$array[2],$array[3],$array[4]);
		
		if($array[0] == 'urls')
		{
			$data = array
   			(
				'ws'=>'01',
				'var1'=>$array[1],
				'var2'=>$array[2],
				'var3'=>$array[3],
				'var4'=>$array[4],
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
			$result = Process($data);
			
				//print "<pre>";
				//print_r($array);
				//print "</pre>";
		
			//if($result['Status'])
			//{
				print_r($result->Data);
			//}
		}
		/*
		print "<pre>";
		print_r($array);
		print "</pre>";
		
		Array
(
    [0] => urls
    [1] => urls
    [2] => urls
    [3] => 
    [4] => 
)
		*/
		//print_r(Process($data));

	}
	
	/**
	 * Request All Staff
	 */
	if($array[0] == 'staff')
	{
		// User_ID 	Username 	Password 	User_Role_ID 
		
		// User_Detail_ID 	User_ID 	Name 	Surname 	Mobile 
		
		// User_Role_ID 	Role 
		
		//$staff = new Staff($DBCon);
		//$staff->requestContent($array[1],$array[2],$array[3],$array[4],$array[5],$array[6],$array[7],$array[8]);
		/*
		Array
		(
		    [0] => staff
		    [1] => newStaff
		    [2] => newStaff
		    [3] => 
		    [4] => 
		    [5] => 
		    [6] => 
		    [7] => 
		    [8] => 
		)
		Array
		(
		    [0] => staff
		    [1] => staff
		    [2] => staff
		    [3] => 
		    [4] => 
		    [5] => 
		    [6] => 
		    [7] => 
		    [8] => 
		)		
		*/
		$data = array
   		(
			'ws'=>'03',
			'var1'=>$array[1],
			'var2'=>$array[2],
			'var3'=>$array[3],
			'var4'=>$array[4],
			'var5'=>$array[5],
			'var6'=>$array[6],
			'var7'=>$array[7],
   			'var8'=>$array[8]
		);
		
		$result = Process($data);

		print_r($result->Data);
		
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
	}
	
	/**
	 * Request All Reports
	 */	
	if($array[0] == 'report')
	{
		$data = array
   		(
			'ws'=>'04',
			'var1'=>$array[0],
			'var2'=>$array[1],
			'var3'=>$array[2]
		);
		
		$result = Process($data);

		print_r($result->Data);
		
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
	}	
	
	/**
	 * Request All Promotions
	 */
	if($array[0] == 'promotions')
	{
		$promotion = new promotion($DBCon);
		$promotion->promotionContent($array[1],$array[2],$array[3],$array[4],$array[5],$array[6]);
		
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
	}
	
	
	/**
	 * Request Schedule
	 */
	if($array[0] == 'Schedule')
	{
		$userCalls = new PromoUser($DBCon);
		$userCalls->promotionContent($array[2]);
	}	
	
	/**
	 * Promotions Request
	 */
	if($array[0] == 'promotionRequest')
	{
		/*
		if($array[1] === 'promotionStore')
		{
			$promotion = new promotion($DBCon);
			$promotion->promotionRequest($array[1],$array[2],$array[3],$array[4]);

			

		}
		*/
		if($array[1] === 'urlStaff')
		{	
			//$staff = new Staff($DBCon);
			//$staff->promotionRequest($array[1],$array[2],$array[3],$array[4]);
			/*
			Array
			(
			    [0] => promotionRequest
			    [1] => urlStaff
			    [2] => 1
			    [3] => list
			    [4] => 0
			)
			*/
			$data = array
   			(
				'ws'=>'02',
				'var1'=>$array[1],
				'var2'=>$array[2],
				'var3'=>$array[3],
				'var4'=>$array[4],
				'var5'=>NULL,
				'var6'=>NULL,
				'var7'=>NULL
			);
			$result = Process($data);

			print_r($result->Data);			
				
			//print "<pre>";
			//print_r($array);
			//print "</pre>";		
		}
		/*
		if($array[1] === 'storeStaff')
		{	
			$staff = new Staff($DBCon);
			$staff->promotionRequest($array[1],$array[2],$array[3],$array[4]);
			
			//echo "|";
			//print "<pre>";
			//print_r($array);
			//print_r($staff);
			//print "</pre>";		
		}	
		*/
	}
	
	
	/**
	 * Request All Logout
	 */
	if($array[0] == 'logout')
	{
		$logout = new User();
		$logout->LogoutUser();	
	}
	
	
	/**
	 * Request new Username Check
	 */
	if($array[0] == 'username')
	{
		$staff = new Staff();
		$staff->usernameCheck($DBCon,$array[1]);
		
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
		
	}
	
	if($array[0] == 'lat-long')
	{
		$calls = new Calls($DBCon);
		$calls->callData($lat=$array[1],$long=$array[2],$Store_ID=$array[3],$User_ID=$array[4],$Promotion_ID=$array[5],$Stamp=$array[6],NULL);
		
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
	}
	
	/**
	 * Request Calls
	 */
	if($array[0] == 'calls')
	{
		$calls = new Calls($DBCon);
		$calls->callComparison($array[0],$array[1],$array[2],$array[3],$array[4],$array[5],$array[6],$array[7]);
	
		//print "<pre>";
		//print_r($array);
		//print "</pre>";
		
	}
}
?>
