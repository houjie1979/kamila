<?phprequire_once('../includes/initialize.php');if(isset($_POST['getref'])) {	// get page Form has been submitted    $page = trim($_POST['page']);	$found_refs = References::get_like_page($page);	$found_holds = Holdings::get_like_page($page);	$message = "Found ". count($found_refs)." refs and ".  count($found_holds)." holds for ". $page;}if(isset($_POST['save'])) {	// save reference Form has been submitted//	$message = "Saved record ".$refkey;}?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Page look up</h3>		<form action="checkpage.php" method="post">			<input type="hidden" name="medi" value="<?php echo $medi; ?>" />		  <table>		    <tr>		      <td>Pages:</td>		      <td>		        <input type="text" name="page" size="10" maxlength="20" value="<?php echo $page; ?>" />		      </td>		    </tr>		      <td >		        <input type="submit" name="getref" value="Get" />			  </td>	  </table>		  </br>		  </br><?php	echo $message."</br>"; ?><?php ?></div>		<div id="data">		<form action="flagauthors.php" method="post">		  <table><?php	if(isset($found_refs))	foreach($found_refs as $rec) echo $rec->code_name()."</br>";	if(isset($found_holds))	foreach($found_holds as $rec) echo $rec->code_name()."</br>";?>		  </table>		</form><?php ?></div><?php include_layout_template('footer.php'); ?>		