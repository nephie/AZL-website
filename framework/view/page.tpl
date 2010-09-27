<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>

        <title>Intranet A.Z. Lokeren</title>

        <link rel="stylesheet" type="text/css" href="files/css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="files/css/menu.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="files/css/grid.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="files/css/datepicker_vista.css" media="screen" />


        <link rel="stylesheet" type="text/css" href="files/css/print.css" media="print" />

        <!--[if IE]>
			<link rel="stylesheet" type="text/css" href="files/css/ie.css" />
		<![endif]-->
    	<!--[if lt IE 7]>
			<link rel="stylesheet" type="text/css" href="files/css/ie6.css" />
		<![endif]-->

     	<script src="files/mootools-1.2.4-core-nc.js" type="text/javascript"></script>
     	<script src="files/mootools-1.2.4.2-more.js" type="text/javascript"></script>
     	<script src="files/datepicker.js" type="text/javascript"></script>

		<script type="text/javascript" src="highslide/highslide.js"></script>
		<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />

     	{$rteheader}

		{$xajax_javascript}

		{literal}
		<script type="text/javascript" charset="UTF-8">

			hs.graphicsDir = 'highslide/graphics/';
			hs.dimmingOpacity = 0.75;
			hs.preserveContent = false;


			window.addEvent('load', function() {
				my_datepicker_time = new DatePicker('.datepicker_time', {
					pickerClass: 'datepicker_vista',
					days: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
					months: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
					timePicker: true,
					format: 'd-m-Y - H:i'
				});
				my_datepicker = new DatePicker('.datepicker', {
					pickerClass: 'datepicker_vista',
					days: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
					months: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
					format: 'd-m-Y'
				});
				my_timepicker = new DatePicker('.timepicker', {
					pickerClass: 'datepicker_vista',
					days: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
					months: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
					timePickerOnly: true,
					format: 'H:i'
				});
			});

			function my_dispatch(){
				xajax.request( { xjxfun: 'dispatch' }, { parameters: arguments } );
			}

			showLoadingMessage = function() {
				$("loading").fade('in');
			}

			hideLoadingMessage = function() {
				$("loading").fade('out');
			}

			highlight = function(element) {
				$(element).fade('hide');
				$(element).fade('in');
			}

			highlightformfielderror = function(element) {
				$(element).morph('.formfieldwitherror');
				$(element).highlight('#ff0000','#FFFFFF');
			}

			removehighlightformfielderror = function(element) {
				$(element).morph('.formfieldwithouterror');
			}

			var currentsuggestresult = [];
			var maxsuggestresult = [];

			suggest_handlekeyboardnavup = function(id) {
				var current = currentsuggestresult[id];

				if(current > 0) {
					current = current - 1;
				}

				currentsuggestresult[id] = current;

				$('suggestresults_' + id + '_' + (current + 1)).set('class', 'suggestresultitem');
				$('suggestresults_' + id + '_' + current).set('class', 'selectedsuggestresultitem');
			}

			suggest_handlekeyboardnavdown = function(id) {
				var current = currentsuggestresult[id];

				if(current < maxsuggestresult[id]){
					current = current + 1;
				}

				currentsuggestresult[id] = current;

				$('suggestresults_' + id + '_' + current).set('class', 'selectedsuggestresultitem');
				$('suggestresults_' + id + '_' + (current - 1)).set('class', 'suggestresultitem');
			}

			suggest_handlemouseover = function(id,index){
				var current = currentsuggestresult[id];
				if($('suggestresults_' + id + '_' + current) != null){
					$('suggestresults_' + id + '_' + current).set('class', 'suggestresultitem');
				}

				current = index;
				currentsuggestresult[id] = current;
				$('suggestresults_' + id + '_' + current).set('class', 'selectedsuggestresultitem');
			}

			suggest_handleenter = function(id){
				var current = currentsuggestresult[id];
				$(id).value = $('suggestresults_' + id + '_' + current).innerHTML;
				$(id + '_result').style.display = 'none';
				suggest_fixie_hide(id);
			}

			nosubmitonenter = function(e, field){
				if(e.which){
			          var keycode = e.which;
			     } else {
			          var keycode = e.keyCode;
			     }

				 if($(field.id + '_result').style.display == 'none' )
				 	return true;
				 else {
			     	return (keycode != 13);
		     	 }
			}

			suggest_handlekeyup = function(e, field, controller, callbackfunction ) {
				if(e.which){
			          var keycode = e.which;
			     } else {
			          var keycode = e.keyCode;
			     }

			     switch (keycode) {
			     	case 13: //enter
			     	case 9: //TAB
			     		suggest_handleenter(field.id);
			     		return false;
			     		break;
			     	case 27: // escape
			     		suggest_fillfield(field.id, field.value);
                    	return false;
                    	break;
                    case 38: // up arrow
                    	suggest_handlekeyboardnavup(field.id);
                    	return false;
                    	break;
                    case 40: // down arrow
                    	suggest_handlekeyboardnavdown(field.id);
	                    return false;
    	                break;
                    default:
                    	if(field.value.length > 1) {
							suggest_starttimer(field, controller, callbackfunction);
						}
						else {
							$(field.id + '_result').style.display = 'none';
							suggest_fixie_hide(id);
						}
                 }

                 return false;
			}

			suggestselect_handlekeyup = function(e, field, controller, callbackfunction , extra) {
				if(e.which){
			          var keycode = e.which;
			     } else {
			          var keycode = e.keyCode;
			     }

			     switch (keycode) {

                    default:

							suggestselect_starttimer(field, controller, callbackfunction, extra);

                 }

                 return false;
			}

			suggest_handlefocusin = function(field, controller, callbackfunction) {
				if(field.value.length > 1) {
						suggest_starttimer(field, controller, callbackfunction);
						$(field.id + '_result').style.display = 'block';
					}
					else {
						$(field.id + '_result').style.display = 'none';
					}
			}

			suggestselect_handlefocusin = function(field, controller, callbackfunction, extra) {
				if(field.value.length > 1) {
						suggestselect_starttimer(field, controller, callbackfunction, extra);
					}
					else {

					}
			}

			suggest_handlefocusout = function(field) {
				setTimeout("$('" + field.id + '_result' + "').style.display = 'none'",350);
				setTimeout("suggest_fixie_hide('" + field.id + "')",350);

			}

			var timers = [];
			suggest_starttimer = function(field, controller, callbackfunction) {
				if(timers[field.id] == undefined){
					timers[field.id] = setTimeout("suggest_sendrequest('" + field.id + "', '" + escape(field.value) + "', '" + controller + "' , '" + callbackfunction + "')",30);
				}
				else {
					clearTimeout(timers[field.id]);
					timers[field.id] = setTimeout("suggest_sendrequest('" + field.id + "', '" + escape(field.value) + "', '" + controller + "' , '" + callbackfunction + "')",30);
				}
			}

			suggestselect_starttimer = function(field, controller, callbackfunction , extra) {
				if(timers[field.id] == undefined){
					timers[field.id] = setTimeout("suggestselect_sendrequest('" + field.id + "', '" + escape(field.value) + "', '" + controller + "' , '" + callbackfunction + "' , '" + extra + "')",30);
				}
				else {
					clearTimeout(timers[field.id]);
					timers[field.id] = setTimeout("suggestselect_sendrequest('" + field.id + "', '" + escape(field.value) + "', '" + controller + "' , '" + callbackfunction + "' , '" + extra + "')",30);
				}
			}

			suggest_sendrequest = function(id, value, controller, callbackfunction){
					xajax_dispatch( 'updatesuggestfield' , 'myform' , 'updatesuggestfield' , 'callbackcontroller:' + controller , 'callbackfunction:' + callbackfunction, 'id:' + id , 'value:' + value );
			}

			suggestselect_sendrequest = function(id, value, controller, callbackfunction, extra){
					xajax_dispatch( 'updatesuggestselectfield' , 'myform' , 'updatesuggestselectfield' , 'callbackcontroller:' + controller , 'callbackfunction:' + callbackfunction, 'id:' + id , 'value:' + value , 'extraparams:' + extra );
			}

			suggest_fillfield = function(id,value) {
				$(id).value = unescape(value);
				$(id + '_result').style.display = 'none';
				suggest_fixie_hide(id);
			}

			suggest_fixie = function(id) {
				$("ie6selectfixer_" + id).setStyle('height',$(id + '_result').getStyle('height'));
				$("ie6selectfixer_" + id).setStyle('width',$(id + '_result').getStyle('width'));
			}

			suggest_fixie_hide = function(id) {
				$("ie6selectfixer_" + id).setStyle('height',0);
				$("ie6selectfixer_" + id).setStyle('width',0);
			}


		</script>
		{/literal}
    </head>

	<body onLoad="xajax_initpage();dhtmlHistoryInit();">

		<div id="topbarleft">&nbsp;</div>
		<div id="topbarright">
			<div class="paddingcontainer">
				{$top_expand}&nbsp;
			</div>
		</div>
		<div id="mainbar">
		<div id="first_level_menu">
			{$first_level_menu}
		</div>
			<table id="maintable">
				<tr>
					<td id="leftcol">
						{$leftcol}
					</td>
					<td id="rightcol">
						<div id="mainarea">

							<div class="paddingcontainer">

									{$main}
								<div id="loadinghorizon"><div id="loading"><img src="files/images/ajax-loader.gif"></div></div>
							</div>

						</div>

					</td>
				</tr>
			</table>
		</div>
		{$popup}
		<div id="errorpane"></div>
		<div id="flashcontainer"></div>
		<div id="content" style="margin-top: 100px;position: relative;"></div>

	</body>
</html>