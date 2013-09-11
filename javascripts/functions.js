 /**    FlashAlert
                 @author: David Brear
                 @date: Feb 26th 2008
                 @version: 2.0
                 @useage: flash(object, array)    [array may have (does not need)
'startColor', 'endColor' and 'timeOut' properties]
                                         flash(object)
*/

var flashObjects = new Array();
        
         function color(nRed, nGreen, nBlue)
         {
                 var red = 255;
                 var green = 255;
                 var blue = 0;
                 if(nRed != null)
                 {
                         this.red = nRed;
                 }
                 if(nGreen != null)
                 {
                         this.blue = nBlue;
                 }
                 if(nBlue != null)
                 {
                         this.green = nGreen;
                 }
         }
        
         function flash(object, arguments)
         {
                 if(!object)
                 {
                         return false;
                 }
                 var startColor;
                 var endColor;
                 var time;
                 if(arguments != null)
                 {
                         if(arguments['timeOut'] == null)
                         {
                                 time = 50;
                         }
                         else
                         {
                                 time = arguments['timeOut'];
                         }
                         if(arguments['startColor'] == null)
                         {
                                 startColor = new color(255, 255, 0);
                         }
                         else
                         {
                                 startColor = arguments['startColor'];
                         }
                         if(arguments['endColor'] == null)
                         {
                                 endColor = new color(255, 255, 255);
                         }
                         else
                         {
                                 endColor = arguments['endColor'];
                         }
                 }
                 else
                 {
                         startColor = new color(255, 255, 0);
                         endColor = new color(255, 255, 255);
                         time = 50;
                 }
                 flashObjects[object.id] = new flashAlert(object, startColor,
endColor, time);
                 flashObjects[object.id].doFlash();
         }
         function flashAlert(obj, startCol, endCol, time)
         {
                 this.id = obj.id;
                 this.timeOut = time;
                 this.startColor = startCol;
                 this.endColor = endCol;
                 this.counter = 0;
                 this.timer = 0;
                 this.redDif = 0;
                 this.greenDif = 0;
                 this.blueDif = 0;
                 this.doFlash = function()
                 {
                         if((this.counter >= 75) ||(this.startColor.red == this.endColor.red
                                 && this.startColor.green == this.endColor.green
                                 && this.startColor.blue == this.endColor.blue))
                         {
                                 this.counter = 0;
                                 this.startColor.red = this.endColor.red;
                                 this.startColor.green = this.endColor.green;
                                 this.startColor.blue = this.endColor.blue;
                                 clearTimeout(this.timer);
                                 return;
                         }
                         else
                         {
                                 this.redDif = this.endColor.red - this.startColor.red;
                                 this.redDif = (this.redDif / 10);
                                 this.greenDif = this.endColor.green - this.startColor.green;
                                 this.greenDif = (this.greenDif / 10);
                                 this.blueDif = this.endColor.blue - this.startColor.blue;
                                 this.blueDif = (this.blueDif / 10);
                                 this.startColor.red += this.redDif;
                                 this.startColor.green += this.greenDif;
                                 this.startColor.blue += this.blueDif;
								 if(document.getElementById(this.id))
								 {
                                 document.getElementById(this.id).style.backgroundColor =
									'rgb('+parseInt(this.startColor.red)+','+parseInt(
									this.startColor.green)+','+parseInt(this.startColor.blue)+')';
								 }
                                 clearTimeout(this.timer);
                                 this.timer = setTimeout('flashObjects["'+this.id+'"].doFlash()',
this.timeOut);
                                 this.counter++;
                         }
                 }
         }
        
/** explode is a function to make an element grow in size and fade in color
*
*
*/

var explodeObjects = new Array();
var screenWidth = window.innerWidth;
var screenHeight = window.innerHeight;

function explode(object, arguments)
{
var direction = 'both';
var timeOut = 50;
var font = false;
var reappear = false;
if(!object.id )
{
         object.id = 'NewFlashElement'+counter;
         counter++;
}
if(arguments)
{
         if(arguments['direction'])
         {
                 direction = arguments['direction'];
         }
         if(arguments['timeOut'])
         {
                 timeOut = arguments['timeOut'];
         }
         if(arguments['font'])
         {
                 font = arguments['font'];
         }
         if(arguments['reappear'])
         {
                 reappear = arguments['reappear'];
         }
}
explodeObjects[object.id] = new explodeCode(object, direction, timeOut,
font, reappear);
document.getElementById(object.id).style.position='relative';
explodeObjects[object.id].doExplode();
}

function explodeCode(obj, dir, time, font, reappear)
{
this.direction = dir;
this.timeOut = time;
this.font = font;
this.reappear = reappear;
this.counter = 0;
this.id = obj.id;
this.opacity = getStyle(this.id, "opacity");
this.timer = 0;
this.timeOut = 50;
if(!this.opacity)
{
         this.opacity = 1;
}
this.origOpacity = this.opacity;
this.width = parseInt(getStyle(this.id, "width"));
this.height  =  parseInt(getStyle(this.id, "height"));
this.origHeight = this.height;
this.origWidth = this.width;
switch(this.direction)
{
         case 'both':
         {
                 this.doHeight = true;
                 this.doWidth = true;
         }break;
         case 'height':
         {
                 this.doHeight = true;
         }break;
         case 'width':
         {
                 this.doWidth = true;
         }break;
         default:
         break;
}

this.doExplode = function()
{
         if((this.counter >= 50) || (this.opacity <= 0))
         {
                 document.getElementById(this.id).style.display = 'none';
                 if(this.reappear)
                 {
                         setTimeout('redraw("'+this.id +'")', 5000);
                 }
                 return  false;
                
         }
         else
         {
                 if(this.height+10 <= screenHeight && this.doHeight)
                 {
                         this.height+= 5;
                 }
                 if(this.width+10 <= screenWidth && this.doWidth)
                 {
                         this.width += 5;
                 }
                 this.opacity-=.1;
                 document.getElementById(this.id).style.width = this.width;
                 document.getElementById(this.id).style.height = this.height;
                 document.getElementById(this.id).style.opacity = this.opacity;
                 clearTimeout(this.timer);
                 this.timer = setTimeout('explodeObjects["'+this.id+'"].doExplode()',
this.timeOut);
         }
}
}

function redraw(elmt)
{
document.getElementById(elmt).style.width = explodeObjects[elmt].origWidth;
document.getElementById(elmt).style.height =
explodeObjects[elmt].origHeight;
document.getElementById(elmt).style.opacity =
explodeObjects[elmt].origOpacity;
document.getElementById(elmt).style.display = 'block';
}





/*** getStyle returns the CSS style of any element
*/
function getStyle(el,styleProp)
{
var x = document.getElementById(el);
if (x.currentStyle)
         var y = x.currentStyle[styleProp];
else if (window.getComputedStyle)
         var y =
document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
return y;
}