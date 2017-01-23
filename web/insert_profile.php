<?
require_once("config.inc.php");
require_once('functions.php');

if(FALSE!==($rDbConn=connectdb()))
{
    if(!check_login($rDbConn))
    {
       echo "<script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
       exit;
    }

	//Check verzonden
	if ((isset($HTTP_POST_VARS["verzonden"])) && ($HTTP_POST_VARS["verzonden"] == "profile")) 
	{
		$curUser = $user->getUserById($_SESSION["user_id"]);
		$curUser->password = $_POST["password"];
		$curUser->email = $_POST["user_email"];
		$curUser->firstname = $_POST["firstname"];
		$curUser->lastname = $_POST["lastname"];
		$curUser->birthdate = $_POST["birthdate"];
		$curUser->room = $_POST["room"];
		$curUser->study = $_POST["study"];
		$curUser->bankaccount = $_POST["bankaccount"];
		$curUser->bankaccountplace = $_POST["account_place"];
		if ($_POST["password"] == ""){
			$curUser->update(false);
			//close page
			echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
		}else{
			$curUser->update(true);
			//logout
			setcookie('validate','',time(),'/','heemskerkstraat.nl');
			setcookie('user_id',0,time(),'/','heemskerkstraat.nl');
			$_SESSION['ingelogd']=FALSE;
			session_destroy(); 
			//Close and refresh page
			echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.opener.location.reload();window.close()</script>";
		}
	}
}
?>
