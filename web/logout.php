<?php
//logout.php
require('functions.php');
if(FALSE!==($rDbConn=connectdb()))
{

    //wordt hieronder behandeld
    clean_up($rDbConn,$_SESSION['user_id']);

    mysqli_close($rDbConn);
}
setcookie('validate','',time(),'/','heemskerkstraat.nl');
setcookie('user_id',0,time(),'/','heemskerkstraat.nl');
$_SESSION['ingelogd']=FALSE;
session_destroy(); 
?>

<html>
<head>
<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">

// ***********************************************
// AUTHOR: WWW.CGISCRIPT.NET, LLC
// URL: http://www.cgiscript.net
// Use the script, just leave this message intact.
// Download your FREE CGI/Perl Scripts today!
// ( http://www.cgiscript.net/scripts.htm )
// ***********************************************

var StayAlive = 2; // Number of seconds to keep window open
function KillMe(){
setTimeout("self.close()",StayAlive * 1000);
}
function refreshParent() {
  window.opener.location.href = window.opener.location.href;
}

</SCRIPT>
</head>
<body onload="KillMe();refreshParent()">
Your logout was succesfull.
</body>
</html>
