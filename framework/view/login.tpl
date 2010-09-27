<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>        
        
        <title>Intranet A.Z. Lokeren</title>
        
        <link rel="stylesheet" type="text/css" href="files/css/main.css" />
        <link rel="stylesheet" type="text/css" href="files/css/menu.css" />
    	<!--[if lt IE 7]>
			<link rel="stylesheet" type="text/css" href="files/css/ie6.css" />
		<![endif]-->
		
     	<script src="files/mootools-1.2.1-core.js" type="text/javascript"></script>  
     	<script src="files/mootools-1.2-more.js" type="text/javascript"></script> 
     	
		{$xajax_javascript}
		
		{literal}		
		<script type="text/javascript" charset="UTF-8">
			function my_dispatch(){	
				xajax.request( { xjxfun: 'dispatch' }, { parameters: arguments } );			
			}			
			
			showLoadingMessage = function() {
				$("loading").fade('in');
			}
			
			hideLoadingMessage = function() {
				$("loading").fade('out');
			}
		</script>
		{/literal}        
    </head>

	<body>		
		
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
	</body>
</html>