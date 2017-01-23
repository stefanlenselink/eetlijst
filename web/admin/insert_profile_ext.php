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
	if ((isset($HTTP_POST_VARS["verzonden"])) && ($HTTP_POST_VARS["verzonden"] == "profile_ext")) 
	{
		if (!$_POST['delete']) 
		{		
			//Omrekenen datum voor mysql
			$birthdate_m = mysql_date($birthdate);
			$user_in_m = mysql_date($user_in);
			$user_out_m = mysql_date($user_out);
			
			if ($password=="") 
			{
				$querystring = "UPDATE users 
								SET 
								username='$username',
								user_email='$user_email',
								user_level='$users_level',
								user_active='$user_active',
								user_in='$user_in_m',
								user_out='$user_out_m',
								firstname='$firstname',
								lastname='$lastname',
								birthdate='$birthdate_m', 
								study='$study',
								bankaccount='$bankaccount',
								account_place='$account_place'
								WHERE
								id='".$profile_id."' ";
			}
			
			else 
			{
				// codeer wachtwoord  
				$encryptedpass = md5($_POST['password']); 
		
				$querystring = "UPDATE users 
								SET 
								username='$username',
								password='$encryptedpass', 
								user_email='$user_email',
								user_level='$users_level',
								user_active='$user_active',
								user_in='$user_in_m',
								user_out='$user_out_m',
								firstname='$firstname',
								lastname='$lastname',
								birthdate='$birthdate_m', 
								study='$study',
								bankaccount='$bankaccount',
								account_place='$account_place'
								WHERE
								id='".$profile_id."' ";
			}
			$query = mysql_query($querystring); 
		}

		else  
		{  
			$querystring4 = "DELETE FROM users WHERE id='".$profile_id."' ";
			$query4		 = mysql_query($querystring4); 
		}
					
		//Refresh page
		echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.opener.location.reload();window.close()</script>";
	}	
	mysql_close($rDbConn);
}
?>
