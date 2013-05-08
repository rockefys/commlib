/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// %REMOVE_START%
	config.height = 480; //高度 
	config.toolbar = 'Comm'; 
	config.toolbar_Comm =  
[  
    ['Source','Maximize','Preview','-','Templates'],  
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'], 
    ['Cut','Copy','Paste','PasteText'],  
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],  
    '/',  
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],  
   ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],  
    
	
	['Link','Unlink','Anchor','Image','Table','HorizontalRule','SpecialChar'],  
	 
    '/',
    ['Styles','Format','Font','FontSize'],  
    ['TextColor','BGColor'],
      
];  

config.toolbar_Full =  
[  
    ['Source','-','Save','NewPage','Preview','-','Templates'],  
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],  
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],  
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],  
    '/',  
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],  
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],  
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],  
    ['BidiLtr', 'BidiRtl'],  
    ['Link','Unlink','Anchor'],  
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],  
    '/',  
    ['Styles','Format','Font','FontSize'],  
    ['TextColor','BGColor'],  
    ['Maximize', 'ShowBlocks','-','About']  
];  


	config.plugins =
		'about,' +
		'a11yhelp,' +
		'basicstyles,' +
		'bidi,' +
		'blockquote,' +
		'clipboard,' +
		'colorbutton,' +
		'colordialog,' +
		'contextmenu,' +
		'div,' +
		'elementspath,' +
		'enterkey,' +
		'entities,' +
		'find,' +
		'flash,' +
		'floatingspace,' +
		'font,' +
		'format,' +
		'forms,' +
		'horizontalrule,' +
		'htmlwriter,' +
		'image,' +
		'iframe,' +
		'indent,' +
		'justify,' +
		'link,' +
		'list,' +
		'magicline,' +
		'maximize,' +
		'newpage,' +
		'pagebreak,' +
		'pastefromword,' +
		'pastetext,' +
		'preview,' +
		'print,' +
		'removeformat,' +
		'resize,' +
		'save,' +
		'scayt,' +
		'selectall,' +
		'showblocks,' +
		'showborders,' +
		'smiley,' +
		'sourcearea,' +
		'specialchar,' +
		'stylescombo,' +
		'tab,' +
		'table,' +
		'tabletools,' +
		'templates,' +
		'toolbar,' +
		'undo,' +
		'wsc,' +
		'wysiwygarea';
	// %REMOVE_END%

//是否对编辑区域进行渲染 plugins/editingblock/plugin.js
config.editingBlock = true
;

//编辑器中回车产生的标签
config.enterMode = CKEDITOR.ENTER_BR; //可选：CKEDITOR.ENTER_BR或CKEDITOR.ENTER_DIV

//是否转换一些难以显示的字符为相应的HTML字符 plugins/entities/plugin.js
config.entities_greek = true
;

//是否转换一些拉丁字符为HTML plugins/entities/plugin.js
config.entities_latin = true;

//是否转换一些特殊字符为ASCII字符 如：”This is Chinese: 汉语.”转换为：”This is Chinese: &#27721;&#35821;.”
//plugins/entities/plugin.js
config.entities_processNumerical = false
;

//字体默认大小 plugins/font/plugin.js
config.fontSize_defaultLabel = "16px";



//是否允许改变大小 plugins/resize/plugin.js
config.resize_enabled = true
;

//改变大小的最大高度 plugins/resize/plugin.js
config.resize_maxHeight = 1000;

//改变大小的最大宽度 plugins/resize/plugin.js
config.resize_maxWidth = 800;

//改变大小的最小高度 plugins/resize/plugin.js
config.resize_minHeight = 300;

//改变大小的最小宽度 plugins/resize/plugin.js
config.resize_minWidth = 500;

//撤销的记录步数 plugins/undo/plugin.js
config.undoStackSize =20
;
};

// %LEAVE_UNMINIFIED% %REMOVE_LINE%
