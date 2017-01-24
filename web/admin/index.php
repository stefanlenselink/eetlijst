<?
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


	$query = mysqli_query($rDbConn, "SELECT * FROM site LIMIT 1");
	$obj = mysqli_fetch_array($query);

	//Timestamp to date
	$update = $obj['updated'];
	$year = substr($update, 0, 4);
	$month = substr($update, 4, 2);
	$day = substr($update, 6, 2);
	$updated = date('d M Y', mktime(0, 0, 0, $month, $day, $year));

	echo('
	<HTML>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../eetlijst.css" rel="stylesheet" type="text/css">
	<title>Adminpage&nbsp;'.$obj['title'].'</title>
	<script language="javascript">');
		include ("../script/popup.php");
	echo ('
	</script>
	</head>
	<body class="body">
	<table width="950" height="500" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
	  <tr>
		<td colspan="2" class="h0">Adminpage <a class="h0" href="../index.php">'.$obj['title'].'</a> <-- click to return</td>
	  </tr>
	  <tr>
		<td width="83">&nbsp;</td>
		<td width="462">&nbsp;</td>
	  </tr>
	<form name="admins" method="post" action="insert_admin.php">
	  <tr>
		<td height="21" align="left" valign="baseline" class="h2">Title</td>
		<td class="h2" align="right"><input type="text" name="title" value="'.$obj['title'].'" size="66" class="input"></td>
	  </tr>
	  <tr height="5">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td height="21" align="left" valign="baseline" class="h2">Version</td>
		<td class="h2" align="right"><input type="text" name="version" value="'.$obj['version'].'" size="66" class="input"></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td valign="top" height="21" align="left" class="h2">Text</td>
		<td class="h2" align="right"><TEXTAREA NAME="text" ROWS="8" COLS="50" WRAP="soft">'.$obj['text'].'</TEXTAREA></td>
	  </tr>
	  <tr height="5">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td valign="baseline" align="left" class="h2">&nbsp;</td>
		<td align="right" class="h2">
			<input type="hidden" name="verzonden" value="admin">
			<input type="submit" value="Opslaan"></td>
	  </tr>
	</form>
	  <tr height="5">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr height="5">
		<td valign="baseline" align="left" class="h2">Users ->> click on username to edit!</td>
		<td valign="baseline" align="right" class="h2"><a class="hyp_2" href="add_user.php" onClick="popupform2(this, \'join\')">Add user</a></td>
	  </tr>
	  <tr>
		 <td width= "950" height="200" colspan="2" valign="top"><div class="div1">
			<table width= "1250" align="left" valign="top">
			 <tr height="15">
				<td class="h1" width ="30" align="left">Id</td>
				<td class="h1" width ="85" align="left">Username</td>
				<td class="h1" width ="200" align="left">Email</td>
				<td class="h1" width ="40" align="left">Level</td>
				<td class="h1" width ="50" align="left">Active</td>
				<td class="h1" width ="80" align="left">Gekomen</td>
				<td class="h1" width ="80" align="left">Vertrokken</td>
				<td class="h1" width ="70" align="left">Voornaam</td>
				<td class="h1" width ="100" align="left">Achternaam</td>
				<td class="h1" width ="80" align="left">Geboren</td>
				<td class="h1" width ="90" align="left">Kamer</td>
				<td class="h1" width ="100" align="left">Studie</td>
				<td class="h1" width ="84" align="left">Rekeningnr</td>
				<td class="h1" width ="90" align="left">te</td>
			 </tr>
	');
	$query = mysqli_query($rDbConn, "SELECT *,
							  DATE_FORMAT(birthdate,'%d-%m-%Y') AS birthdate,
							  DATE_FORMAT(user_in,'%d-%m-%Y') AS user_in,
							  DATE_FORMAT(user_out,'%d-%m-%Y') AS user_out FROM users ORDER BY id DESC");
	while ($obj = mysqli_fetch_array($query))
	{
	echo(' <tr height="15">
			<td class="h3">'.$obj['id'].'</td>
			<td class="h3"><a class="hyp_2" href="profile_ext.php?id='.$obj['id'].'" onClick="popupform2(this, \'join\')">'.$obj['username'].'</td>
			<td class="h3">'.$obj['user_email'].'</td>
			<td class="h3" align="center">'.$obj['user_level'].'</td>
			<td class="h3" align="center">'.$obj['user_active'].'</td>
			<td class="h3">'.$obj['user_in'].'</td>
			<td class="h3">'.$obj['user_out'].'</td>
			<td class="h3">'.$obj['firstname'].'</td>
			<td class="h3">'.$obj['lastname'].'</td>
			<td class="h3">'.$obj['birthdate'].'</td>
			<td class="h3">'.$obj['room'].'</td>
			<td class="h3">'.$obj['study'].'</td>
			<td class="h3">'.$obj['bankaccount'].'</td>
			<td class="h3">'.$obj['account_place'].'</td>
		  </tr>');
	}
	echo('
		</table></div>
	</td>
	  </tr>
	 <tr height="5">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	 </tr>
	 <tr>
		<td colspan="2" valign="baseline" align="center" class="h2">
		<p><font size="2">|&nbsp;scripted by <a class="hyp_2" href="&#109;&#97;&#105;&#108;&#116;&#111;:&#78;&#101;&#48;&#102;&#114;&#48;&#103;&#64;&#104;&#111;&#116;&#109;&#97;&#105;&#108;&#46;&#99;&#111;&#109;">Ne0fr0g</a>&nbsp;&nbsp;|&nbsp;
 		last update: '.$updated.' &nbsp;&nbsp;| </font></p></td>
	 </tr>
	</table>
	</body>
	</HTML>');
}
?>
