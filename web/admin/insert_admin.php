<?
require('../functions.php');
if(FALSE!==($rDbConn=connectdb()))
{
    if(!check_login($rDbConn))
    {
       header('location: ../login.php');
       exit;
    }

	if(!check_userlevel(9))
    {
        echo ('access denied');
		exit;
	}

	//Check verzonden
	if ((isset($HTTP_POST_VARS["verzonden"])) && ($HTTP_POST_VARS["verzonden"] == "admin")) {

			//UPDATE `site` SET `update` = NOW() WHERE `id` = 1 LIMIT 1;
			$querystring = "UPDATE site
							SET
							title='$title',
							version='$version',
							text='$text',
							updated= NOW()
							WHERE
							id='1' ";
			$query = mysqli_query($rDbConn, $querystring);


			//Reload parent
			header('location: index.php');

	}

	mysqli_close($rDbConn);
}

else
{
	//Close page
	echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
}
?>
