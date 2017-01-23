<?php 
require_once("config.inc.php"); //moet voor session start ivm incomplete class problems

//parsetimescript -- bovenaan 
    $microtime = microtime(); 
    $split = explode(" ", $microtime); 
    $exact = $split[0]; 
    $secs = date("U"); 
    $bgtm = $exact + $secs; 

require_once('functions.php');


if(FALSE!==($rDbConn=connectdb()))
{
   
	if(isset($_POST["username"])){
		$user->login($_POST["username"], $_POST["password"]);
	}
	
	$MainPage = new GlobalClass;
	
	//Title + Version + timestamp
	$query = mysql_query("SELECT * FROM site LIMIT 1");
	$obj = mysql_fetch_array($query);

	//Timestamp to date 
	$updated = date('d M Y', strtotime($obj['updated']));

	//calculate height popups (form1)
	$hoogte1= (($user->getTotalNumberOfUsers() + "4") * "25");
	$hoogte2= (($user->getTotalNumberOfUsers() + "3") * "25");

	//Begin html output
	echo ('
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="description" content="Eetlijst voor de Heemskerkstraat">
	<link href="eetlijst.css" rel="stylesheet" type="text/css">
	<title>'.$obj['title'].'&nbsp;'.$obj['version'].'</title>
	<script language="javascript" type="text/javascript">');
	include ("script/datumcheck.php");
	include ("script/popup.php");
	echo ('
	</script>
	</head>

	<body class="body">
	<table width="960" align="center">
 	 <tr> 
    	<td width="360" valign="top" class="h3">
			<div>
				<div class="minheight">');
				include ("text.php");
	echo('
				</div>
			</div>
		</td>
	    <td width="267" align="center" valign="top">');
	
	
	if(check_login($rDbConn))
	{
		include ("input.php");
	} else{
		$user->printLoginForm();
	}
	
	
	echo('
		</td>
		<td width="360" align="right" class="h3" valign="top">
			<div>
				<div class="minheight">');
				include ("stats.php");
	echo('
				</div>
			</div>
		</td>
	 </tr>
	 <tr>
		<td height="20" colspan="3"></td>
	 </tr>
	 <tr> 
    	<td colspan="3" align="center"'); if(!check_login($rDbConn)) {echo('class="h2"');} echo(' > ');
		
	if(check_login($rDbConn))
    {
		include ("show.php");
	}

	else
	{
		include ("welcome.php");
	}
	
	echo('
		</td>
	 </tr>
	 	 <tr>
		<td colspan="3" height="15"></td>
	</tr>
	 <tr> 
    	<td colspan="3" align="center" valign="bottom">
			<p><font size="2">|&nbsp;scripted by <a class="hyp_1" href="&#109;&#97;&#105;&#108;&#116;&#111;:&#78;&#101;&#48;&#102;&#114;&#48;&#103;&#64;&#104;&#111;&#116;&#109;&#97;&#105;&#108;&#46;&#99;&#111;&#109;">Ne0fr0g</a>&nbsp;&nbsp;|&nbsp;
				last update: '.$updated.' &nbsp;|&nbsp; ');

	// parsetimescript -- onderaan
    $microend = microtime(); 
    $split = explode(" ", $microend); 
    $exactend = $split[0]; 
    $secsend = date("U"); 
    $edtm = $exactend + $secsend; 

    $difference = $edtm - $bgtm; 
    $difference = round($difference,5); 
	
	echo (' 
				parsetime: '.$difference.' sec.&nbsp;|
				</font>
			</p>
		</td>
	 </tr>
	 <tr>
		<td colspan="3" height="10"></td>
	</tr>
	 <tr>
		<td colspan="3" align="center" valign="bottom">
		  <a href="http://validator.w3.org/check?uri=referer"><img border="0" src="image/button-html.png" alt="Valid HTML 4.01!"></a>
  		  <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://eetlijst.heemskerkstraat.nl/eetlijst.css"><img border="0" src="image/button-css.png" alt="Valid CSS!"></a>
		</td>
	 </tr>
	  
	</table>
   	</body>
	</html>'); 

	mysql_close($rDbConn);
}
?>
