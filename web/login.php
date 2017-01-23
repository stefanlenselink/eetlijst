<?php
require('functions.php');

if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(FALSE!==($rDbConn=connectdb()))
    {
        $sQuery='SELECT id, user_level FROM users WHERE username="'.$_POST['username'].'" AND password="'.md5($_POST['password']).'" AND user_active="yes"';
        if(!$rResult=mysql_query($sQuery,$rDbConn))
        {
            echo 'Hey een foutmelding: '.mysql_error($rDbConn).'<BR>'.$sQuery;
        }
        else
        {
            if(mysql_num_rows($rResult)==0)
            {
                echo "<script language=\"javascript\" type=\"text/javascript\">alert(\"Username and/or password are incorrect\");history.go(-1)</script>";
            }
            else
            {
                //En dan komt hier het loginverhaal
								//willekeurige string maken
								$sValidate=md5(rand(0,99999));
								
								//cookies setten voor 7dagen
								$iUserId=mysql_result($rResult,0,'id');
								$iUserLevel=mysql_result($rResult,0,'user_level');
								setcookie('validate',$sValidate,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
								setcookie('user_id',$iUserId,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
								setcookie('user_level',$iUserLevel,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
								
								//de gegevens in de tabel zetten
								$sQuery='REPLACE INTO logins (tijdstip, validate, user_id, client_ip)
														VALUES (NOW(), "'.$sValidate.'", '.$iUserId.', "'.$_SERVER['REMOTE_ADDR'].'")';
								if(!mysql_query($sQuery,$rDbConn))
								{
										echo 'Hey een foutmelding: '.mysql_error($rDbConn).'<BR>'.$sQuery;
								}
								
								//de sessie gegevens schrijven
								$_SESSION['user_id']=$iUserId;
								$_SESSION['user_level']=$iUserLevel;
								$_SESSION['ingelogd']=TRUE;
								$_SESSION['client_ip']=$_SERVER['REMOTE_ADDR'];
								//TODO moet toch anders.....
								require_once("config.inc.php");
								$user->username = $_POST['username'];
								$_SESSION["logedinuser"] = $user;

								//doorsturen naar de volgende pagina
								header('location: index.php'); 
            }
        }
        mysql_close($rDbConn);
    }
}
?>


