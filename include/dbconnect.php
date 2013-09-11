<?php
    $connection = mysql_connect('localhost','root','') or die("error connecting");
	mysql_select_db("searchengine", $connection) or die("error connecting");
?>
<style type="text/css">
		div.top
		{
			padding:0 0 20px 0;
			height:15%;
			margin:0 0 0 0;
			background-color:gray;
			clear:left;
		}
		div.alert
		{
			background-color:#007700;
			color:white;
			width:100px;
			border:solid #000077 1px;
		}
		div.bottom
		{
			padding:0 0 20px 0;
			height:3%;
			margin:0 0 0 0;
			background-color:gray;
			font:"Courier New" 8;
			color: black;
			clear:left;
		}
		div.sidebar
		{
			float:left;
			border:solid gray 1px;
			width: 100px;
			height: 200px;
			padding:1em;
		}
		div.center
		{
			width:100%;
			height:75%;
			display:block;
			border:gray 1px;
		}
		label
		{
			display:block;
			margin:1em 0 0 0;
		}
		label.line
		{
			display:inline;
			margin:2px;
		}
		br
		{
			margin:1em 0 0 0;
			padding:5px 0 5px 0;
		}
		th.headers
		{
			margin:0 0 0 10em;
			padding:2em 0 0 3em;
			height:50px;
			font:bolder;
		}
		td.des
		{
			width:400px;
		}
		input[type="submit"] {display:block;}
		h1.title
		{
			color: white;
			size: +12;
		}
	</style>
