<html>
	<head>
		<link rel="stylesheet" href="{baseurl}css/blueprint/screen.css" type="text/css" media="screen, projection">
		<link rel="stylesheet" href="{baseurl}css/blueprint/print.css" type="text/css" media="print">
        <link rel="stylesheet" href="{baseurl}css/blueprint/src/form.css" type="text/css" media="screen, object"> 
		
		<link rel="stylesheet" href="{baseurl}css/custom.css" type="text/css" media="screen, projection">
		
		<link rel="stylesheet" href="{baseurl}css/jquery/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css" media="screen, projection"> 
		<script type="text/javascript" src="{baseurl}js/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="{baseurl}js/jquery/jquery-ui-1.7.2.custom.min.js"></script>
		
		<link rel="stylesheet" type="text/css" media="screen" href="{baseurl}css/jquery/menu/superfish.css" />
		<script type="text/javascript" src="{baseurl}js/jquery/menu/hoverIntent.js"></script> 
		<script type="text/javascript" src="{baseurl}js/jquery/menu/superfish.js"></script>
		<script type="text/javascript"> 
			$(document).ready(function(){ 
				$('ul.sf-menu').superfish({ 
					hoverClass:    'sfHover',          // the class applied to hovered list items 
					pathClass:     'overideThisToUse', // the class you have applied to list items that lead to the current page 
					pathLevels:    1,                  // the number of levels of submenus that remain open or are restored using pathClass 
					delay:         100,                // the delay in milliseconds that the mouse can remain outside a submenu without it closing 
					animation:     {opacity:'show',height:'show'},   // an object equivalent to first parameter of jQuery’s .animate() method 
					speed:         'fast',             // speed of the animation. Equivalent to second parameter of jQuery’s .animate() method 
					autoArrows:    true,               // if true, arrow mark-up generated automatically = cleaner source code at expense of initialisation performance 
					dropShadows:   true,               // completely disable drop shadows by setting this to false 
					disableHI:     false,              // set to true to disable hoverIntent detection 
					onInit:        function(){},       // callback function fires once Superfish is initialised – 'this' is the containing ul 
					onBeforeShow:  function(){},       // callback function fires just before reveal animation begins – 'this' is the ul about to open 
					onShow:        function(){},       // callback function fires once reveal animation completed – 'this' is the opened ul 
					onHide:        function(){}        // callback function fires after a sub-menu has closed – 'this' is the ul that just closed 
				}); 
			}); 
		</script>
		
		<!--[if IE]>
		<link rel="stylesheet" href="/204gaesales/css/blueprint/ie.css" type="text/css" media="screen, projection">
		<![endif]-->
		<script type="text/javascript" src="{baseurl}js/_other/js.js"></script>
		<script type="text/javascript" src="{baseurl}js/calendar.js"></script>
        <link rel="stylesheet" href="{baseurl}css/jquery/pagination.css" type="text/css">
        <script type="text/javascript" src="{baseurl}js/jquery/jquery.pagination.js"></script>
		
		<script type="text/javascript" src="{baseurl}js/validate.js"></script>
	</head>
	<body>
		<div class="container">
			<div id="header" class="span-24 last"></div>
			<div class="span-24 last"> <!-- id="menu" -->
				<ul id="sample-menu-1" class="sf-menu">
					<li><a href="{basesiteurl}/main/pengaduan">Pengaduan</a></li>
					<li><a href="{basesiteurl}/main/statistik">Statistik</a></li>
					<li><a href="{basesiteurl}/main/">Login</a></li>
			</div>
			<div id="content" class="span-23 prepend-1 last">