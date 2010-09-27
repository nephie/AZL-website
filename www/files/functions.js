//
// Verschachteltes Mootools-Accordion
// Nested Mootools Accordion
// 
// von / by Bogdan Günther
// http://www.medianotions.de
//

window.addEvent('domready', function() {
	
	// Anpassung IE6
	if(window.ie6) var heightValue='100%';
	else var heightValue='';
	
	// Selektoren der Container für Schalter und Inhalt
	var togglerName='dt.accordion_toggler_';
	var contentName='dd.accordion_content_';
	
	
	// Selektoren setzen
	var counter=1;	
	var toggler=$$(togglerName+counter);
	var content=$$(contentName+counter);
	
	while(toggler.length>1)
	{
		// Accordion anwenden
		new Accordion(toggler, content, {
			opacity: false,
			display: -1,
			alwaysHide: true,
			onComplete: function() { 
				var element=$(this.elements[this.previous]);
				if(element && element.offsetHeight>0) element.setStyle('height', heightValue);			
			},
			onActive: function(toggler, content) {
				toggler.addClass('open');
			},
			onBackground: function(toggler, content) {
				toggler.removeClass('open');
			}
		});
		
		// Selektoren für nächstes Level setzen
		counter++;
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
	}
});