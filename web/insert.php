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

	$insert = new Insert;
	
	if (!$_POST['delete']) 
	{
		if ($_POST['verzonden'] == 'Kosten')
		{
			$insert->SetTable('expenses','exp_detail');
			$insert->calc_input();
			$insert->insert_exp();
		}
		
		if ($_POST['verzonden'] == 'Wijzig_kosten')
		{
			$insert->SetTable('expenses','exp_detail');
			$insert->calc_input();
			$insert->update_exp();
		}

		if (($_POST['verzonden'] == 'Opgeven') || ($_POST['verzonden'] == 'Wijzig_opgeven'))
		{
			if (empty($_POST['edit_id']))
			{
				$insert->SumNumber();	
				$insert->insert_enroll();
			}
		
			else
			{
				$insert->SumNumber();	
				$insert->update_enroll();
			}
		}
	echo ('ok');
	}
	
	if ($_POST['delete']) 
	{
		if ($_POST['verzonden'] == 'Wijzig_kosten')
		{
			$insert->SetTable('expenses','exp_detail');
			$insert->delete_it();
		}
		
		else
		{
			$insert->delete_it();
		}
	}
	
	$insert->refresh();

}

else 
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">window.close()</script>";
}
	
mysqli_close($rDbConn);
?>
