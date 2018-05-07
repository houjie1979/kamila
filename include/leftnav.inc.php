<div class="panel-group" id="accordion">
	<div class="panel panel-default">
	    <div class="panel-heading">
	    	<h3 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse_1">图像</a></h3>
	    </div>
	    <div id="collapse_1" class="panel-collapse collapse in">
			    <ul class="list-group">
			        <li class="list-group-item"><a href="<?php echo ROOT_URL."controller.php?action=presentation_list&vt=my" ?>"><span class="glyphicon glyphicon-list-alt"> 我的图像</span></a></li>
			        <li class="list-group-item"><a href="<?php echo ROOT_URL."controller.php?action=presentation_list&vt=sharetome" ?>"><span class="glyphicon glyphicon-share"> 与我分享</span></a></li>
			        <li class="list-group-item"><a href="<?php echo ROOT_URL."controller.php?action=presentation_list&vt=sharebyme" ?>"><span class="glyphicon glyphicon-share-alt"> 由我分享</span></a></li>
			    </ul>
		</div>
	</div>					
</div>

<div class="panel-group" id="accordion2">
	<div class="panel panel-default">
	    <div class="panel-heading">
	    	<h3 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2">用户资料</a></h3>
	    </div>
	    <div id="collapse_2" class="panel-collapse collapse in">
			    <ul class="list-group">
			        <li class="list-group-item"><span class="glyphicon glyphicon-user"> 修改用户信息</span></li>
			        <li class="list-group-item"><span class="glyphicon glyphicon-lock"> 修改密码</span></li>
			    </ul>
		</div>
	</div>						
</div>
<?php
/*
$menu_array = array(
	array("title"=>getLangDictData("presentations",$_SESSION["language"]),"class"=>"","url"=>"","submenu"=>array(
		array("title"=>getLangDictData("my_presentations",$_SESSION["language"]),"class"=>"glyphicon glyphicon-list-alt","privilege"=>"presentation_list","url"=>ROOT_URL."controller.php?action=presentation_list&vt=my"),
		array("title"=>getLangDictData("shared_with_me",$_SESSION["language"]),"class"=>"glyphicon glyphicon-share","privilege"=>"presentation_list","url"=>ROOT_URL."controller.php?action=presentation_list&vt=sharetome"),
		array("title"=>getLangDictData("shared_by_me",$_SESSION["language"]),"class"=>"glyphicon glyphicon-share-alt","privilege"=>"presentation_list","url"=>ROOT_URL."controller.php?action=presentation_list&vt=sharebyme"),	
	)),	
	array("title"=>getLangDictData("user_profile",$_SESSION["language"]),"class"=>"","url"=>"","submenu"=>array(
		array("title"=>getLangDictData("user_list",$_SESSION["language"]),"class"=>"glyphicon glyphicon-list-alt","privilege"=>"user_list","url"=>ROOT_URL."controller.php?action=user_list"),
		array("title"=>getLangDictData("change_user_info",$_SESSION["language"]),"class"=>"glyphicon glyphicon-user","privilege"=>"user_update","url"=>"javascript:;"),
		array("title"=>getLangDictData("change_password",$_SESSION["language"]),"class"=>"glyphicon glyphicon-lock","privilege"=>"user_update","url"=>"javascript:;"),	
	)),					
);

$menu_index=1;
$user_role=$_SESSION["sd_user"]["role_id"];
foreach($menu_array as $lv1_menu){ 
	$menu_param=array("panel_index"=>"leftmenugroup_".$menu_index,"header_index"=>"leftmenugroupheader_".$menu_index);
?>
<div class="panel-group" id="<?php echo $menu_param["panel_index"] ?>">
	<div class="panel panel-default">
	    <div class="panel-heading">
	    	<h3 class="panel-title"><a data-toggle="collapse" data-parent="#<?php echo $menu_param["panel_index"] ?>" href="#<?php echo $menu_param["header_index"] ?>"><?php echo $lv1_menu["title"] ?></a></h3>
	    </div>
	    <div id="<?php echo $menu_param["header_index"] ?>" class="panel-collapse collapse in">
			    <ul class="list-group">
			    	<?php if(empty($lv1_menu["submenu"])){
			    		continue;
			    	}
			    	foreach($lv1_menu["submenu"] as $lv2_menu){ 
			    		if(hasPrivilege($lv2_menu["privilege"],$user_role)){
			    	?>
			        <li class="list-group-item"><a href="<?php echo $lv2_menu["url"] ?>"><span class="<?php echo $lv2_menu["class"] ?>"> <?php echo $lv2_menu["title"] ?></span></a></li>
			        <?php }
			    	} ?>
			    </ul>
		</div>
	</div>					
</div>
<?php 
$menu_index++;
} 
*/
?>
