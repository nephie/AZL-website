<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">        
        <title>Voorinschrijving Open bedrijvendag</title>
         <link rel="stylesheet" type="text/css" href="files/css/plain.css" />        
        <!-- compliance patch for microsoft browsers -->
        <!--[if IE]><script src="files/IE7/ie7-standard-p.js" type="text/javascript"></script><![endif]-->
        
        {$xajax_javascript}
		{literal}
		<script type="text/javascript" charset="UTF-8">
		function my_dispatch(){
			return xajax.request( { xjxfun: 'dispatch' }, { parameters: arguments } );
		}
		</script>
		{/literal}
        
    </head>

	<body>
	{$popup}
	{$main}
	</body>
</html>