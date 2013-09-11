<?php
include("dbconnect.php");
	if($_POST['submitted'])
	{
		$category = $_POST['category'];
		if (mysql_query('INSERT INTO category(category) values ("'.$category.'");', $connection))
		{
			print '<div class="success">Category successfully added</div>';	
		}
		else
		{
			$error = mysql_error();
			print '<div class="error">'.$error."</div><br>";
			$size = strlen($error);
		}
		mysql_close($connection);	
	}
?>

<html>
	<style type="text/css">
		input.submit
		{
			position:relative;
			display:inline;
		}
		div.success
		{
			background-color:#009911;
			border: 5px solid #001199;
			color:#FFFFFF;
			overflow:auto;
			width:<?php if($size){print $size;} else{print '300px';} ?>;
		}
		div.error
		{
			background-color:#990000;
			color:#000000;
			border: 5px dashed #000000;
			width:<?php if($size){$size=($size *6)+10; print $size.'px';} else{print '300px';} ?>;
		}
	</style>
	<head>
		<title>Add Category</title>
	</head>
	<body>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<label for="category">Category Name</label>
			<input type="text" name="category" size="20">
			<input type="hidden" name="submitted" value="1">
			<br />
			<br />
			<input class="submit" type="submit" value="Submit">
		</form>
		<form action="edit.php">
			<input type="submit" value="Back">
		</form>
	</body>
</html>
