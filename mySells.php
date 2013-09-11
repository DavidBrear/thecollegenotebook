<?php
	include_once('include/authentication.php');
	if(isset($_COOKIE['msg']))
	{
		$message = $_COOKIE['msg'];
		setcookie('msg', '', time()-100000);
	}
	include_once('include/header.php');
?>
<script type="text/javascript">
(new Image()).src = '/images/addItemDown.png';
</script>
<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
		<div id="schoolIndexHeader"><p>&nbsp;&nbsp;&nbsp;&nbsp;This is a list of your personal "Sells" which you are
			free to add/edit/delete. You can have a maximum of 10 Sells. This is not a place to start a business and in intended to help students
			exchange books and other important college items. You may choose to sell only to college students by setting the privacy to "Just TCN users".
			</p></div>
		<?php
			if(isset($message))
			{
				print '<div class="success">'.$message.'</div>';
			}
			print '<table border="1">';
			$products = mysql_query('SELECT * FROM products WHERE userid='.$_COOKIE['userid'].';', $connection);
			while($row = mysql_fetch_array($products, MYSQL_ASSOC))
			{
				print '<tr><td><a href="/product.php?pid='.$row['id'].'"><img src="'.$row['thumb_url'].'"></img><p>'.$row['name'].'</p></a></td><td><a href="/addSell.php?ed='.$row['id'].'">Edit</a>&nbsp;|&nbsp;<a onclick="return confirm(\'Are you sure you want to delete this item?\');" href="/addSell.php?del='.$row['id'].'">Delete</a></td></tr>';
			}
			print '</table>';
		?>
		<a href="/addSell.php">Add Item</a>&nbsp;|&nbsp;
		<a href="/U-Sell.php">Back to U-Sell</a>
	</div>
</div>
</td></tr></table>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>