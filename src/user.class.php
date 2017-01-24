<?
abstract class User {
	public static $NORMALUSER = 0;
	public static $ADMINUSER = 9;
	public $username;
	public $active;
	public $usertype = 0;
	public $email;
	public $firstname;
	public $lastname;
	public $birthdate;
	public $room;
	public $study;
	public $bankaccount;
	public $bankaccountplace;
	public $password;
	public $userid;

	public abstract static function getTotalNumberOfUsers($user_active = true, $user_level = 0);
	public abstract static function getUserById($userid);
	public abstract static function getUserByUsernameEmail($username, $email);
	public abstract function update($password = false);
	public abstract function userExists($username, $email);
	public abstract function login($username, $password);
	public abstract function printLoginForm();
	public function getAllUsers(){
		if(!isset($_SESSION["allusers"])){
			//store it
			$_SESSION["allusers"] = $this->getAllUsersImpl();
		}
		return $_SESSION["allusers"];
	}
	protected abstract function getAllUsersImpl();
	public function getUsernameById($userid){
		if(!isset($_SESSION["user" . $userid])){
			$_SESSION["user" . $userid] = $this->getUserById($userid);
		}
		return $_SESSION["user" . $userid]->username;
	}
	public function isVisible(){
		return ($this->active && ($this->usertype == self::$NORMALUSER));
	}
}

class DBUser extends User {
	public function __construct($userid = 0){
		require_once('functions.php');
		if(FALSE!==($rDbConn=connectdb()) && $userid){
			$query = mysqli_query($rDbConn, "SELECT * FROM users WHERE id=" . $userid . ";");
			if($userinfo = mysqli_fetch_array($query)){
				$this->username = $userinfo["username"];
				$this->active = $userinfo["user_active"] == 0 ? true : false;
				$this->usertype = $userinfo["user_level"];
				$this->email = $userinfo["user_email"];
				$this->firstname = $userinfo["firstname"];
				$this->lastname = $userinfo["lastname"];
				$this->birthdate = $userinfo["birthdate"];
				$this->room = $userinfo["room"];
				$this->study = $userinfo["study"];
				$this->bankaccount = $userinfo["bankaccount"];
				$this->bankaccountplace = $userinfo["account_place"];
				$this->userid = $userid;
			}else{
				die("User not found!");
			}
		}
	}
	public static function getUserById($userid){
		return new DBUser($userid);
	}
	public static function getUserByUsernameEmail($username, $email){
	    $rDbConn=connectdb();
		$query = mysqli_query($rDbConn, "SELECT id FROM users WHERE username='" . $username . "' and user_email = '" . $email . "';");
		if($userinfo = mysqli_fetch_array($query)){
			return new DBUser($userinfo["id"]);
		}else{
			//TODO Error handling
			return null;
		}
	}
	public static function getTotalNumberOfUsers($user_active = true, $user_level = 0){
		if(FALSE!==($rDbConn=connectdb())){
			$query = mysqli_query($rDbConn, "SELECT id FROM users WHERE user_active=" . ($user_active ? "\"yes\"" : "\"no\"") . " AND user_level=\"" . $user_level . "\"");
			return mysqli_num_rows($query);
		}
	}
	protected function getAllUsersImpl(){
		$returnArray = array();
		$rDbConn=connectdb();
		$query = mysqli_query($rDbConn, "SELECT id FROM users WHERE user_active='yes' AND user_level='0' ORDER BY id ASC");
		while ($obj = mysqli_fetch_array($query)){
			$userid = $obj["id"];
			if(!isset($_SESSION["user" . $userid])){
				$_SESSION["user" . $userid] = $this->getUserById($userid);
			}
			$returnArray[] = $_SESSION["user" . $userid];
		}
		return $returnArray;
	}
	public function update($password = false){
	    $rDbConn=connectdb();
		$query = "UPDATE users SET
				user_email='" . $this->email . "',
				firstname='" . $this->firstname . "',
				lastname='" . $this->lastname . "',
				birthdate='" . $this->birthdate . "',
				study='" . $this-> study . "',
				bankaccount='" . $this->bankaccount . "',
				account_place='" . $this->bankaccountplace . "'" . ( $password ? ", password='" . trim(md5(trim($this->password))) . " ' " : "") . "
				WHERE id='" . $this->userid . "';";
		$res = mysqli_query($rDbConn, $query);
	}
	public function userExists($username, $email){
	    $rDbConn=connectdb();
		$query = "SELECT * FROM users WHERE username='$username' AND user_email='$email' AND user_active='yes' ";
		$resultaat = mysqli_query($rDbConn, $query) or die (mysqli_error($rDbConn));
		$aantal = mysqli_num_rows($resultaat);
		return ($aantal == 1 ? true : false);
	}
	public function login($username, $password){
		$rDbConn=connectdb();
		$sQuery='SELECT id, user_level FROM users WHERE username="'.$_POST['username'].'" AND password="'.md5($_POST['password']).'" AND user_active="yes"';
		if(!$rResult=mysqli_query($rDbConn, $sQuery)){
			echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
			return false;
		}else{
			if(mysqli_num_rows($rResult)==0){
				echo "<script language=\"javascript\" type=\"text/javascript\">alert(\"Username and/or password are incorrect\");history.go(-1)</script>";
				return false;
			}else{
				//En dan komt hier het loginverhaal
				//willekeurige string maken
				$sValidate=md5(rand(0,99999));

				//cookies setten voor 7dagen
				$data = mysqli_fetch_array($rResult);
				$iUserId=$data['id'];
				$iUserLevel=$data['user_level'];
				setcookie('validate',$sValidate,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
				setcookie('user_id',$iUserId,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
				setcookie('user_level',$iUserLevel,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');

				//de gegevens in de tabel zetten
				$sQuery='REPLACE INTO logins (tijdstip, validate, user_id, client_ip) VALUES (NOW(), "'.$sValidate.'", '.$iUserId.', "'.$_SERVER['REMOTE_ADDR'].'")';
				if(!mysqli_query($rDbConn, $sQuery)){
					echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
				}

				//de sessie gegevens schrijven
				$_SESSION['user_id']=$iUserId;
				$_SESSION['user_level']=$iUserLevel;
				$_SESSION['ingelogd']=TRUE;
				$_SESSION['client_ip']=$_SERVER['REMOTE_ADDR'];
				//TODO moet toch anders.....
				require("config.inc.php");
				$_SESSION["logedinuser"] = $user->getUserById($iUserId);
				return true;
			}
		}
	}
	public function printLoginForm(){
		echo('
			<!--   start login    -->

			<form name="inlogform" method="post" action="' . $_SERVER["PHP_SELF"] . '">
			<table width="200" align="center" class="tb2">
			 <tr>
				<td class="h1" align="center" colspan="2">Inloggen</td>
			 </tr>
			 <tr>
				<td class="h2" align="left">username</td>
				<td class="h2" align="right"><input type="text" name="username" class="input_1"></td>
			 </tr>
			 <tr>
				<td class="h2" align="left">password</td>
				<td class="h2" align="right"><input type="password" name="password" class="input_1"></td>
			 </tr>
			 <tr>
				<td class="h2">
					<a class="h4" href="getpassword.php" onClick="return popitup2(\'getpassword.php\')">forgot password?</a>
				</td>
				<td class="h2" align="right"><input type="submit" class="button" value="Inloggen"></td>
			 </tr>
			</table>
			</form>

			<!--   einde login   -->');
	}
}

class LDAPUser extends User {
	public static function getTotalNumberOfUsers($user_active = true, $user_level = 0){
		return 5;
	}
	public function __construct($userid = 0){
		//TODO fill in LDAP
		$this->username = "Empty from LDAP";
		$this->active = true;
		$this->usertype = self::$NORMALUSER;
	}
	public static function getUserById($userid){
		return new LDAPUser($userid);
	}
	public static function getUserByUsernameEmail($username, $email){

	}
	public function update($password = false){

	}
	public function userExists($username, $email){
		return true;
	}
	protected function getAllUsersImpl(){
	}
	public function login($username, $password){
		$auth->start();
		if($auth->checkAuth()){
		    $rDbConn = connectdb();
			//de sessie gegevens schrijven
			$sQuery='SELECT id, user_level FROM users WHERE username="'.$_POST['username'].'" AND password="'.md5($_POST['password']).'" AND user_active="yes"';
			$rResult=mysqli_query($rDbConn, $sQuery);
			//willekeurige string maken
			$sValidate=md5(rand(0,99999));

			//cookies setten voor 7dagen
			$data = mysqli_fetch_array($rResult);
			$iUserId=$data['id'];
			$iUserLevel=$data['user_level'];
			setcookie('validate',$sValidate,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
			setcookie('user_id',$iUserId,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
			setcookie('user_level',$iUserLevel,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');

			//de gegevens in de tabel zetten
			$sQuery='REPLACE INTO logins (tijdstip, validate, user_id, client_ip) VALUES (NOW(), "'.$sValidate.'", '.$iUserId.', "'.$_SERVER['REMOTE_ADDR'].'")';
			if(!mysqli_query($rDbConn, $sQuery))
			{
				echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
			}

			//de sessie gegevens schrijven
			$_SESSION['user_id']=$iUserId;
			$_SESSION['user_level']=$iUserLevel;
			$_SESSION['ingelogd']=TRUE;
			$_SESSION['client_ip']=$_SERVER['REMOTE_ADDR'];

			//cookies setten voor 7dagen
			setcookie('validate',$sValidate,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
			setcookie('user_id',$_SESSION['user_id'],time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
			setcookie('user_level',$_SESSION['user_level'],time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
			//TODO moet toch anders.....
			require_once("config.inc.php");
			$_SESSION["logedinuser"] = $user->getUserById($iUserId);
			return true;
		}else{
			return false;
		}
	}
	public function printLoginForm(){
		$auth->start();
	}
}
?>