<?php
@session_start();
//functions.php

//Omrekenen datum voor mysqli
// Functie moet eruit, staat al in global.class.php. Is nu nog noodzakelijk vanwege insert_profile.php
function mysqli_date($formdate)
{
	return(substr($formdate,6,4).'-'.substr($formdate,3,2).'-'.substr($formdate,0,2));
}

function connectdb()
{
    //inloggegevens van de database
    $sHost='a.database.hostname';
    $sUser='the.username';
    $sPass='database.password';
    $sDb='name.of.the.database';

    if(!$rDbConn=@mysqli_connect($sHost,$sUser,$sPass))
    {
        echo 'Kon niet verbinden met de databaseserver, pas de database gegevens aan in functions.php';
        return FALSE;
    }
    else
    {
        if(!@mysqli_select_db($rDbConn, $sDb))
        {
            echo 'Kon de database niet selecteren, pas de database gegevens aan in functions.php';
            return FALSE;
        }
    }
    return $rDbConn;
}

function check_login($rDbConn)
{
    /*$bLogin=FALSE;

    if(empty($_SESSION['ingelogd']))
    {
        //niet aangemeld volgens sessie, wel volgens db?
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['validate']) &&
                strlen($_COOKIE['validate'])==32 && preg_match('/^[0-9]{1,8}$/',$_COOKIE['user_id']) &&
                        preg_match( '/^[a-f0-9]{32}$/',$_COOKIE['validate']))
        {
            //de cookies bestaan en zijn geldig, kijken in de db
            $sQuery='SELECT COUNT(1) FROM logins
                    WHERE user_id='.$_COOKIE['user_id'].' AND validate="'.$_COOKIE['validate'].'"
                        AND client_ip="'.$_SERVER['REMOTE_ADDR'].'"
                        AND tijdstip>DATE_SUB(NOW(),INTERVAL 7 DAY)';
            if(!$rResult=mysqli_query($rDbConn, $sQuery))
            {
                echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
            }
            else
            {
                $data = mysqli_fetch_array($rResult);
                if(count($data) && current($data))
                {
                    //volgens db al geldig ingelogd
                    $bLogin=TRUE;

                    //de sessie gegevens schrijven
                    $_SESSION['ingelogd']=TRUE;
                    $_SESSION['client_ip']=$_SERVER['REMOTE_ADDR'];
					$_SESSION['user_level']=$_COOKIE['user_level'];
                    $_SESSION['user_id']=$_COOKIE['user_id'];

                    //willekeurige string maken
                    $sValidate=md5(rand(0,99999));

                    //cookies setten voor 7dagen
                    setcookie('validate',$sValidate,time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
                    setcookie('user_id',$_SESSION['user_id'],time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');
					setcookie('user_level',$_SESSION['user_level'],time()+60*60*24*7,'/','eetlijst.heemskerkstraat.nl');

                    //de gegevens in de tabel zetten
                    $sQuery='REPLACE INTO logins (tijdstip, validate, user_id, client_ip)
                                VALUES (NOW(), "'.$sValidate.'", '.$_SESSION['user_id'].', "'.$_SERVER['REMOTE_ADDR'].'")';
                    if(!mysqli_query($rDbConn, $sQuery))
                    {
                        echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
                    }

                    //deze functie behandel ik straks
                    clean_up($rDbConn);

                    return $bLogin;
                }
            }
        }
    }
    elseif(isset($_SESSION['client_ip']) && $_SESSION['client_ip']==$_SERVER['REMOTE_ADDR']
            && isset($_SESSION['user_id']) && preg_match('/^[0-9]{1,8}$/',$_SESSION['user_id'])
            && isset($_SESSION['ingelogd']) && $_SESSION['ingelogd']===TRUE)
    {
        return TRUE;
    }
    return FALSE;*/
	//require("config.inc.php");
	if(isset($_SESSION["ingelogd"])){
            return ($_SESSION["ingelogd"] ? true : false);
        }
        return false;
}

//Check level for admin
function check_userlevel($ulevel)
{
	if ($_SESSION['user_level'] >= $ulevel)
	{
  		 return TRUE;
	}

	else
	{
		 return FALSE;
	}
}

function clean_up($rDbConn,$iUserId=0)
{
    if(empty($iUserId))
    {
        //er werd geen userID meegegeven, dus algemene opruiming?
        if(rand(1,500)==1)
        {
            //alle rijen ouder dan een maand wissen
            $sQuery='DELETE FROM logins WHERE tijdstip<DATE_SUB(NOW(),INTERVAL 1 MONTH)';
            if(!mysqli_query($rDbConn, $sQuery))
            {
                echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
            }
        }
    }
    elseif(preg_match('/^[0-9]{1,8}$/',$iUserId))
    {
        //er werd een userID meegegeven, wissen dat kreng
        $sQuery='DELETE FROM logins WHERE user_id='.$iUserId;
        if(!mysqli_query($rDbConn, $sQuery))
        {
            echo 'Hey een foutmelding: '.mysqli_error($rDbConn).'<BR>'.$sQuery;
        }
    }
}

?>
