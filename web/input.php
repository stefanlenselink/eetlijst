<?
$rDbConn = connectdb();
$query	= mysqli_query($rDbConn, "SELECT DATE_FORMAT(ratio_from,'%d-%m-%Y') AS ratio_from, DATE_FORMAT(exp_from,'%d-%m-%Y') AS exp_from FROM date");
$obj = mysqli_fetch_array($query);

echo ('

<!--   start input    -->

<table width="200" align="center" class="tb1">
 <tr>
	<td class="h1" align="center">Invoer</td>
 </tr>
 <tr>
	<td class="h2" align="center" height="10"></td>
 </tr>
 <tr>
	<td class="h2" height="74" align="center">
		<form method="post" name="form1" action="form.php" onSubmit="popupform(this, \'join\')">
			<table>
			 <tr>
				<td class="h2" colspan="3" align="center">
				<input type="text" name="exp_date" class="input_1" value="'.$MainPage->_datum.'" onblur="datumCheck(this,\'datum\')" >
				</td>
			 </tr>
			 <tr>
				<td class="h2" colspan="3" height="5"></td>
			 </tr>
			 <tr>
				<td class="h2">
					<input type="submit" class="button" name="t" value="Opgeven">
				</td>
				<td class="h2" width="5"></td>
				<td class="h2">
					<input type="submit" class="button" name="t" value="Kosten">
				</td>
			 </tr>
			</table>
		</form>
	</td>
 </tr>
 <tr>
 	<td height="10"></td>
 </tr>
 <tr>
	<td class="h1" align="center">Weergave ratio vanaf</td>
 </tr>
 <tr>
 	<td class="h2" align="center">'.$obj[ratio_from].'</td>
 </tr>
 <tr>
 	<td height="10"></td>
 </tr>
 <tr>
	<td class="h1" align="center">Weergave kosten vanaf</td>
 </tr>
 <tr>
 	<td class="h2" align="center">'.$obj[exp_from].'</td>
 </tr>

</table>

<!--   end input   -->
');
?>
