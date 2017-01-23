<?
//inloggen db
require('../functions.php');  
if(FALSE!==($rDbConn=connectdb()))  
{  
    if(!check_login($rDbConn))  
    {  
        header('location: ../login.php');  
        exit;  
    }  
	 
	elseif(!check_userlevel(9)) 
    { 
        echo ('access denied');   
		exit; 
	} 


	//Check verzonden
	if ((isset($HTTP_POST_VARS["verzonden"])) && ($HTTP_POST_VARS["verzonden"] == "add_user")) {
		
		//Omrekenen datum voor mysql
		$birthdate_m = mysql_date($birthdate);
		$user_in_m = mysql_date($user_in);
		$user_out_m = mysql_date($user_out);
		
		//Check input & codeer wachtwoord  
		if ((check_input($username,username)) && (check_input($password,password)))
		{
	   		$encryptedpass = md5($_POST['password']); 
	
			$querystring = "INSERT INTO users 
							VALUES (
							'',
							'$username',
							'$encryptedpass',
							'$user_email',
							'$users_level',
							'$user_active', 
							'$user_in_m',
							'$user_out_m',
							'$firstname',
							'$lastname',
							'$birthdate_m',
							'$room',
							'$study',					
							'$bankaccount', 
							'$account_place')";
			 
			$query = mysql_query($querystring); 
		}
			
		//Close and refresh page
		echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.opener.location.reload();window.close()</script>";
				
	
	mysql_close($rDbConn);
	}
}

else 
{
	//Close page
	echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
}
?>
