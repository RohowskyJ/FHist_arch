/* Accordion definitions für $module == F_G */
jQuery(document).ready(function($){
	recId = $('#recId').val();
	recEigner = $('#recEigner').val();
	allUpd = $('#allUpd').val();
	console.log('RecId ',recId);
	console.log('Eigner ', recEigner) ; 
	console.log('Updateable ',allUpd) ;
	let actIndex = 'activIndex : false ';
	if (recId != 0) {actIndex = 'activIndex : false '; };
	console.log('ActIndex ',actIndex );
	
	/* Sammlung- Abfrage */
    $("#sa-accordion").accordionjs({
		actIndex
	});
	   
	/* Taktische Bezeichnung */
	$("#ta-accordion").accordionjs({
		actIndex
	});

    /* Fahrzeug- Hersteller */  
	$("#fg-accordion").accordionjs({
		actIndex
	});
	
	/* Aufbau- Hersteller */
	$("#au-accordion").accordionjs({
		actIndex 
	});
	
	/* fixe Einbauten */
	$("#eb-accordion").accordionjs({
		actIndex
	});
	
	/* CTIF Zertifizierung */
	$("#ct-accordion").accordionjs({
		actIndex
	});
	
	/* Neuer Eigentümer */
	$("#ne-accordion").accordionjs({
		actIndex
	});
	
	/* Museums- Sammlungsbeschreibung */
	$("#ms-accordion").accordionjs({
		actIndex
	});
			
	/* Museums- Information */
	$("#in-accordion").accordionjs({
		actIndex
	});
				
	/* Neuer Eigentümer */
	$("#ne-accordion").accordionjs({
		actIndex
	});
		
	/* Museums- Sammlungen */
	$("#ms-accordion").accordionjs({
		actIndex
	});
	
	/* Museums- Kustos */
	$("#ku-accordion").accordionjs({
		actIndex
	});
			
	/* Museums- Infrastruktur */
	$("#in-accordion").accordionjs({
		actIndex
	});
					
	/* Museums- Öffnungszeiten */
	$("#oe-accordion").accordionjs({
		actIndex
	});
			
	/* Museums- Führungen */
	$("#fu-accordion").accordionjs({
		actIndex
	});
						
	/* Foto- Auswahl oder Upload */
	/*
	$("#fo-accordion_1").accordionjs({
		actIndex
	});
	$("#fo-accordion_2").accordionjs({
		actIndex
	});
    $("#fo-accordion_3").accordionjs({
		actIndex
	});
	$("#fo-accordion_4").accordionjs({
		actIndex
	});
	
	$("#fo-accordion_5").accordionjs({
		actIndex
	});
	const selLibsNo = $('#sel_libs_nein' + biNr);
	/ * Originale Optionen
	   $("#my-accordion").accordionjs({
	           // Allow self close.(data-close-able)
	           closeAble   : false,

	           // Close other sections.(data-close-other)
	           closeOther  : true,

	           // Animation Speed.(data-slide-speed)
	           slideSpeed  : 150,

	           // The section open on first init. A number from 1 to X or false.(data-active-index)
	           activeIndex : 1,

	           // Callback when a section is open
	           openSection: function( section ){},

	           // Callback before a section is open
	           beforeOpenSection: function( section ){},
	       });
		   */
   });
 