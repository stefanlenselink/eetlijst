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
}
?>

<HTML>
<HEAD>
  <TITLE>admin</TITLE>
</HEAD>
<BODY>
Overzicht van gebruikers:<hr><BR>
<BASEFONT SIZE="-2" FACE="Arial">
<?php
//pak alle bestaande users en encrypted passwds uit de database
$my_query = "SELECT * FROM users ORDER BY id";
$my_result = @mysqli_query($rDbConn, $my_query) or die(mysqli_error());

// verwerk het resultaat van deze query in een tabel
echo ("<TABLE cellSpacing=0 cellPadding=1 width='100%' border=0 style='FONT-SIZE: x-small'>");
echo  "<TBODY>";

echo ("<TR><TD>nr</TD><TD>naam</TD><TD>emailadres</TD>");
while($my_row = mysqli_fetch_array($my_result))
{
    echo ("<TR>");
    echo ("<TD><FONT face='Courier New'>");echo $my_row['id'];echo ("</FONT></TD>");
    echo ("<TD><FONT face='Courier New'>");echo $my_row['username'];echo ("</FONT></TD>");
    echo ("<TD><FONT face='Courier New'>");echo $my_row['user_email'];echo ("</FONT></TD>");
    echo ("</TR>");
    $i++;
}

echo ("</tablebody>");
echo ("</table><hr>");

?>

<TABLE cellSpacing=0 cellPadding=0 border=0>
  <FORM id=FORM1 name=FORM1 action=addusers.php method=post>
  <TBODY bgColor=lemonchiffon style='FONT-SIZE: x-small'>
  <TR>
    <TD>Nieuwe naam: </TD>
    <TD><INPUT size=10 name=f_user></TD></TR>
  <TR>
    <TD>Wachtwoord: </TD>
    <TD><INPUT type=password size=10 name=f_pass1></TD></TR>
  <TR>
    <TD>Wachtwoord Bevestigen: </TD>
    <TD><INPUT type=password size=10 name=f_pass2></TD></TR>
  <TR>
    <TD align=middle
    colSpan=2><INPUT type=submit value="Toevoegen" name=submit></TD></TR>
  </FORM>
  </TBODY>
</TABLE>
<hr>
</BODY>
</HTML>
