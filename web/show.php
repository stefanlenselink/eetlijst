<?
echo ('

<!--   start show gegevens   -->

<table>');
	
$ShowPage = new Show;
$ShowPage->EnrollData();
$ShowPage->OverviewCost();
$ShowPage->ShowData();
	
echo('
	
</table>

<!--   einde show gegevens   -->');
?>	
