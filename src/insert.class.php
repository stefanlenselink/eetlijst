<?
require_once "global.class.php";

class Insert extends GlobalClass
{
	var $_number; 	// aantal personen wat mee-eet
	var $_exp; 		//kosten
	var $_exp_pp;	//kosten per persoon
	var $_datum_m;
	var $_user_id;
	var $_description;
	var $_nb;		//array
	var $_edit_id;
	
	function Insert()
	{
		//initialiseer 
		GlobalClass::Globalclass();

		$this->_user_id = $_POST['user__id'];
		
		//waarden uit formulier
		$this->_datum_m = $_POST['datum_m'];
		$this->_exp = $_POST['exp'];
		$this->_description = $_POST['description'];
		$this->_edit_id = $_POST['edit_id'];
	}	
	
	function SumNumber()
	{
		//Put values in array, calculate $number
		//TODO misschien moet het toch anders? eigenlijk moet require_once ipv require....
		require("config.inc.php");
		foreach($user->getAllUsers() as $user2){
			$p= id.$user2->userid;							//genereer naam variabelen die via form zijn doorgegeven
			
			if ($_POST[$p] > 0) 						//stop waarde variabele in array als deze groter dan 0 is
			{ 
				$nb[$user2->userid]= $_POST[$p];
			}
		}
		if (count($nb) > 0) 							// check of array inhoud heeft
		{
			$this->_nb = $nb;							//hernoemen voor gebruik in andere functies
			$this->_number = array_sum($this->_nb);		//optellen inhoud array 
		}
	}
	
	//input check
	function check_input($input,$text)
	{
		if (!empty($input))
		{	
			return TRUE;
		}

		else
		{
			echo "<script language=\"javascript\" type=\"text/javascript\">alert(\"Je hebt geen ".$text." ingevuld!\");history.go(-1)</script>";
			exit;
		}
	}
	
	//input verwerken
	function calc_input()
	{
		$this->SumNumber();

		//check of er input is
		$this->check_input($this->_number,personen);
		$this->check_input($this->_exp,bedrag);
					
		// Komma in getal vervangen door punt
		$this->_exp = str_replace("," ,"." , $this->_exp);

		// Bereken exp_pp
		$this->_exp_pp = ($this->_exp/$this->_number); 
	}
	
	//insert exp
	function insert_exp()
	{
		//zet kok
		$this->_user_id = $_SESSION['user_id'];
		
		//table_1
		if (empty($this->_description)) 
		{ 
			//Input data zonder omschrijving
			$querystring="INSERT INTO ".$this->_table_1." (exp_date, user_id, exp, number, exp_pp) 
						  VALUES ('$this->_datum_m', '$this->_user_id', '$this->_exp', '$this->_number', '$this->_exp_pp')";
		}
			
		else 
		{
			//Input data met omschrijving
			$querystring="INSERT INTO ".$this->_table_1." (exp_date, user_id, exp, description, number, exp_pp) 
						  VALUES ('$this->_datum_m', '$this->_user_id', '$this->_exp', '$this->_description', '$this->_number', '$this->_exp_pp')";
		}
	
		$query = mysql_query($querystring) or die(mysql_error());
	
		//select id last insert
		$query = mysql_query("Select id FROM ".$this->_table_1." ORDER BY id DESC LIMIT 1");
		$obj = mysql_fetch_array($query);
		$exp_idd = $obj['id'];
			
		//table_2
		foreach ($this->_nb as $z => $q) 
		{
			$exp_pu = ($q * $this->_exp_pp);
			$query="INSERT INTO ".$this->_table_2." (exp_id, exp_date, user_id, nb, exp_pu)
						   VALUES ('$exp_idd', '$this->_datum_m', '$z', '$q', '$exp_pu') ";
			$Result = mysql_query($query) or die(mysql_error());
		}

	}

	// update exp
	function update_exp()
	{
		if (empty($this->_description)) 
		{ 
			//Input data zonder omschrijving
			$querystring ="UPDATE ".$this->_table_1." SET exp='$this->_exp', description=null, number='$this->_number', exp_pp='$this->_exp_pp' 
						   WHERE id=".$this->_edit_id." ";
		}
			
		else 
		{
			//Input data met omschrijving
			$querystring ="UPDATE ".$this->_table_1." SET exp='$this->_exp', description='$this->_description',	number='$this->_number', exp_pp='$this->_exp_pp' 
						   WHERE id=".$this->_edit_id." ";
			echo $querystring;
		}
			
		$query = mysql_query($querystring) or die(mysql_error());
		
		//Delete existing data for exp_id
		$query= mysql_query("DELETE FROM ".$this->_table_2." WHERE exp_id='".$this->_edit_id."' "); 
		
		foreach ($this->_nb as $z => $q) 
		{
			$exp_pu = ($q * $this->_exp_pp);
			$querystring="INSERT INTO ".$this->_table_2." (exp_id, exp_date, user_id, nb, exp_pu)
						   VALUES ('$this->_edit_id', '$this->_datum_m', '$z', '$q', '$exp_pu') ";
			
			$query = mysql_query($querystring) or die(mysql_error());
		}

	}
	
	// insert enroll
	function insert_enroll()
	{
		$querystring="INSERT INTO ".$this->_table_1." (exp_date, user_id) VALUES ('$this->_datum_m', '$this->_user_id')";
			
		$query = mysql_query($querystring) or die(mysql_error());
		
		//select id last insert
		$query = mysql_query("Select id FROM ".$this->_table_1." ORDER BY id DESC LIMIT 1");
		$obj = mysql_fetch_array($query);
		$exp_idd = $obj['id'];
				
		//input
		foreach ($this->_nb as $z => $q) 
		{
			$querystring2="INSERT INTO ".$this->_table_2." (exp_id, exp_date, user_id, nb)
						   VALUES ('$exp_idd', '$this->_datum_m', '$z', '$q') ";
			$query2 = mysql_query($querystring2) or die(mysql_error());
		}
	}
	
	// update enroll
	function update_enroll()
	{
		$querystring="UPDATE ".$this->_table_1." SET user_id='".$this->_user_id."' WHERE exp_date='".$this->_datum_m."'";
		$query = mysql_query($querystring) or die(mysql_error());
		
		//Delete existing data for exp_id
		$query = mysql_query("DELETE FROM ".$this->_table_2." WHERE exp_id='".$this->_edit_id."' "); 

		foreach ($this->_nb as $z => $q) 
		{
			$querystring2="INSERT INTO ".$this->_table_2." (exp_id, exp_date, user_id, nb)
						   VALUES ('$this->_edit_id', '$this->_datum_m', '$z', '$q') ";
			$query2 = mysql_query($querystring2) or die(mysql_error());
		}

	}

	// Delete
	function delete_it()
	{
		$query	 = mysql_query("DELETE FROM ".$this->_table_1." WHERE id=".$this->_edit_id." "); 
		$query	 = mysql_query("DELETE FROM ".$this->_table_2." WHERE exp_id=".$this->_edit_id." "); 
	}
	
	//refresh
	function refresh()
	{
		echo "die <script language=\"JavaScript\" type=\"text/javascript\">window.opener.location.reload();window.close()</script>";
	}

}
?>