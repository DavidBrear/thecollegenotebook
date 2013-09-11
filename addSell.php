<?php
	include_once('include/authentication.php');
	
	if(isset($_POST['submitted']))
	{
		$error = false;
		$added = false;
			$description = strip_tags(addslashes($_POST['description']));
			$category = strip_tags(addslashes($_POST['category']));
			$privacy = strip_tags(addslashes($_POST['privacy']));
			$date_added = strip_tags(addslashes($_POST['commentDate']));
			$name = strip_tags(addslashes($_POST['name']));
			$phone = strip_tags(addslashes($_POST['phone']));
			$aim = strip_tags(addslashes($_POST['aim']));
			$price = strip_tags(addslashes($_POST['price']));
			$data = mysql_query('SELECT count(id) as numOfProducts FROM products WHERE userid='.$_COOKIE['userid'].';', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$numOfSales = $row['numOfProducts'];
			if($numOfSales >= 10)
			{
				$errorMsg = 'You are at the max number of items to sell. Delete items to continue uploading.';
				$error = true;
			}
			else
			{
				if(isset($_GET['ed']))
				{
					$data = mysql_query('SELECT * FROM products WHERE userid='.$_COOKIE['userid'].';', $connection);
					$row = mysql_fetch_array($data, MYSQL_ASSOC);
					if($row['userid'] != $_COOKIE['userid'])
					{
						$errorMsg = 'This is not your product';
						$error = true;
					}
					else
					{
						mysql_query('UPDATE products SET name = "'.$name.'", description ="'.$description.'", category ="'.$category.'", private='.$privacy.', phone="'.$phone.'", price="'.$price.'", aim="'.$aim.'" WHERE id = '.$_GET['ed'].' AND userid='.$_COOKIE['userid'].';', $connection) or die('error adding');
						if(isset($_POST['verify']))
						{
							$uploaded = false;
							$picture = $_FILES['file']['name'];
							if($_POST['verify'] == 'on')
							{
								if(eregi('\.[gif|jpg|bmp|jpeg]*$', $picture))
								{
									if(is_uploaded_file($_FILES['file']['tmp_name']) && ($_FILES['file']['size'] < 5000000))
									{
										$data = mysql_query('SELECT email FROM login WHERE id = '.$_COOKIE['userid'].';', $connection);
										$row = mysql_fetch_array($data, MYSQL_ASSOC);
										$uploaddir = 'images/'.$_COOKIE['userid'].'_'.md5($row['email']).'/';
										$data = mysql_query('SELECT image_url, thumb_url FROM products WHERE id = '.$_GET['ed'].';', $connection);
										$ImageRow = mysql_fetch_array($data, MYSQL_ASSOC);
										if(!file_exists($uploaddir))
										{
											mkdir($uploaddir);
											chmod($uploaddir, 0777);
										}
										else
										{
											$handle = opendir($uploaddir);
											if($ImageRow['image_url'] != 'images/noImage.jpg')
											{
												unlink($ImageRow['image_url']);
												unlink($ImageRow['thumb_url']);
											}
										}
										switch($_FILES['file']['type'])
										{
											case 'image/gif':
												$type = '.gif';
												break;
											case 'image/jpg':
												$type = '.jpg';
												break;
											case 'image/bmp':
												$type = '.bmp';
												break;
											case 'image/jpeg':
												$type = '.jpeg';
												break;
											default:
												$type = '.jpg';
												break;			
										}
										srand(time());
										//start the session
										$pictureName = md5(rand());
										if(strcmp($ImageRow['image_url'], 'images/noImage.jpg') != 0)
										{
											$num = explode('_', $ImageRow['image_url']);
											$prodNum = $num[2];
										}
										else
										{
											$prodNum = $numOfSales;
										}
										$thumbnailDir = $uploaddir.'Product_'.$prodNum.'_'.$pictureName.'Thumb.jpg';
										$uploaddir = $uploaddir.'Product_'.$prodNum.'_'.$pictureName.$type;
										mysql_query('update products set image_url = "'.$uploaddir.'", thumb_url="'.$thumbnailDir.'" where userid = '.$_COOKIE['userid'].' AND id='.$_GET['ed'].';', $connection) or die("error");
										if (rename($_FILES['file']['tmp_name'], $uploaddir))
										{
											// The file you are resizing
											$file = $uploaddir;
											// Setting the resize parameters
											list($width, $height) = getimagesize($file);
											$size = max($height, $width);
											$size = 80 / $size;
											$modwidth = $width * $size;
											$modheight = $height * $size;
											// Creating the Canvas
											$tn= imagecreatetruecolor($modwidth, $modheight);
											if($type=='.jpeg' || type=='.jpg')
											{
												$source = imagecreatefromjpeg($file);
											}
										else if($type =='.gif')
										{
											$source = imagecreatefromgif($file);
										}
										else if($type == '.png')
										{
											$source = imagecreatefrompng($file);
										}
										else if($type == '.bmp')
										{
											$source = imagecreatefrombmp($file);
										}
										else
										{
											$source = imagecreatefromjpeg($file);
										}
					
										// Resizing our image to fit the canvas
										imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
										
										// Outputs a jpg image, you could change this to gif or png if needed
										imagejpeg($tn, $thumbnailDir, 100);
										// The file you are resizing
										$file = $uploaddir;
										
										// Setting the resize parameters
										list($width, $height) = getimagesize($file);
										$size = max($height, $width);
										$size = 250 / $size;
										$modwidth = $width * $size;
										$modheight = $height * $size;
										// Creating the Canvas
										$tn= imagecreatetruecolor($modwidth, $modheight);
										if($type=='.jpeg' || type=='.jpg')
										{
											$source = imagecreatefromjpeg($file);
										}
										else if($type =='.gif')
										{
											$source = imagecreatefromgif($file);
										}
										else if($type == '.png')
										{
											$source = imagecreatefrompng($file);
										}
										else if($type == '.bmp')
										{
											$source = imagecreatefrombmp($file);
										}
										else
										{
											$source = imagecreatefromjpeg($file);
										}
										
										// Resizing our image to fit the canvas
										imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
															
										// Outputs a jpg image, you could change this to gif or png if needed
										if($type=='.jpeg' || type=='.jpg')
										{
											imagejpeg($tn, $uploaddir, 100);
										}
										else if($type =='.gif')
										{
											imagegif($tn, $uploaddir, 100);
										}
										else if($type == '.png')
										{
											imagepng($tn, $uploaddir, 100);
										}
										else if($type == '.bmp')
										{
											imagejpeg($tn, $uploaddir, 100);
										}
										else
										{
											imagejpeg($tn, $uploaddir, 100);
										}
					
										$uploaded = true;
										
										chmod($uploaddir, 0777);
										}
										else
										{
											$Picerror = $Picerror.'<div class="error">error uploading '.$_FILES['file']['name'].'</div>';
										}
								
									}
									else
									{
										$Picerror = $Picerror.'<div class="error">error uploading '.$_FILES['file']['name'].' file too big</div>';
									}
								}
								else
								{
									$Picerror = $Picerror.'<div class ="error">File must be (.gif, .jpg, .bmp, .jpeg)</div>';
								}
							}
						}
						$action = 'updated';
					}
				}
				else
				{
					$uploaddir = 'images/noImage.jpg';
					$thumbnailDir = 'images/noImage.jpg';
					if(isset($_POST['verify']))
						{
							$uploaded = false;
							$picture = $_FILES['file']['name'];
							
							if($_POST['verify'] == 'on' && is_uploaded_file($_FILES['file']['tmp_name']))
							{
								if(eregi('\.[gif|jpg|bmp|jpeg]*$', $picture))
								{
										$data = mysql_query('SELECT email FROM login WHERE id = '.$_COOKIE['userid'].';', $connection);
										$row = mysql_fetch_array($data, MYSQL_ASSOC);
										$uploaddir = 'images/'.$_COOKIE['userid'].'_'.md5($row['email']).'/';						
										if(!file_exists($uploaddir))
										{
											mkdir($uploaddir);
											chmod($uploaddir, 0777);
										}
										else
										{
											$handle = opendir($uploaddir);

										}
										switch($_FILES['file']['type'])
										{
											case 'image/gif':
												$type = '.gif';
												break;
											case 'image/jpg':
												$type = '.jpg';
												break;
											case 'image/bmp':
												$type = '.bmp';
												break;
											case 'image/jpeg':
												$type = '.jpeg';
												break;
											default:
												$type = '.jpg';
												break;			
										}
										srand(time());
										//start the session
										$pictureName = md5(rand());
										$thumbnailDir = $uploaddir.'Product_'.$numOfSales.'_'.$pictureName.'Thumb.jpg';
										$uploaddir = $uploaddir.'Product_'.$numOfSales.'_'.$pictureName.$type;
										
										if (rename($_FILES['file']['tmp_name'], $uploaddir))
										{
											// The file you are resizing
											$file = $uploaddir;
											// Setting the resize parameters
											list($width, $height) = getimagesize($file);
											$size = max($height, $width);
											$size = 80 / $size;
											$modwidth = $width * $size;
											$modheight = $height * $size;
											// Creating the Canvas
											$tn= imagecreatetruecolor($modwidth, $modheight);
											if($type=='.jpeg' || type=='.jpg')
											{
												$source = imagecreatefromjpeg($file);
											}
										else if($type =='.gif')
										{
											$source = imagecreatefromgif($file);
										}
										else if($type == '.png')
										{
											$source = imagecreatefrompng($file);
										}
										else if($type == '.bmp')
										{
											$source = imagecreatefrombmp($file);
										}
										else
										{
											$source = imagecreatefromjpeg($file);
										}
					
										// Resizing our image to fit the canvas
										imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
										
										// Outputs a jpg image, you could change this to gif or png if needed
										imagejpeg($tn, $thumbnailDir, 100);
										// The file you are resizing
										$file = $uploaddir;
										
										// Setting the resize parameters
										list($width, $height) = getimagesize($file);
										$size = max($height, $width);
										$size = 250 / $size;
										$modwidth = $width * $size;
										$modheight = $height * $size;
										// Creating the Canvas
										$tn= imagecreatetruecolor($modwidth, $modheight);
										if($type=='.jpeg' || type=='.jpg')
										{
											$source = imagecreatefromjpeg($file);
										}
										else if($type =='.gif')
										{
											$source = imagecreatefromgif($file);
										}
										else if($type == '.png')
										{
											$source = imagecreatefrompng($file);
										}
										else if($type == '.bmp')
										{
											$source = imagecreatefrombmp($file);
										}
										else
										{
											$source = imagecreatefromjpeg($file);
										}
										
										// Resizing our image to fit the canvas
										imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
															
										// Outputs a jpg image, you could change this to gif or png if needed
										if($type=='.jpeg' || type=='.jpg')
										{
											imagejpeg($tn, $uploaddir, 100);
										}
										else if($type =='.gif')
										{
											imagegif($tn, $uploaddir, 100);
										}
										else if($type == '.png')
										{
											imagepng($tn, $uploaddir, 100);
										}
										else if($type == '.bmp')
										{
											imagejpeg($tn, $uploaddir, 100);
										}
										else
										{
											imagejpeg($tn, $uploaddir, 100);
										}
					
										$uploaded = true;
										
										chmod($uploaddir, 0777);
										}
										else
										{
											$Picerror = $Picerror.'<div class="error">error uploading '.$_FILES['file']['name'].'</div>';
										}
								}
								else
								{
									$Picerror = $Picerror.'<div class ="error">File must be (.gif, .jpg, .bmp, .jpeg)</div>';
								}
								
							}
							else if($_POST['verify'] == 'on')
							{
								$Picerror = $Picerror.'<div class="error">Error: no picture specified</div>';
							}
							else if(is_uploaded_file($_FILES['file']['tmp_name']))
							{
								$Picerror = $Picerror.'<div class="error">Error: You must verify that this picture is yours before it can be uploaded</div>';
							}
						}
					if(!isset($Picerror))
					{
						mysql_query('INSERT INTO products(name, description, category, userid, private, date_added, image_url, thumb_url, price, phone, aim) VALUES("'.$name.'", "'.$description.'", "'.$category.'", "'.$_COOKIE['userid'].'", '.$privacy.', "'.$date_added.'", "'.$uploaddir.'", "'.$thumbnailDir.'", "'.$price.'", "'.$phone.'", "'.$aim.'");', $connection) or die('error inserting: '.mysql_error());
						$action = 'added';
						$image_url = $uploaddir;
						list($image_Width, $image_Height, $image_type, $image_Attr) = getimagesize($image_url);
						setcookie('msg', 'Item Added', time()+10);
						header('Location: /mySells.php');
					}
				}
				$added = true;
			}
	}
	if(isset($_GET['ed']))
	{
		$data = mysql_query('SELECT * FROM products WHERE userid='.$_COOKIE['userid'].' AND id='.$_GET['ed'].';', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		if($row['userid'] != $_COOKIE['userid'])
		{
			$errorMsg = 'This is not your product';
			$error = true;
		}
		else
		{
			$name = $row['name'];
			$image_url = $row['image_url'];
			list($image_Width, $image_Height, $image_type, $image_Attr) = getimagesize($row['image_url']);
			$description = $row['description'];
			$category = $row['category'];
			$privacy = $row['private'];
			$date_added = $row['date_added'];
			$image_url = $row['image_url'];
			$price = $row['price'];
			$aim = $row['aim'];
			$phone = $row['phone'];
		}
	}
	else if(isset($_GET['del']))
	{
		$productData = mysql_query('SELECT * FROM products WHERE id='.addslashes(strip_tags($_GET['del'])).' and userid='.$_COOKIE['userid'].';', $connection);
		if(mysql_num_rows($productData) == 1)
		{
			$product=mysql_fetch_array($productData, MYSQL_ASSOC);
			if($product['image_url'] != 'images/noImage.jpg')
			{
				unlink($product['image_url']);
				unlink($product['thumb_url']);
			}
			mysql_query('DELETE FROM products WHERE id='.addslashes(strip_tags($_GET['del'])).' and userid='.$_COOKIE['userid'].';', $connection);
			
			setcookie('msg', 'Item deleted', time()+10);
		}
		header('Location: /mySells.php');
	}
	else
	{
		$privacy = 2;
		$date_added = '';
	}
	include_once('include/header.php');
?>
<script type="text/javascript" src="/javascripts/runafter.js"></script>

<div id="mainContentPart">
	<div id="USellCenterBox">
	<div id="USellBody">
		<?php
			if($error)
			{
				print '<div class="error">'.$errorMsg.'</div>';
			}
			if(isset($Picerror))
			{
				print '<div class="error">'.$Picerror.'</div>';
			}
			else if($added)
			{
				switch($action)
				{
					case 'updated':
					{
						print '<div class="alert" style="text-align: -moz-center; text-align: center;">product updated</div>';
						break;
					}
					case 'added':
					{
						print '<div class="alert" style="text-align: -moz-center; text-align: center;">product added</div>';
						break;
					}
				}
			}
		?>
		<table id="addSellTable">
		<tr>
		<td class="tables">
			<table id="addSell">
			<form name="addSellForm" enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
				<tr><td class="cat"><label for="name">Name of item:</label></td><td><input type="text" name="name" value="<?php print stripslashes($name); ?>"></td></tr>
				<tr><td class="cat"><label for="description">Description of item:</label></td><td><textarea rows="15" cols="50" name="description"><?php print stripslashes(nl2br($description)); ?></textarea></td></tr>
				<tr><td class="cat"><label for="category">Category:</label></td><td><select name="category">
				<option value="book" <?php if($category == 'book'){print 'selected="selected"';}?>>Book</option>
				<option value="electronic"<?php if($category == 'electronic'){print 'selected="selected"';}?>>Electronics</option>
				<option value="furniture"<?php if($category == 'furniture'){print 'selected="selected"';}?>>Furniture</option>
				<option value="service"<?php if($category == 'service'){print 'selected="selected"';}?>>Service</option>
				<option value="housing"<?php if($category == 'housing'){print 'selected="selected"';}?>>Housing</option>
				<option value="other"<?php if($category == 'other'){print 'selected="selected"';}?>>Other</option>
				</select></td></tr>
				<tr><td class="cat">Image: </td><td><input type="file" name ="file"></td></tr>
				<tr><td class="cat">Verify that this picture is yours</td><td><input type="checkbox" name="verify"></td></tr>
				<tr><td class="cat">Price</td><td><input type="text" name="price" value="<?php print stripslashes($price); ?>"></td></tr>
				<tr><td class="cat">AIM</td><td><input type="text" name="aim" value="<?php print stripslashes($aim); ?>"></td></tr>
				<tr><td class="cat">Phone</td><td><input type="text" name="phone" value="<?php print stripslashes($phone); ?>"></td></tr>
				<tr><td class="cat"><label for="privacy">Privacy:</label></td><td><select name="privacy">
				<option value="2"<?php if($privacy == 2){print 'selected="selected"';}?>>Public</option>
				<option value="1" <?php if($privacy == 1){print 'selected="selected"';}?>>Just TCN users</option>
				<option value="0"<?php if($privacy == 0){print 'selected="selected"';}?>>Just Friends</option>
				</select></td></tr>
				<input type="hidden" id="commentDate" value="" name="commentDate">
				<input type="hidden" value="1" name="submitted">
				<tr><td colspan="100%" align="center"><input type="submit" value="Save" onclick="setDate();">&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/U-Sell.php">Back to USell</a></td></tr>
			</form>
		</table>
		</td>
		<td class="tables">
			<table id="currentSell">
				<tr><td colspan="100%" align="center"><caption>Currently</caption></td></tr>
				<tr><td class="cat">Added on:</td><td><?php print date('l, F jS Y', strtotime($date_added)).' at '.date('h:i a', strtotime($date_added)); ?></td></tr>
				<tr><td class="cat">Name of item:</td><td><?php print stripslashes($name); ?></td></tr>
				<tr><td class="cat">Picture:</td><td><div style="padding: 0px; text-align: -moz-center; text-align: center; margin: 0px auto; <?php if($image_url!= '') { print 'width: '.($image_Width + 5).'px; height: '.($image_Height + 5).'px;' ;} ?>"><?php if($image_url!= ''){ print '<img style="margin: 2px auto !important; background-image: url(\'/images/loading.png\');" id="productPicture" src="'.stripslashes($image_url).'"></img>';} else { print 'No Image';}  ?></div></td></tr>
				<tr><td class="cat">Description:</td><td><?php print stripslashes(nl2br($description)); ?></td></tr>
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
		</td>
		</tr>
	</div>
</div>
</td></tr></table>
</div>
</div>
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