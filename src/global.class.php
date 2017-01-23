<?
Class GlobalClass
{
	var $_datum; 	//datum vandaag
	var $_datum_m;	//datum vandaag in mysql-formaat
	var $_exp_from; //begindatum
	var $_table_1; 	// expenses of enroll
	var $_table_2; 	// exp_detail of e_detail
	
	//Constructor
	function GlobalClass()
	{
		// zet datum
		$this->_datum = date("d-m-Y");
		
		//zet begindatum
		$query = mysql_query("SELECT exp_from FROM date"); 
		$obj = mysql_fetch_array($query);
		$this->_exp_from = $obj['exp_from'];
		
		//zet tabelnamen
		$this->_table_1 = "enroll";
		$this->_table_2 = "e_detail";
			
	}

	//zet tabelnamen
	function SetTable($table_1, $table_2)
	{	
		$this->_table_1 = $table_1;
		$this->_table_2 = $table_2;
	}	

	//Omrekenen datum voor mysql
	function Mysql_date($formdate)
	{	
		$this->_datum_m = (substr($formdate,6,4).'-'.substr($formdate,3,2).'-'.substr($formdate,0,2));
	}

}
?>