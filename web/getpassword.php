<?
require_once("config.inc.php"); //moet voor session start ivm incomplete class problems
if($_POST["Submit"]) {

require_once('functions.php');
// Maak verbinding
connectdb();

      if($user->userExists($_POST["username"], $_POST["email"]))
      {
      $pass = Generate_Pass(10);
      $message = "<html>
                        <head>
                         <title>nieuw wachtwoord</title>
                        </head>
                             <body>
                             <p>Hallo " . $_POST["username"] . ",<br>
                             <br>
                             Deze email is verstuurd omdat jij (of iemand die zich voordeed als '" . $_POST["username"] . "') 
                             een nieuw wachtwoord aangevraagd hebt voor je account op de kostenlijst vd Heemskerkstraat.<br>
                             <br>
                             Je nieuwe wachtwoord is nu $pass.<br>
                             <br>
                             Groet,<br>
                             <br>
                             Admin eetlijst</p>
                             </body>
                        </html>";
      $headers  = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
      $headers .= "From: Kostenlijst Heemskerkstraat <webmaster@heemskerkstraat.nl>\r\n";
 

      mail($_POST["email"], "nieuw wachtwoord", $message, $headers);
		$newUser = $user->getUserByUsernameEmail($_POST["username"], $_POST["email"]);
		$newUser->password = $pass;
		$newUser->update(true);
            echo "<div align=\"center\"><table width=\"50%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\"> 
            <tr><td bordercolor=\"#5074B0\" bgcolor=\"#FFFFFF\" height=\"36\"> 
            <div align=\"center\">Uw wachtwoord is verzonden 
            <br>klik <a class =\"h1\" href=\"javascript: window.close();\">hier</a> 
            om dit venster te sluiten.</font></div> 
            </td></tr></table> 
            </div>";
      }
      else
      {
            echo "<div align=\"center\"><table width=\"80%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\"> 
            <tr bordercolor=\"#5074B0\"> 
            <td bordercolor=\"#5074B0\" bgcolor=\"#FFFFFF\" height=\"20\"> 
            <div align=\"center\"><div align=center>uw username en/of emailadres<br> 
            komt niet in de database voor. " . $_POST["username"] . ", " . $_POST["email"] . "
            </font></div></font></div> 
            <form method=\"post\" action=\"javascript:history.go(-1)\"> 
            <div align=center><input type=\"submit\" name=\"terug\" value=\"terug\"></div></form></td></tr></table></div>"; 

    
      }
}

else

{
?>

      <form method="post" action="<? echo"$_SERVER[REQUEST_URI]"; ?>"> 
      <div align="center">
      <table border="0" cellspacing="0" cellpadding="0"> 
        <tr><td align="center"><div align="right">username:</div></td>
            <td align="center"><p align="left"><input type="text" name="username" size="20"></td></tr> 
        <tr><td align="center"><div align="right">emailadres:</div></td>
            <td align="center"><p align="left"><input type="text" name="email" size="20"></td></tr> 
        <tr><td align="center" colspan="2"><input type="submit" value="verstuur" name="Submit"></td></tr>
      </table> 
      </div>
  </form>
<? 
}
 
function Generate_Pass ($pass_len)
{
  $nps = "";
  mt_srand ((double) microtime() * 1000000);
  while (strlen($nps)<$pass_len)
  {
    $c = chr(mt_rand (0,255));
    if (eregi("^[a-z0-9]$", $c)) $nps = $nps.$c;
  }
  return ($nps);
}

?>
