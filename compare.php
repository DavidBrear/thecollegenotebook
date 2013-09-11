<?php
	include_once('include/collegeconnection.php');
	include_once('include/header.php');
?>
<script type="text/javascript">
	(new Image()).src = '/images/exclamation.png';
</script>
<div id="mainContentPart">
	<div id="CompareCenterBox">
		<table id="compareTable">
		<tr>
			<td>
				<h3>School 1</h3>
				<div class="alert">Type a school's name, abreviation, state or city to search for it.
				<input type="text" class="CompareInput" name="LeftSchool" onkeyup="if(this.value.length > 1) {getData('/SchoolCompare.php', 'schoolName='+this.value + '&left=1', 'leftResults');}"></div>
			</td>
			<td>
				<h3>School 2</h3>
				<div class="alert">Type a school's name, abreviation, state or city to search for it.
				<input type="text" class="CompareInput" name="RightSchool" onkeyup="if(this.value.length > 1) {getData('/SchoolCompare.php', 'schoolName='+this.value+'&right=1', 'rightResults');}"></div>
			</td>
		</tr>
		<tr>
			<td>
			<div id="leftCol">
			
			<div id="leftResults" class="compareResults">
			<br><br><br><br><br><br>
			</div>
			</div>
			</td><td>
			<div id="rightCol">
			
			<div id="rightResults" class="compareResults">
			<br><br><br><br><br><br>
			</div>
			</div>
			</td>
		</tr>
		</table>
	</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
<script type="text/javascript" src="javascripts/aj.js"></script>