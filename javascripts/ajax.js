function alerttest()
{
	alert('working here');
}

try
		{
		function getData(url, sendData, objectID)
		{
		  var xmlHttp;
			if (navigator.appName.indexOf("Microsoft") == -1)
			{
		    	xmlHttp=new XMLHttpRequest();
			}
			else
			{
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		    xmlHttp.onreadystatechange=function()
			{
		      if(xmlHttp.readyState==4)
		        {
					if(objectID.indexOf("message") != -1)
					{
		        		if(navigator.appName.indexOf("Microsoft") > -1)
						{				
							document.getElementById(objectID).outerHTML = "<a href=\"#\">" + xmlHttp.responseText + "</a>";
						}
						else
						{
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						}		
					}
					if(objectID.indexOf("school") != -1)
					{
						if(navigator.appName.indexOf("Microsoft") > -1)
						{
							
							document.getElementById(objectID).outerHTML = "<select id =\"schoolList\">" + xmlHttp.responseText + "<select>";
							
						}
						else
						{
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						}
					}
					if(objectID.indexOf("logCheck") != -1)
					{
						
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						
					}
		        }
		    }
		    xmlHttp.open("POST", url, true);
			xmlHttp.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');
		    xmlHttp.send(sendData);
		}
		
		function initMessages()
		{
			getData("http://www.thecollegenotebook.com/include/messCheck.php", null, "message");
			setInterval(\'getData("http://www.thecollegenotebook.com/include/messCheck.php", null, "message")\', 5000);
		}
		}
		catch(errMsg)
		{
			alert(errMsg.message);
		}