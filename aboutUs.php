<?php
	include_once('include/header.php');
?>
<style type="text/css">
	#aboutUsPage
	{
		text-align: -moz-left;
		text-align: left;
	}
	.benefits
	{
		width: 50%;
		margin: 0px auto;
		border: 2px #525252 solid;
		background-color: #F0F0F0;
		color: #222;
	}
	.benefits li
	{
		list-style: square;
		margin-bottom: 2px;
	}
	fieldset
	{
		border: 1px #CCC solid;
		background-color: #F0F5F0;
	}
	fieldset legend
	{
		font-size: 12px;
		margin-left: 50px;
	}
	.personal table
	{
		margin: 0px auto;
	}
	.personal img
	{
		border: 2px #000 solid;
	}
	.personal div
	{
		border: 1px #525252 solid;
		overflow: hidden;
		background-color: #FFF;
		padding: 1px;
	}
	.personal td
	{
		vertical-align: bottom;
		text-align: -moz-center;
		text-align: center;
	}
	.personal .type
	{
		vertical-align: middle !important;
		text-align: -moz-left;
		text-align: left;
	}
</style>

<div id="aboutUsPage">
	The College Notebook was created in mid 2007 by <a href="/drumma5">David Brear</a> in an effort to help people find information about college. While the target audience of this webpage
	is college students (highschoolers can sign up for <a href="http://411onCollege.com">411onCollege</a>) anyone is able to view reviews and information on schools.
	<div class="benefits">
		The benefits of signing up for TheCollegeNotebook are:
		<ul>
			<li>Joining a social network to make and keep in touch with friends through comment boards and messages.</li>
			<li>Write reviews about your school</li>
			<li>Get special deals offered only to students by vendors around your school</li>
			<li>Get access to <a href="/usell">U-Sell</a>, a place to buy and sell your books and other college items</li>
			<li>Access to edit information about your specific college</li>
		</ul>
	</div>
	<div class="personal">
		<fieldset>
		<legend>The College Notebook Team</legend>
		<table>
		<tr>
		<?php
			$data = mysql_query('SELECT thumb_url FROM users WHERE id = 1;', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$imagesize = getimagesize($row['thumb_url']);
			print '<tr><td><a href="/profile?id=1"><div style="height:'.($imagesize[1]+ 7).'; width:'.($imagesize[0] + 7).';"><img style="height:'.$imagesize[1].'; width:'.$imagesize[0].';" onmouseover="grow(this)" onmouseout="shrink(this)" src="'.$row['thumb_url'].'"></img></div>David Brear</a></td><td class="type">(Site owner, developer, designer)</td></tr>';
			$data = mysql_query('SELECT thumb_url FROM users WHERE id = 93;', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$imagesize = getimagesize($row['thumb_url']);
			print '<tr><td><a href="/profile?id=93"><div style="height:'.($imagesize[1]+ 7).'; width:'.($imagesize[0] + 7).';"><img style="height:'.$imagesize[1].'; width:'.$imagesize[0].';" onmouseover="grow(this)" onmouseout="shrink(this)" src="'.$row['thumb_url'].'"></img></div>Phil Garber</a></td><td class="type">(Site designer)</td></tr>';
			$data = mysql_query('SELECT thumb_url FROM users WHERE id = 58;', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$imagesize = getimagesize($row['thumb_url']);
			print '<tr><td><a href="/profile?id=58"><div style="height:'.($imagesize[1]+ 7).'; width:'.($imagesize[0] + 7).';"><img style="height:'.$imagesize[1].'; width:'.$imagesize[0].';" onmouseover="grow(this)" onmouseout="shrink(this)" src="'.$row['thumb_url'].'"></img></div>Brian Griffin</a></td><td class="type">(Site developer)</td></tr>';
			$data = mysql_query('SELECT thumb_url FROM users WHERE id = 44;', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$imagesize = getimagesize($row['thumb_url']);
			print '<tr><td><a href="/profile?id=44"><div style="height:'.($imagesize[1]+ 7).'; width:'.($imagesize[0] + 7).';"><img style="height:'.$imagesize[1].'; width:'.$imagesize[0].';" onmouseover="grow(this)" onmouseout="shrink(this)" src="'.$row['thumb_url'].'"></img></div>Bryan Davis<a></td><td class="type">(Site designer)</td></tr>';
			$data = mysql_query('SELECT thumb_url FROM users WHERE id = 23;', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$imagesize = getimagesize($row['thumb_url']);
			print '<tr><td><a href="/profile?id=23"><div style="height:'.($imagesize[1]+ 7).'; width:'.($imagesize[0] + 7).';"><img style="height:'.$imagesize[1].'; width:'.$imagesize[0].';" onmouseover="grow(this)" onmouseout="shrink(this)" src="'.$row['thumb_url'].'"></img></div>Emily Steinbacher<a></td><td class="type">(Site designer, Data collection)</td></tr>';
		?>
		</tr></table>
		</fieldset>
	</div>
	<fieldset>
		<legend>Other Team Productions</legend>
		<a href="http://411onCollege.com">411 on College</a><br>
		<a href="http://www.ToTheMusic.com">To The Music (Coming Soon)</a>
	</fieldset>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
<script type="text/javascript">
	function grow(elmt)
	{
		elmt.style.height = parseInt(elmt.style.height) + 5 + 'px';
		elmt.style.width = parseInt(elmt.style.width) + 5 + 'px';
	}
	function shrink(elmt)
	{
		elmt.style.height = parseInt(elmt.style.height) - 5 + 'px';
		elmt.style.width = parseInt(elmt.style.width) - 5 + 'px';
	}
</script>