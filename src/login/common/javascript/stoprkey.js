// Diese javascript Funktion verhindert das ein Drücken der Enter Taste zum Absenden des Formulars führt
//
// Diese javascript Funktion wird beim Drücken jeder Tastaturtaste aufgerufen. 
// Wurde die Enter Taste (key-code=13) gedrückt - wird dieser Tastendruck unterdrückt (return false)  
//
  function stopRKey(evt) {
	   var evt = (evt) ? evt : ((event) ? event : null);
	   var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	   if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
	                                              else {return true;}
	 }

  document.onkeypress = stopRKey;