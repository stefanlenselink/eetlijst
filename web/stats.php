<?
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

$querystring = "SELECT  exp_detail.user_id as Userid, sum(nb) as Aantalkeermeegegeten FROM exp_detail where exp_date >= \"" . $ratio_from . "\" group by exp_detail.user_id ORDER BY exp_detail.user_id ASC";
$res = mysqli_query($rDbConn, $querystring);
$rows = array();
while ($row = mysqli_fetch_array($res)) {
	$rows[$row["Userid"]] = $row["Aantalkeermeegegeten"];
}


//Select users
$querystring = "SELECT expenses.user_id as Userid, count( expenses.user_id ) AS Gekookt, sum( number ) AS Voormensengekookt, ROUND( IFNULL( SUM( exp ) / SUM( number ) , 0 ) , 2 ) AS ratio2
FROM expenses WHERE exp_date >= \"" . $ratio_from . "\" GROUP BY expenses.user_id ORDER BY expenses.user_id ASC";
$query10 = mysqli_query($rDbConn, $querystring);
	while ($obj10 = mysqli_fetch_array($query10)) {
		if($rows[$obj10["Userid"]]){
echo ('
 <tr>
 	<td class="h5" width="70" align="left">'.$user->getUsernameById($obj10["Userid"]).'</td>
	<td class="h3" width="85" align="center">'.$obj10["Gekookt"].'</td>
	<td class="h3" width="105" align="center">'.$rows[$obj10["Userid"]].'</td>
	<td class="h3" width="50" align="center">&nbsp;' . number_format($obj10["Voormensengekookt"] / $rows[$obj10["Userid"]], 2, '.', ' ') . '</td>
	<td class="h5" align="center">&nbsp;'.$obj10["ratio2"].'</td>
 </tr>');
		}
}

echo('

</table>


<!--   einde stat gegevens   -->


');
?>
