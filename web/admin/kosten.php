<?
//Check if user is logged in
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

	//From wich date do i calulate?
	$querystring = "SELECT * FROM date";
	$query		  = mysql_query($querystring); 
	$obj		  = mysql_fetch_array($query);
	$exp_from 	  = $obj['exp_from'];
	
	//drop old tables
	$querystring0 = "DROP TABLE IF EXISTS tmp_list_1, tmp_list_2";
	$query0 = mysql_query($querystring0); 

	//create table 1
	$querystring1 = "CREATE TABLE tmp_list_1 (user_id TINYINT(4) NOT NULL, PRIMARY KEY(user_id), saldo DECIMAL(15,10) NOT NULL)"; 
	$query1 = mysql_query($querystring1); 
	
	//create table 2
	$querystring = "CREATE TABLE tmp_list_2 (creditor TINYINT(4) NOT NULL, debtor TINYINT(4) NOT NULL, amount DECIMAL(15,10) NOT NULL)"; 
	$query = mysql_query($querystring); 
	
	//select active users
	$querystring2 = "Select id FROM users WHERE user_active='yes' AND user_level='0' ORDER BY id ASC";
	$query2 = mysql_query($querystring2); 
		while ($obj2 = mysql_fetch_array($query2)) {
		
		//define $id
		$id = $obj2['id'];
		
		//select paid 
		$querystring3 = "SELECT SUM(exp) AS paid FROM expenses WHERE user_id='$id' AND exp_date >= '$exp_from'";
		$query3 = mysql_query($querystring3); 
		$obj3 = mysql_fetch_array($query3);
				
		//select debt
		$querystring4 = "SELECT SUM(exp_pu) AS debt FROM exp_detail WHERE user_id='$id' AND exp_date >= '$exp_from'";
		$query4 = mysql_query($querystring4); 
		$obj4 = mysql_fetch_array($query4);
		
		//insert
		$saldo = $obj3['paid'] - $obj4['debt'];
		$querystring5 = "INSERT INTO tmp_list_1 (user_id, saldo) VALUES ('$id', '$saldo')";
		$query5 = mysql_query($querystring5); 
		
	}
	
	$query = mysql_query($querystring2); 
	$aantal = mysql_num_rows($query);
	$z= ($aantal - 1);

	for ($i = 1; $i <= $z; $i++) {
	
		//Bepalen Max en Min
		$querystring1 = "SELECT MAX(saldo) AS max, MIN(saldo) AS min, ((MAX(saldo)) +  (MIN(saldo))) AS x FROM tmp_list_1";
		$query1 = mysql_query($querystring1);
		$obj1 = mysql_fetch_array($query1);

		//Max
		$querystring2 = "SELECT user_id, saldo FROM tmp_list_1 WHERE saldo = '".$obj1['max']."' ";
		$query2 = mysql_query($querystring2);
		$obj2 = mysql_fetch_array($query2);
				
		//Min
		$querystring3 = "SELECT user_id, saldo FROM tmp_list_1 WHERE saldo = '".$obj1['min']."' ";
		$query3 = mysql_query($querystring3);
		$obj3 = mysql_fetch_array($query3);
						
		if (($obj1['max'] != '0') && ($obj1['min'] != '0')) {
	
			if ($obj1[x] >= '0') {
		
			//Insert tmp_list_2
			$querystring4 = "INSERT INTO tmp_list_2 (creditor, debtor, amount) VALUES ('".$obj2['user_id']."', '".$obj3['user_id']."', '".$obj3['saldo']."')";
			$query4 = mysql_query($querystring4);
		
			//Update tmp_list_1
			$querystring5 = "UPDATE tmp_list_1 SET saldo = '".$obj1['x']."' WHERE user_id = '".$obj2['user_id']."' ";
			$querystring6 = "UPDATE tmp_list_1 SET saldo = '0' WHERE user_id = '".$obj3['user_id']."' ";
			$query5 = mysql_query($querystring5);
			$query6 = mysql_query($querystring6);
			}
		
			else {
		
			//Insert tmp_list_2
			$querystring4 = "INSERT INTO tmp_list_2 (creditor, debtor, amount) VALUES ('".$obj2['user_id']."', '".$obj3['user_id']."', '".$obj2['saldo']."')";
			$query4 = mysql_query($querystring4);
		
			//Update tmp_list_1
			$querystring5 = "UPDATE tmp_list_1 SET saldo = '".$obj1['x']."' WHERE user_id = '".$obj3['user_id']."' ";
			$querystring6 = "UPDATE tmp_list_1 SET saldo = '0' WHERE user_id = '".$obj2['user_id']."' ";
			$query5 = mysql_query($querystring5);
			$query6 = mysql_query($querystring6);
			}
		}	
		}

	//text email
		echo('
	<html>
	<body>
	<font face="Arial Narrow, Arial, Helvetica, sans-serif">Beste mensen,<BR>
	<BR>
	Het is weer tijd om te gaan betalen.<BR>
	Voor u ziet u de (automatische) uitdraai van de etenslijst.<BR>
	<BR>
	Groet, de webmaster.<BR>
	<BR>
	<BR>
	<table border="0" style="border-collapse:collapse">');
		
	$querystring2 = "SELECT * FROM tmp_list_2 ORDER BY creditor ASC"; 
	$query2= mysql_query($querystring2);
	while ($obj2 = mysql_fetch_array($query2)){
		$querystring3 = "SELECT username FROM users WHERE id='".$obj2['debtor']."' ";
		$query3= mysql_query($querystring3);
		$obj3 = mysql_fetch_array($query3);
		echo('<tr><td width="60" align="left"><li> '.$obj3['username'].'</td><td  width="80" align="left"> betaald aan</td>');
		$querystring4 = "SELECT username FROM users WHERE id='".$obj2['creditor']."' ";
		$query4= mysql_query($querystring4);
		$obj4 = mysql_fetch_array($query4);
		echo(' <td width="60" align="left">'.$obj4['username'].'</td> ');
		$amount = round ($obj2['amount'], 2);
		if ($amount < 0)
		{
			$amount=$amount*-1;
		}
		echo('<td align="left"> € '.$amount.'</td></li></tr>');
	}
	
 echo('</table></font></body></html>');	
	mysql_close($rDbConn);
}

echo('
<BR>
<table style="border-collapse:collapse">
 <tr height="10"><td colspan="2"></td></tr>
 	<td></td>
	<td colspan="3">
		<input type="button" value="Verstuur de email!" OnClick="window.open(\'mail.php\', \'NewWin\', \'HEIGHT=200,WIDTH=300,toolbar=no,scrollbars=no,resizable=no,left=375,top=125\')">
	</td>
 </tr>
</table>
');
?>
