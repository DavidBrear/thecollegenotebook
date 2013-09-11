<?php
	
	include("include/collegeconnection.php");
	if (!isset($_GET['id']) || !(is_numeric($_GET['id'])))
	{
		header('Location: index.php');
	}
	$school = $_GET['id'];
	$data = mysql_query('SELECT count(id) as verify from schools where id = '.$school.';', $connection);
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	include("include/header.php");
	if($row['verify'] == 0)
	{
		if(isset($_COOKIE['userid']))
		{
			die('invalid school <a href="profile.php?id='.$_COOKIE['userid'].'">Home</a>');
		}
		else
		{
			die('invalid school <a href="login.php">Home</a>');
		}
	}
	$data = mysql_query('SELECT name, abrev, description, city, schoolEmail, state, num_students, public, girlguy, frats, sorority, percentgreek, religiousgroups, percentreligious, sports, transferrate, alcoholpolicy, classpercent, avgentergpa, avgsat, avggpa, newBuild, achieve, attract, mascot, socialLife, campType, stuPerMaj, legalAvail, avgCost from schools where id='.$school.';', $connection) or die('error finding school');
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	$schoolName = $row['abrev'];
	$student = false;
	if(isset($_COOKIE['userid']))
	{
		$userData = mysql_query("SELECT email FROM login, schools WHERE login.id = ".$_COOKIE['userid'].";", $connection) or die('error');
		$userID = mysql_fetch_array($userData, MYSQL_ASSOC);
		$emailEnding = explode('@', $userID['email']);
		if(($row['schoolEmail'] == $emailEnding[1]) || (isset($_COOKIE['admin']) && $_COOKIE['admin'] == 1))
		{
			$student = true;
			$email = $userID['email'];
		}
		else
		{
			$email = 'not logged in';
		}
		
	}
	function findLast($string)
	{
		$array = split("Last edited by:", $string);
		if(count($array) > 1)
		{
			$string = $array[0].'<p class="alert">Last edited by: '.$array[1].'</p>';
		}
		return $string;
	}
?>

<html>
<head>
<title><?php print $row['name'].' home - TheCollegeNotebook'; ?> </title>
<link rel="Stylesheet" type="text/css" href="/include/SchoolHomeStyle.css">
<script type="text/javascript" src="../javascripts/SIJS.js"></script>
</head>

<body>
<div id="mainContentPart">
<div class="schoolHomeHeader">
<a class="ReportPage" href="compose.php?id=30&m=_e&s=<?php print $_GET['id'].'&error=159dae9-5935'; ?>">*Report this page*</a>
	<div id="schoolHomeNav">
		<div>Skip to...</div>
		<a href="#reviews" name="top">Reviews</a><br>
		<a href="#vendors">Vendors</a>
	</div>
<h1><p class="schoolName"><?php print $row['name']; ?></p></h1>
<?php print $row['city'].', '.$row['state'];?>
</div>
<div id="schoolHomeBack">
<table id="schoolHomeTable" cellspacing="0"><tr><td>
<div class="schoolMini"><img style="margin: 0px auto;" src="/images/schoolMiniTop.gif"></img><table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<form onsubmit="return false;" name="editForm">
<tr><td class="attribute">A Bit About <?php print $row['name']; if ($student){ print '<a href="#desc" name="desc" onclick="openEdit(1)">{ Edit }</a>';} ?></td><td class="gray"><div id="row1"><?php print findLast(stripslashes(strip_tags(nl2br($row['description']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="1" class="editField"><textarea id="1Area" name="desc" rows="10" cols="50" style="overflow: auto;">';
print stripslashes(strip_tags($row['description']));
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'1\', document.editForm.desc.value, '.$_GET['id'].', \'description\', \''.$email.'\'); flash(document.getElementById(\'row1\'));"><a href="javascript:openEdit(1);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>'; 
}
?>
</td></tr>
</table>
<img style="margin: 0px auto; border-top: 1px #454b65 solid;" src="/images/schoolMiniBot.gif"></img>
</div>
</td>
<td>
<div class="schoolMini"><img style="margin: 0px auto;" src="/images/schoolMiniTop.gif"></img><table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Nearby Attractions<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(2)">{ Edit }</a>';}?></td><td><div id="row2"><?php print findLast(stripslashes(strip_tags(nl2br($row['attract']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="2" class="editField"><textarea id="2Area" name="attract" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['attract']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'2\', document.editForm.attract.value, '.$_GET['id'].', \'attract\', \''.$email.'\'); flash(document.getElementById(\'row2\'));"><a href="javascript:openEdit(2);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Achievements
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(3)">{ Edit }</a>';}?></td><td class="gray"><div id="row3"><?php print findLast(stripslashes(strip_tags(nl2br($row['achieve']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="3" class="editField"><textarea id="3Area" name="achieve" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['achieve']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'3\', document.editForm.achieve.value, '.$_GET['id'].', \'achieve\', \''.$email.'\'); flash(document.getElementById(\'row3\'));"><a href="javascript:openEdit(3);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Newest Building
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(4)">{ Edit }</a>';}?></td><td><div id="row4"><?php print findLast(stripslashes(strip_tags(nl2br($row['newBuild']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="4" class="editField"><textarea id="4Area" name="newBuild" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['newBuild']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'4\', document.editForm.newBuild.value, '.$_GET['id'].', \'newBuild\', \''.$email.'\'); flash(document.getElementById(\'row4\'));"><a href="javascript:openEdit(4);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Campus Type
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(5)">{ Edit }</a>';}?></td><td class="gray"><div id="row5"><?php print findLast(stripslashes(strip_tags(nl2br($row['campType']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="5" class="editField"><textarea id="5Area" name="campType" rows="10" cols="50" style="overflow: auto;">';
print stripslashes(nl2br($row['campType']));
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'5\', document.editForm.campType.value, '.$_GET['id'].', \'campType\', \''.$email.'\'); flash(document.getElementById(\'row4\'));"><a href="javascript:openEdit(5);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<!----This row was left out so it's number 22 even though it's between 5 and 6-->
<tr><td class="attribute">Number of Students
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(6)">{ Edit }</a>';}?></td><td><div id="row6"><?php $numStu = explode('<br>', $row['num_students']); print number_format($row['num_students']).'<br>'; print findLast(stripslashes($numStu[1])) ?></div>
<?php if ($student) { print '<div id="6" class="editField"><textarea id="6Area" onkeypress="onlynumbers(this, 10)" onkeyup="onlynumbers(this, 10)" name="num_students" rows="10" cols="50" style="overflow: auto;">';
print stripslashes(nl2br($row['num_students']));
print '</textarea><input type="submit" value="Submit" onclick="onlynumbers(document.getElementById(\'6Area\'), 10); submitSchool(\'6\', document.editForm.num_students.value, '.$_GET['id'].', \'num_students\', \''.$email.'\'); flash(document.getElementById(\'row5\'));"><a href="javascript:openEdit(6);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Students per Major<br><div style=" font-size: 10px; color: #000; width: 95%; background-color: #F0F0F0; padding-bottom: 2px; height: 10px;">&nbsp;&nbsp;(major : number)&nbsp;&nbsp;</div>
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(22)">{ Edit }</a>';}?></td><td class="gray"><div id="row22"><?php print findLast(stripslashes(strip_tags(nl2br($row['stuPerMaj']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="22" class="editField"><textarea id="22Area" name="stuPerMaj" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['stuPerMaj']);
print '</textarea><input type="submit" value="Submit" onclick=" submitSchool(\'22\', document.editForm.stuPerMaj.value, '.$_GET['id'].', \'stuPerMaj\', \''.$email.'\'); flash(document.getElementById(\'row22\'));"><a href="javascript:openEdit(22);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>

<tr><td class="attribute">Social Life
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(7)">{ Edit }</a>';}?></td><td><div id="row7"><?php print findLast(stripslashes(strip_tags(nl2br($row['socialLife']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="7" class="editField"><textarea id="7Area" name="socialLife" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['socialLife']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'7\', document.editForm.socialLife.value, '.$_GET['id'].', \'socialLife\', \''.$email.'\'); flash(document.getElementById(\'row7\'));"><a href="javascript:openEdit(7);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
</table>
<img style="margin: 0px auto; border-top: 1px #454b65 solid;" src="/images/schoolMiniBot.gif"></img>
</div>
</td></tr>
<tr><td>
<div class="schoolMini"><img style="margin: 0px auto;" src="/images/schoolMiniTop.gif"></img><table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Mascot
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(8)">{ Edit }</a>';}?></td><td class="gray"><div id="row8"><?php print findLast(stripslashes(strip_tags(nl2br($row['mascot']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="8" class="editField"><input id="8Area" type="text" name="mascot"';
print 'value="'.stripslashes($row['mascot']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'8\', document.editForm.mascot.value, '.$_GET['id'].', \'mascot\', \''.$email.'\'); flash(document.getElementById(\'row8\'));"><a href="javascript:openEdit(8);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Public/Private</td><td><div><?php print $row['public'] ?></div></td></tr>
<tr><td class="attribute">Girl:Guy Ratio
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(9)">{ Edit }</a>';}?></td><td class="gray"><div id="row9"><?php print findLast(stripslashes(strip_tags(nl2br($row['girlguy']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="9" class="editField"><input id="9Area" type="text" name="girlguy"';
print 'value="'.stripslashes($row['girlguy']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'9\', document.editForm.girlguy.value, '.$_GET['id'].', \'girlguy\', \''.$email.'\'); flash(document.getElementById(\'row9\'));"><a href="javascript:openEdit(9);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Fraternities
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(10)">{ Edit }</a>';}?></td><td><div id="row10"><?php print findLast(stripslashes(strip_tags(nl2br($row['frats']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="10" class="editField"><textarea id="10Area" name="frats" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['frats']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'10\', document.editForm.frats.value, '.$_GET['id'].', \'frats\', \''.$email.'\'); flash(document.getElementById(\'row10\'));"><a href="javascript:openEdit(10);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Sororities
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(11)">{ Edit }</a>';}?></td><td class="gray"><div id="row11"><?php print findLast(stripslashes(strip_tags(nl2br($row['sorority']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="11" class="editField"><textarea id="11Area" name="sorority" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['sorority']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'11\', document.editForm.sorority.value, '.$_GET['id'].', \'sorority\', \''.$email.'\'); flash(document.getElementById(\'row11\'));"><a href="javascript:openEdit(11);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Percent Greek
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(12)">{ Edit }</a>';}?></td><td><div id="row12"><?php print findLast(stripslashes(strip_tags(nl2br($row['percentgreek']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="12" class="editField"><input id="12Area" onkeypress="onlynumbers(this, 2)" onkeyup="onlynumbers(this, 2)" type="text" name="percentgreek"';
print 'value="'.stripslashes($row['percentgreek']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'12\', document.editForm.percentgreek.value, '.$_GET['id'].', \'percentgreek\', \''.$email.'\'); flash(document.getElementById(\'row12\'));"><a href="javascript:openEdit(12);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
</table>
<img style="margin: 0px auto; border-top: 1px #454b65 solid;" src="/images/schoolMiniBot.gif"></img>
</div>
</td>
<td>
<div class="schoolMini"><img style="margin: 0px auto;" src="/images/schoolMiniTop.gif"></img><table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Relgious Groups
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(13)">{ Edit }</a>';}?></td><td class="gray"><div id="row13"><?php print findLast(stripslashes(strip_tags(nl2br($row['religiousgroups']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="13" class="editField"><textarea id="13Area" name="religiousgroups" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['religiousgroups']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'13\', document.editForm.religiousgroups.value, '.$_GET['id'].', \'religiousgroups\', \''.$email.'\'); flash(document.getElementById(\'row13\'));"><a href="javascript:openEdit(13);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Relgious Percent Active
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(14)">{ Edit }</a>';}?></td><td><div id="row14"><?php print findLast(stripslashes(strip_tags(nl2br($row['percentreligious']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="14" class="editField"><input id="14Area" onkeypress="onlynumbers(this, 2)" onkeyup="onlynumbers(this, 2)" type="text" name="percentreligious"';
print 'value="'.stripslashes($row['percentreligious']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'14\', document.editForm.percentreligious.value, '.$_GET['id'].', \'percentreligious\', \''.$email.'\'); flash(document.getElementById(\'row14\'));"><a href="javascript:openEdit(14);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Sports Teams
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(15)">{ Edit }</a>';}?></td><td class="gray"><div id="row15"><?php print findLast(stripslashes(strip_tags(nl2br($row['sports']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="15" class="editField"><textarea id="15Area" name="sports" rows="10" cols="50" style="overflow: auto;">';
print stripslashes($row['sports']);
print '</textarea><input type="submit" value="Submit" onclick="submitSchool(\'15\', document.editForm.sports.value, '.$_GET['id'].', \'sports\', \''.$email.'\'); flash(document.getElementById(\'row15\'));"><a href="javascript:openEdit(15);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Transfer Rate
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(16)">{ Edit }</a>';}?></td><td><div id="row16"><?php print findLast(stripslashes(strip_tags(nl2br($row['transferrate']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="16" class="editField"><p class="note">This Field is for the transfer <b>out</b> rate.</p><br><br><input id="16Area" type="text" name="transferrate"';
print 'value="'.stripslashes($row['transferrate']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'16\', document.editForm.transferrate.value, '.$_GET['id'].', \'transferrate\', \''.$email.'\', \''.$email.'\'); flash(document.getElementById(\'row16\'));"><a href="javascript:openEdit(16);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Alcohol Policy
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(17)">{ Edit }</a>';}?></td><td class="gray"><div id="row17"><?php print findLast(stripslashes(strip_tags(nl2br($row['alcoholpolicy']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="17" class="editField"><p class="note">This Field is for the alcohol policy of the school. This should be basic (dry, wet).</p><br><input class="alchBox" id="17Area" type="text" name="alcoholpolicy"';
print 'value="'.stripslashes($row['alcoholpolicy']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'17\', document.editForm.alcoholpolicy.value, '.$_GET['id'].', \'alcoholpolicy\', \''.$email.'\'); flash(document.getElementById(\'row17\'));"><a href="javascript:openEdit(17);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
</table>
<img style="margin: 0px auto; border-top: 1px #454b65 solid;" src="/images/schoolMiniBot.gif"></img>
</div>
</td></tr>
<tr><td>
<div class="schoolMini"><img style="margin: 0px auto;" src="/images/schoolMiniTop.gif"></img><table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Average Class Size
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(18)">{ Edit }</a>';}?></td><td><div id="row18"><?php print findLast(stripslashes(strip_tags(nl2br($row['classpercent']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="18" class="editField"><input id="18Area" type="text" name="classpercent"';
print 'value="'.stripslashes($row['classpercent']).'" ';
print '><input type="submit" value="Submit" onclick="submitSchool(\'18\', document.editForm.classpercent.value, '.$_GET['id'].', \'classpercent\', \''.$email.'\'); flash(document.getElementById(\'row18\'));"><a href="javascript:openEdit(18);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Average Entering GPA
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(19)">{ Edit }</a>';}?></td><td class="gray"><div id="row19"><?php print findLast(stripslashes(strip_tags(nl2br($row['avgentergpa']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="19" class="editField"><input id="19Area" onkeypress="onlynumbers(this, 4)" onkeyup="onlynumbers(this, 4)" type="text" name="avgentergpa"';
print 'value="'.stripslashes($row['avgentergpa']).'" ';
print '><input type="submit" value="Submit" onclick="onlynumbers(document.getElementById(\'19Area\'), 4); submitSchool(\'19\', document.editForm.avgentergpa.value, '.$_GET['id'].', \'avgentergpa\', \''.$email.'\'); flash(document.getElementById(\'row19\'));"><a href="javascript:openEdit(19);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Average Entering SAT
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(20)">{ Edit }</a>';}?></td><td><div id="row20"><?php print findLast(stripslashes(strip_tags(nl2br($row['avgsat']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="20" class="editField"><input id="20Area" onkeypress="onlynumbers(this, 4)" onkeyup="onlynumbers(this, 4)" type="text" name="avgsat"';
print 'value="'.stripslashes($row['avgsat']).'" ';
print '><input type="submit" value="Submit" onclick="onlynumbers(document.getElementById(\'20Area\'), 4); submitSchool(\'20\', document.editForm.avgsat.value, '.$_GET['id'].', \'avgsat\', \''.$email.'\'); flash(document.getElementById(\'row2\'));"><a href="javascript:openEdit(20);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Average Student GPA
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(21)">{ Edit }</a>';}?></td><td class="gray"><div id="row21"><?php print findLast(stripslashes(strip_tags(nl2br($row['avggpa']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="21" class="editField"><input id="21Area" onkeypress="onlynumbers(this, 4)" onkeyup="onlynumbers(this, 4)" type="text" name="avggpa"';
print 'value="'.stripslashes($row['avggpa']).'" ';
print '><input type="submit" value="Submit" onclick="onlynumbers(document.getElementById(\'21Area\'), 4); submitSchool(\'21\', document.editForm.avggpa.value, '.$_GET['id'].', \'avggpa\', \''.$email.'\'); flash(document.getElementById(\'row21\'));"><a href="javascript:openEdit(21);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
<tr><td class="attribute">Average Annual Cost<br><br><div class="note">Including Room and Board</div>
</td><td><div id="row24"><?php print stripslashes(strip_tags(nl2br($row['avgCost']), '<br>')) ?></div>

</td></tr>
<tr><td class="attribute">Legal Assistance
<?php if ($student){ print '<a href="javascript: void(null);" onclick="openEdit(23)">{ Edit }</a>';}?></td><td class="gray"><div class="note">This is whether students are offered assistance, by the school, for legal matters.</div><div id="row23"><?php print findLast(stripslashes(strip_tags(nl2br($row['legalAvail']), '<br>'))) ?></div>
<?php if ($student) { print '<div id="23" class="editField"><select id="23Area" type="text" name="legalAvail">';
print '<option selected="selected" value="';
$arr = explode( ' ', stripslashes($row['legalAvail']));
print $arr[0].'">Currently - *';
if(stripslashes(nl2br($row['legalAvail'])) == '')
{
	print 'Unknown*</option>';
}
else
{
	$arr = explode( ' ', stripslashes(nl2br($row['legalAvail'])));
	print $arr[0].'*</option>';
}
print '<option value="Yes">Yes</option>';
print '<option value="No">No</option>';
print '</select><input type="submit" value="Submit" onclick=" submitSchool(\'23\', document.editForm.legalAvail.value, '.$_GET['id'].', \'legalAvail\', \''.$email.'\'); flash(document.getElementById(\'row23\'));"><a href="javascript:openEdit(23);">Cancel</a><br>
<div class="alert">Your IP address is: ';
print getenv("REMOTE_ADDR").'. Your email address is: '.$email.'. This will be recorded if data is submitted. <font color="#FF5555">Inappropriate or invalid entries are grounds for banning.</font></div></div>';
}
?>
</td></tr>
</table>
<img style="margin: 0px auto; border-top: 1px #454b65 solid;" src="/images/schoolMiniBot.gif"></img>
</div>
</td></tr>
</form>
<tr><td colspan="100%">
<div class="back">
	<a href="compose.php?id=30&m=_e&s=<?php print $_GET['id'].'&error=159dae9-5935'; ?>">Report this page</a> |
	<a href="schoolindex.php">Back to Index</a>&nbsp;|&nbsp;
	<a href="#top">Back to Top</a>
</div>
</td></tr>
</table>
<table id="schoolHomeBottom"><tr>
<td>
<div id="schoolHomeSearch">
			
			<table border="0" cellspacing="0">
			<a name="reviews"></a>
			<div class="pageTitle2">Review Search for <?php print $schoolName ?></div>
			<form  name = "search" action="reviewSearch.php" method="POST">
			
			<input type="hidden" name="school" value="<?php print $schoolName ?>">
			<tr><td><p>Subject:</p></td><td align="left"><input class="text" type="text" size="15" name="subject" onClick="document.search.subject.select();" value="search by subject"></td></tr>
			<tr><td cellspacing="100%"><input class="button" type="image" name="submit" src="images/searchButton.gif"></td></tr>
			<input type="hidden" name="submitted" value="1">
			<tr><td><a href="reviewSearch.php" color="black">Advanced Search</a></td><td></td></tr>
			</form>
			</table>
		  </div>

<div id="schoolHomeReviews">
<?php
$category = '';
$first = true;
print '<p class="schoolTitle">Reviews for '.$row['name'].'</p>';
print '<div class="">**Click a review category below to expand the list of reviews.**</div>';
print '<table id="SchoolHomeReviewTable" cellspacing="0">';
$data = mysql_query('SELECT * FROM reviews WHERE schoolid = '.$school.' ORDER BY category, id DESC;', $connection);
while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
{
	
	if($category != $row['category'])
	{
		if(!$first)
		{
		print '</table></td></tr>';
		}
		else
		{
			$first = false;
		}
		$category = $row['category'];
		print '<tr><th colspan="100%" class="headerRow"><a href="javascript:onclick=openCategory(\''.ucfirst($row['category']).'\')" class="ReviewTableHeader"><p id="'.ucfirst($row['category']).'Plus">+'.ucfirst($row['category']).' Reviews</p></a></th></tr>';
		print '<tr><td colspan="100%"><table id="'.ucfirst($row['category']).'Reviews" cellspacing="0" border="0"  class="ReviewsDiv">';
		print '<tr><th>Category</th><th>Subject</th></tr>';
	}
		print '<tr><td class="cat">'.ucfirst($row['category']).'</td><td><a href="review.php?rid='.$row['id'].'">'.$row['subject'].'</a></td></tr>';
}
if(!$first)
{
	print '</table></td></tr>';
}
if(mysql_num_rows($data) == 0)
{
	print '<div class="alert"><center>No Reviews<br>Go to '.$schoolName.'? <a href="/myReviews.php">Be the first to review it</a></center></div>';
}
?>

</table>
</div>
</td>
<td class="schoolVendor">
<div id ="schoolHomeVendor">
<a name="vendors"></a>
<?php

print '<div class="pageTitle2">Vendors for '.$schoolName.'</div>';
print '<table cellspacing="0">';
print '<tr><th>Category</th><th>Vendor</th></tr>';
$data = mysql_query('SELECT id, category, schoolid, name FROM place WHERE schoolid = '.$school.' ORDER BY category;', $connection);
while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
{
	print '<tr><td class="cat">'.ucfirst($row['category']).'</td><td><a href="vendor.php?pid='.$row['id'].'">'.$row['name'].'</a></td></tr>';
}

?>

</table>
</div>
</div>
</div>
</body>
</html>