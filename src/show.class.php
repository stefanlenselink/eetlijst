<?
class Show extends GlobalClass
{
	var $_columns; //aantal kolommen
	var $_show_records;	
	
	function Show()
	{
		
		GlobalClass::Globalclass();
		
		//bepaal aantal kolommen
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		$rows = $user->getTotalNumberOfUsers(true, User::$NORMALUSER);
		$this->_columns = ($rows + "5");
		
		//bepaal te tonen aantal records
		if (!ISSET($_POST['show_records']))
			{$this->_show_records = "LIMIT 5";}
		else
			{$this->_show_records = $_POST['show_records'];}
		
	}
	
	//rij met users
	function ShowUsers()
	{
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		foreach($user->getAllUsers() as $user2){
			echo ('
			<td class="h1" width="63" align="center" >'.$user2->username.'</td>');
		}

	}

	//enroll-data
	function EnrollData()
	{
		echo('
		 <tr>
			<td class="h1" width ="75" align="left">Datum</td>
			<td class="h1" width ="65" align="left">Kok</td>');

			//laat users zien
			$this->ShowUsers();

		echo ('
			<td class="h1" width="75" align="right" colspan="2">Totaal</td>
			<td class="h1" width="100"></td>
		 </tr>
		 <tr>');

		//datum omzetten naar datum_m
		$this->mysql_date($this->_datum);

		//Enroll
		$query18 = mysql_query("SELECT *, DATE_FORMAT(exp_date,'%d-%m-%Y') as date FROM enroll WHERE exp_date='".$this->_datum_m."' ");
		$aantal = mysql_num_rows($query18);
		if ($aantal < 1) 
		{
			echo ('
				<td class="h3" align="left">'.$this->_datum.'</td>');
		}
		
		else
		{
			$obj18 = mysql_fetch_array($query18);
			echo ('
				<td class="h3" align="left">
					<a class="hyp_2" href="form.php?t=4&id='.$obj18['id'].'&exp_date='.$obj18['date'].'" onClick="popupform1(this, \'join\')">'.$this->_datum.'</a>
				</td>');
		}

		//Username zoeken bij id
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		
		echo ('
			<td class="h3" width ="65" align="left">'.($user->getUsernameById($obj18['user_id'])?$user->getUsernameById($obj18['user_id']):'geen').'</td>');
		
		//inhoud uit e_detail
		foreach($user->getAllUsers() as $user2){
			$querystring20 = "SELECT nb FROM e_detail WHERE exp_id='".$obj18['id']."' AND user_id='".$user2->userid."'";
			$query20 = mysql_query($querystring20);
			$aantal = mysql_num_rows($query20);
			if ($aantal < 1) 
			{
				echo ('
					<td class="h3" width ="63" align="center">0</td>');
			}
			else 
			{
				while ($obj20 = mysql_fetch_array($query20)) 
				{
					echo ('
						<td class="h3" width ="63" align="center">'.$obj20['nb'].'</td>');
				}
			}
		}	
			
		//sum nb uit e_detail
		$query21 = mysql_query("SELECT SUM(nb) AS nb FROM e_detail WHERE exp_date='".$this->_datum_m."'");
		$obj21 = mysql_fetch_array($query21);
		echo ('	
			<td class="h3" width ="75" align="right" colspan="2">'.($obj21['nb']?$obj21['nb']:'0').'</td>
			<td class="h3"></td>
		 </tr>
	 	 <tr>
			<td height="15" colspan="'.$this->_columns.'"></td>
		 </tr>');
	}
	
	//overzicht kosten
	function OverviewCost()
	{
		echo('
		 <tr>
			<td class="h1" width="140" colspan="2" align="left">Bewoner</td>');
	
			$this->ShowUsers();
	
		echo ('
			<td class="h1" width="75" align="right" colspan="2">Totaal</td>
			<td class="h1" width="100"></td>
		 </tr>
		 <tr>
			<td class="h3" colspan="2">Betaald</td>');
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		//Berekenen kosten per kok
		foreach($user->getAllUsers() as $user2){
			$query2 = mysql_query("SELECT ROUND(IFNULL(SUM(exp),0),2) AS paid FROM expenses WHERE user_id='".$user2->userid."' AND exp_date >= '$this->_exp_from' "); 
			$obj2 = mysql_fetch_array($query2);
	
			echo ('
				<td class="h3" align="center">'.$obj2[paid].'</td> ');
		}
	
		//Berekenen kosten totaal
		$query3 = mysql_query("SELECT ROUND(IFNULL(SUM(exp),0),2) AS paid FROM expenses WHERE exp_date >= '$this->_exp_from' "); 
		$obj3 = mysql_fetch_array($query3);
	
		echo ('
			<td class="h3" align="right" colspan="2">'.$obj3[paid].'</td>
			<td class="h3"></td>
		 </tr>
		 <tr>
			<td class="h3" colspan="2">Schuld</td>');	
		
		//Berekenen schuld per persoon	
		foreach($user->getAllUsers() as $user2){
			$query5= mysql_query("SELECT ROUND(IFNULL(SUM(exp_pu),0),2) AS debt FROM exp_detail WHERE user_id='".$user2->userid."' AND exp_date >= '$this->_exp_from'"); 
			$obj5= mysql_fetch_array($query5);
			echo('
				<td class="h3" align="center">'.$obj5[debt].'</td>');
		}
	
		//Berekenen schuld totaal
		$query6= mysql_query("SELECT ROUND(IFNULL(SUM(exp_pu),0),2) AS debt FROM exp_detail WHERE exp_date >= '$this->_exp_from'"); 
		$obj6=mysql_fetch_array($query6);
		echo ('
			<td class="h3" align="right" colspan="2">'.$obj6[debt].'</td>
			<td class="h3"></td>
		 </tr>
		 <tr>
			<td height="8" class="row_split" colspan="'.$this->_columns.'"></td>
		 </tr>
		 <tr>
			<td class="h3" colspan="2">Saldo</td>');
	
		//Saldo
		foreach($user->getAllUsers() as $user2){
			$query8 = mysql_query("SELECT ROUND(IFNULL(SUM(exp),0),2) AS paid FROM expenses WHERE user_id='".$user2->userid."' AND exp_date >= '$this->_exp_from' "); 
			$query9= mysql_query("SELECT ROUND(IFNULL(SUM(exp_pu),0),2) AS debt FROM exp_detail WHERE user_id='".$user2->userid."' AND exp_date >= '$this->_exp_from'");
			$obj8 = mysql_fetch_array($query8);
			$obj9 = mysql_fetch_array($query9);
			$tot = $obj8['paid'] - $obj9['debt'];
			$saldo = number_format($tot, 2, '.', ' ');
			echo ('
				<td class="h3" align="center">'.$saldo.'</td>');
		}
	
		$check = number_format(($obj3['paid'] - $obj6['debt']), 2, '.', ' ');
	
		echo ('
			<td class="h3" align="right" colspan="2">'.$check.'</td>
			<td class="h3"></td>
		 </tr>
		 <tr>
			<td height="15" colspan="'.$this->_columns.'"></td>
		 </tr>');
	}
	
	//Lijst met totale input
	function ShowData()
	{
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		echo(' 
		 <tr>	
			<form name="form_show" method="post" action='.$PHP_SELF.'>
			<td class="radio" align="right" colspan="'.$this->_columns.'">Aantal rijen: 5 <input type="radio" name="show_records" value="LIMIT 5" class="radio" '); 
				if ($this->_show_records=="LIMIT 5") 
					{echo "checked";} 
				echo(' onclick="this.form.submit()">all<input type="radio" name="show_records" value="" class="radio" '); 
				if ($this->_show_records=="")
					{echo "checked";} 
				echo(' onclick="this.form.submit()"> 
			</td>
			</form>
		 </tr>
		 <tr>
			<td class="h1" width ="75" align="left">Datum</td>
			<td class="h1" width ="65" align="left">Kok</td>');
			
			//laat users zien
			$this->ShowUsers();
				
		echo ('	
			<td class="h1" align="right" width="25">#&nbsp;&nbsp;</td>
			<td class="h1" align="right" width="50">Kosten</td>
			<td class="h1" align="left" width="100">&nbsp;Omschr.</td>
		 </tr>
		 <tr>
			<td colspan="'.$this->_columns.'">
			<div class="div2">
				<table>');
	
		// --> Inhoud tabel
		$query11 = mysql_query("SELECT *, DATE_FORMAT(exp_date,'%d-%m-%Y') as date FROM expenses ORDER BY exp_date DESC ".$this->_show_records." ");
		while ($obj11 = mysql_fetch_array($query11)) 
		{ 
			echo ('
			 <tr>
				<td class="h3" width ="75" align="left">'.$obj11['date'].'</td>');
	
			//Username zoeken bij id
			$userName = $user->getUsernameById($obj11['user_id']);
			
			//If else om edit-link weer te geven voor ingelogde user/admin.
			if 	(($_SESSION['user_id'] == $obj11['user_id']) && (!empty($_SESSION['ingelogd'])) && ($obj11['exp_date'] >= $this->_exp_from)
					|| ($_SESSION['user_level'] == 9) && (!empty($_SESSION['ingelogd'])) && ($obj11['exp_date'] >= $this->_exp_from)) 
			{
				echo ('
					<td class="h3" width ="65" align="left"><a class="hyp_2" href="form.php?t=5&id='.$obj11['id'].'&exp_date='.$obj11['date'].'" onClick="popupform(this, \'join\')">'.$userName.'</a></td>');
			}
			else 
			{
				echo ('
					<td class="h3" width ="65" align="left">'.$userName.'</td>');
			}
	
			//inhoud uit exp_detail
			foreach($user->getAllUsers() as $user2){
				$query12 = mysql_query("SELECT nb FROM exp_detail WHERE exp_id='".$obj11['id']."' AND user_id='".$user2->userid."'");
				$aantal = mysql_num_rows($query12);
				if ($aantal < 1) 
				{
					echo ('
					<td class="h3" width ="63" align="center">0</td>');
				}
				else 
				{
					while ($obj12 = mysql_fetch_array($query12)) 
					{
						echo ('
							<td class="h3" width ="63" align="center">'.$obj12['nb'].'</td>');
					}
				}
			}
			
			echo('	
				<td class="h3" width="25" align="right">'.$obj11['number'].'&nbsp;&nbsp;</td>
				<td class="h3" width="50" align="right">'.$obj11['exp'].'</td>
				<td class="h3"'); if ($this->_show_records=="") {echo "width=\"84\"";} else {echo "width=\"100\"";} echo(' align="left">&nbsp;'.$obj11['description'].'</td>
			 </tr>');
		}
		
		echo (' 
				</table>
			</div>
			</td>
		 </tr>
		 <tr>
			<td height="8" class="row_split" colspan="'.$this->_columns.'"></td>
		</tr>
		<tr>
			<td class="h3" align="left" colspan="2">Totaal</td>');
	
		//Rij met totalen	
		foreach($user->getAllUsers() as $user2){
			$query14 = mysql_query("SELECT COUNT(nb) AS totaal FROM exp_detail WHERE user_id='".$user2->userid."' AND nb > 0 AND exp_date >= '$this->_exp_from'");
			$obj14 = mysql_fetch_array($query14); 
			echo ('
				<td class="h3" align="center">'.$obj14['totaal'].'</td>');
		}
	
		echo ('
			<td class="h3" align="right" colspan="3">');
		if (($_SESSION['user_level'] == 9) && (!empty($_SESSION['ingelogd'])))
			{
				echo('<a class="hyp_2" href="admin/index.php">Admin Panel</a>');
			}
		
		echo('
			</td>
		 </tr>');

	}
}
?>
