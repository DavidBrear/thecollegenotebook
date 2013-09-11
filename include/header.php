
<?php
	include_once("collegeconnection.php");
	
	ob_start();
	print '<html>';
	print '<head><title>The College Notebook</title>';
	
	print '<script type="text/javascript">';
	print '(new Image()).src = \'images/navigationBackDown.gif\';';
	
	print '</script>';
	
	print '<link rel="Stylesheet" href="include/header.css" type="text/css">';
	print '<link rel="icon" href="favicon.ico" type="image/x-icon" media="all">';
	print '<script type="text/javascript" src="../javascripts/MBJS.js" defer></script>';
	print '<script type="text/javascript" src="../javascripts/functions.js" defer></script>';
	print '<script type="text/javascript" src="../javascripts/buttons.js" defer></script>';
	print '<script type="text/javascript" src="../javascripts/includeCSS.js"></script>';
	
	print '<link rel="stylesheet" href="include/style.css" type="text/css" media="all">';
	print '</head>';
	print '<body>';
	print '<div id="body">';
	print '<div id="header">
			<div id="title">
				<img src="../images/title.jpg" style="float: left; margin-left: 100px;"></img>
					<div id="headerSearch">
					
					<form action="Users" method="POST" name="UsersSearch">
						Search Users:<input id="userSearchBox" type="text" name="name"><br>
						<input type="image" src="../images/searchButton.gif" style="margin-top: 5px">
						<input type="hidden" value="1" name="submitted">
					</form>
					</div>
				</div>
			<div id="headerNavigation">';
		if(isset($_COOKIE['_sess']) && isset($_COOKIE['userid']))
		{
			print '<a href="profile?id='.$_COOKIE['userid'].'"><div onmouseover="overHead(this)" onmouseout="outHead(this)">Home</div></a><img src="/images/splitter.gif"></img>';
			$messages = mysql_query("SELECT userid FROM inbox WHERE userid=".$_COOKIE['userid']." AND new = 1 and showMess = 1;", $connection);
			if( mysql_num_rows($messages) >0)
			{
				print '<a href="inbox"><div onmouseover="overHead(this)" onmouseout="outHead(this)"><p id="message">Inbox('.mysql_num_rows($messages).')</p></div></a><img src="/images/splitter.gif"></img>';
			}
			else
			{
				print '<a href="inbox"><div onmouseover="overHead(this)" onmouseout="outHead(this)"><p id="message">Inbox</p></div></a><img src="/images/splitter.gif"></img>';
			}
		}
		else
		{
			print '<a href="/"><div onmouseover="overHead(this)" onmouseout="outHead(this)">Login</div></a><img src="/images/splitter.gif"></img>';
		}
		print '<a href="schoolindex"><div onmouseover="overHead(this)" onmouseout="outHead(this)">Schools</div></a><img src="/images/splitter.gif"></img>';
		print '<a href="compare"><div onmouseover="overHead(this)" onmouseout="outHead(this)">Compare</div></a><img src="/images/splitter.gif"></img>';
		print '<a href="/U-Sell"><div onmouseover="overHead(this)" onmouseout="outHead(this)">U-Sell</div></a><img src="/images/splitter.gif"></img>';
		print '<a href="/Ask"><div onmouseover="overHead(this)" onmouseout="outHead(this)">Ask</div></a><img src="/images/splitter.gif"></img>';
		if(isset($_COOKIE['vendorid']))
		{
			print '<a id="logoutButton" href="vendorLogin?log=1"><div onmouseover="overHead(this)" onmouseout="outHead(this)" style="margin-left: 0px; margin-right: 10px !important;">Logout</div></a>
			<img src="/images/splitter.gif" style="float: right !important;"></img>';
		}
		else if(isset($_COOKIE['_sess']))
		{
			print '</img><a id="logoutButton" href="login?log=1"><div onmouseover="overHead(this)" onmouseout="outHead(this)" style="margin-left: 0px; margin-right: 10px !important;">Logout</div></a>
			<img src="/images/splitter.gif" style="float: right !important;"></img>';
		}
		
		print '</div>';
		print '</div>';
		print '<div id="bodyContent">';
		print '<img src="images/bodyTop.gif"></img>';
		print '<div id="background">';
		print '<div id="content">';
		
		ob_end_flush();
?>
<script type="text/javascript" src="../javascripts/aj.js" defer></script>
<script type="text/javascript">
initMessages();
function addHome(val, type)
{
	if(val==1)
	{
		document.getElementById(type+'Nav').style.backgroundImage = "url('/images/"+type+"Small.png')";
		document.getElementById(type+'Nav').style.backgroundRepeat = 'no-repeat';
	}
	else
	{
		document.getElementById(type+'Nav').style.backgroundImage = 'none';
	}
}
function overHead(obj)
		{
			obj.style.backgroundImage = "url('/images/navigationBackDown.gif')";
		}
		function outHead(obj)
		{
			obj.style.backgroundImage = "url('/images/navigationBack.gif')";
		}
</script>
<style>
	body{ padding: 0px; }
</style>