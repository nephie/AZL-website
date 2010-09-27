<?php

class myrte extends getandsetLib {

	public function getHeader(){
		return '
			<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
			<script type="text/javascript">
				tinyMCE.init({
					mode : "none",
					theme : "advanced",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,outdent,indent,|,table,|,formatselect,|,headerline,|,charmap,|,cleanup,removeformat",
					theme_advanced_buttons2 : "cut,copy,paste,|,link,unlink,image,|,fullscreen,code,|,undo,redo",
					theme_advanced_buttons3 : "",



					setup: function(ed){
				           // Add a custom button
				           ed.addButton("headerline", {
				               title : "Headerline",
				               image : "files/images/headerline.gif",
				               onclick : function() {
						     // Add you own code to execute something on click
						     ed.focus();
				                     ed.selection.setContent("<div class=\"headerline\">&nbsp;</div>");
				                }
				            });
				        },
					content_css : "files/css/editor.css",
					file_browser_callback : "tinyBrowser",
					plugins : "advimage,fullscreen,table,paste"
				});
			</script>
		';
	}

}

?>