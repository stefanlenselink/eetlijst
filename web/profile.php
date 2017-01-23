<?
require_once("config.inc.php"); //moet voor session start ivm incomplete class problems
require_once('functions.php');
if(FALSE!==($rDbConn=connectdb()))
{
    if(!check_login($rDbConn))
    {
        header('location: login.php');
        exit;
    }

$currentUser = $user->getUserById($_SESSION['user_id']);

echo('
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="eetlijst.css" 		rel="stylesheet" type="text/css">
<title>Wijzig profiel</title>
<script language="javascript" type="text/javascript">');
	include ("script/emailcheck.php");
echo ('</script>
</head>
<body class="body">

<form method="post" name="edit" action="insert_profile.php" onSubmit="return validate()">

<table align="center" class="tb2">
 <tr>
	<td class="h1" align="left">Username</td>
	<td class="h1" align="right">'.$currentUser->username.'</td>
 </tr> 
 <tr>
	<td class="h2" align="left">Password</td>
	<td class="h2" align="right"><input type="password" name="password" value="" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left"></td>
	<td class="h2" align="right">Alleen invullen bij wijziging!</td>
 </tr>
 <tr>
	<td class="h2" align="left">Email</td>
	<td class="h2" align="right"><input type="text" name="user_email" value="'.$currentUser->email.'" class="input_2" onBlur="validate()"></td>
 </tr>
 <tr>
	<td class="h2" align="left">Voornaam</td>
	<td class="h2" align="right"><input type="text" name="firstname" value="'.$currentUser->firstname.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">Achternaam</td>
	<td class="h2" align="right"><input type="text" name="lastname" value="'.$currentUser->lastname.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">Geboren</td>
	<td class="h2" align="right"><input type="text" name="birthdate" value="'.$currentUser->birthdate.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">Kamer</td>
	<td class="h2" align="right"><input type="text" name="room" value="'.$currentUser->room.'" readonly class="input_2"</td>
 </tr>
 <tr>
	<td class="h2" align="left">Studie</td>
	<td class="h2" align="right"><input type="text" name="study" value="'.$currentUser->study.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">Rekeningnr</td>
	<td class="h2" align="right"><input type="text" name="bankaccount" value="'.$currentUser->bankaccount.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">te</td>
	<td class="h2" align="right"><input type="text" name="account_place" value="'.$currentUser->bankaccountplace.'" class="input_2"></td>
 </tr>
 <tr>
	<td class="h2" align="left">
	
            <input type="submit" value="Wijzigen" class="button">
            <input type="hidden" name="verzonden" value="profile">
    </td>
	<td class="h2"></td>
 </tr>
</table>
</form>
</body>
</html>

');
}
?>

