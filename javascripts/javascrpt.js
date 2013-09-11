				var browser = navigator.userAgent.toLowerCase();
				var IE = browser.indexOf("msie");
				var id = -1;
				var _sess = -1;
				if (IE == -1)
				{
					var object = null;
					var cx = 0;
					var cy = 0;
					function initMove()
					{
						if(document.cookie != "")
						{
							cookies = document.cookie.split("; ");
							for (var i = 0; i < cookies.length; i++)
							{
								if (cookies[i].split("=")[0] == "userid")
								{
									id = cookies[i].split("=")[1];
									
								}
								if (cookies[i].split("=")[0] == "_sess")
								{
									_sess = cookies[i].split("=")[1];
									
								}
							}
						}
						document.onmousedown = pickIt;
						document.onmousemove = dragIt;
						document.onmouseup = dropIt;
					}
					function pickIt(evt)
					{
						var evt = (evt) ? evt : ((window.event) ? window.event : null);
						var objectID = (evt.target)? evt.target.id : ((evt.srcElement) ? evt.srcElement.id : null);
						if (objectID.indexOf("square")!=-1)
						{
							object = document.getElementById(objectID);
							object.onselectstart = function() {return false;}
						}
						if (object)
						{
							object.style.zIndex=100;
							cx = evt.clientX - object.offsetLeft;
							cy = evt.clientY - object.offsetTop;
							object.style.opacity = .5;
							return;
						}
						else
						{
							object = null;
							return;
						}
					}
					
					function dragIt(evt)
					{
						var evt = (evt) ? evt : ((window.event) ? window.event : null);
						if (object)
						{
							object.style.left = evt.clientX - cy + "px";
							object.style.top = evt.clientY - cy + "px";
							var str = "coords" + object.id;
							document.getElementById(str).value = "left:" + object.style.left + "; top: " + object.style.top + ";";
							return false;
						}
					}
					
					function dropIt()
					{
						if (object)
						{
							object.style.zIndex = 0;
							object.style.opacity = 1.0;
							object = null;
							setSquare(object, id, _sess);
							return false;
						}
					}
				}
				else
				{
					var object = null;	
					mouseover = true
					function coordinates()
					{		
						document.getElementById(object).select();	
						if (event.Element.id.indexOf("square") > -1)
						{
							object = event.srcElement.id;
							mouseover=true
							pleft=document.getElementById(object).style.pixelLeft
							ptop=document.getElementById(object).style.pixelTop
							xcoor=event.clientX
							ycoor=event.clientY
							document.onmousemove=moveImage();
						}
					}
					
					function moveImage()
					{
						document.getElementById(object).select();
					if (mouseover&&event.button==1)
						{
						document.getElementById(object).style.pixelLeft=pleft+event.clientX-xcoor
						document.getElementById(object).style.pixelTop=ptop+event.clientY-ycoor
						return false
						}
					}
					
					function mouseup()
					{
					object = null;
					mouseover=false
					}
					document.onmousedown = coordinates
					document.onmouseup = mouseup
				}
				function setSquare(obj, id, _sess)
				{
					var x = obj.style.left;
					var y = obj.style.top;
					var id = obj.id;
					data = "object=" + id + "&x=" + x + "&y=" + y + "&id=" + id + "&_sess=" + _sess;
					getData("http://www.thecollegenotebook.com/setSquare.php", data, id);				
				}