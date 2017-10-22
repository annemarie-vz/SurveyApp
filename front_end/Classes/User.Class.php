<?php
class User 
{ 
   /**
    * Logout User ....
    */
   public function LogoutUser()
   {
		session_destroy();
		echo " |Refresh";
   }  
}
