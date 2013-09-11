
<html>
<head>
	<title>
		MySQL Database Admin
	</title>
</head>
<body>
	<style type="text/css">
		body{ position="center"}
	</style>
	<?php
		if($_POST['submitted'])
		{		
			$user = $_POST['username'];
			$password = $_POST['password'];
			$connection = mysql_connect("localhost", "root", "") or die(mysql_error());
			$database = "showstop";
			mysql_select_db($database, $connection) or die(mysql_error());
			$data = mysql_query('SELECT password FROM login WHERE username = \''.$user.'\';');
			function wrong()
			{
				
			}
			if (!$data)
			{
				wrong();
			}
			while($row = mysql_fetch_row($data))
			{
				if ($row[0]==$password)
				{
					print "logged in";
				}
				else
				{
					print 'incorrect username/password <br>';
					print '<a href="http://localhost/messageBoard.htm">Retry</a>';
				}
			}
			/*mysql_query('INSERT INTO login(username, password) VALUES(\''.$user.'\', \''.$password.'\');', $connection) or 
			header("Location: http://localhost/messageBoard.htm");
			print 'Data stored';
			$data = mysql_query('SELECT * FROM products', $connection) or die("Could not read from the DB");
			while ($row = mysql_fetch_row($data))
			{
				foreach ($row as $field)
				{
					print $field." ";
				}
				print "<br>";
			}*/
		}
	?>
</body>
</html>