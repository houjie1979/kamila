<?php
if (! defined ( 'MVC_ACCESS' )) {
	exit();
}
$page_title="Import 4D data";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<title>Template for Bootstrap</title>
	
	<!-- Bootstrap core CSS -->
	<link href="<?php echo LIBCSS_BOOTSTRAP ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_DOC ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_THEME ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_SUBMENU ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_VALIDATOR ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_FONTAWESOME ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_FRONT ?>" rel="stylesheet">
		
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="<?php echo LIBJS_HTML5SHIV ?>"></script>
	  <script src="<?php echo LIBJS_RESPOND ?>"></script>
	<![endif]-->
	 
	<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
	<script src="<?php echo LIBJS_JQ ?>"></script>
	
	<!-- 包括所有已编译的插件 -->
	<script src="<?php echo LIBJS_BOOTSTRAP ?>"></script>
	<script src="<?php echo LIBJS_BOOTSTRAP_SUBMENU ?>"></script>  
	<script src="<?php echo LIBJS_BOOTSTRAP_HOVERDROPDOWN ?>"></script>
	<script src="<?php echo LIBJS_BOOTSTRAP_VALIDATOR ?>"></script>
	<script src="<?php echo LIBJS_FRONT ?>"></script>
<script>
var ROOT_URL="<?php echo ROOT_URL ?>";
var g_pagedata={
};
$(document).ready(function() {
	main_page_init();
});	

function main_page_init(){
	$("[data-hover='dropdown']").dropdownHover();
	$("[data-submenu]").submenupicker();
	$("#kamila_tablesall").click(function(){
		if(this.checked){
			//$(":checkbox[name='kamila_tables']").prop("checked",true);
			$(":checkbox[name='kamila_tables']").each(function(index,element){element.checked=true;});
		}else{
			//$(":checkbox[name='kamila_tables']").prop("checked",false);
			$(":checkbox[name='kamila_tables']").each(function(index,element){element.checked=false;});
		}
	});	
	$("#btn_execute").click(function(){
		queryTopPage();
	});			
}

function getQueryData(){	
	$("#execute_result").html("").hide();	
	var ajaxData=new Object();
	ajaxData.pageNumber=1;
	ajaxData.pageSize=100;	
	ajaxData.action="import-sylk_process";
	ajaxData.is_view="no";	
	ajaxData.kamila_tables=new Array();
	$(":checkbox[name='kamila_tables']:checked").each(function(index,element){
		ajaxData.kamila_tables.push(element.value);
	});
	if(ajaxData.kamila_tables.length==0){
		alert("Please select at least one kamila tables");
		return false;;
	}
//	alert(JSON.stringify(ajaxData));
	return ajaxData;	
}

function queryTopPage(){
	var ajaxObj=getAjaxObj();
	ajaxObj.ajaxData=getQueryData();
	if(ajaxObj.ajaxData==false){
		return;
	}
	$("#pageLoadingLayer").fadeIn(100,function(){	
	sendAjaxRequest(ajaxObj,
		function(JSONobj){
			var contentObj=$("#pt_list");	
			contentObj.empty();
					
			var code = JSONobj.result.code;
			var msg = JSONobj.result.msg; 
			if(code!="000"){
				alert(msg);
				return;
			}
			$("#execute_result").html(msg).show();			
		},function(){},
		function(){	
			$("#pageLoadingLayer").css({display:"none"});		
		}
	);});		
}	

function createPresentation(){
	var ajaxData=new Object();
	ajaxData.action="presentation_create";
	ajaxData.is_view="yes";
	ajaxData.title="Create presentation";
	show_bsdialog("bs_dialog_div",ajaxData);	
}

</script>    
</head>

<body class="bd-home">
	<!--hidden fields-->
	<div class="modal fade" id="bs_dialog_div"></div>
	<!--top nav-->
	<?php include "include/header.inc.php"?>
		
	<div class="container" style="margin-top:50px;">
		<div class="row">			
			<div class="page-header">
				<h1><?php echo $page_title ?></h1>
			</div>	
		</div>
		<div class="row">
			<ol class="breadcrumb">
			    <li><a href="<?php echo ROOT_URL ?>public">Home</a></li>
			</ol>
		</div>		
		<div class="row">					
			<div class="col-md-12">	
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<div class="btn-group">
						    <button type="button" class="btn btn-primary" name="btn_execute" id="btn_execute">Load SYLK</button>
						</div>												
					</div>								       
				
					<div class="form-group">
						<label class="checkbox-inline">
							<input type="checkbox" name="kamila_tablesall" id="kamila_tablesall" value="yes">Select All
					    </label>			    							
					</div>				
					<div class="form-group">
						<?php foreach($kamila_table_list as $kamila_table){ 
							$checked="";
							if($kamila_table["selected"]){
								$checked="checked";
							}
						?>
						<label class="checkbox-inline">
							<input type="checkbox" name="kamila_tables" value="<?php echo $kamila_table["table_name"] ?>" <?php echo $checked ?>><?php echo $kamila_table["table_name"] ?>
					    </label>
					    <?php } ?>			    							
					</div>
				</form>
			</div><!--end of right region col-md-10 -->
		</div><!-- end of row -->	
		<div class="row alert alert-success" style="margin-top:5px;display:none;" id="execute_result">
		</div>
	</div> <!-- /container -->
	
	<?php include "include/footer.inc.php";?>
	<div id="pageLoadingLayer" class="sd-underlay"><div class="sd-spinner-large">&nbsp;</div></div>    	
</body>
</html>
