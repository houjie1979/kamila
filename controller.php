<?php
include "common/config.php";
include "common/db_mysql.class.php";
include "common/func.inc.php";
include "common/func_db.inc.php";

$action = $_REQUEST["action"];//action=import-sylk_view
if(empty($action)){
  echo "empty action";
  exit;
}
define("MVC_ACCESS", true);
$curr_action = array();
$action_tmp = explode("_",$action);

//Action object & sub object eg: import-sylk
$curr_action["obj"] = $curr_action["dir"] = $action_tmp[0];//obj=import-sylk
if(strpos($curr_action["obj"],"-")!==false){
	$action_tmp1 = explode("-",$action_tmp[0]);
	$curr_action["dir"] = $action_tmp1[0];//dir=import
}

//Action OP
$curr_action["op"] = $action_tmp[1];//op=view
$pri = $curr_action["obj"]."_".$curr_action["op"];
//action dispatcher....
$target_file="{$curr_action["dir"]}/{$curr_action["obj"]}.action.php";//import/import-sylk.action
if(!file_exists($target_file)){
	echo "Page not found.";
	exit;
}

//action dispatcher....
include $target_file;
?>