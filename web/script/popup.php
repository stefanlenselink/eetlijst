<?
echo('
		<!--
		function popupform(myform, windowname)
		{
		if (! window.focus)return true;
		window.open(\'\', windowname, \'height='.$hoogte1.',width=215,toolbar=no,scrollbars=no,resizable=no,left=395,top=105 \');
		myform.target=windowname;
		return true;
		}
		//-->
		
		<!--
		function popupform1(myform, windowname)
		{
		if (! window.focus)return true;
		window.open(\'\', windowname, \'height='.$hoogte2.',width=215,toolbar=no,scrollbars=no,resizable=no,left=395,top=105 \');
		myform.target=windowname;
		return true;
		}
		//-->
		
		<!--
		function popupform2(myform, windowname)
		{
		if (! window.focus)return true;
		window.open(\'\', windowname, \'height=440,width=300,toolbar=no,scrollbars=no,resizable=no,left=395,top=105 \');
		myform.target=windowname;
		return true;
		}
		//-->

		<!--
		function popitup(url)
		{
		newwindow=window.open(url,\'name\',\'height=50,width=100,toolbar=no,scrollbars=no,resizable=no,statusbar=no,left=460,top=190 \');
		if (window.focus) {newwindow.focus()}
		return false;
		}
		// -->

		<!--
		function popitup2(url)
		{
		newwindow=window.open(url,\'name\',\'height=150,width=300,toolbar=no,scrollbars=no,resizable=no,statusbar=no,left=350,top=190 \');
		if (window.focus) {newwindow.focus()}
		return false;
		}
		// -->

		<!--
		function popitup3(url)
		{
		newwindow=window.open(url,\'name\',\'height=320,width=300,toolbar=no,scrollbars=no,resizable=no,statusbar=no,left=350,top=190 \');
		if (window.focus) {newwindow.focus()}
		return false;
		}
		// -->

		


');
?>