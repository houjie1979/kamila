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

define("DB_HOST","localhost");  //172.18.219.116
define("DB_NAME","kamila");
define("DB_USERNAME","STAFFUSER");
define("DB_PASSWORD","DALIAN2018");
$DB_INSTANCE_ARRAY=array(
	"kamila"=>array("dbhost"=>DB_HOST,"dbuser"=>DB_USERNAME,"dbpw"=>DB_PASSWORD,"dbname"=>DB_NAME),
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