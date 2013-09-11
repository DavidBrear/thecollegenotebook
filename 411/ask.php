<?php

	include_once('include/411Connection.php');
	include_once('include/collegeconnection.php');
	include_once('include/userAuthentication.php');

	if(isset($_POST['submitted']))
	{
		if(isset($_COOKIE['user']) && authUser($_COOKIE['user'], $connection411))
		{
			if($_POST['question'] != 'Type your question here...' && $_POST['question'] != '')
			{
				$numberQuestions = mysql_query('SELECT numQuest FROM users WHERE id='.$_COOKIE['user'].';', $connection411);
				$numQuest = mysql_fetch_array($numberQuestions, MYSQL_ASSOC);
				if($numQuest['numQuest'] > 0)
				{
					$question = strip_tags($_POST['question']);
					$subject = strip_tags($_POST['subject']);
					mysql_query('INSERT INTO ask(siteID, userid, schoolid, question, date_posted, subject) VALUES(1, '.$_COOKIE['user'].', 1, "'.$question.'", "'.$_POST['commentDate'].'" , "'.$subject.'");', $connection) or print('error:'.mysql_error());
					mysql_query('UPDATE users SET numQuest = numQuest - 1 WHERE id = '.$_COOKIE['user'].';', $connection);
				}
			}
			else
			{
				$question = '<div class="error">You must specify a question</div>';
			}
		}
		else
		{
			$question = '<div class="error">You must be logged in to ask a question.</div>';
		}
	}
	$data = mysql_query('SELECT * FROM ask ORDER BY id DESC LIMIT 10', $connection);
	include_once('include/header.php');

?>
<script type="text/javascript">
	parent.document.title = "411onCollege - Ask.";
	(new Image()).src = '/images/askButtonDown.gif';
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
</script>

<link rel="Stylesheet" type="text/css" href="/include/AskStyle.css">
</div>
<table id="mainBodyTable"><tr><td>
	<div class="alert" style="height: 40px; padding-top: 5px; font-size: 14px;">Ask is not working as of yet. <a href="/admin" style="font-size: 14px">Tell us</a> if you have any suggestions for it though.</div>
	<?php
		if(isset($question))
		{
			print $question;
		}
	?>
		<table id="askForm" cellpadding="0" style="margin: 2px"><tr><td>Ask
		<form  name="askForm" onsubmit="setDate()" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
			Subject:<input type="text" name="subject">
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
			onblur="if(this.value == ''){this.value = 'Type your question here...'; this.style.color='#777';}">Type your question here...</textarea>
			<select>
				<option value="1">Christopher Newport University</option>
			</select>
			<br>
			<input class="button" style="border: 2px #000 solid; margin-top: 5px; width:180px; background-color: #004a91; color: #FFF; cursor: pointer;" onclick="setDate()" type="submit" name="submit" value="Ask">
			<input type="hidden" value="1" name="submitted">
		</form>
		</td>
		<td>
		Search Questions
		<form  name="askSearch" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
			Subject:<input type="text" name="subject">
			<input class="button" style="border: 2px #000 solid; background-color: #004a91; color: #FFF; cursor: pointer;" type="submit" name="submit" value="Search">	
			<input type="hidden" value="1" name="submitted">
		</form>
		</td>
		</tr>
		</table>
		<table id="askTable" border="0" cellspacing="2" cellpadding="0" style="text-align: -moz-left !important; text-align: left !important;">
		<?php
			$counter = 1;
			while($row = mysql_fetch_array($data, MYSQL_ASSOC))
			{
				$questionData = mysql_query('SELECT count(id) as numResponses FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				$question = mysql_fetch_array($questionData, MYSQL_ASSOC);
				print '<tr><td><div class="questionDate">{'.date('l, F jS Y', strtotime($row['date_posted'])).'}';
				print '</div><div class="questionSubject">'.$row['subject'].'-</div><div class="questionBody">&nbsp;&nbsp;&nbsp;&nbsp;'.$row['question'].'</div>';
				print '<a href="javascript: openResponses('.$counter.');">Responses('.$question['numResponses'].')</a>';
				print '<div class="askResponseArea" id="'.$counter.'ResponseArea">';
				print '<table>';
				print '<tr><th>Responses</th></tr>';
				$responseData = mysql_query('SELECT * FROM Responses WHERE questionID = '.$row['id'].';', $connection);
				while($responses = mysql_fetch_array($responseData, MYSQL_ASSOC))
				{
					print '<tr><td><div class="questionDate">{'.date('l, F jS Y', strtotime($responses['date_posted'])).'}</div><div class="responseText">'.$responses['answer'].'</div></td></tr>';
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
		?>
		</table>
	</div>
</div>
<script type="text/javascript" src="/javascripts/runafter.js"></script>