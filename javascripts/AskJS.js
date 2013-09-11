var mouseOver = false;
var xPos;
var yPos;


function setAbout()
{
	document.getElementById('whatAbout').style.left = window.screen.width/2.5;
	document.getElementById('whatAbout').style.top = '300px';
		
}
setAbout();

document.getElementById('whatAbout').onmousedown = function(evt)
{
	if(!mouseOver)
	{
		mouseOver = true;
		if(navigator.appName.indexOf('icrosoft') == -1)
		{
			
			xPos = parseInt(evt.clientX);
			yPos = parseInt(evt.clientY);
		}
		else
		{	
			xPos = parseInt(window.event.x);
			yPos = parseInt(window.event.y);
		}
	}
}

document.onmousemove = function(evt)
{
	var XCoord;
	var YCoord;
	if (mouseOver)
	{
		if(navigator.appName.indexOf('icrosoft') == -1)
		{
			XCoord = parseInt(document.getElementById('whatAbout').style.left);
			YCoord = parseInt(document.getElementById('whatAbout').style.top);
			document.getElementById('whatAbout').style.left = XCoord + (evt.clientX - xPos);
			document.getElementById('whatAbout').style.top = YCoord + (evt.clientY - yPos);
			xPos = parseInt(evt.clientX);
			yPos = parseInt(evt.clientY);
		}
		else
		{
			
			XCoord = parseInt(document.getElementById('whatAbout').style.left);
			YCoord = parseInt(document.getElementById('whatAbout').style.top);
			document.getElementById('whatAbout').style.left = XCoord + (window.event.x - xPos);
			document.getElementById('whatAbout').style.top = YCoord + (window.event.y - yPos);
			xPos = parseInt(window.event.x);
			yPos = parseInt(window.event.y);
		}
	}
}
document.onmouseup = function(evt)
{
	mouseOver = false;
}

function markResponse(id, val)
{
	
	switch(val)
	{
		
		case 0:
		{
			document.getElementById(id+'Response').style.backgroundImage = 'none';
			document.getElementById(id+'Response').style.backgroundColor = '#cc0055';
			getData('/setResponse.php', 'id='+id+'&correct=1', null);
			
			
		}break;
		case 1:
		{
			document.getElementById(id+'Response').style.backgroundImage = 'none';
			document.getElementById(id+'Response').style.backgroundColor = '#00cc55';
			getData('/setResponse.php', 'id='+id+'&correct=2', '5Answer');
			var d = document.getElementById(questionID+'Answer');
			d.parentNode.removeChild(document.getElementById(questionID+'Answer'));
		}break;
	}
}