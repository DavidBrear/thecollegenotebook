function findBrowser()
{
	if( navigator.appVersion.indexOf('Safari') > -1)
	{
		document.writeln('<link rel="stylesheet" href="include/safari.css" type="text/css" media="all">');
	}
	if( navigator.appName.indexOf('Microsoft') > -1)
	{
		document.writeln('<link rel="stylesheet" href="include/IE.css" type="text/css" media="all">');
	}
	
}

findBrowser();