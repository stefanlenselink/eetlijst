<?
class Form extends GlobalClass
{
	var $_formname; // naam formulier
	var $_datum_m; // datum uit formulier in mysqli-format
	var $_enroll; // opgegeven?
	var $_edit_id; //

	//constructor
	public function __construct()
	{
		//initialiseer
		parent::__construct();
		$this->_edit_id = $_GET['id'];
	}

	//zet naam formulier
	function SetFormname($formname)
	{
		switch ($formname)
		{
	    	case 4:
	        	$this->_formname = 'Wijzig_opgeven';
				break;
	    	case 5:
	        	$this->_formname = 'Wijzig_kosten';
				break;
			default:
			    $this->_formname = $formname;
				break;
		}

	}

	//Invoerdatum checken
	function CheckDate()
	{
		if ($this->_datum_m < $this->_exp_from)
		{
			echo "<script language=\"javascript\" type=\"text/javascript\">alert(\"Invoerdatum ligt voor de verrekendatum!!\");window.close('form2.php')</script>";
		}
	}

	//Check of iemand zich al opgegeven heeft
	function CheckEnroll()
	{
	    $rDbConn = connectdb();
		$query = mysqli_query($rDbConn, "SELECT id, exp_date FROM enroll WHERE exp_date='".$this->_datum_m."' ");
		$aantal = mysqli_num_rows($query);
		if ($aantal == 0)
		{
			$this->_enroll = 0;
		}
		else
		{
			$this->_enroll = 1;
			$obj = mysqli_fetch_array($query);
			$this->_edit_id = $obj['id'];
		}

	}

	//Begin formulier
	function FormStart()
	{
		echo('
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="eetlijst.css" rel="stylesheet" type="text/css">
		<title>.:'.$this->_formname.':.</title>
		<script language="javascript" type="text/javascript">');
		include ("script/datumcheck.php");
		echo ('
		</script>
		</head>
		<body class="body">
		<form method="post" name="'.$this->_formname.'" action="insert.php">
		<table align="center" class="tb2">
		 <tr>
			<td class="h1" align="left">Datum:</td>
			<td class="h1" align="right">'.($_POST['exp_date']?$_POST['exp_date']:$_GET['exp_date']).'
				<input type="hidden" name="datum_m" value="'.$this->_datum_m.'">
 		</tr>');
	}

	//Dropdown kok
	function FormKok()
	{
	    $rDbConn = connectdb();
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		$kok = $user->getUserById($_SESSION['user_id']);

		//Check of er al een kok is
		if ($this->_enroll == 1)
		{
			$query2 = mysqli_query($rDbConn, "SELECT * FROM ".$this->_table_1." WHERE exp_date='".$this->_datum_m."' ");
			$obj2 = mysqli_fetch_array($query2);
			//$query3	= mysqli_query("SELECT id, username FROM users WHERE id=".$obj2['user_id']);
			//$obj3 = mysqli_fetch_array($query3);
			$u = $user->getUserById($obj2['user_id']);
		}

		echo ('
	   	 <tr>
			<td align="left" class="h2">Kok:</td>
			<td class="h2" align="right">
				<select name="user__id" class="input_1">
				<option value="'.$obj2['user_id'].'">'.($u->username?$u->username:'geen').'</option>');

		if ($_SESSION['user_id'] != $obj2['user_id'])
		{
			echo('
				<option value="'.$_SESSION['user_id'].'">'.$kok->username.'</option>');
		}

		if (!empty($u->username))
		{
			echo('
				<option value="0">Geen</option>');
		}

		echo('
				</select>
			</td>
		 </tr>');
	}

	//Lijst met users (dropdownboxes)
	function FormList()
	{
	    $rDbConn = connectdb();
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		foreach($user->getAllUsers() as $user2){
			if (($this->_enroll == 1) || (isset($this->_edit_id)))
			{
				$query3 = mysqli_query($rDbConn, "SELECT user_id, nb FROM ".$this->_table_2." WHERE exp_id='".$this->_edit_id."' AND user_id='".$user2->userid."' ");
				$aantal = mysqli_num_rows($query3);
				if ($aantal == 0)
				{
					echo ('
					 <tr>
						<td align="left" class="h2">'.$user2->username.'</td>
						<td align="right" class="h2">
							<select name="id'.$user2->userid.'" class="input_1">');

						//-->loop voor # gasten
						for ($i = 0; $i <= 5; $i++)
						{
							echo('
							<option value="'.$i.'">'.$i.'</option>');
						}

					echo ('
							</select>
						</td>
					 </tr>');
				}
				else
				{
					$obj3 = mysqli_fetch_array($query3);
					echo ('
					 <tr>
						<td align="left" class="h2">'.$user2->username.'</td>
						<td align="right" class="h2">
							<select name="id'.$user2->userid.'" class="input_1">');

							//-->loop voor # gasten
							for ($i = 0; $i <= 5; $i++)
							{
								if ($obj3['nb']==$i)
								{
								echo('
									<option value="'.$i.'" selected>'.$i.'</option>');
								}
								else
								{
								echo('
									<option value="'.$i.'">'.$i.'</option>');
								}
							}

					echo ('
							</select>
						</td>
					 </tr>');
				}
			}
			else
			{
				echo ('
				 <tr>
					<td align="left" class="h2">'.$user2->username.'</td>
					<td align="right" class="h2">
						<select name="id'.$user2->userid.'" class="input_1">');

						//-->loop voor # gasten
						for ($i = 0; $i <= 5; $i++)
						{
							echo('
							<option value="'.$i.'">'.$i.'</option>');
						}

					echo ('
						</select>
					</td>
				 </tr>');
			}
		}
	}

	//Input-veld voor opgave kosten
	function FormCost()
	{
	    $rDbConn = connectdb();
		if ($this->_table_1 == 'expenses')
		{
			$query = mysqli_query($rDbConn, "SELECT exp, description FROM ".$this->_table_1." WHERE id='".$this->_edit_id."' ");
			$obj = mysqli_fetch_array($query);
		}
		echo ('
		 <tr>
			<td align="left" class="h2">Kosten:</td>
			<td align="right" class="h2"><input type="text" name="exp" value="'.($obj['exp']?$obj['exp']:'').'" class="input_1"></td>
		 </tr>
		 <tr>
			<td align="left" class="h2">Omschrijving:</td>
			<td align="right" class="h2"><input type="text" maxlength="12" name="description" value="'.($obj['description']?$obj['description']:'').'" class="input_1"></td>
		 </tr>');
	}

	//Einde formulier
	function FormEnd()
	{
		if (isset($this->_edit_id))
		{
			echo('
			 <tr>
				<td align="right" class="h1"><input type="submit" value="Verwijderen" name="delete" class="button"></td>');
		}
		else
		{
			echo('
			 <tr>
				<td align="right" class="h1"><input type="Reset" value="Wissen" name="Reset" class="button"></td>');
		}
		echo('
			<td align="left" class="h1"><input type="submit" value="Opslaan" class="button"></td>
		 </tr>
		</table>
			<input type="hidden" name="verzonden" value="'.$this->_formname.'">
			<input type="hidden" name="edit_id" value="'.$this->_edit_id.'">
		</form>
		</body>
		</html>');
	}
}
?>
