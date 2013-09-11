		function checkWords(string)
		{
			var arr = new Array();
			arr = string.split(' ');
			for(var i = 0; i < arr.length; i++)
			{
				
				if(arr[i].length > 50)
				{
					var first = arr[i].substring(0, 50);
					var last = arr[i].substring(50);
					last = checkWords(last);
					arr[i] = first + ' ' + last;
				}
			}
			string = "";
			for(var i = 0; i < arr.length; i++)
			{
				string = string + ' ' + arr[i];
			}
			return string;
		}
		function runForm()
		{
			var string = document.Comments.comment.value;
			var newString = checkWords(string);
			document.Comments.comment.value = newString;
			return false;
		}
		
/*this function is for adding the little ammount of text to the comment when it's focused*/
function addCommentText()
{
	if (document.Comments.comment.value == 'Type here to comment on this board...')
	{
		document.Comments.comment.style.color = '#000';
		document.Comments.comment.value = '';
	}
}
function removeCommentText()
{
	if (document.Comments.comment.value == '')
	{
		
		if(document.Comments.comment.setSelectionRange)
		{
			document.Comments.comment.style.color = '#AAA';
			document.Comments.comment.value = 'Type here to comment on this board...';
			document.Comments.comment.setSelectionRange(0, 0);
		}
	}
}

//function for opening different parts on the edit page
function editOpen(name)
{
	if (name == 'AboutMe')
	{
		document.getElementById('editLeft').style.display = 'none';
		document.getElementById('editRight').style.display = 'none';
		document.getElementById('profileedit').style.display = 'block';
		document.getElementById('privacyEdit').style.display = 'none';
		document.getElementById('likes').style.display = 'none';
	}
	else if (name == 'Pass')
	{
		document.getElementById('editLeft').style.display = 'none';
		document.getElementById('editRight').style.display = 'block';
		document.getElementById('profileedit').style.display = 'none';
		document.getElementById('likes').style.display = 'none';
		document.getElementById('privacyEdit').style.display = 'none';
	}
	else if (name == 'likes')
	{
		document.getElementById('editLeft').style.display = 'none';
		document.getElementById('editRight').style.display = 'none';
		document.getElementById('profileedit').style.display = 'none';
		document.getElementById('privacyEdit').style.display = 'none';
		document.getElementById('likes').style.display = 'block';
	}
	else if (name == 'Priv')
	{
		document.getElementById('editLeft').style.display = 'none';
		document.getElementById('editRight').style.display = 'none';
		document.getElementById('profileedit').style.display = 'none';
		document.getElementById('privacyEdit').style.display = 'block';
		document.getElementById('likes').style.display = 'none';
	}
	else
	{
		document.getElementById('editLeft').style.display = 'block';
		document.getElementById('editRight').style.display = 'none';
		document.getElementById('profileedit').style.display = 'none';
		document.getElementById('privacyEdit').style.display = 'none';
		document.getElementById('likes').style.display = 'none';
	}
}

function openEdit(num)
{
	if(document.getElementById(''+num).style.display=='block')
	{
		document.getElementById(''+num).style.display='none';
		document.getElementById('row'+num).style.display='block';
	}
	else
	{
		if(document.getElementById(num+'Area').value.indexOf('Last edited by') > -1)
		{
			document.getElementById(num+'Area').value = document.getElementById(num+'Area').value.substring(0, document.getElementById(num+'Area').value.indexOf('Last edited by') - 5);
		}
		document.getElementById(''+num).style.display='block';
		if (document.getElementById(num+'Area').value == '')
		{
			document.getElementById(num+'Area').style.color = '#A0A0A0';
			document.getElementById(num+'Area').value = 'Enter information here (Remember that you are responsible for whatever you type)...';
			document.getElementById(num+'Area').onfocus = function ()
			{
				document.getElementById(num+'Area').style.color = '#000000';
				if (document.getElementById(num+'Area').value == 'Enter information here (Remember that you are responsible for whatever you type)...')
				{
					document.getElementById(num+'Area').value = '';
				}
			}
			
		}
		document.getElementById('row'+num).style.display='none';
		for(var i = 1; i < num; i++)
		{
			if(document.getElementById('row'+i))
			{
				document.getElementById('row'+i).style.display='block';
			}
			if(document.getElementById(''+i))
			{
				document.getElementById(''+i).style.display='none';
			}
		}
		for(var i = num + 1; i <= 29; i++)
		{
			if(document.getElementById('row'+i))
			{
				document.getElementById('row'+i).style.display='block';
			}
			if(document.getElementById(''+i))
			{
				document.getElementById(''+i).style.display='none';
			}
		}
	}
}

//function for submitting school information
function submitSchool(id, value, school, area, email)
{
	if (value == '' || value == 'Enter information here (Remember that you are responsible for whatever you type)...')
	{
		alert('you must enter information');
		openEdit(id);
		return false;
	}
	//document.getElementById(id).value = value + "\n" + '<p class="alert">Last edited by: '+ email + '</p>';
	if (navigator.appName.indexOf("Microsoft") == -1)
	{
		document.getElementById('row'+id).innerHTML = value + "\n\n" + '<br><p class="alert">Last edited by: '+ email + '</p>';
	}
	else
	{
		document.getElementById('row'+id).innerHTML = value + "\n\n" + '<br><p class="alert">Last edited by: '+ email + '</p>';
	}
	openEdit(id);
	var data = 'area='+area+'&data='+value+'&SID='+school;
	getData('http://thecollegenotebook.com/saveSchool.php', data, area);
}

//onlyNumbers restricts the entries for an input box to only numbers
function onlynumbers(obj, limit)
{
	while(obj.value.search(/[^0-9|\.]/) != -1)
	{
		obj.value = obj.value.replace(/[^0-9|\.]/, '');
	}
	if(obj.value.indexOf('.') > -1)
	{
		var front = obj.value.substring(0, obj.value.indexOf('.'));
		var back = obj.value.substring(obj.value.indexOf('.')+1);
		back = back.replace(/(\.)+/, '');
		obj.value = front + '.' + back;
	}
	if(obj.value.length > limit)
	{
		obj.value = obj.value.substring(0, limit);
	}
	
}

function makeMouseVis(num)
{
	if(num == 0)
	{
		document.getElementById('IDbox').style.display = 'none';
	}
	else if(num == 1)
	{
		document.getElementById('IDbox').style.display = 'block';
	}
}


/*FUNCTIONS FOR SCHOOL INDEX GUIDE BOX*/
var guidePos;
var overbutton;
var mousePos;
var mousePosLeft;
var IEmousePos;
if(!(document.getElementById('whatAbout')))
{
document.onmousemove = function (evt)
{
	evt = evt || window.event;
	mousePos = evt.clientY;
	mousePosLeft = evt.screenX;
	IEmousePos = evt.screenY;
	if(document.getElementById('IDbox') != null)
	{
	if(document.body.scrollTop)
	{
		if(document.getElementById('IDbox') != undefined)
		{
			document.getElementById('IDbox').style.top = evt.clientY + 10 + document.body.scrollTop;
			if(document.getElementById('IDbox'))
			{
				document.getElementById('IDbox').style.left = evt.clientX + 10;//ie
			}
		}
	}
	else
	{
		if(document.getElementById('IDbox') != undefined)
		{
		document.getElementById('IDbox').style.top = evt.clientY + 10;
		document.getElementById('IDbox').style.left = evt.clientX + 10;
		}
	}
	}
}
}
function MessSelectAll(num)
{
	
	for(var i=0; i<num; i++)
	{
		if(document.getElementById('selectAll').checked)
		{
			document.getElementById(i+'box').checked='on';
		}
		else
		{
			document.getElementById(i+'box').checked='';
		}
	}
}
/**
function openQuote opens the quote box on the profiles page for editing
*/
function openQuote(val)
{
	if(val == 1)
	{
		document.getElementById('quoteDiv').style.display = 'block';
		document.getElementById('editQuote').style.display = 'none';
	}
	else
	{
		document.getElementById('quoteDiv').style.display = 'none';
		document.getElementById('editQuote').style.display = 'block';
	}
}

