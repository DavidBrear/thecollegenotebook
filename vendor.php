<?php
include_once('include/collegeconnection.php');

if(isset($_GET['pid']) && is_numeric($_GET['pid']))
{
	$data = mysql_query('SELECT name, category, city, state, about, distance, address, schoolid, dealDesc, dealURL, URL, AdURL FROM place WHERE id = '.$_GET['pid'].';', $connection);
	$place = mysql_fetch_array($data, MYSQL_ASSOC);
}
else
{
	header('Location: login.php');
}

$vendor = false;
if(isset($_COOKIE['vendorid']) && $_GET['pid'] == $_COOKIE['vendorid'])
{
	$vendor = true;
}
include_once('include/header.php');
?><head><title><?php print $place['name'] ?></title>
<link rel="Stylesheet" href="include/vendor.css" type="text/css">

</head>


<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
		<?php if ((isset($_COOKIE['admin']) && $_COOKIE['admin'] == 1) || ($vendor))
			{
				print '<a href="editDeal.php?edit='.$_GET['pid'].'"><div class="alert"><h4>Click here to Edit Deal</h4></div></a>';
			}
		?>
		<span id="placeAd">
			<?php
							$adData = mysql_query('SELECT * from ads WHERE vendorid='.$_GET['pid'].';', $connection);
							
							$adRow = mysql_fetch_array($adData, MYSQL_ASSOC);
							switch($adRow['type'])
							{
								case 'image':
								{
									print '<a href="'.$adRow['link'].'" onclick="setAdClicked(\''.$adRow['id'].'\')">';
									print '<img src="'.$adRow['image_url'].'">';
									print '</a>';
								}break;
								case 'div':
								{
									print '<div id="testAd" style="float: left; clear: both; border: 1px #000 solid; width: 120px; overflow: hidden;">';
									print $adRow['text'];
									if($adRow['image_url'] != 'noImage')
									{
										print '<img src="'.$adRow['image_url'].'"></img>';
									}
									print $adRow['text2'];
									print '<br>';
									print '</div>';
								}break;
								case 'flash':
								{
								}break;
								default:
								{
									print '<div class="error">Error Loading ad</div>';
								}break;
							}
						?>
		</span>
		<table id="placeTable" cellspacing="0" border="0">
			<tr><td class="heading" colspan="100%"><?php print ucfirst($place['name']); ?></td></tr>
			<tr><td><p class="class">Category:&nbsp;</p><?php print ucfirst($place['category']); ?></td></tr>
			<tr><td><p class="class">Address:&nbsp;</p><?php print $place['address'] ?>&nbsp;</td><td><?php print $place['city'].', '.$place['state']; ?></td></tr>
			<tr><td><p class="class">Distance from Campus:</p> <?php print $place['distance'] ?></td></tr>
			<tr><td colspan="100%"><?php print '<p class="class">About '.$place['name'].':</p><br><p  class="description">&nbsp;&nbsp;&nbsp;&nbsp;'.stripslashes($place['about']); ?></p></td></tr>
			<tr><td colspan="100%" class="vendorHomeURL"><?php print '<center><a href="'.$place['URL'].'"><div onmouseover="this.style.backgroundColor=\'#005b0c\'" onmouseout="this.style.backgroundColor=\'#454b65\'"> '.$place['name'].' Website </div></a></center>'; ?></td></tr>
			<tr><td class="deals" colspan="100%"><?php
			if (isset($place['dealDesc']) && $place['dealDesc'] != ' ' && $place['dealDesc'] != '')
			{
				print '<p class="heading">Deals</p><br><div class="dealDesc">'.stripslashes($place['dealDesc']).'</div>';
			}
			if (isset($place['dealURL']) && $place['dealURL'] != ' ' && $place['dealURL'] != '')
			{
				
				print '<img src="'.$place['dealURL'].'"></img>';
			}
			 ?></td></tr>
			
		</table>
		
	</div>
	<div id="footer">
	2007 The College Notebook LLC.
	</div>
</div>