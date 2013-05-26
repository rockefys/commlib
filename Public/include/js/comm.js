(function(e,t){"use strict";function y(e){if(e._done)return;e(),e._done=1}function b(e){var t=e.split("/"),n=t[t.length-1],r=n.indexOf("?");return r!=-1?n.substring(0,r):n}function w(e){var t;if(typeof e=="object")for(var n in e)e[n]&&(t={name:n,url:e[n]});else t={name:b(e),url:e};var r=l[t.name];return r&&r.url===t.url?r:(l[t.name]=t,t)}function E(e,t){if(!e)return;typeof e=="object"&&(e=[].slice.call(e));for(var n=0;n<e.length;n++)t.call(e,e[n],n)}function S(e){return Object.prototype.toString.call(e)=="[object Function]"}function x(e){e=e||l;var t;for(var n in e){if(e.hasOwnProperty(n)&&e[n].state!=g)return!1;t=!0}return t}function T(e){e.state=d,E(e.onpreload,function(e){e.call()})}function N(e,n){e.state===t&&(e.state=v,e.onpreload=[],k({src:e.url,type:"cache"},function(){T(e)}))}function C(e,t){if(e.state==g)return t&&t();if(e.state==m)return p.ready(e.name,t);if(e.state==v)return e.onpreload.push(function(){C(e,t)});e.state=m,k(e.url,function(){e.state=g,t&&t(),E(f[e.name],function(e){y(e)}),x()&&o&&E(f.ALL,function(e){y(e)})})}function k(e,t){var r=n.createElement("script");r.type="text/"+(e.type||"javascript"),r.src=e.src||e,r.async=!1,r.onreadystatechange=r.onload=function(){var e=r.readyState;!t.done&&(!e||/loaded|complete/.test(e))&&(t.done=!0,t())},(n.body||i).appendChild(r)}function L(){o||(o=!0,E(u,function(e){y(e)}))}var n=e.document,r=e.navigator,i=n.documentElement,s,o,u=[],a=[],f={},l={},c=n.createElement("script").async===!0||"MozAppearance"in n.documentElement.style||e.opera,h=e.head_conf&&e.head_conf.head||"head",p=e[h]=e[h]||function(){p.ready.apply(null,arguments)},d=1,v=2,m=3,g=4;c?p.js=function(){var e=arguments,t=e[e.length-1],n={};return S(t)||(t=null),E(e,function(r,i){r!=t&&(r=w(r),n[r.name]=r,C(r,t&&i==e.length-2?function(){x(n)&&y(t)}:null))}),p}:p.js=function(){var e=arguments,t=[].slice.call(e,1),n=t[0];return s?(n?(E(t,function(e){S(e)||N(w(e))}),C(w(e[0]),S(n)?n:function(){p.js.apply(null,t)})):C(w(e[0])),p):(a.push(function(){p.js.apply(null,e)}),p)},p.ready=function(e,t){if(e==n)return o?y(t):u.push(t),p;S(e)&&(t=e,e="ALL");if(typeof e!="string"||!S(t))return p;var r=l[e];if(r&&r.state==g||e=="ALL"&&x()&&o)return y(t),p;var i=f[e];return i?i.push(t):i=f[e]=[t],p},p.ready(n,function(){x()&&E(f.ALL,function(e){y(e)}),p.feature&&p.feature("domloaded",!0)});if(e.addEventListener)n.addEventListener("DOMContentLoaded",L,!1),e.addEventListener("load",L,!1);else if(e.attachEvent){n.attachEvent("onreadystatechange",function(){n.readyState==="complete"&&L()});var A=1;try{A=e.frameElement}catch(O){}!A&&i.doScroll&&function(){try{i.doScroll("left"),L()}catch(e){setTimeout(arguments.callee,1);return}}(),e.attachEvent("onload",L)}!n.readyState&&n.addEventListener&&(n.readyState="loading",n.addEventListener("DOMContentLoaded",handler=function(){n.removeEventListener("DOMContentLoaded",handler,!1),n.readyState="complete"},!1)),setTimeout(function(){s=!0,E(a,function(e){e()})},300)})(window);
$(function(){

	/*============================================================================
	*
	*Global
	*
	*=============================================================================
	*/

	$(document).ajaxStart(function() {
	  loading();
	});

	$(document).ajaxStop(function() {
	  finish();
	});
	$('.content').tooltip({
	    selector: '[rel=tooltip]',container:'body'
	});

	$('.content').popover({
	    selector: '[rel=popover]'
	});

	$('[rel=datepicker]').datepicker();
	/*
	$('[rel=datepicker]').each(function(){
		var f=$(this).data('date-format');
		$(this).datepicker({
			format:f,
			autoClose:true
		});
	})
	*/
	$('[data-toggle="tab"]').mouseenter(function (e) {
        e.preventDefault();
    });
    $('.close').on('click',function(){
    	$('.alert').hide();
	});


	/*============================================================================
	*
	*Form
	*
	*=============================================================================
	*/

	//validate
	$("#form").validate({
        highlight: function(label) {
            $(label).closest('.control-group').addClass('error');
        },
        success: function(label) {
            $(label)
            .text('OK!').addClass('valid')
            .closest('.control-group').addClass('success');
        },
         submitHandler:function(form){
            form.submit();
        }
    });

	 $('.content').on('click','a.selec-all',function(){
    	$("#"+$(this).data('target')+" :checkbox").attr("checked","checked");
	});
	$('.content').on('change',':checkbox.select-all',function (){ 
	    var checked=$(this).attr('checked')?true:false;
	    $(this).closest("table").find("input[type='checkbox']").attr("checked",checked);
	});
	$(".content").on('click','a.select-invert',function(){
	    var checkbox=$("#"+$(this).data('target')+" :checkbox");
	     for(var i=0;i<checkbox.length;i++){
	         checkbox[i].checked=!checkbox[i].checked;
	     }
	 });

	 $('.content').on('click','a.selec-cancel',function(){
	     $("#"+$(this).data('target')+" :checkbox").removeAttr("checked");
	 });  

	 //enable
	 $('.table-content').on('click','.btn-ables .btn-enable',function(e){
	 	if($(this).hasClass('disabled'))return;
	 	e.preventDefault();
		$(this).addClass('btn-info disabled');
		$(this).siblings().removeClass('btn-inverse disabled');
		var href=$(this).data("href");
		$.ajax({
			type:"get",  
		    url:href,
		    success:function(data,textStatus){ 
		    	alert("Enabled.");      
		    },
		    error:function(x){  
		        alert("Request error"); 
		    } 
	    });  
	});

	//disable
	$('.table-content').on('click','.btn-ables .btn-disable',function(e){
		if($(this).hasClass('disabled'))return;
		e.preventDefault();
		$(this).addClass('btn-inverse disabled');
		$(this).siblings().removeClass('btn-info disabled');
		var href=$(this).data("href");
		$.ajax({
			type:"get",  
		    url:href,
		    success:function(data,textStatus){ 
		    	alert("Disabled.");      
		    },
		    error:function(x){  
		        alert("Request error"); 
		    } 
	    }); 
	});

	/*============================================================================
	*
	*Modal
	*
	*=============================================================================
	*/
	$('.modal').on('shown', function () {
		$('[rel=datepicker]').datepicker();
		$('.selected').each(function(){
        	$(this).val($(this).data('value'));
    	});
	});

	$('.modal').on('shown','form.readonly',function () {
		alert('asf');
		$(this,"input").attr("disabled",'true'); 
		$(this,"textarea").attr("disabled",'true');  
		$(this,"select").attr("disabled",'true');
		$(this,'#btn-submit').addClass('hidden');
	});

	$('.modal').on('hidden', function () {
    	$(this).removeData();
    	$('#add-modal #btn-submit').removeClass('hidden');
	});

	$('.content').on('click', '[data-toggle=modal]',function (e) {
	    e.preventDefault();
	    var target=$(this).data('target');
	    $(  target+ " .modal-body").load($(this).data("href"), function(response,status,xhr) { 
	    	if (status == "success") {
		        $(target).modal("show");
		    }
	    });
	    return false;
	});

	$('.modal #btn-submit').on('click',function(){
		var params=$('#add-modal form').serialize();
		var addr=$('#add-modal form').attr('action');
		$.ajax({
			url:addr,
			type:'post',
			cache : false,
			dataType:'json',
			data:params,
			ajaxStart:loading,
			success: function(msg){alert(msg); }
		}); 
	});

	/*============================================================================
	*
	*Toolbar
	*
	*=============================================================================
	*/
	$('#table-toolbar .btn-tool').on('click',function(){
    	$('.btn-tool').removeClass('operate-cur');
    	$(this).addClass('operate-cur');
    });

	$('.content').on('click','#table-toolbar .btn-add, #table-toolbar-operate .btn-add',function(){
		$($(this).data('target')+' #title').text('Add');
	});

	$('.content').on('click','#table-toolbar .btn-edit, #table-toolbar-operate .btn-edit',function(){
		$($(this).data('target')+' #title').text('Edit')
	});
	$('.content').on('click','#table-toolbar .btn-view, #table-toolbar-operate .btn-view',function(){
		$($(this).data('target')+' #title').text('View')
	});

$('.content').on('click','#table-searchbar #btn-query',function(){
	var addr=$('#table-searchbar #btn-query').data('href');
	var params=$('#table-searchbar').serialize();
	$.ajax({
		url:addr,
		type:'post',
		cache : false,
		data:params,
		success: function(msg){
			$('.table-content').html(msg);
		}
	});
});

	$('.content').on('click','#table-toolbar-operate .btn-delete',function result(){
		$(this).addClass('for-delete');
		var addr=$(this).data('href');
		$.ajax({
			url:addr,
			type:'post',
			cache : false,
			dataType:'json',
			success: function(msg){
				alert(msg);
				$('tbody .for-delete').parent().parent().parent().html('');
			}
		}); 
	});
	$('.content').on('click','#table-toolbar .btn-delete',function(){
		var params=getChecked();
		if(!params){
			alert('Select a row for operate.');
			return false;
		}
		var  decide= confirm("Are you sure want to delete it ?");
		if(!decide)return false;
		var addr=$(this).data('href');
		$.ajax({
			url:addr,
			type:'post',
			cache : false,
			dataType:'json',
			data:{id:params},	
			success: function(msg){alert(msg);removeRow(); }
		});
		return false;
	});

	
	$('#table-toolbar-operate .btn-edit,#table-toolbar-operate .btn-view').on('click',function(){
		var href=$(this).data("href");
	    var target=$(this).data("target");
	    $( target +" .modal-body").load(href, function() { 
	         $(target).modal("show"); 
	         
	    });
	});
	$('#table-toolbar .btn-edit').on('click',function(){
		var n=$("#data-table input:checked").length;
		if(!n){
			alert('Select a row for operate.');
			return false;
		}
		else{
			if(n>1){
				alert('One row limit to operate at a time.');
				return false;
			}
		}
		if(n!=1){
			return false;
		}

		var n=$("#data-table input:checked").attr('id');
		var href=$(this).data("href");
	    var target=$(this).data("target");
	    $( target +" .modal-body").load(href+'?id='+n, function() { 
	         $(target).modal("show"); 
	    });
	});
	

    /*============================================================================
	*
	*Pretty date
	*
	*=============================================================================
	*/
	var n = $(".prettydate").length;
	if(n>0){
		$.prettyDate.messages = {
			now: "刚刚",
			minute: "1分钟前",
			minutes: $.prettyDate.template("{0}分钟前"),
			hour: "1小时前",
			hours: $.prettyDate.template("{0}小时前"),
			yesterday: "昨天",
			days: $.prettyDate.template("{0}天前"),
			weeks: $.prettyDate.template("{0}星期前"),
			months: $.prettyDate.template("{0}个月前"),
			years: $.prettyDate.template("{0}年前")
		};
		$(".prettydate").prettyDate();
	}

	/*============================================================================
	*
	*Menu
	*
	*=============================================================================
	*/
	$(".menus").click(function() { 
		$(this).siblings('ul').toggle(); 
		if($(this).children('.icon-chevron-down').length>0)
			$(this).children('.icon-chevron-down').attr('Class','icon-chevron-up'); 
		else
			$(this).children('.icon-chevron-up').attr('Class','icon-chevron-down');
		return false;
	});

	$('.sidebar-nav a').click(function (){
		var addr=$(this).data('href');
		if(!addr)return;
		$.ajax({
			url:addr,
			type:'post',
			cache : false,
			success: function(msg){
				$('.content').html(msg);
			}
		});
		return false;
	});

	/*============================================================================
	*
	*Block
	*
	*=============================================================================
	*/
	$('.content').delegate('.block-title .btn-minimize','click',function(){
		$(this).parent().parent().next('.block-content').toggle(); 
		if($(this).children('.icon-chevron-down').length>0)
			$(this).children('.icon-chevron-down').attr('Class','icon-chevron-up'); 
		else
			$(this).children('.icon-chevron-up').attr('Class','icon-chevron-down');
	});
	
	$('.content').delegate('.block .btn-close','click',function(){
		$(this).parent().parent().parent().fadeOut();
	});
		
	/*============================================================================
	*
	*Views
	*
	*=============================================================================
	*/
	$('.views li').mouseenter(function(){
		$(' i',this).addClass('icon-white')
	});
	$('.views li').mouseleave(function(){
		$(' i',this).removeClass('icon-white')
	});

	/*
	$('input').iCheck({
	   checkboxClass: 'icheckbox_flat-blue',
    	radioClass: 'iradio_flat-blue'
	  });
	*/

});

function event_init(){
	
}

function clear_form_data(id){ 
	$(id+' #form').each(function(index){
		$(id+' #form')[index].reset();
	});
}

function refer_quote(source,data){
	$('#'+source+" input").each(function(){
		var field = $(this).data("target");
		var result=data[field];
		$(this).val(data[field]);
	});
	$('#modal-refer').modal('hide');
}

function setValue(obj){ 
    for(var p in obj){  
       if(typeof(obj[p])=="function"){        
        }else{      
            $("form #"+p).val(obj[p]);                 
        }        
    }
}

function removeRow(){
	$("#data-table input:checked").parent().parent().parent().html('');
}

function getChecked(){
	var list=$("#data-table input:checked").map(function() {
		return $(this).attr('id');
	}).get().join(',');
	return list;
}
function alert(msg){
	$('#alert').html("<div class='center alert message fade in hide'><a class='dismiss close' data-dismiss='alert'>×</a><label>"
		+ msg + "</label></div>"
    );
    $(".alert").show();
    $(".alert").delay(3000).fadeIn(5000).fadeOut(1000);
}
function alert(msg,type){
	$('#alert').html("<div class='center alert alert-"+type+" message fade in hide'><a class='dismiss close' data-dismiss='alert'>×</a><label>"
		+ msg + "</label></div>"
    );
    $(".alert").show();
    $(".alert").delay(3000).fadeIn(5000).fadeOut(1000);
}

function loading(){
	$('.loading').show();

}
function finish(){
	$('.loading').hide();
}

function thumbName(name){
	return slice(name);
}  
function slice(name){ 
	if (name.length > 27){
	    name = name.slice(0, 19) + '...' + name.slice(-8);
	}
	return name;
}
