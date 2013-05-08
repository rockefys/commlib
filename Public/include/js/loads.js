function loads(component,callback){var fileType = component.substring(component.lastIndexOf('.'));var head = document.getElementsByTagName("head")[0];var done = false;if (fileType === ".js") {var fileRef = document.createElement('script');fileRef.setAttribute("type", "text/javascript");fileRef.setAttribute("src", component);fileRef.onload = fileRef.onreadystatechange = function(){if (!done && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") ) {done = true;if(callback){eval(callback+'()');}head.removeChild(fileRef);}};} else if (fileType === ".css") {var fileRef = document.createElement("link");fileRef.setAttribute("type", "text/css");fileRef.setAttribute("rel", "stylesheet");fileRef.setAttribute("href", component);}if(typeof fileRef != "undefined"){head.appendChild(fileRef);}};
$(function(){
	$(".menus").click(function() { 
		$(this).siblings('ul').toggle(); 
		if($(this).children('.icon-chevron-down').length>0)
			$(this).children('.icon-chevron-down').attr('Class','icon-chevron-up'); 
		else
			$(this).children('.icon-chevron-up').attr('Class','icon-chevron-down');
	});
	$(".block .title a").click(function() { 
		$(this).parent().siblings('.control-group').toggle(); 
		if($(this).children('.icon-chevron-down').length>0)
			$(this).children('.icon-chevron-down').attr('Class','icon-chevron-up'); 
		else
			$(this).children('.icon-chevron-up').attr('Class','icon-chevron-down');
	
	});
});