<?php
///inloggen db
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

	$querystring = "SELECT *, DATE_FORMAT(birthdate,'%d-%m-%Y') AS birthdate,
							  DATE_FORMAT(user_in,'%d-%m-%Y') AS user_in,
							  DATE_FORMAT(user_out,'%d-%m-%Y') AS user_out FROM users where id='".$id."' ";
	$query		 = mysql_query($querystring); 
	$obj 		 = mysql_fetch_array($query);    

	echo('
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../eetlijst.css" rel="stylesheet" type="text/css">
	<title>Edit profile_ext</title>
	<script language="javascript">');
	include ("../script/emailcheck.php");
	echo ('</script>
	</head>
	<body class="body">
	<form method="post" name="profile_ext" action="insert_profile_ext.php" onSubmit="return validate()">
	<table align="center" bgcolor="#797986" style="border-collapse:collapse">
	 <tr>
		<td class="h1" align="left">Username</td>
		<td class="h2" align="right"><input type="text" name="username" value="'.$obj['username'].'" class="input_2"></td>
	 </tr> 
	 <tr>
		<td class="h1" align="left">Password</td>
		<td class="h2" align="right"><input type="password" name="password" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left"></td>
		<td class="h2" align="right">Alleen invullen bij wijziging!</td>
 	</tr>
	<tr>
		<td class="h1" align="left">Email</td>
		<td class="h2" align="right"><input type="text" name="user_email" value="'.$obj['user_email'].'" class="input_2" onBlur="validate()"></td>
 	</tr>
 	<tr>
		<td class="h1" align="left">Level</td>
		<td class="h2" align="right"><input type="text" name="users_level" value="'.$obj['user_level'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Active</td>
		<td class="h2" align="right"><input type="text" name="user_active" value="'.$obj['user_active'].'" class="input_2"></td>
 	</tr>
 	<tr>
		<td class="h1" align="left">User_in</td>
		<td class="h2" align="right"><input type="date" name="user_in" value="'.$obj['user_in'].'" class="input_2"></td>
 	</tr>
	 <tr>
		<td class="h1" align="left">User_out</td>
		<td class="h2" align="right"><input type="date" name="user_out" value="'.$obj['user_out'].'" class="input_2"></td>
	 </tr>
 	<tr>
		<td class="h1" align="left">Voornaam</td>
		<td class="h2" align="right"><input type="text" name="firstname" value="'.$obj['firstname'].'" class="input_2"></td>
 	</tr>
	 <tr>
		<td class="h1" align="left">Achternaam</td>
		<td class="h2" align="right"><input type="text" name="lastname" value="'.$obj['lastname'].'" class="input_2"></td>
 	</tr>
		<tr>
		<td class="h1" align="left">Geboren</td>
		<td class="h2" align="right"><input type="date" name="birthdate" value="'.$obj['birthdate'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Kamer</td>
		<td class="h2" align="right"><input type="text" name="room" value="'.$obj['room'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Studie</td>
		<td class="h2" align="right"><input type="text" name="study" value="'.$obj['study'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Rekeningnr</td>
		<td class="h2" align="right"><input type="text" name="bankaccount" value="'.$obj['bankaccount'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">te</td>
		<td class="h2" align="right"><input type="text" name="account_place" value="'.$obj['account_place'].'" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1"></td>
		<td align="right" class="h2">
			<input type="submit" value="Verwijder" name="delete" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Wijzig" class="button"> 
			<input type="hidden" name="profile_id" value="'.$id.'">
			<input type="hidden" name="verzonden" value="profile_ext">
		</td>
	 </tr>
	<table>
	
		
	</form>
	</body>
	</html>

	');
    mysql_close($rDbConn);
}
?>

