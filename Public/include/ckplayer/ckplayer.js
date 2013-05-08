
function ckcpt() { //自定义加载图片或flash，该类可以自己随意添加多个自定义的图片或者flash,请注意前面的都要在最后一个参数后面加上竖号，做为区隔，最后一个不用加
    var cpt = "";
    cpt += 'sch_lr.png,0,2,14,-36,0|';
    //分别是图片或flash地址，水平对齐方式，垂直对齐方式，水平偏移量，垂直偏移量，插件等级
    cpt += 'sch_lr.png,2,2,-15,-36,0|';
	cpt += '';
    return cpt;
}
function ckstyle() { //定义总的风格
    var ck = new Object();
    ck.cpath = '/Public/include/ckplayer/assets/'; //风格总路径
    ck.mylogo = '';
    ck.logo = '';
    ck.buffer = 'buffer.swf';
    ck.controlbar = 'images_buttom_bg.png';
    ck.cplay = 'images_Play_out.png,images_Play_on.png';
    ck.cpause = 'images_Pause_out.png,images_Pause_on.png';
    ck.pausec = 'images_Pause_Scgedyke.png,images_Pause_Scgedyke_on.png';
    ck.sound = 'images_Sound_out.png,images_Sound_on.png';
    ck.mute = 'images_Mute_out.png,images_Mute_on.png';
    ck.full = 'images_Full_out.png,images_Full_on.png';
    ck.general = 'images_General_out.png,images_General_on.png';
    ck.cvolume = 'images_Volume_back.png,images_Volume_on.png,images_Volume_Float.png';
    ck.schedule = 'images_Schedule_bg.png,images_Schedule_load.png,images_Schedule_play.png,images_Schedule.png';
    ck.fast = 'images_Fashf_out.png,images_Fashf_on.png,images_Fashr_out.png,images_Fashr_on.png';
    ck.advmute = 'images_v_off.png,images_v_on.png';
    ck.advjump = 'images_adv_skip.png';
    ck.advclose = 'images_close_adv.png';
    ck.control_r='right.swf,2,1,-75,-100,1';//右侧调整按钮的文件，坐标(4个参数)，传递的参数(默认是用来判断是否调用开关灯效果的，0是不使用关灯，1是使用，将调用function closelights(){};在站外时这里应该改成0,不使用关灯
	ck.control_c='';//分享按钮的文件，坐标,相关设置的xml文件,编码0是默认的utf-8,1是gbk2312
	ck.control_c2='adjustment.swf,1,1,-180,-100';//调整大小和颜色按钮的文件，坐标
	ck.control_rel='related.swf,ckplayer/related.xml,0';
	//视频播放结束后显示相关视频的插件文件（注意，要在视频结束动作设置成3），最大尺寸宽，最大尺寸高，最小尺寸宽，最小尺寸高，相关视频的xml文件,编码0是默认的utf-8,1是gbk2312
    ck.control_pv='Preview.swf,105,2000';
	//预览视频插件，文件名，离控制栏的距离，延时多长时间出现，默认2000秒
	ck.setup = '1,1,1,1,1,1,0,0,1,2,4';
	<!--总体设置：是否使用鼠标手型1是使用手型0是不使用，是否支持单击暂停1是支持0不支持，是否支持双击全屏1是支持0不支持,在播放前置flash广告时是否同时加载视频,logo在播放广告时是否显示1不显示0显,是否使用http流式控制,1是/0不是,是否在隐藏控制栏时调用视频大小0不是1是,是否使用视频拉伸,是否在多视频时分段加载,初始图片调整方式(0是智能调整，1是原始尺寸，2是附着视频，3是附着播放器),视频加载失败后再加载几次-->
    ck.pm_repc='';
	//视频地址替换符，当视频地址里包含&这样的符号时请用上面的符号
	ck.pm_spac = '|';
    //视频地址间隔符，默认使用逗号，如果视频地址里本身存在逗号的话需要另外设置一个间隔符，注意，即使只有一个视频也需要设置
    ck.pm_ctbar = '1,2,0,-30,0,30,0,1,5000';
    //控制栏的参数，这里分二种情况,第7个参数是设置定位方式(0：相对定位，1：绝对定位)
    //0：第一种情况的参数说明，默认1:中间对齐，上中下对齐，离左边的距离，Y轴偏移量，离右边的距离，高度，定位方式，隐藏方式(0不隐藏，1全屏时隐藏，2都隐藏)，隐藏时间
    //1：第二种情况的参数说明，左中右对齐，上中下对齐，x偏移量，y偏移，宽度，高度，定位方式，隐藏方式(0不隐藏，1全屏时隐藏，2都隐藏)，隐藏时间
    ck.pm_sch = '1,2,15,-37,15,5,0,14,8';
    //进度条的参数，这里分二种情况，前面7个参数对照控制栏的，第8个参数是拖动按钮的宽，第9个参数是拖动按钮的高
    ck.pm_play = '0,2,0,-30';
    //播放和暂停按钮的坐标
    ck.pm_clock = '0,2,40,0,0';
    //已播放时间的坐标和宽度
    ck.pm_clock2 = '0,2,40,-25,0';
    //总时间的坐标和宽度
    ck.pm_full = '2,2,-35,-30';
    //全屏和取消全屏按钮的坐标
    ck.pm_vol = '2,2,-95,-18,53,4,6,12';
    //音量调节框的坐标，宽度，高度，拖动按钮的宽度,高度
    ck.pm_sound = '2,2,-130,-30';
    //静音和取消静音的坐标
    ck.pm_fastf = '2,2,-13,-39';
    //快进按钮的坐标
    ck.pm_fastr = '0,2,0,-39';
    //快退按钮的坐标
    ck.pm_fasttime = '10';
    //快进和快退的秒数
    ck.pm_video_bottom = '35,35';
    //视频离底部的距离，广告的底部参考距离-该参数的作用请到网站查看
    ck.pm_video_bo = '200,1';
    //视频缓冲时间-默认200毫秒,是否优化视频-默认为1优化
    ck.pm_pa = '0,30';
    //是否自动调整中间暂停按钮的位置-默认0自动调整1是不自动调整，中间暂停按钮的透明度
    ck.pm_buffer_wh = '40,40';
    //缓冲图片的宽和度
    ck.pm_pr = '0x000000,0xfdfdfd,0xffffff,0,50,80,18,5';
    //提示框背景颜色,边框颜色,文字的颜色,边框的弧度,提示框透明度,文字透明度,提示框的高度,离按钮的距离
    ck.pm_advmaskap = '100';
    //播放前置广告时底部透明度0-100,只对flash有效，视频时将自动隐藏控制栏，图片时将自动设置成0，做为链接用
    ck.pm_advstatus = '1,3,280,30';
    //前置广告是否显示静音按钮(0/1),位置(0/1/2/3),x,y
    ck.pm_advjp = '1,0,3,190,30';
    //前置广告是否显示跳过广告按钮,跳过按钮触发对象(值0/1,0是直接跳转,1是触发js:function ckadjump(){}),对齐方式,x,y
    ck.pm_advtime = '100,3,100,30';
    //前置广告倒计时文本宽,对齐方式,x,y
	ck.pm_start='10,5,0xFF0000,20';
	//进度栏提示框宽，高，颜色,透明度
    ck.pm_advmarquee = '0,1,2,50,-60,50,18,0,0,15,3000,1,20,0x000000';
    //滚动广告的参数，这里分二种情况,第8个参数是设置定位方式(0：相对定位，1：绝对定位)
    //0：第一种情况的参数说明，是否显示，默认1:中间对齐，上中下对齐，离左边的距离，Y轴偏移量，离右边的距离，高度，定位方式，滚动方向（0向左1向上），滚动行距，移动单位像素，移动单位时长，向上滚动时每次停留时间，背景颜色
    //1：第二种情况的参数说明，左中右对齐，上中下对齐，x偏移量，y偏移，宽度，高度，定位方式，滚动方向（0向左1向上），向上时的高度，滚动行距，移动单位像素，移动单位时长，向上滚动时每次停留时间，背景颜色
    //是否显示底部广告(0/1),背景颜色,高度,左边的距离,右边的距离,离下面的距离,滚动的距离(0静止),间隙时间(越小越快,建议不小于20),如果开启广告需要有function ckmarqueeadv(){return '广告内容'}
    ck.advmarquee = '';
    ck.pm_advms = '0,3,40,55';
    //滚动广告是否显示关闭按钮(0/1),位置(0/1/2/3),x,y
    ck.pm_load = '70,20,0,40,{font color="#FFFFFF"}已加载[$prtage]%{/font},{font color="#FFFFFF"}加载失败{/font}';
    //加载视频百分比的位置默认距中，参数说明：宽度,高度,右偏移（正/负），下偏移(正/负),字符[$prtage]将被自动替换成百分比的数字,加载失败的提示
    ck.pm_buffer = '50,20,12,-16,{font color="#FFFFFF"}[$buffer]%{/font}';
    //视频缓冲百分比的位置默认距中，参数说明：宽度,高度,右偏移（正/负），下偏移(正/负),只显示百分比数据
    ck.statustrue =3;
    ck.pr_play = '点击播放';
    ck.pr_pause = '点击暂停';
    ck.pr_mute = '点击静音';
    ck.pr_nomute = '取消静音';
    ck.pr_full = '点击全屏';
    ck.pr_nofull = '退出全屏';
    ck.pr_fastf = '快进';
    ck.pr_fastr = '快退';
    ck.pr_time = '[$Time]';
    //[$Time]会自动替换目前进度提示
    ck.pr_volume = '[$Volume]';
    //[$Volume]会自动替换音量
    ck.pr_clock = '';
    //这里定义进度时间的显示，并且同时会替换二个参数，[$Time]会被替换成已播放时间，[$TimeAll]会被替换成总时间，[$Timewait]会在默认需要点击播放按钮才加载视频时替换ck.pr_clockwait的值
    ck.pr_clock2 = '{font color="#FFFFFF" size="12"}[$Time] | [$TimeAll]{/font}';
    //同pr_clock,这二个是相等的，主要是为了方便在不同的地方调用已播放时间和总时间
	ck.pr_clockwait='视频已加载，点击播放';
	//当使用默认不加载设置时，时间点上显示的文字
    ck.pr_adv = '{font color="#FFFFFF" size="12"}广告剩余：{font color="#FF0000" size="14"}{b}[$Second]{/b}{/font} 秒{/font}';
    ck.myweb = '';
    ck.cpt_list = ckcpt();
    return ck;
}
/*	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
	is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac}}(),k=function(){if(!M.w3){return}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f()}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false)}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);f()}});if(O==top){(function(){if(J){return}try{j.documentElement.doScroll("left")}catch(X){setTimeout(arguments.callee,0);return}f()})()}}if(M.wk){(function(){if(J){return}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return}f()})()}s(f)}}();function f(){if(J){return}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));Z.parentNode.removeChild(Z)}catch(aa){return}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]()}}function K(X){if(J){X()}else{U[U.length]=X}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false)}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false)}else{if(typeof O.attachEvent!=D){i(O,"onload",Y)}else{if(typeof O.onload=="function"){var X=O.onload;O.onload=function(){X();Y()}}else{O.onload=Y}}}}}function h(){if(T){V()}else{H()}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return}}X.removeChild(aa);Z=null;H()})()}else{H()}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa)}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class")}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align")}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value")}}P(ai,ah,Y,ab)}else{p(ae);if(ab){ab(aa)}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z}ab(aa)}}}}}function z(aa){var X=null;var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z}}}return X}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312)}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null}else{l=ae;Q=X}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310"}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137"}j.title=j.title.slice(0,47)+" - Flash Player Installation";var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac}else{ab.flashvars=ac}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae)}else{setTimeout(arguments.callee,10)}})()}u(aa,ab,X)}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y)}else{setTimeout(arguments.callee,10)}})()}else{Y.parentNode.replaceChild(g(Y),Y)}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML}else{var Y=ab.getElementsByTagName(r)[0];if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true))}}}}}return aa}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X}if(aa){if(typeof ai.id==D){ai.id=Y}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae]}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"'}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"'}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />'}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id)}else{var Z=C(r);Z.setAttribute("type",q);for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac])}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac])}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab])}}aa.parentNode.replaceChild(Z,aa);X=Z}}return X}function e(Z,X,Y){var aa=C("param");aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa)}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";(function(){if(X.readyState==4){b(Y)}else{setTimeout(arguments.callee,10)}})()}else{X.parentNode.removeChild(X)}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null}}Y.parentNode.removeChild(Y)}}function c(Z){var X=null;try{X=j.getElementById(Z)}catch(Y){}return X}function C(X){return j.createElement(X)}function i(Z,X,Y){Z.attachEvent(X,Y);I[I.length]=[Z,X,Y]}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return}var aa=j.getElementsByTagName("head")[0];if(!aa){return}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;G=null}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1]}G=X}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y)}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"))}}}function w(Z,X){if(!m){return}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y}else{v("#"+Z,"visibility:"+Y)}}function L(Y){var Z=/[\\\"<>\.;]/;var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2])}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa])}for(var Y in M){M[Y]=null}M=null;for(var X in swfobject){swfobject[X]=null}swfobject=null})}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;w(ab,false)}else{if(Z){Z({success:false,id:ab})}}},getObjectById:function(X){if(M.w3){return z(X)}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al]}}aj.data=ab;aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak]}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai]}else{am.flashvars=ai+"="+Z[ai]}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true)}X.success=true;X.ref=an}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);return}else{w(ah,true)}}if(ac){ac(X)}})}else{if(ac){ac(X)}}},switchOffAutoHideShow:function(){m=false},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]}},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X)}else{return undefined}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y)}},removeSWF:function(X){if(M.w3){y(X)}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X)}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1]}if(aa==null){return L(Z)}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block"}}if(E){E(B)}}a=false}}}}();