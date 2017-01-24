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
			//Omrekenen datum voor mysqli
			$birthdate_m = mysqli_date($birthdate);
			$user_in_m = mysqli_date($user_in);
			$user_out_m = mysqli_date($user_out);

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
			$query = mysqli_query($rDbConn, $querystring);
		}

		else
		{
			$querystring4 = "DELETE FROM users WHERE id='".$profile_id."' ";
			$query4		 = mysqli_query($rDbConn, $querystring4);
		}

		//Refresh page
		echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.opener.location.reload();window.close()</script>";
	}
	mysqli_close($rDbConn);
}
?>
