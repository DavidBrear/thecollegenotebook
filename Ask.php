<?php

	include_once('include/collegeconnection.php');
	include_once('include/userAuthentication.php');

	if(isset($_POST['submitted']))
	{
		if(isset($_COOKIE['userid']) && authUser($_COOKIE['userid'], $connection))
		{
			if($_POST['question'] != 'Type your question here...' && $_POST['question'] != '')
			{
				$numberQuestions = mysql_query('SELECT numQuest FROM users WHERE id='.$_COOKIE['userid'].';', $connection);
				$numQuest = mysql_fetch_array($numberQuestions, MYSQL_ASSOC);
				if($numQuest['numQuest'] > 0)
				{
					$question = strip_tags($_POST['question']);
					$subject = strip_tags($_POST['subject']);
					mysql_query('INSERT INTO ask(siteID, userid, schoolid, question, date_posted, subject) VALUES(0, '.$_COOKIE['userid'].', 1, "'.$question.'", "'.$_POST['commentDate'].'" , "'.$subject.'");', $connection) or print('error:'.mysql_error());
					mysql_query('UPDATE users SET numQuest = numQuest - 1 WHERE id = '.$_COOKIE['userid'].';', $connection);
				}
				else
				{
					$error = '<div class="error">You are out of question points. Answer a question correctly to get more points.</div>';
				}
			}
			else
			{
				$error = '<div class="error">You must specify a question</div>';
			}
		}
		else
		{
			$error = '<div class="error">You must be logged in to ask a question.</div>';
		}
	}
	else if(isset($_POST['searchSubmitted']))
	{
		if($_POST['schoolid'] != '' && $_POST['subject'] != '')
		{
			$dataSearch = mysql_query('SELECT id, schoolid, subject FROM ask WHERE schoolid = '.$_POST['schoolid'].';', $connection);
			if(isset($_POST['subject']))
			{
				$subConstant = $_POST['subject'];
				$subject = strtoupper($subConstant);
			}
			if(strcmp($subConstant,' '))
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
				$data = mysql_query('SELECT * FROM ask WHERE subject = \''.$subConstant.'\';', $connection);
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				$found = true;
				if(strcmp(strtoupper($row['subject']), strtoupper($subConstant))) //if there is no data matching names exactly...
				{
					$found = false;
					$ids = array(); //initialize a new array to hold the school id numbers
					$common = array();
					$gotOne = false;
					while($row = mysql_fetch_array($dataSearch, MYSQL_ASSOC)) //while there is still data to be served
					{
						$occur = 0;
						foreach($finds as $find) //for each part of the school name entered...
						{
							//if the section of the entered data is in the name or the description..
							if(!strcmp($find, $row['subject']) ||
							(strpos(strtoupper($row['subject']), $find) > -1))
							{//|| (strpos(strtoupper($row['description']), $find) > -1)
								$gotOne = true;
								array_push($ids, $row['id']); //add this school's id.
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
			else
			{
				$subConstant = "You didn't enter anything...";
			}
			
		}
		else if($_POST['subject'] != '')
		{
			$dataSearch = mysql_query('SELECT id, schoolid, subject FROM ask;', $connection);
			if(isset($_POST['subject']))
			{
				$subConstant = $_POST['subject'];
				$subject = strtoupper($subConstant);
			}
			if(strcmp($subConstant,' '))
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
				$data = mysql_query('SELECT * FROM ask WHERE subject = \''.$subConstant.'\';', $connection);
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				$found = true;
				if(strcmp(strtoupper($row['subject']), strtoupper($subConstant))) //if there is no data matching names exactly...
				{
					$found = false;
					$ids = array(); //initialize a new array to hold the school id numbers
					$common = array();
					$gotOne = false;
					while($row = mysql_fetch_array($dataSearch, MYSQL_ASSOC)) //while there is still data to be served
					{
						$occur = 0;
						foreach($finds as $find) //for each part of the school name entered...
						{
							//if the section of the entered data is in the name or the description..
							if(!strcmp($find, $row['subject']) ||
							(strpos(strtoupper($row['subject']), $find) > -1))
							{//|| (strpos(strtoupper($row['description']), $find) > -1)
								$gotOne = true;
								array_push($ids, $row['id']); //add this school's id.
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
			else
			{
				$subConstant = "You didn't enter anything...";
			}
		}
		else if($_POST['schoolid'] != '')
		{
			$result = mysql_query('SELECT * FROM ask WHERE schoolid='.$_POST['schoolid'].' ORDER BY id DESC;', $connection);
			if(mysql_num_rows($result) < 1)
			{
				$gotOne = false;
			}
		}
	}
	else if(isset($_POST['responseID']))
	{
		if(isset($_COOKIE['userid']) && authUser($_COOKIE['userid'], $connection))
		{
			$data = mysql_query('SELECT userid FROM ask WHERE id = '.$_POST['responseID'].' AND userid != '.$_COOKIE['userid'].';', $connection);
			if(mysql_num_rows($data) > 0)
			{
				$answer = strip_tags($_POST['answer']);
				mysql_query('INSERT INTO Responses(userid, questionID, date_posted, answer) VALUES('.$_COOKIE['userid'].', '.$_POST['responseID'].', "'.$_POST['responseDate'].'" , "'.$answer.'");', $connection) or print('error:'.mysql_error());
			}
			else
			{
				$error = '<div class="error">You cannot answer your own questions.</div>';
			}
		}
	}
	$data = mysql_query('SELECT * FROM ask ORDER BY id DESC LIMIT 10', $connection);
	if(isset($_COOKIE['userid']))
	{
		$schoolData = mysql_query('SELECT school, schoolid FROM users WHERE id='.$_COOKIE['userid'].';', $connection);
		$schoolInfo = mysql_fetch_array($schoolData, MYSQL_ASSOC);
	}
	include_once('include/header.php');

?>
<script type="text/javascript">
	parent.document.title = "TheCollegeNotebook - Ask.";
	var questionID;
	function openAnswer(num)
	{
		if(document.getElementById(num + 'textArea').style.display == 'block')
		{
			document.getElementById(num + 'textArea').style.display = 'none';
		}
		else
		{
			document.getElementById(num + 'textArea').style.display = 'block';
			
		}
		
	}
	function openResponses(num)
	{
		if(document.getElementById(num + 'ResponseArea').style.display == 'block')
		{
			document.getElementById(num + 'ResponseArea').style.display = 'none';
		}
		else
		{
			document.getElementById(num + 'ResponseArea').style.display = 'block';
			
		}
		
	}
	function openAbout(val)
	{
		switch(val)
		{
			case 1:
			{
				document.getElementById('whatAbout').style.display = 'block';
			}break;
			case 2:
			{
				document.getElementById('whatAbout').style.display = 'none';
				document.getElementById('whatAbout').style.left = window.screen.width/2.5;
				document.getElementById('whatAbout').style.top = '300px';
			}break;
		}
	}
	function openConfirm(id, val, quest)
	{
		questionID = quest;
		responseID = id;
		mark = val;
		var Top;
				if(document.body.offsetHeight)
				{
					Top = document.body.offsetHeight;
				}
				else
				{
					Top = window.innerHeight;
				}
		document.getElementById('confirmation').style.top = (parseInt(document.body.scrollTop) + (Top/3)) + 'px';
		switch(val)
		{
			case -1:
			{
				document.getElementById('confirmation').style.display = 'none';
				document.getElementById('confirmation').style.left = window.screen.width/2.5;
				
			}break;
			case 1:
			{
				document.getElementById('confirmID').innerHTML = 'Are you sure you want to mark this response as correct?';
				document.getElementById('confirmation').style.display = 'block';
			}break;
			case 0:
			{
				document.getElementById('confirmID').innerHTML = 'Are you sure you want to mark this response as wrong?';
				document.getElementById('confirmation').style.display = 'block';
			}break;
		}
	}

</script>
<link rel="Stylesheet" type="text/css" href="/include/askStyle.css">
<div id="mainContentPart">
	<div class="schoolIndexCenterBox">
	<a href="javascript: openAbout(1);">What is Ask About?<img src="/images/questionMark.gif"></img>
	</a>
	<?php
		if(isset($error))
		{
			print $error;
		}
	?>
		<table id="askForm" cellpadding="0" style="margin: 2px"><tr><td><img src="images/askTop.gif"></img>
		<form  name="askForm" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
			
			Subject:<input type="text" name="subject"><br>
			<input type="hidden" id="commentDate" name="commentDate" value="100">
			<textarea cols="50" rows="5" name="question"
			onfocus="if(this.value == 'Type your question here...'){this.value = ''; this.style.color='#000';}" 
			onkeyup="if(this.value==''){if(this.setSelectionRange){
			this.style.color='#777';
			this.value = 'Type your question here...'; 
			this.setSelectionRange(0, 0);
			}
			else
			{
				var range = this.createTextRange(0,0);
				range.collapse(true);
				range.moveStart('character', 0);
				range.moveEnd('character', 0);
				
			}
			}"
			onkeypress="if(this.value == 'Type your question here...'){this.value = ''; this.style.color='#000';}"
			onblur="if(this.value == ''){this.value = 'Type your question here...'; this.style.color='#777';}">Type your question here...</textarea><br>
			
				<?php 
					if($schoolInfo['schoolid'] != 0)
					{
						print 'Asking questions of: <select><option value="'.$schoolInfo['schoolid'].'">'.$schoolInfo['school'].'</option></select>'; 
					}
					else if(isset($_COOKIE['userid']))
					{
						print '<div class="error">Your school is not added yet.<br> You will be able to ask a question when your school is added.</div>';
					}
					else
					{
						print '<div class="error">Log in to ask a question.</div>';
					}
				?>
				<br>
			<input type="submit" value="Ask" id="askSubmit">
			<input type="hidden" value="1" name="submitted">
		</form>
		</td>
		<td>
		<img src="images/askSearchTop.gif"></img>
		<table class="SearchTable">
		<form  name="askSearch" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
			<tr><td align="right">Subject:</td><td><input type="text" name="subject"></td></tr>
			<tr><td align="right">School:</td><td><select name="schoolid"><option value="" align="center">*** Select School ***</option>
			<?php
				$schoolsData = mysql_query('SELECT id, name, state, abrev FROM schools ORDER BY state, abrev;', $connection);
				$state = '';
				$first = true;
				while($schoolRow = mysql_fetch_array($schoolsData, MYSQL_ASSOC))
				{
					if($state != $schoolRow['state'])
					{
						if($first)
						{
							$first = false;
						}
						else
						{
							print '</optgroup>';
						}
						$state = $schoolRow['state'];
						print '<optgroup label="'.$state.'">';
					}
					print '<option value="'.$schoolRow['id'].'">'.$schoolRow['abrev'].' - '.$schoolRow['name'].'</option>';
				}
			?>
			</select></td></tr>
			<tr><td colspan="100%" align="center"><input class="button" type="image" name="submit" src="images/searchButton.gif"></td></tr>
			<input type="hidden" value="1" name="searchSubmitted">
		</form>
		</table>
		</td>
		</tr>
		</table>
		<table id="askTable" border="0" cellspacing="2" cellpadding="0" style="text-align: -moz-left !important; text-align: left !important;">
		<?php
		if(isset($gotOne))
		{
			if($gotOne)
			{
				$string = 'SELECT * FROM ask WHERE id = 0';
				foreach($ids as $id)
				{
					$string = $string.' OR id = '.$id;
				}
				$string = $string.' ORDER BY id DESC;';
				$result = mysql_query($string, $connection);
				$counter = 1;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$questionData = mysql_query('SELECT count(id) as numResponses FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				$question = mysql_fetch_array($questionData, MYSQL_ASSOC);
				print '<tr><td><div class="questionDate">{'.date('l, F jS Y', strtotime($row['date_posted'])).'}';
				print '</div><div class="questionSubject">'.$row['subject'].'-</div><div class="questionBody">&nbsp;&nbsp;&nbsp;&nbsp;'.$row['question'].'</div>';
				print '<a href="javascript: openResponses('.$counter.');">Responses('.$question['numResponses'].')</a>';
				print '<a style="margin-left: 10px;" href="javascript: openAnswer('.$counter.');">Respond</a>';
				print '<div class="askTextarea" id="'.$counter.'textArea">
				<form action="'.$_SERVER['PHP_SELF'].'" method="POST" onsubmit="setAskDate('.$row['id'].');"><textarea name="answer" onfocus="this.value=\'\';">Click here to answer...</textarea>
				<input type="hidden" name="responseID" value="'.$row['id'].'">
				<input type="hidden" name="responseDate" id="response'.$row['id'].'" value="100"><input type="submit" value="Answer" onclick="setAskDate('.$row['id'].');"></form>
				<a href="javascript: openAnswer('.$counter.')">Cancel</a></div>';
				print '<div class="askResponseArea" id="'.$counter.'ResponseArea">';
				print '<table>';
				print '<tr><th>Responses</th></tr>';
				$responseData = mysql_query('SELECT * FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				while($responses = mysql_fetch_array($responseData, MYSQL_ASSOC))
				{
					$userData = mysql_query('SELECT name, thumb_url FROM users WHERE id='.$responses['userid'].';', $connection);
					$userRow = mysql_fetch_array($userData, MYSQL_ASSOC);
					print '<tr><td';
					//switch whether this answer is correct or not. 0 means no answer, 1 is incorrect and 2 is correct.
					switch($responses['correct'])
					{
						case 0:
						{
							//no answer
						}break;
						case 1:
						{
							//incorrect
								print ' style="background: #cc0055 url(\'/images/wrongAnswer.gif\') no-repeat 200px 20px" ';
								$answered = true;
						}break;
						case 2:
						{
							//correct
							print ' style="background: #00cc55 url(\'/images/rightAnswer.gif\') no-repeat 200px 20px" ';
							$answered = true;
						}break;
					}
					print ' id="'.$responses['id'].'Response"><div class="questionDate">{'.date('l, F jS Y', strtotime($responses['date_posted'])).'}</div>';
					print '<div class="responseUser"><img src="'.$userRow['thumb_url'].'"></img><br><p>'.$userRow['name'].'</p></div>';
					print '<div class="responseText">'.$responses['answer'].'</div>';
					if($_COOKIE['userid'] == $row['userid'] && $row['answered']!= 1)
					{
						print '<div id="'.$row['id'].'Answer"><a class="responseRate" href="javascript: openConfirm('.$responses['id'].', 1, '.$row['id'].');"><img src="/images/right.png"></img></a><a class="responseRate" href="javascript: openConfirm('.$responses['id'].', 0, '.$row['id'].');"><img src="/images/wrong.png"></img></a></div></td></tr>';
					}
					else
					{
						print '</td></tr>';
					}
				}
				if(mysql_num_rows($responseData) == 0)
				{
					print '<tr><td><div class="responseText">No Responses yet...</div></td></tr>';
				}
				print '</table>';
				print '</div>';
				print '</td></tr>';
				$counter++;
			}
				
			}
			else
			{
				print '<div class="error">No Matches Found</div>';
			}
		}
		else
		{
			$counter = 1;
			while($row = mysql_fetch_array($data, MYSQL_ASSOC))
			{
				$questionData = mysql_query('SELECT count(id) as numResponses FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				$question = mysql_fetch_array($questionData, MYSQL_ASSOC);
				print '<tr><td><div class="questionDate">{'.date('l, F jS Y', strtotime($row['date_posted'])).'}';
				print '</div><div class="questionSubject">'.$row['subject'].'-</div><div class="questionBody">&nbsp;&nbsp;&nbsp;&nbsp;'.$row['question'].'</div>';
				print '<a href="javascript: openResponses('.$counter.');">Responses('.$question['numResponses'].')</a>';
				print '<a style="margin-left: 10px;" href="javascript: openAnswer('.$counter.');">Respond</a>';
				print '<div class="askTextarea" id="'.$counter.'textArea">
				<form action="'.$_SERVER['PHP_SELF'].'" method="POST" onsubmit="setAskDate('.$row['id'].');"><textarea name="answer" onfocus="this.value=\'\';">Click here to answer...</textarea>
				<input type="hidden" name="responseID" value="'.$row['id'].'">
				<input type="hidden" name="responseDate" id="response'.$row['id'].'" value="100"><input type="submit" value="Answer" onclick="setAskDate('.$row['id'].');"></form>
				<a href="javascript: openAnswer('.$counter.')">Cancel</a></div>';
				print '<div class="askResponseArea" id="'.$counter.'ResponseArea">';
				print '<table>';
				print '<tr><th>Responses</th></tr>';
				$responseData = mysql_query('SELECT * FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				while($responses = mysql_fetch_array($responseData, MYSQL_ASSOC))
				{
					$userData = mysql_query('SELECT name, thumb_url FROM users WHERE id='.$responses['userid'].';', $connection);
					$userRow = mysql_fetch_array($userData, MYSQL_ASSOC);
					print '<tr><td';
					//switch whether this answer is correct or not. 0 means no answer, 1 is incorrect and 2 is correct.
					switch($responses['correct'])
					{
						case 0:
						{
							//no answer
						}break;
						case 1:
						{
							//incorrect
								print ' style="background: #cc0055 url(\'/images/wrongAnswer.gif\') no-repeat 200px 20px" ';
								$answered = true;
						}break;
						case 2:
						{
							//correct
							print ' style="background: #00cc55 url(\'/images/rightAnswer.gif\') no-repeat 200px 20px" ';
							$answered = true;
						}break;
					}
					print ' id="'.$responses['id'].'Response"><div class="questionDate">{'.date('l, F jS Y', strtotime($responses['date_posted'])).'}</div>';
					print '<div class="responseUser"><img src="'.$userRow['thumb_url'].'"></img><br><p>'.$userRow['name'].'</p></div>';
					print '<div class="responseText">'.$responses['answer'].'</div>';
					if($_COOKIE['userid'] == $row['userid'] && $row['answered']!= 1)
					{
						print '<div id="'.$row['id'].'Answer"><a class="responseRate" href="javascript: openConfirm('.$responses['id'].', 1, '.$row['id'].');"><img src="/images/right.png"></img></a><a class="responseRate" href="javascript: openConfirm('.$responses['id'].', 0, '.$row['id'].');"><img src="/images/wrong.png"></img></a></div></td></tr>';
					}
					else
					{
						print '</td></tr>';
					}
				}
				if(mysql_num_rows($responseData) == 0)
				{
					print '<tr><td><div class="responseText">No Responses yet...</div></td></tr>';
				}
				print '</table>';
				print '</div>';
				print '</td></tr>';
				$counter++;
			}
		}
		?>
		</table>
	</div>
</div>
</div>
</div>
<div id="whatAbout">
	<div>
	<div id="close">
		<div></div>
		<a href="javascript:openAbout(2);">X</a>
	</div>
	<div id="AboutMain"><img src="/images/questionMark.gif"></img>
	&nbsp;&nbsp;&nbsp;&nbsp;Ask is a part of The College Notebook linked with our sister site (<a style="font-weight: bold;" href="http://411onCollege.com">411onCollege.com</a>). To Ask a question, log in. Each user of The College Notebook gets <u>10</u> questions to ask and <u>15</u> responses.
	When you answer a question correctly, and the asker of a question marks your response as correct you will gain another question to ask and another 5 respones.
	</div>
	</div>
</div>
<div id="confirmation">
	<div id="close">
		<div>Confirmation</div>
		<a href="javascript:openConfirm(-1, -1);">X</a>
	</div>
	<div id="confirmID">Are you sure you want to mark this response as incorrect?</div>
	<a style="margin-left: 100px;" class="confirmA" href="javascript: markResponse(responseID, mark); openConfirm(-1,-1);">YES</a>
	<a style="margin-left: 10px;" class="confirmA" href="javascript: openConfirm(-1, -1)">NO</a>
</div>
<script type="text/javascript" src="/javascripts/AskJS.js"></script>
<script type="text/javascript" src="/javascripts/runafter.js"></script>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>