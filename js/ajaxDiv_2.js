/////////////////////////////////锁屏必要BEGIN/////////////////////////////////////////
 var docEle = function() {
  return document.getElementById(arguments[0]) || false;
 }

  var overlayID="overlay";
  var msgID = "overlayMsg";
  if (docEle(overlayID)) document.removeChild(docEle(overlayID));
  if (docEle(msgID)) document.removeChild(docEle(msgID));

/////////////////////////////////锁屏必要END/////////////////////////////////////////

function $(){return document.getElementById?document.getElementById(arguments[0]):eval(arguments[0]);}
var OverH,OverW,ChangeDesc,ChangeH=50,ChangeW=50;
function OpenDiv(_Dw,_Dh,_Desc) {

/////////////////////////////////锁屏必要BEGIN/////////////////////////////////////////

/////////////////////////////////锁屏必要END/////////////////////////////////////////

$("Loading").innerHTML="相关数据正在读取...";
OverH=_Dh;OverW=_Dw;ChangeDesc=_Desc;
$("Loading").style.display='';

$("Loading").style.position = "absolute";
$("Loading").style.zIndex = "997";

if(_Dw>_Dh){ChangeH=Math.ceil((_Dh-10)/((_Dw-10)/50))}else if(_Dw<_Dh){ChangeW=Math.ceil((_Dw-10)/((_Dh-10)/50))}

/////////////////////////////////锁屏必要BEGIN/////////////////////////////////////////
  var scrolltop = window.pageYOffset  || document.documentElement.scrollTop  || document.body.scrollTop || 0;
  var _clientheight=0;
        //ie FF  在有DOCTYPE时各有区别 
    _clientheight = Math.min(document.body.clientHeight , document.documentElement.clientHeight);
    if(_clientheight==0)
      _clientheight= Math.max(document.body.clientHeight , document.documentElement.clientHeight);
        
        var _clientwidth= document.documentElement.clientWidth || document.body.clientWidth;
        //整个页面的高度
        var _pageheight =  Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);

        var msgtop = (scrolltop+(_clientheight-400)/2)+"px";
        var msgleft = (_clientwidth-400)/2+"px";//(_clientwidth-200)/2+"px";

  // 锁屏图层，原理：建立绝对定位的高层以覆盖低层！（可以结合filter滤镜美化～）
  var newMask = document.createElement("div");
  newMask.id = overlayID;
  newMask.style.position = "absolute";  
  newMask.style.zIndex = "996";
  newMask.style.width = (_clientwidth*2) + "px";//_clientwidth + "px";考虑到有滚条，所加大了尺寸
  newMask.style.height = (_pageheight*1.25) + "px";//_pageheight + "px";考虑到有滚条，所加大了尺寸
  newMask.style.top = "0px";
  newMask.style.left = "0px";
  newMask.style.background = "#777";
  newMask.style.filter ="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75";
  //newMask.style.filter = "alpha(opacity=40)";
  newMask.style.opacity = "0.40";

  document.body.appendChild(newMask);

  // 关闭锁屏
  var newA = document.createElement("a");
  newA.href = "#";
  newA.innerHTML = "关闭激活层";
  newA.onclick = function() {
   document.body.removeChild(docEle(overlayID));
   //document.body.removeChild(docEle(msgID));
   $("Loading").style.display="none";
   return false;
  }
  //$("Loading").appendChild(newA); //添加打开过程中的“关闭链接”
/////////////////////////////////锁屏必要END/////////////////////////////////////////

$("Loading").style.top=msgtop;//新层距顶的距离
$("Loading").style.left=msgleft;//新层距左的距离
OpenNow()
}
var Nw=10,Nh=10;
function OpenNow() {
if (Nw>OverW-ChangeW)ChangeW=2;
if (Nh>OverH-ChangeH)ChangeH=2;
Nw=Nw+ChangeW;Nh=Nh+ChangeH;

if(OverW>Nw||OverH>Nh) {
if(OverW>Nw) {
$("Loading").style.width=Nw+"px";
//$("Loading").style.left=(document.documentElement.clientWidth-Nw)/2+"px";
}
if(OverH>Nh) {
$("Loading").style.height=Nh+"px";
//$("Loading").style.top=(document.documentElement.clientHeight-Nh)/2+"px"
}
window.setTimeout("OpenNow()",10)
}else{
Nw=10;Nh=10;ChangeH=50;ChangeW=50;
AjaxGet(ChangeDesc)
}
}

//创建XML对象
function createXMLHttps(){
var ret = null;
try {ret = new ActiveXObject('Msxml2.XMLHTTP')}
catch (e) {
try {ret = new ActiveXObject('Microsoft.XMLHTTP')}
        catch (ee) {ret = null}
}
if (!ret&&typeof XMLHttpRequest !='undefined') ret = new XMLHttpRequest();
return ret;
}

function AjaxGet(URL) {
var xmlhttp = createXMLHttps();
URL = encodeURI(URL);//使中文参数可以传送是（防止乱码）
xmlhttp.open("Get",URL,true);
xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlhttp.onreadystatechange = function() {
if (xmlhttp.readyState == 4 && xmlhttp.status==404) {$("Loading").innerHTML='读取页面失败,文件'+URL+'不存在!';return}
if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
$("Loading").innerHTML="<div class='LoadContent'><span id='divCloseBTN' onclick='closeLock();'>关闭窗口</span> "+xmlhttp.responseText+"</div>";
}
}
xmlhttp.send(null);
}

//定义关闭层的链接
function closeLock()
	 {
	   document.body.removeChild(docEle(overlayID)); 
	   $("Loading").style.display="none";
	   //return false;
	 }





