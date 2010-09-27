function initmenu() {

	if(window.ie6) var heightValue='100%';
	else var heightValue='';
	
	var togglerName='div.toggler_';
	var contentName='div.content_';
	
	
	var counter=1;	
	var toggler=$$(togglerName+counter);
	var content=$$(contentName+counter);
	
	while(toggler.length>1)
	{
		var index = -1;
		for(var i = 0; i < toggler.length; i++){
			if(toggler[i].hasClass('subactive')){
				index = i;
				break;
			}
		}
		 alert(toggler);
		var tmp = new Accordion(toggler, content, {
			opacity: false,
			show: index,
			alwaysHide: true,
			onComplete: function() { 
				var element=$(this.elements[this.previous]);
				if(element && element.offsetHeight>0) element.setStyle('height', heightValue);			
			},
			onActive: function(toggler, content) {
				toggler.addClass('open');
				if(toggler.hasClass('subactive')) toggler.addClass('subactive_open');
			},
			onBackground: function(toggler, content) {
				toggler.removeClass('open');
				toggler.removeClass('subactive_open');
			}
		});
		
		counter++;
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
	}
}
