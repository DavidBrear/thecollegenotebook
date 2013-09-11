var selection;

function initStars()
{
	if(document.getElementById('star1'))
	{
	for(var i = 1; i<=5; i++)
	{
		document.getElementById('star' + i).onmouseover = function() {overStar(this)};
		document.getElementById('star' + i).onmouseout = function() {outStar(this)};
		document.getElementById('star' + i).onclick = function() {clickStar(this)};
	}
	}
}

function setStar(num)
{
	selection = num;
	for (var i = 5; i >= 1; i--)
	{
		if (i > selection)
		{
			document.getElementById('star' + i).src = '../images/star.gif';
		}
		if (i <= selection)
		{
			document.getElementById('star' + i).src = '../images/starSelected.gif';
		}
	}
}

function overStar(elmt)
{
	var starNum = elmt.id.substring(4)*1;
	for (var i = 5; i > starNum; i--)
	{
		document.getElementById('star' + i).src = '../images/star.gif';
	}
	for (var i = starNum; i >= 1; i--)
	{
		document.getElementById('star' + i).src = '../images/starSelected.gif';
	}
}

function outStar(elmt)
{
	var starNum = elmt.id.substring(4)*1;
	for (var i = 5; i >= 1; i--)
	{
		if (i > selection)
		{
			document.getElementById('star' + i).src = '../images/star.gif';
		}
		if (i <= selection)
		{
			document.getElementById('star' + i).src = '../images/starSelected.gif';
		}
	}
}

function clickStar(elmt)
{
	
	document.editForm.rating.value = elmt.id.substring(4)*1;
	selection = elmt.id.substring(4)*1;
}