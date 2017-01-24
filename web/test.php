<?

//GLOBAL zit in index
//parsetimescript -- bovenaan
    $microtime = microtime();
    $split = explode(" ", $microtime);
    $exact = $split[0];
    $secs = date("U");
    $bgtm = $exact + $secs;

require('functions.php');
//
//inloggen db
$rDbConn = connectdb();

$querystring0 = "SELECT * FROM date";
$query0		  = mysqli_query($rDbConn, $querystring0);
$obj0 		  = mysqli_fetch_array($query0);
$ratio_from  = $obj0['ratio_from'];

echo('

<!--   start stat gegevens   -->

<table align="left">
 <tr>
	<td class="h1" width="70" align="left">Kok</td>
	<td class="h1" width="85" align="center" title="aantal keer gekookt">gekookt</td>
	<td class="h1" width="105" align="center" title="aantal keer meegegeten">meegegeten</td>
	<td class="h1" width="50" align="center" title="gekookt / meegegeten">ratio 1</td>
	<td class="h1" width="50" align="center" title="Kosten per persoon per maaltijd">ratio 2</td>
 </tr>');

//Select users
$querystring = "SELECT id, username FROM users WHERE user_active='yes' AND user_level='0' ORDER BY id ASC";
$query10 = mysqli_query($rDbConn, $querystring);
	while ($obj10 = mysqli_fetch_array($query10)) {

		//Tellen aantal maal kok
		$querystring11 = "SELECT COUNT(user_id) AS x_payd FROM expenses WHERE user_id='".$obj10['id']."' AND (exp_date >= '$ratio_from') ";
		$query11 = mysqli_query($rDbConn, $querystring11);
		$obj11 = mysqli_fetch_array($query11);
echo ('
 <tr>
 	<td class="h5" width="70" align="left">'.$obj10[username].'</td>
	<td class="h3" width="85" align="center">'.$obj11[x_payd].'</td>');

		//Create temporary table; Expenses without decription
		$querystring1 = "CREATE TEMPORARY TABLE tmp_expenses SELECT id FROM expenses WHERE exp_date >= '".$ratio_from."'";
		$query1 = mysqli_query($rDbConn, $querystring1);

		//Create temporary table; Join
		$querystring2 = "CREATE TEMPORARY TABLE tmp_exp_detail SELECT exp_detail.user_id, exp_detail.nb FROM tmp_expenses, exp_detail WHERE tmp_expenses.id=exp_detail.exp_id";
		$query2 = mysqli_query($rDbConn, $querystring2);

		//Tel aantal keer meegegeten
		$querystring3 = "SELECT COUNT(nb) AS x_debt FROM tmp_exp_detail WHERE user_id='".$obj10['id']."' AND nb > 0";
		$query3 = mysqli_query($rDbConn, $querystring3);
		$obj3 = mysqli_fetch_array($query3);

echo ('
	<td class="h3" width="105" align="center">'.$obj3[x_debt].'</td>');

		//Ratio1, check division by zero
		if 		($obj3['x_debt'] < "1") {
					$ratio1 =	number_format($obj11['x_payd']/"1", 2, '.', ' '); }
		else 	{
					$ratio1 =	number_format($obj11['x_payd']/$obj3['x_debt'], 2, '.', ' '); }

echo ('
	<td class="h3" width="50" align="center">&nbsp;'.$ratio1.'</td>');

		//cost/number per user, without decription
		$querystring14="SELECT ROUND(IFNULL(SUM(exp)/SUM(number),0),2) AS ratio2 FROM expenses WHERE user_id='".$obj10['id']."' AND exp_date >= '$ratio_from'";
		echo $querystring14;
		$query14 = mysqli_query($rDbConn, $querystring14);
		$obj14 = mysqli_fetch_array($query14);

echo ('
	<td class="h5" align="center">&nbsp;'.$obj14[ratio2].'</td>
 </tr>');
}

echo('

</table>


<!--   einde stat gegevens   -->


');
?>
