<?php
$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
//URL根路径
if(stripos($host,".com")!==false){	
	defined("ROOT_URL") ? null : define("ROOT_URL","//".$host."/");//in domian root dir	
}else{//for localtest
	defined("ROOT_URL") ? null : define("ROOT_URL","//".$host."/kamila/");//in root/imimusicart/");
}
//define("PROJECT_ROOT_DIR",substr(dirname(__FILE__),0,-(strlen("/lib")))."/");
define("SERVER_ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/");
defined("PROJECT_ROOT_DIR") ? null : define("PROJECT_ROOT_DIR", SERVER_ROOT_DIR."kamila/");
define("UPLOAD_DIR",SERVER_ROOT_DIR."uploaded/kamila/");//本地pc
define("LIB_URL","/kamila/lib/");

/*js library*/
//jquery
define("LIBJS_JQ",LIB_URL."jquery/jquery-1.11.1.min.js");
//jquery.ui
define("LIBJS_JQUI",LIB_URL."jquery.ui/1.11.4.smoothness/jquery-ui.min.js");
define("LIBJS_JQUIWIDGET",LIB_URL."jquery.ui/1.10.4.smoothness/js/jquery.ui.widget.js");
define("LIBJS_JQUIAUTOCOMPLETE",LIB_URL."jquery.ui/1.12.1.custom/jquery-ui-autocomplete.js");
//jquery.easyui
define("LIBJS_EASYUI",LIB_URL."jquery.easyui/1.5.2/jquery.easyui.min.js");
define("LIBJS_EASYUI_EDATAGRID",LIB_URL."jquery.easyui/jquery.edatagrid.imi.js");
define("LIBJS_EASYUI_SUBGROUPGRID",LIB_URL."jquery.easyui/datagrid-detailview.js");
//jquery.printArea
define("LIBJS_PRINTAREA",LIB_URL."jquery.printarea/2.4.0/jquery.PrintArea.js");
//jquery.weekcalendar
define("LIBJS_WEEKCALENDAR",LIB_URL."jquery.weekcalendar/jquery.weekcalendar.imi.js");
//Bootstrap core JS
define("LIBJS_BOOTSTRAP",LIB_URL."bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js");
define("LIBJS_HTML5SHIV",LIB_URL."bootstrap/js/html5shiv-3.7.3.min.js");
define("LIBJS_RESPOND",LIB_URL."bootstrap/js/respond-1.4.2.min.js");
//Bootstrap Plugin
define("LIBJS_BOOTSTRAP_SUBMENU",LIB_URL."bootstrap/bootstrap-submenu/2.0.4/dist/js/bootstrap-submenu.min.js");
define("LIBJS_BOOTSTRAP_HOVERDROPDOWN",LIB_URL."bootstrap/bootstrap-hover-dropdown/2.2.1/bootstrap-hover-dropdown.min.js");
define("LIBJS_BOOTSTRAP_VALIDATOR",LIB_URL."bootstrap/bootstrapvalidator/0.5.3/dist/js/bootstrapValidator.min.js");
define("LIBJS_BOOTSTRAP_DATETIMEPICKER",LIB_URL."bootstrap/bootstrap-datetimepicker/2.4.4/js/bootstrap-datetimepicker.min.js");
define("LIBJS_SPARK",LIB_URL."spark/spark-md5.min.js");
//System JS
define("LIBJS_UPLOADER",ROOT_URL."js/uploader.js");
define("LIBJS_FRONT",ROOT_URL."js/front.js");
define("LIBJS_HEADER",ROOT_URL."js/header.js");

/*css library*/
//jquery.ui
define("LIBCSS_JQUI",LIB_URL."jquery.ui/1.11.4.smoothness/jquery-ui.min.css");
define("LIBCSS_JQUIAUTOCOMPLETE",LIB_URL."jquery.ui/1.12.1.custom/jquery-ui-autocomplete.css");
//jquery.easyui
define("LIBCSS_EASYUI",LIB_URL."jquery.easyui/1.5.2/themes/bootstrap/easyui.css");
define("LIBCSS_EASYUI_ICON",LIB_URL."jquery.easyui/1.5.2/themes/icon.css");
//jquery.weekcalendar
define("LIBCSS_WEEKCALENDAR",LIB_URL."jquery.weekcalendar/jquery.weekcalendar.css");
define("LIBCSS_WEEKCALENDAR_PRINT",LIB_URL."jquery.weekcalendar/jquery.weekcalendar.print.css");
/* Bootstrap core CSS */
define("LIBCSS_BOOTSTRAP",LIB_URL."bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css");
define("LIBCSS_BOOTSTRAP_DOC",LIB_URL."bootstrap/css/docs.min.css");
define("LIBCSS_BOOTSTRAP_THEME",LIB_URL."bootstrap/bootstrap-3.3.7/dist/css/bootstrap-theme.min.css");
//Bootstrap Plugin
define("LIBCSS_BOOTSTRAP_SUBMENU",LIB_URL."bootstrap/bootstrap-submenu/2.0.4/dist/css/bootstrap-submenu.min.css");
define("LIBCSS_BOOTSTRAP_VALIDATOR",LIB_URL."bootstrap/bootstrapvalidator/0.5.3/dist/css/bootstrapValidator.min.css");
define("LIBCSS_BOOTSTRAP_DATETIMEPICKER",LIB_URL."bootstrap/bootstrap-datetimepicker/2.4.4/css/bootstrap-datetimepicker.min.css");
//fontawesome
define("LIBCSS_FONTAWESOME",LIB_URL."fontawesome/4.2.0/css/font-awesome.min.css");
//System CSS
define("LIBCSS_FRONT",ROOT_URL."css/front.css");

define("DB_HOST","localhost");  
define("DB_NAME","kamila");
define("DB_USERNAME","STAFFUSER");
define("DB_PASSWORD","DALIAN2018");
$DB_INSTANCE_ARRAY=array(
	"kamila"=>array("dbhost"=>DB_HOST,"dbuser"=>DB_USERNAME,"dbpw"=>DB_PASSWORD,"dbname"=>DB_NAME),
);

$SYS_MESSAGE=array(
	"000"=>"Succeed",
	"100" => "Param invalid",
	"200"=>"SQL Execution ERROR",
	"201"=>"SQL Invalid - No value in Insert/Update",
	"202"=>"SQL Invalid - No constraint condition in Update/Delete",
	"203"=>"Business Constraint ERROR",
	"403"=>"您的访问受限(403),您可能没有访问此操作的权限.",
);

$CONST_DICT=array(
	"common_sex"=>array("M"=>array("0"=>"Male","1"=>"Male"),"F"=>array("0"=>"Female","1"=>"Female")),
	"pt_status"=>array("draft"=>array("0"=>"Draft","1"=>"Draft Status"),"normal"=>array("0"=>"Normal","1"=>"Normal Status")),
	"pt_library"=>array("001"=>array("0"=>"前列腺","1"=>"前列腺"),"002"=>array("0"=>"细胞学","1"=>"细胞学")),
	"pt_format"=>array("001"=>array("0"=>"大切片","1"=>"大切片"),"002"=>array("0"=>"穿刺","1"=>"穿刺")),
	"share_type"=>array("U"=>array("0"=>"User","1"=>"User"),"G"=>array("0"=>"Group","1"=>"Group")),
	"user_role"=>array("100"=>array("0"=>"Admin","1"=>"Admin"),"1"=>array("0"=>"User","1"=>"User")),
	"common_yesno"=>array("Y"=>array("0"=>"Yes","1"=>"Yes"),"N"=>array("0"=>"No","1"=>"No")),
);


/********************************************************
					Global Settings & processing
*********************************************************/
date_default_timezone_set("Asia/Shanghai");
// Parse URL requests
if(!get_magic_quotes_gpc()){
	foreach($_GET as $key => $value) {
		$_GET[$key] = myaddslashes($value);
	}
	foreach($_COOKIE as $key => $value) {
		$_COOKIE[$key] = myaddslashes($value);
	}
	foreach($_POST as $key => $value) {
		$_POST[$key] = myaddslashes($value);
	}
	foreach($_REQUEST as $key => $value) {
		$_REQUEST[$key] = myaddslashes($value);
	}
}

function myaddslashes($string){
	if(is_array($string)){
		foreach($string as $key => $val) {
			$string[$key] = myaddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}	
	return $string;
}
?>