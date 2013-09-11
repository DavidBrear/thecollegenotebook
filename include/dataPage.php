<?php
print "hello";
if(isset($_POST['sendData'])))
{
	$string = $_POST['sendData'];
	
	if($string == 1)
	{
		$data = sprintf("<h1>Hello</h1>");
	}
	else if($string == 2)
	{
		$data = sprintf("<h1>World</h1>");
	}
	else if($string == 3)
	{
		$data = sprintf("</h1>David</h1>");
	}
	else
	{
		$data = sprintf("<h1>error</h1>");
	}
}

echo $data;
?>