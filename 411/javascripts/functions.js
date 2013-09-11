/**
*	functions is a javascript file created on september 31st 2007 at 6:30pm
*	this file is to extend the MBJS.js javascript file except this one will contain more extensive functions
*	This code is reuseable as long as the proper credit is given to David Brear
*/
var origColor;
var timerId;
var objID;
var origRed;
var origGreen;
var origBlue;
var counter = 20;
function flash(obj)
{
	if(obj && (!obj.id))
	{
		obj.setAttribute('id', obj.name);
	}
	if(obj)
	{
	origColor = findStyleValue(obj.id, 'background-color', 'backgroundColor');
	if(navigator.appName.indexOf('icrosoft') > -1)
	{
		if(origColor.length == 4)
		{
			origRed = origColor.charAt(1)+''+origColor.charAt(1);
			origBlue = origColor.charAt(3)+''+origColor.charAt(3);
			origGreen = origColor.charAt(2)+''+origColor.charAt(2);
		}
		else
		{
			origRed = origColor.charAt(1)+''+ origColor.charAt(2);
			origBlue = origColor.charAt(5) + origColor.charAt(6);
			origGreen = origColor.charAt(4) + origColor.charAt(5);
		}
		origRed = parseInt('0x'+origRed);
		origGreen = parseInt('0x'+origGreen);
		origBlue = parseInt('0x'+origBlue);
	}
	else
	{
		origColors = origColor.substring(origColor.indexOf('(') + 1, origColor.length-1);
	origColors = origColors.split(',');
	origRed = origColors[0];
	origGreen = origColors[1];
	origBlue = origColors[2];
	}
	counter = 0;
	objID = obj.id;
	obj.style.backgroundColor = 'rgb(255,255,96)';
	timerId = setTimeout('returnColor(objID)', 600);
	}
}
function returnColor(obj)
{
	
	var red = 0;
	var green = 0;
	var blue = 0;
	var IEred = 0;
	var IEgreen = 0;
	var IEblue = 0;
	var colorLine;
	var redDiff = 0;
	var greenDiff = 0;
	var blueDiff = 0;
	if(document.getElementById(obj))
	{
	colorLine = document.getElementById(obj).style.backgroundColor.substring(document.getElementById(obj).style.backgroundColor.indexOf('(') + 1,
		document.getElementById(obj).style.backgroundColor.length -1);
		var colors = colorLine.split(',');
		redDiff = parseInt(colors[0]) - origRed;
		blueDiff = parseInt(colors[2]) - origBlue;
		greenDiff = parseInt(colors[1]) - origGreen;
		colors = colorLine.split(',');
	redDiff = redDiff/4;
	greenDiff = greenDiff/4;
	blueDiff = blueDiff/4;
	redDiff = -redDiff;
	greenDiff = -greenDiff;
	blueDiff = -blueDiff;
	red = parseInt(colors[0]) + redDiff;
	green = parseInt(colors[1]) + greenDiff;
	blue = parseInt(colors[2]) + blueDiff;
	red = Math.round(red);
	green = Math.round(green);
	blue = Math.round(blue);
	if(counter >= 20)
	{
		counter = 0;
		clearTimeout(timerId);
		document.getElementById(obj).style.backgroundColor = origColor;
	}
	else
	{
		document.getElementById(obj).style.backgroundColor = 'rgb('+red+','+green+','+ blue+ ')';
		timerId = setTimeout('returnColor(objID)', 200);
		counter = counter + 1;
	}
	}
	
}

//this function takes in an object id and returns the style value from that object.
function findStyleValue(obj, styleProp, IEStyleProp)
{
	var object = document.getElementById(obj);
	if (object.currentStyle)
	{
		return object.currentStyle[IEStyleProp];
	}
	else if(window.getComputedStyle)
	{
		compStyle = window.getComputedStyle(object, '');
		return compStyle.getPropertyValue(styleProp);
	}
}