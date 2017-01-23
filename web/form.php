<?
header("Cache-control: private");
require('config.inc.php');
require('functions.php');
 	
if(FALSE!==($rDbConn=connectdb()))
{
    if(!check_login($rDbConn))
    {
       echo "<script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
       exit;
    }

	$form= new Form;
	
	$form->SetFormname($_POST['t']?$_POST['t']:$_GET['t']);
	$form->Mysql_date($_POST['exp_date']?$_POST['exp_date']:$_GET['exp_date']);
	$form->FormStart();
	
	if ($_GET['t']== "5")
	{
		$form->SetTable('expenses','exp_detail');
	}
	
	if ($_POST['t']== "Kosten" || ($_POST['t']== "Opgeven"))
	{
		$form->CheckDate();
		$form->CheckEnroll();
	}
	
	if ($_POST['t']== "Opgeven" || ($_GET['t']== "4"))
	{
		$form->CheckEnroll();
		$form->FormKok();
	}
	
	$form->FormList();	
	
	if ($_POST['t']== "Kosten" || ($_GET['t']== "5"))
	{
		$form->FormCost();
	}
	
	$form->FormEnd();
}

else 
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
}
	
mysql_close($rDbConn);
?>
