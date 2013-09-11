<?php
	
?>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="include/header.css">
		<link rel="icon" href="favicon.ico" type="image/x-icon" media="all">
		<title>411onCollege</title>
		<script type="text/javascript" src="/javascripts/runafter.js"></script>
		<script type="text/javascript" src="/javascripts/header.js"></script>
		<script type="text/javascript" src="/javascripts/buttons.js"></script>
		<script type="text/javascript" src="/javascripts/MBJS.js"></script>
		<script type="text/javascript" src="/javascripts/functions.js"></script>
		<script type="text/javascript">
			function setNavColor(val, obj)
			{
				switch(val)
				{
					case 1:
					{
						obj.style.backgroundImage='url(\'/images/navigationBackDown.gif\')';
					}
					break;
					case 2:
					{
						obj.style.backgroundImage='url(\'/images/navigationBack.gif\')';
					}
					break;
				}
			}
			function findBrowser()
			{
				if( navigator.appVersion.indexOf('Safari') > -1)
				{
					document.writeln('<link rel="stylesheet" href="include/safari.css" type="text/css" media="all">');
				}
				if( navigator.appName.indexOf('Microsoft') > -1)
				{
					document.writeln('<link rel="stylesheet" href="include/headerIE.css" type="text/css" media="all">');
				}
	
			}
			findBrowser();
		</script>
		
	</head>
	<body>
		<div id="header">
			<div id="Title411"><img src="/images/411onCollegeTitle.gif"></img></div>
			</div>
		
		<div id="mainBodyContent">
		<div id="navigation">
			<ul>
			<?php
			if(!isset($_COOKIE['user']))
			{
				print '<li><a href="/"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Login</div></a></li>';
			}
			else
			{
				print '<li><a href="/profile.php?id='.$_COOKIE['user'].'"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Home</div></a></li>';
			}
			?>
			<li><a href="/schools"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Schools</div></a></li>
			<li><a href="http://TheCollegeNotebook.com/compare"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Compare</div></a></li>
			<li><a href="/ask"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Ask</div></a></li>
			<?php
			if(isset($_COOKIE['user']))
			{
				print '<li style="float: right; border: 1px #FFF solid;"><a href="/index.php?log=1"><div onmouseover="setNavColor(1, this)" onmouseout="setNavColor(2, this)">Logout</div></a></li>';
			}
			?>
			</ul>
		</div>