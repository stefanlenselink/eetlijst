<?php
///inloggen db
header("Cache-control: private");
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

	echo('
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="../eetlijst.css" rel="stylesheet" type="text/css">
		<title>Add user</title>
	<script language="javascript">');
	include ("../script/emailcheck.php");
	echo ('</script>
	</head>
	<body class="body">
	<form method="post" name="add_user" action="insert_add_user.php" onSubmit="return validate()">
	<table align="center" bgcolor="#797986" style="border-collapse:collapse">
	 <tr>
		<td class="h1" align="left">Username*</td>
		<td class="h2" align="right"><input type="text" name="username" value="" class="input_2"></td>
	 </tr> 
	 <tr>
		<td class="h1" align="left">Password*</td>
		<td class="h2" align="right"><input type="password" name="password" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Email*</td>
		<td class="h2" align="right"><input type="text" name="user_email" value="" onBlur="validate()" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Level*</td>
		<td class="h2" align="right">
			<select name="users_level" class="input_2"> 
			<option value="0" selected="selected" class="input_2">user</option>; 
			<option value="9" class="input_2">admin</option>; 
			</select>
		</td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Active*</td>
		<td class="h2" align="right">
			<select name="user_active" class="input_2"> 
			<option value="yes" selected="selected" class="input_2">yes</option>; 
			<option value="no" class="input_2">no</option>; 
			</select>
		</td>
	 </tr>
	 <tr>
		<td class="h1" align="left">User_in</td>
		<td class="h2" align="right"><input type="date" name="user_in" value="00-00-0000" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">User_out</td>
		<td class="h2" align="right"><input type="date" name="user_out" value="00-00-0000" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Voornaam</td>
		<td class="h2" align="right"><input type="text" name="firstname" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Achternaam</td>
		<td class="h2" align="right"><input type="text" name="lastname" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Geboren</td>
		<td class="h2" align="right"><input type="date" name="birthdate" value="00-00-0000" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Kamer</td>
		<td class="h2" align="right"><input type="text" name="room" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Studie</td>
		<td class="h2" align="right"><input type="text" name="study" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">Rekeningnr</td>
		<td class="h2" align="right"><input type="text" name="bankaccount" value="" class="input_2"></td>
	 </tr>
	 <tr>
		<td class="h1" align="left">te</td>
		<td class="h2" align="right"><input type="text" name="account_place" value="" class="input_2"></td>
	 </tr>
	  <tr>
		<td class="h1" align="left"></td>
		<td class="h2" align="right">Velden met een * zijn verplicht!</td>
	 </tr>
	 <tr>
		<td class="h1"></td>
		<td align="right" class="h2">
			<input type="submit" value="Add" >
			<input type="hidden" name="verzonden" value="add_user">
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

