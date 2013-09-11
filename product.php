<?php
include('include/collegeconnection.php');
if(isset($_GET['pid']) && is_numeric($_GET['pid']))
{
	$productid = $_GET['pid'];
}
else
{
	header('Location: U-Sell');
}
$data = mysql_query('SELECT private, name, category, image_url, thumb_url, date_added, description, phone, aim, price, userid from products WHERE id = '.$productid.';', $connection) or die('error');
if(mysql_num_rows($data) < 1)
{
	header('Location: U-Sell');
}
$rowData = mysql_fetch_array($data, MYSQL_ASSOC);
if($rowData['private'] == 1)
{
	if(!isset($_COOKIE['userid']))
	{
		setcookie('private', 'private');
		header('Location: U-Sell');
	}
	
}
$date_added = $rowData['date_added'];
$name = $rowData['name'];
$image_url = $rowData['image_url'];
$description = $rowData['description'];
$category = $rowData['category'];
$price = $rowData['price'];
$aim = $rowData['aim'];
$phone = $rowData['phone'];
$privacy = $rowData['private'];
$userIDdata = mysql_query('SELECT users.name, login.email FROM login, users WHERE login.id = users.id AND users.id ='.$rowData['userid'].';', $connection);
$userID = mysql_fetch_array($userIDdata, MYSQL_ASSOC);
include('include/header.php');
?>
<style type="text/css" rel="Stylesheet">
	.biggerBox
	{
		width: 600px;
	}
</style>
<div id="mainContentPart">
	<div class="schoolIndexCenterBox">
		<p class='pagetitle'><?php print stripslashes($rowData['name']) ?></p>
		<table id="currentSell">
				<tr><td class="cat" colspan="100%"><div class="biggerBox">Name of item:&nbsp;<?php print stripslashes($name); ?></div></td></tr>
				<tr><td class="cat" colspan="100%"><div class="biggerBox">For Sale By:&nbsp;<?php print '<a style="color: #EEE !important;" href="/profile?id='.$rowData['userid'].'">'.stripslashes($userID['name']).'('.stripslashes($userID['email']).')</a>&nbsp;|&nbsp;'.'<a style="color: #E0E0E0 !important;" href="/compose?id='.$rowData['userid'].'">Message This User</a>'; ?></div></td></tr>
				<tr><td class="cat">Picture:</td><td><?php if($image_url!= ''){ print '<img id="productPicture" src="'.stripslashes($image_url).'"></img>';} else { print 'No Image';}  ?></td></tr>
				<tr><td class="cat">Added on:</td><td><?php print date('l, F jS Y', strtotime($date_added)).' at '.date('h:i a', strtotime($date_added)); ?></td></tr>
				
				
				<tr><td class="cat">Description:</td><td style="padding-top: 9px; padding-bottom: 10px;"><?php print stripslashes(nl2br($description)); ?></td></tr>
				<tr><td class="cat">Category:</td><td><?php print stripslashes($category); ?></td></tr>
				<tr><td class="cat">Price:</td><td><?php print stripslashes($price); ?></td></tr>
				<tr><td class="cat">AIM:</td><td><?php print stripslashes($aim); ?></td></tr>
				<tr><td class="cat">Phone:</td><td><?php print stripslashes($phone); ?></td></tr>
				
				<tr><td class="cat">Privacy:</td><td>
				<?php switch($privacy)
					{
					case 0:
					{
						print 'Just your friends can see this product';
						break;
					}
					case 1:
					{
						print 'Just TCN members can see this product';
						break;
					}
					case 2:
					{
						print 'Anyone can see this product';
						break;
					}
					default:
					{
						break;
					}
					}
				?></td></tr>
			</table>
		<a href="/U-Sell">Back to U-Sell</a>
	</div>
</div>
</td></tr></table>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
<script type="text/javascript">
	function resize()
	{
		var width = document.getElementById('productPicture').width;
		var height = document.getElementById('productPicture').height;
		while (width >= (275) || height >= (275))
		{
			document.getElementById('productPicture').width = width * .9;
			document.getElementById('productPicture').height = height *.9;
			width = document.getElementById('productPicture').width;
			height = document.getElementById('productPicture').height;
		}
		var boxWidth = (screen.width*.43);
		var boxHeight = (screen.height*.3);
		var picWidth = document.getElementById('productPicture').width;
		var picHeight = document.getElementById('productPicture').height; 
		document.getElementById('productPicture').style.marginTop = (boxHeight-picHeight)/2;
		document.getElementById('productPicture').style.visibility = "visible";
	}
	resize();
</script>