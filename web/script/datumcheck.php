<?php
echo('
		function datumCheck(theElement, theElementName){
		var datePat = /^(\d{1,2})?(|-|.|\/)(\d{1,2})?(|-|.|\/)(\d{4})?$/; //delimiter invoer is "-,/,." of leeg, altijd 4 cijferig jaar
		var dateStr=theElement.value;
		var err = 0
		if (dateStr.length==0)return true;
		if (dateStr.length<8||dateStr.length>10){alert(theElementName+" moet het formaat DD-MM-YYYY hebben");return false;};
		var matchArray = dateStr.match(datePat);
		if (matchArray == null){alert(theElementName + " is ongeldig, moet het formaat DD-MM-YYYY hebben");return false;};
		dag=matchArray[1];if(dag.length==1)dag=0+dag; //ontbrekende voorloopnul toevoegen
		mnd=matchArray[3];if(mnd.length==1)mnd=0+mnd; //ontbrekende voorloopnul toevoegen
		jr =matchArray[5];
		if(parseInt(jr)<1850)err=2 // onderbegrenzing jaarinvoer: 1850
		if(parseInt(jr)>2099)err=2 // bovenbegrenzing jaarinvoer: 2099
		if((mnd<1||mnd>12)&& err==0)err=3 //maand
		if((dag<1||dag>31)&& err==0)err=4 //dag
		if(mnd==4||mnd==6||mnd==9||mnd==11){if((dag==31)&& err==0) err=4}//alg. maandlengte
		if(mnd==2&& err==0){//februari lengte
		 if(dag>29)err=4;
		 if(dag==29){
		  if((jr/4)!=parseInt(jr/4)){err=5;//niet deelbaar door 4?
		  }else{if((jr/100)==parseInt(jr/100)){if((jr/400)!=parseInt(jr/400))err=5;}//deelbaar door 100 en niet deelbaar door 400?
		  }
		 }
		}
		//alerts
		theElement.value=dag+"-"+mnd+"-"+jr;
		if(err==1){alert(theElementName + " moet het volgende formaat hebben: DD-MM-YYYY");theElement.select();return false;}
		if(err==2){alert(theElementName + " heeft geen geldig jaar");theElement.select();return false;}
		if(err==3){alert(theElementName + " heeft geen geldige maand");theElement.select();return false;}
		if(err==4){alert(theElementName + " heeft geen geldige dag voor deze maand");theElement.select();return false;}
		if(err==5){alert(theElementName + " is geen geldig schrikkeljaar");theElement.select();return false;}
		//
		if(err==0){return true;}//datumdelimiter is altijd "-"
		}
');
?>