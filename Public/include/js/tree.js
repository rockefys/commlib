
var setting = {
	check: {
		enable: true
	},
	view: {
		fontCss: getFont,
		nameIsHTML: true,
		showLine: true,
		showIcon: true,
		expandSpeed:"",
		dblClickExpand :false,
		addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
		selectedMulti: false
	},
	async: {
		enable: true,
		url:"nodes",
		autoParam:["id"],
		dataFilter: filter
	},
	edit: {
		drag: {
			autoExpandTrigger: true,
			prev: dropPrev,
			inner: dropInner,
			next: dropNext
		},
		enable: true,
		editNameSelectAll: true
	},
	data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "pId",
			rootPId: 0
		}

	},
	callback: { //事件说明在下方
		//异步加载树节点
		beforeAsync: beforeAsync,
		onAsyncError: onAsyncError,
		onAsyncSuccess: onAsyncSuccess,
		
		//编辑
		beforeEditName: beforeEditName,

		//删除
		beforeRemove: beforeRemove,
		onRemove: onRemove,

		//修改名称
		beforeRename: beforeRename,
		onRename: onRename,

		//拖动改变位置
		beforeDrag: beforeDrag,
		beforeDrop: beforeDrop,
		beforeDragOpen: beforeDragOpen,
		onDrag: onDrag,
		onDrop: onDrop,

		//单击节点展开
		onClick: onClick,
		onExpand: onExpand
		
	}
};
<!--
/*
callback 说明
	//分为事件发生前事件(beforeXXX)和发生后的事件(onXXX),事件触发时调用相应的函数
	//beforeXXX事件的返回值影响onXXX事件是否调用
	//比如 如果beforeClick事件的函数返回false,则onClick事件不再调用

	beforeAsync : beforeAsync,//异步加载之前
	beforeDrag : beforeDrag,//节点被拖拽之前的事件，并且根据返回值确定是否允许开启拖拽操作
	beforeDragOpen : beforeDragOpen,//拖拽节点移动到折叠状态的父节点后，即将自动展开该父节点之前的事件，并且根据返回值确定是否允许自动展开操作
	beforeDrop : beforeDrop,//节点拖拽操作结束之前的事件，并且根据返回值确定是否允许此拖拽操作
	beforeEditName : beforeEditName,//节点编辑按钮的 click 事件，并且根据返回值确定是否允许进入名称编辑状态
	beforeRemove : beforeRemove,//节点被删除之前的事件，并且根据返回值确定是否允许删除操作
	beforeRename : beforeRename,//节点编辑名称结束（Input 失去焦点 或 按下 Enter 键）之后，更新节点名称数据之前的事件，并且根据返回值确定是否允许更改名称的操作
	
	//beforeCheck : null,//勾选 或 取消勾选之前的事件，并且根据返回值确定是否允许 勾选 或 取消勾选
	//beforeClick : null,//单击节点之前的事件，并且根据返回值确定是否允许单击操作
	//beforeCollapse : null,//父节点折叠之前的事件，并且根据返回值确定是否允许折叠操作
	//beforeDblClick : null,// zTree 上鼠标双击之前的事件，并且根据返回值确定触发 onDblClick 事件
	//beforeExpand : null,//父节点展开之前的事件，并且根据返回值确定是否允许展开操作
	//beforeMouseDown : null,// zTree 上鼠标按键按下之前的事件，并且根据返回值确定触发 onMouseDown 事件回调
	//beforeMouseUp : null,// zTree 上鼠标按键松开之前的事件，并且根据返回值确定触发 onMouseUp 事件
	//beforeRightClick : null,// zTree 上鼠标右键点击之前的事件，返回值确定触发 onRightClick 事件
	//onCheck : null,// checkbox / radio 被勾选 或 取消勾选的事件 
	//onCollapse : null,//节点被折叠的事件
	//onDblClick : null,// zTree 上鼠标双击之后的事件
	//onMouseDown : null,// zTree 上鼠标按键按下后的事件
	//onMouseUp : null,// zTree 上鼠标按键松开后的事件
	//onNodeCreated : null,//节点生成 DOM 后的事件
	//onRightClick : null// zTree 上鼠标右键点击之后的事件

	onAsyncError : onAsyncError,//异步加载出现异常错误的事件
	onAsyncSuccess : onAsyncSuccess,//异步加载正常结束的事件
	onClick : onClick,//节点被点击的事件 
	onDrag : onDrag,//节点被拖拽的事件
	onDrop : onDrop,//节点拖拽操作结束的事件1
	onExpand : onExpand,//节点被展开的事件
	onRemove : onRemove,//删除节点之后的事件。
	onRename : onRename//,//节点编辑名称结束之后的事件。如果通过直接修改 treeNode 的数据，并且利用 updateNode 方法更新，是不会触发此的
*/
-->
var zNodes =[
	{ id:0, name:"authority",isParent:true,url:"http://code.google.com/p/jquerytree/", target:"_blank",font:{'font-weight':'bold'}}
];

/*
*Async
*/
var log, className = "dark";
function beforeAsync(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	return true;
}
function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
	alert("节点加载失败");
}
function onAsyncSuccess(event, treeId, treeNode, data) {

}
function filter(treeId, parentNode, childNodes) {
	if (!childNodes) return null;
	for (var i=0, l=childNodes.length; i<l; i++) {
		childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
	}
	return childNodes;
}
/*
*edit
*/
function beforeEditName(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.selectNode(treeNode);
	return true;
}
function beforeRename(treeId, treeNode, newName) {
	className = (className === "dark" ? "":"dark");
	if (newName.length == 0) {
		alert("节点名称不能为空.");
		var zTree = $.fn.zTree.getZTreeObj("treeDemo");
		setTimeout(function(){zTree.editName(treeNode)}, 10);
		return false;
	}
	return true;
}
function onRename(e, treeId, treeNode) {
	$.ajax({
	  type: "POST",
	  url: "nodes",
	  data: { id: treeNode.id,name: treeNode.name,pid: treeNode.pId,level:treeNode.level, type: (treeNode.id<0?"add":"edit") },
	  success:(function(msg) {
	  	if(treeNode.id<0)
			if(msg>0)
				treeNode.id=msg;
		return(msg>0);
	})});
}
function onRemove(e, treeId, treeNode) {
	$.ajax({
	  	type: "POST",
	  	url: "nodes",
	  	data: { id: treeNode.id, type: "del" },
	  	success:(function(msg) {
	  		return(msg==1);
	})});
}
/*
*remove
*/
function beforeRemove(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.selectNode(treeNode);
	return confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
}


/*
*click
*/
function beforeClick(treeId, treeNode) {
	if (!treeNode.isParent) {
		alert("请选择父节点");
		return false;
	} else {
		return true;
	}
}
function onClick(e,treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.expandNode(treeNode);
	//单击节点时打开链接 待补
}
/*
*工具按钮 增删改
*/
var newCount = 1;
function addHoverDom(treeId, treeNode) {
	var sObj = $("#" + treeNode.tId + "_span");
	if (treeNode.editNameFlag || $("#addBtn_"+treeNode.id).length>0) return;
	var addStr = "<span class='button add' id='addBtn_" + treeNode.id
		+ "' title='add node' onfocus='this.blur();'></span>";
	sObj.after(addStr);
	var btn = $("#addBtn_"+treeNode.id);
	if (btn) btn.bind("click", function(){
		var zTree = $.fn.zTree.getZTreeObj("treeDemo");
		zTree.selectNode(treeNode);
		/*
		$.ajax({
		  type: "POST",
		  url: "nodes",
		  data: { id: treeNode.id,name: type: "add" },
		  complete:(function(msg) {
		  		return (msg=="1");
		})});	*/
		//zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
		var e ={data:{isParent:true}};
		add(e);
		return false;
	});
};
function removeHoverDom(treeId, treeNode) {
	$("#addBtn_"+treeNode.id).unbind().remove();
};

/*
*drop
*/
function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
	className = (className === "dark" ? "":"dark");
	$.ajax({
	  type: "POST",
	  url: "nodes",
	  data: { id: treeNodes[0].id,name: treeNodes[0].name,pid: targetNode.id,level:treeNodes[0].level+1, type: "edit" },
	  success:(function(msg) {
		return(msg>0);
	})});
	return true;
}
function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
	className = (className === "dark" ? "":"dark");
}
function dropPrev(treeId, nodes, targetNode) {
	var pNode = targetNode.getParentNode();
	if (pNode && pNode.dropInner === false) {
		return false;
	} else {
		for (var i=0,l=curDragNodes.length; i<l; i++) {
			var curPNode = curDragNodes[i].getParentNode();
			if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
				return false;
			}
		}
	}
	return true;
}
function dropInner(treeId, nodes, targetNode) {
	if (targetNode && targetNode.dropInner === false) {
		return false;
	} else {
		for (var i=0,l=curDragNodes.length; i<l; i++) {
			if (!targetNode && curDragNodes[i].dropRoot === false) {
				return false;
			} else if (curDragNodes[i].parentTId && curDragNodes[i].getParentNode() !== targetNode && curDragNodes[i].getParentNode().childOuter === false) {
				return false;
			}
		}
	}
	return true;
}
function dropNext(treeId, nodes, targetNode) {
	var pNode = targetNode.getParentNode();
	if (pNode && pNode.dropInner === false) {
		return false;
	} else {
		for (var i=0,l=curDragNodes.length; i<l; i++) {
			var curPNode = curDragNodes[i].getParentNode();
			if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
				return false;
			}
		}
	}
	return true;
}

/*
*Drag
*/
var log, className = "dark", curDragNodes, autoExpandNode;
function beforeDrag(treeId, treeNodes) {
	className = (className === "dark" ? "":"dark");
	for (var i=0,l=treeNodes.length; i<l; i++) {
		if (treeNodes[i].drag === false) {
			curDragNodes = null;
			return false;
		} else if (treeNodes[i].parentTId && treeNodes[i].getParentNode().childDrag === false) {
			curDragNodes = null;
			return false;
		}
	}
	curDragNodes = treeNodes;
	return true;
}
function onDrag(event, treeId, treeNodes) {
	className = (className === "dark" ? "":"dark");
}
function beforeDragOpen(treeId, treeNode) {
	autoExpandNode = treeNode;
	return true;
}

/*
*other
*/
function refreshNode(e) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	type = e.data.type,
	silent = e.data.silent,
	nodes = zTree.getSelectedNodes();
	if (nodes.length == 0) {
		alert("请先选择一个父节点");
	}
	for (var i=0, l=nodes.length; i<l; i++) {
		zTree.reAsyncChildNodes(nodes[i], type, silent);
		if (!silent) zTree.selectNode(nodes[i]);
	}
}
function selectAll() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
}
function onExpand(event, treeId, treeNode) {
	if (treeNode === autoExpandNode) {
		className = (className === "dark" ? "":"dark");
	}
}
function setTrigger() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.setting.edit.drag.autoExpandTrigger = $("#callbackTrigger").attr("checked");
}
function getFont(treeId, node) {
	return node.font ? node.font : {};
}
function showLog(str) {
	if (!log) log = $("#log");
	log.append("<li class='"+className+"'>"+str+"</li>");
	if(log.children("li").length > 8) {
		log.get(0).removeChild(log.children("li")[0]);
	}
}
function getTime() {
	var now= new Date(),
	h=now.getHours(),
	m=now.getMinutes(),
	s=now.getSeconds(),
	ms=now.getMilliseconds();
	return (h+":"+m+":"+s+ " " +ms);
}

/*
*curd by tree fun
*/
var newCount = 1;
function add(e) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	isParent = e.data.isParent,
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (treeNode) {
		treeNode = zTree.addNodes(treeNode, {id:(-1 - newCount), pId:treeNode.id, isParent:isParent, name:"new node" + (newCount++)});
	} else {
		treeNode = zTree.addNodes(null, {id:(-1 - newCount), pId:0, isParent:isParent, name:"new node" + (newCount++)});
	}
	if (treeNode) {
		zTree.editName(treeNode[0]);
	} else {
		alert("叶子节点被锁定，无法增加子节点");
	}
};
function edit() {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个节点");
		return;
	}
	zTree.editName(treeNode);
};
function remove(e) {
	alert("asdf");
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个节点");
		return;
	}
	var callbackFlag = $("#callbackTrigger").attr("checked");
	zTree.removeNode(treeNode, callbackFlag);
};
function clearChildren(e) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0 || !nodes[0].isParent) {
		alert("请先选择一个父节点");
		return;
	}
	zTree.removeChildNodes(treeNode);
};


