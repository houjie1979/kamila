<?phprequire_once('../includes/initialize.php');$tablename = "Languages";if(isset($_GET['new'])) {	// new Form has been submitted  $entry['lang_code'] = strtoupper(trim($_GET['lang_code']));  $entry['language'] = trim($_GET['language']);  $entry['lourn_lang'] = strtoupper(trim($_GET['journ_lang']));  $entry['begin_use'] = trim($_GET['begin_use']); $id="";   if(!$session->allow('enter')) $message = "Can't ";  $message .= "Create new record in " . $tablename . " table"; }if (isset($_POST['edit'])) { // edit Form has been submitted.	$id = $_POST['id']; 	$current_record = Languages::find_rec_by_id($id);	$message = "Editing record " . $id . " of " . $tablename . " table";}if (isset($_POST['save'])) { // save Form has been submitted.  	$entry['lang_code'] = strtoupper(trim($_POST['lang_code']));  	$entry['language'] = strtoupper(trim($_POST['language']));	$entry['journ_lang'] = trim($_POST['journ_lang']);	$entry['begin_use'] = trim($_POST['begin_use']);	if($_POST['id'] != "" && $session->allow('update'))  {	// update existing record		$entry['id'] = $_POST['id'];	  	$current_record[0] = Languages::instantiateit($entry);			if(Languages::authenticate($current_record[0]->lang_code)->id == $current_record[0]->id) { // update record			$current_record[0]->save();				$message = "Record updated in " . $tablename . " table";		}			else $message = "can't update record";				}	else {	// create new record	    if($session->allow('enter')){		unset($entry['id']);  		$current_record[0] = Languages::instantiateit($entry);				if(Languages::authenticate($current_record[0]->lang_code))	$message = "Code already exists in " . $tablename . " table";		else {				$current_record[0]->save();			$message = "Record createded in " . $tablename . " table";		}			}	  else $message = "Can't create new record. No create privilege";	}}if (isset($_POST['delete'])) { // delete Form has been submitted.	if($_POST['id'] != "" && $session->allow('delete')) {		$delrecord = new Languages;		$delrecord->id = $_POST['id'];		$message = "Record ".$delrecord->full_name. " is deleted from " . $tablename . " table.";		$delrecord->delete();		unset($id);	}    else $message = "Record is not deleted. No delete privilege";}?><?php include_layout_template('header.php'); ?><?php echo output_message($message); ?>		<form action="editlanguage.php" method="post">		  <table>		        <input type="hidden" name="id"  value="<?php echo $id; ?>" />		    <tr>		      <td>Lang Code:</td>		      <td>		        <input type="text" name="lang_code" maxlength="3" value="<?php echo htmlentities($current_record[0]->lang_code); ?>" />		      </td>		    </tr>		    <tr>		      <td>Language:</td>		      <td>		        <input type="text" name="language" maxlength="50" value="<?php echo htmlentities($current_record[0]->language); ?>" />		      </td>		    </tr>		    <tr>		      <td>Journal Language:</td>		      <td>		        <input type="text" name="journ_lang" maxlength="50" value="<?php echo htmlentities($current_record[0]->journ_lang); ?>" />		      </td>		    </tr>		    <tr>		      <td>Begin use:</td>		      <td>		        <input type="text" name="begin_use" maxlength="10" value="<?php echo htmlentities($current_record[0]->begin_use); ?>" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="save" value="Save" />		      </td>			  <td colspan="1">		        <input type="submit" name="delete" value="Delete" />		      </td>		    </tr>		  </table>		</form>		<a href="listlanguages.php">Done</a><?php include_layout_template('admin_footer.php'); ?>		