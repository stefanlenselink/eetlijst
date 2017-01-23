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

	/* recipients */
	$querystring1 = "SELECT user_email FROM users WHERE user_active='yes' AND user_level= '0' ORDER BY id ASC"; 
	$query1= mysql_query($querystring1);
	while ($obj1 = mysql_fetch_array($query1))
	{
		$to = $to."".$obj1[user_email].",";
	}

	/* subject */
	$subject = "Verrekenen van de kostenlijst";
	
	/* message */
	$message = "
	<font face=\"Arial Narrow, Arial, Helvetica, sans-serif\">Beste mensen,<BR>
	<BR>
	Het is weer tijd om te gaan betalen.<BR>
	Voor u ziet u de (automatische) uitdraai van de etenslijst.<BR>
	<BR>
	Groet, de webmaster.<BR>
	<BR><BR>
	<table border=\"0\" style=\"border-collapse:collapse\">";
	
	//tabel
	$querystring2 = "SELECT * FROM tmp_list_2 ORDER BY debtor ASC"; 
	$query2= mysql_query($querystring2);
	while ($obj2 = mysql_fetch_array($query2))
	{
		$amount = round($obj2['amount'], 2);
		if ($amount < 0)
		{
			$amount=$amount*-1;
		}
		$querystring3 = "SELECT username FROM users WHERE id='".$obj2['debtor']."' ";
		$query3= mysql_query($querystring3);
		$obj3 = mysql_fetch_array($query3);
		
		$querystring4 = "SELECT * FROM users WHERE id='".$obj2['creditor']."' ";
		$query4= mysql_query($querystring4);
		$obj4 = mysql_fetch_array($query4);
		
		$message = $message."<tr><td align=\"left\"><li> $obj3[username] moet € $amount overmaken op rekeningnr. $obj4[bankaccount] tnv $obj4[firstname] $obj4[lastname] te $obj4[account_place]</td></li></tr>";
	}
	$message = $message."</table>
	<BR>
	<BR>
	<table border=\"0\" style=\"border-collapse:collapse\"></font>";
	
	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	/* additional headers */
	$headers .= "From: Mailer Eetlijst <webmaster@heemskerkstraat.nl>\r\n";

	/* and now mail it */
	$verzonden = @mail($to, $subject, $message, $headers);
	
	if($verzonden)
	{ 
		echo "<center>De emails zijn verzonden.</center>";   
		
		// update kn_vanaf en lijst_vanaf 
		$exp_from = $ratio_from = date("Y-m-d");
		$querystring6 = "UPDATE date SET exp_from = '".$exp_from."', ratio_from = '".$ratio_from."' ";
		$query6 = mysql_query($querystring6);	
		
	}
	 
	else 
	{   
		echo "<center>Er is een fout opgetreden, probeert u het nog eens.</center>"; 
	} 

	mysql_close($rDbConn);
}
?>
