<?php
include('include/collegeconnection.php');
if (isset($_COOKIE['private']) && $_COOKIE['private'] == 'private')
{
	setcookie('private', '', time() - (365*24*60*60));
	$private = true;
}
else
{
	$private = false;
}
if(isset($_POST['subject']) && $_POST['subject'] != '')
{
	{
		$subjectconst = $_POST['subject'];
		$subject = strtoupper($subjectconst);
	}
	if(strcmp($subjectconst, ""))
	{
		$finds = array();
		$continue = true;
		while($continue)
		{
			$pos = strpos($subject, " ");
			if($pos)
			{
				$name = substr($subject, 0 , $pos);
			}
			else
			{
				$name = substr($subject, 0);
				$continue = false;
			}
			if(strlen($name) > 2)
			{
				array_push($finds, $name);
			}
			$subject = substr($subject, $pos + 1);
		}
		if($_POST['category'] == 'all')
			{
				$data = mysql_query('SELECT * FROM products WHERE name = \''.$subjectconst.'\';', $connection);
			}
			else if($_POST['category'] != 'all')
			{
				$data = mysql_query('SELECT * FROM products WHERE name = \''.$subjectconst.'\' AND category ="'.$_POST['category'].'";', $connection);
			}
		
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($subjectconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$ids = array(); //initialize a new array to hold the subject id numbers
			if($_POST['category'] == 'all')
			{
				$data = mysql_query('SELECT * FROM products;', $connection) or die("error"); //retrieve all the data from subjects
			}
			else if($_POST['category'] != 'all')
			{
				$data = mysql_query('SELECT * FROM products WHERE category = "'.$_POST['category'].'";', $connection) or die("error"); //retrieve all the data from subjects
			}
			else
			{
				die($_POST['category']);
			}
			$common = array();
			$gotOne = false;
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the subject name entered...
				{
					//if the section of the entered data is in the name or the description..
					if(!strcmp(strtoupper($find), strtoupper($row['name'])) ||
					(strpos(strtoupper($row['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $row['id']); //add this subject's id.
						$occur++;
					}//end if
				}//end for
				$common[$row['id']] = $occur;
			}//end while
			$ids = array_unique($ids);
		} //end if
		if (isset($common))
		{
			arsort($common);
		}
	}
}
	else if(isset($_POST['submitted']) && $_POST['category'] != 'all')
	{
		$common = array();
		$ids = array();
		$data = mysql_query('SELECT * FROM products WHERE category = "'.$_POST['category'].'";', $connection) or die("error"); //retrieve all the data from subjects
		if(mysql_num_rows($data) > 0)
		{
			while($row = mysql_fetch_array($data, MYSQL_ASSOC))
			{
				$gotOne = true;
				array_push($ids, $row['id']);
				$occur = 1;
				$common[$row['id']] = $occur;
			}
			if($gotOne)
			{
				$common = array_unique($common);
				arsort($common);
			}
		}
		else
		{
			$gotOne = false;
		}
	}
	else if(isset($_POST['submitted']))
	{
		$subjectconst = "<div class='error'>You didn't enter anything...</div>";
		$error = true;
	}
include("include/header.php");
?>
<script src="/javascripts/functions2.js" type="text/javascript">
</script>
<link rel="stylesheet" type="text/css" href="/include/usellStyle.css">
<body>
<div id="mainContentPart">
	<div id="USellCenterBox">
			<?php
				if(isset($_COOKIE['userid']))
				{
					print '<div class="alert" style="color: #000"><img style="margin: 0px auto; float: center;" src="/images/new.png"></img>Click the my Items option to add an item</div>';
				}
			?>
			</p></div><br><br><br>
		</div>
	<?php
		if($private)
		{
			print '<div class="error">That product is set to private. <a href="/register.php">Sign in</a> to view private items.</div>';
		}
	?>
		<div id="searchBar">
			<table>
				<tr><td>
					<p>TCN U-Sell Search:</p></td><td>
					<form id="USellSearch" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
					<label class='header5Bl' for="subject">Search For:</label><input type="text" name="subject">
					<label class='header5Bl' for="category">Category</label>
					<select name="category">
					<option value="all">All</option>
					<option value="book">Books</option>
					<option value="furniture">Furniture</option>
					<option value="electronic">Electronics</option>
					<option value="service">Service</option>
					<option value="housing">Housing</option>
					<option value="Other">Other</option>
					</select>
					<input type="submit" value="Search">
					<input type="hidden" name="submitted" value="1">
				</form>
				</td></tr>
			</table>
		</div>
		
	</div>
	</div>
		<div id="USellBody">
	
		<table id="USellProdTable">
		<tr>
		<td>
	<div class="mySellsLink" id="testBox"><p>My Options: </p><br>
		<a href="mySells" class="header4Bl">My Items</a><br>
		<a href="addSell" class="header4Bl">Add Item</a><br>
	</div>
		</td>
		<td rowspan="100%">
		<div id="products">
		<table cellspacing="0">
			
			<?php
				if($error)
				{
					print $subjectconst;
				}
				if(isset($gotOne) && !$gotOne)
				{
					print '<div class="alert" style="text-align: -moz-center; text-align: center">No Matches Found<br><a href="/U-Sell.php">Back to U-Sell Index</a></div>';
				}
				else
				{
				
				if(isset($gotOne) && $gotOne)
				{
					print '<tr><td></td><td colspan="100%" class="alert">Search Results for: subject="'.strip_tags($_POST['subject'].'" category="'.$_POST['category']).'"</td></tr>';
					print '<tr><th>Title</th><th>Description</th><th>Date Added</th></tr>';
					foreach($common as $id => $num)
					{
						if($num > 0)
						{
							$data = mysql_query('SELECT name, description, date_added, thumb_url, private FROM products WHERE id = '.$id.';', $connection);
							$row = mysql_fetch_array($data, MYSQL_ASSOC);
							if((isset($_COOKIE['userid']) && $row['private']!=2) || isset($_COOKIE['userid']))
							{
								print '<tr><td><a href="product.php?pid='.$id.'">'.stripslashes($row['name']).'<br><img src="'.$row['thumb_url'].'"></img></a></td><td>'.stripslashes($row['description']).'</td>';
								$date = date('F jS Y', strtotime($row['date_added']));
								print '<td>'.$date.'</td></tr>';
							}
						}
					}
				}
				else
				{
					
					if(!isset($_COOKIE['userid']))
					{
						print '<tr><td></td><td colspan="100%" class="alert"><a href="/login.php">Sign in</a> to see more items</td></tr>';
						$products = mysql_query('SELECT id, thumb_url, private,  image_url, name, description, date_added, userid, category FROM products WHERE private = 2 ORDER BY id DESC LIMIT 10;', $connection);
					}
					else
					{
						$products = mysql_query('SELECT id, thumb_url, private,  image_url, name, description, date_added, userid, category FROM products ORDER BY id DESC LIMIT 10;', $connection);
					}
					$counter = 0;
					while($productData = mysql_fetch_array($products, MYSQL_ASSOC))
					{
						print '<tr><td id="row'.$counter.'"><a href="product.php?pid='.$productData['id'].'">'.stripslashes($productData['name']).'<br><img id="product'.$counter.'" src="'.$productData['thumb_url'].'"></img></a></td><td class="description">'.stripslashes(strip_tags($productData['description'])).'</td>';
						$date = date('F jS Y', strtotime($date_added));
						print '<td>'.$date.'</td></tr>';
						$counter++;
					}
				}
				}
			?>
		</table>
		</div>
		</td>
		</tr>
		</table>
		</div>
	
</div>
<br><br>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</td></tr></table>
</div>
</div>
</div>
