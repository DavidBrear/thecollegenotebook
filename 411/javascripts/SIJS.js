//School Index Javascript
//written by David Brear on Sept 13th 2007
//the main purpose of this script is to allow the expanding and contracting of the school indexes
function openState(name)
{
	if(document.getElementById(name+'Schools').style.display == 'none')
	{
		document.getElementById(name+'Schools').style.display = 'block';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '-' + name + ' Schools';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openState(\'' + name + '\')"><p id="'+ name + 'Plus">-' + name + ' Schools</p></a>';
		}
	}
	else if(document.getElementById(name+'Schools').style.display == 'block')
	{
		document.getElementById(name+'Schools').style.display = 'none';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '+' + name + ' Schools';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openState(\'' + name + '\')"><p id="'+ name + 'Plus">+' + name + ' Schools</p></a>';
		}
	}
	else
	{
		document.getElementById(name+'Schools').style.display = 'block';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '-' + name + ' Schools';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openState(\'' + name + '\')"><p id="'+ name + 'Plus">-' + name + ' Schools</p></a>';
		}
	}
}

function openCategory(name)
{
	if(document.getElementById(name+'Reviews').style.display == 'none')
	{
		document.getElementById(name+'Reviews').style.display = 'block';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '-' + name + ' Reviews';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openCategory(\'' + name + '\')"><p id="'+ name + 'Plus">-' + name + ' Reviews</p></a>';
		}
	}
	else if(document.getElementById(name+'Reviews').style.display == 'block')
	{
		document.getElementById(name+'Reviews').style.display = 'none';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '+' + name + ' Reviews';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openCategory(\'' + name + '\')"><p id="'+ name + 'Plus">+' + name + ' Reviews</p></a>';
		}
	}
	else
	{
		document.getElementById(name+'Reviews').style.display = 'block';
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.getElementById(name+'Plus').innerHTML = '-' + name + ' Reviews';
		}
		else
		{
			document.getElementById(name+'Plus').innerHTML = '<a href="javascript:onclick=openCategory(\'' + name + '\')"><p id="'+ name + 'Plus">-' + name + ' Reviews</p></a>';
		}
	}
}