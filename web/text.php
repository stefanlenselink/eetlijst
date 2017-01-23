<?
//inloggen db
connectdb();

echo('
<table align="left">
 <tr>
	<td width="306" class="h0">Welkom');
 
if(!empty($_SESSION['ingelogd']))
{
	echo(' 
		<a class="hyp_1" href="profile.php" onClick="return popitup3(\'profile.php\')">'.$_SESSION["logedinuser"]->username.'!</a>
	</td>
	<td width="100" align="right" class="h0">
		<a class="hyp_1" href="logout.php" onClick="return popitup(\'logout.php\')">Logout</a>
	</td>');
}

else 
{
	echo('
	</td>
	<td width="100" align="right" class="h0">Not logged in</td>');
}

//Text
$query = mysql_query("SELECT text FROM site LIMIT 1");
$obj = mysql_fetch_array($query);
	
echo('
 </tr>
 <tr>
	<td colspan="2" class="h2">
		'.$obj['text'].'
	</td>	
 </tr>
</table>');
?>