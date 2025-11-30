/* PopOver, generiert mit Opera AI */

$(document).ready(function(){
           // Dropdown-Funktionalität
           $('.dropupstrg').click(function(e){
			  console.log('string klicked');
               e.stopPropagation(); // Verhindert das Schließen des Dropdowns, wenn auf das Dropdown selbst geklickt wird
               $(this).next('.dropup-content').toggle(); // Zeigt das Dropdown-Menü an oder blendet es aus
           });

           // Schließen des Dropdowns, wenn außerhalb geklickt wird
           $(document).click(function(e) {
               if (!$(e.target).closest('.dropup').length) {
                   $('.dropup-content').hide();
               }
           });

           // Popover-Funktionalität
           $('#toggleButt-sD').click(function(){
               var buttonOffset = $(this).offset();
               $('#popover').css({
                   top: buttonOffset.top + $(this).outerHeight() + 'px',
                   left: buttonOffset.left + 'px',
                   display: 'block'
               });
           });

		   // Popover-Funktionalität
		       $('#toggleButt-sD').click(function(event){
		           event.preventDefault(); // Verhindert das Absenden des Formulars oder andere Standardaktionen
				   /*
		           var buttonOffset = $(this).offset();
		           $('#popover').css({
		               top: buttonOffset.top + $(this).outerHeight() + 'px',
		               left: buttonOffset.left + 'px',
		               display: 'block'
		           });
				   */
		       });
			   
           // Schließen des Popovers
           $('#closePopover').click(function() {
               $('#popover').hide();
           });

           // Verhindern, dass das Popover bei Mausbewegungen verschwindet
           $('#popover').mouseenter(function() {
               $(this).data('hover', true);
           }).mouseleave(function() {
               $(this).data('hover', false);
               // Optional: Hier kann eine Verzögerung eingefügt werden, bevor das Popover geschlossen wird
               // um dem Benutzer Zeit zu geben, mit dem Inhalt zu interagieren.
               setTimeout(function() {
                   if (!$('#popover').data('hover')) {
                       $('#popover').hide();
                   }
               }, 200); // 200 Millisekunden Verzögerung
           });
       });