/**
*	functions is a javascript file created on september 31st 2007 at 6:30pm
*	this file is to extend the MBJS.js javascript file except this one will contain more extensive functions
*	This code is reuseable as long as the proper credit is given to David Brear
*/
function flashObject()
{
	var origColor;
	var timerId;
	var objID;
	var origRed;
	var origGreen;
	var origBlue;
	var counter = 20;
	var obj;
}
var objects = new Array();

function flash2(obj)
{
	objects[obj.id] = new flashObject();
	objects[obj.id].obj = obj;
	objects[obj.id].origColor = findStyleValue(obj.id, 'background-color', 'backgroundColor');
	var str = 'hello';
	objects[obj.id].timerId = setTimeout('setBack()', 2000);
}
function setBack(obj)
{
	document.getElementById(objects[obj.id].obj).style.backgroundColor = '#FFFF00';
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