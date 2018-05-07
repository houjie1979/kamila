<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<title>您所访问的页面不存在！</title>
	
	<!-- Bootstrap core CSS -->
	<link href="<?php echo LIBCSS_BOOTSTRAP ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_DOC ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_THEME ?>" rel="stylesheet">
	<link href="<?php echo LIBCSS_BOOTSTRAP_SUBMENU ?>" rel="stylesheet">
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
	<script src="<?php echo LIBJS_FRONT ?>"></script>
<script>
$(document).ready(function() {
	$('[data-hover="dropdown"]').dropdownHover();
	$('[data-submenu]').submenupicker();
	$("[data-toggle='tooltip']").tooltip();
	setTimeout("window.location.href=\"<?php echo ROOT_URL ?>\"",2000);	
});	
</script>    
</head>
<body>
	<?php include "include/header.inc.php"?>		
	<div class="container" style="margin-top:50px;">			
		<div class="page-header text-center">
			<img src="<?php echo ROOT_URL."image/404.png" ?>">
            <h3>您所访问的页面不存在！</h3>
            <p>资源不存在或者没有访问权限，现在将转入首页，请稍候...</p>
            <p>如果您的浏览器没有自动跳转，请<a href="<?php echo ROOT_URL ?>"><strong>点击这里</strong></a>返回主页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>		
		</div>

	</div> <!-- /container -->

	<?php include "include/footer.inc.php";?>	
</body>
</html>
