
		(new Image()).src = '/images/exclamation.png';
		try
		{
		function getData(url, sendData, objectID)
		{
			//alert("message sent to " + url + " " + sendData + " " + objectID);
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
					if(objectID)
					{
					if(objectID.indexOf("message") != -1)
					{
						document.getElementById(objectID).innerHTML = xmlHttp.responseText;	
					}
					else if(objectID.indexOf("school") != -1)
					{
						if(navigator.appName.indexOf("Microsoft") > -1)
						{
							document.getElementById('schoolList').outerHTML = '<select name=\"schoolName\" id =\"schoolList\">' + xmlHttp.responseText + '<select>';
							
						}
						else
						{
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						}
					}
					else if(objectID.indexOf("logCheck") != -1)
					{
						
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						
					}
					else if(objectID.indexOf("commentsBox") != -1)
					{
						
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
							document.getElementById('first').style.backgroundColor = '#fffa7e';
							setTimeout("flash(document.getElementById('first'), {'startColor':new color(255,250,126), 'timeOut':200})", 1000);
					}
					else if(objectID.indexOf("adUploa") != -1)
					{
						document.getElementById('uploading').style.display = 'none';
						document.getElementById(objectID).innerHTML = xmlHttp.responseText;
					}
					else
					{
						if (document.getElementById(objectID).innerHTML)
						{
							document.getElementById(objectID).innerHTML = xmlHttp.responseText;
						}
					}
					}
		        }
		    }
		    xmlHttp.open("POST", url, true);
			xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		    xmlHttp.send(sendData);
		}
		
		function initMessages()
		{
			if(document.getElementById('message'))
			{
			getData("/include/messCheck.php", null, "message");
			setInterval('getData("/include/messCheck.php", null, "message")', 5000);
			}
		}
		}
		catch(errMsg)
		{
			alert(errMsg.message);
		}
		