<?phprequire_once('../includes/initialize.php');$tablename = "Countries";//if(isset($_POST['new'])) {	// new Form has been submittedif(isset($_POST['new'])) {	// new Form has been submitted  $entry['country_abb'] = strtoupper(trim($_POST['country_abb']));  $entry['country'] = trim($_POST['country']);  $id="";	if(!$session->allow('enter')) $message = "Can't ";  $message .= "Create new record in " . $tablename . " table"; }if (isset($_POST['edit'])) { // edit Form has been submitted.	$id = $_POST['id'];	$current_record = Country::find_rec_by_id($id);	$message = "Editing record " . $id . " of " . $tablename . " table";}if (isset($_POST['save'])) { // save Form has been submitted.  $entry['country_abb'] = trim($_POST['country_abb']);  $entry['country'] = trim($_POST['country']);    $entry['region_abb'] = trim($_POST['region_abb']);  	if($_POST['id'] != "" && $session->allow('update'))  {	// update existing record		$entry['id'] = $_POST['id'];	  	$current_record[0] = Country::instantiateit($entry);			if(Country::authenticate($current_record[0]->country_abb)->id == $current_record[0]->id) { // update record			$current_record[0]->save();				$message = "Record updated in " . $tablename . " table";		}			else $message = "can't update record";				}	else {	// create new record		if($session->allow('enter')){		unset($entry['id']);  		$current_record[0] = Country::instantiateit($entry);				if(Country::authenticate($current_record[0]->state_code))	$message = "Code already exists in " . $tablename . " table";		else {				$current_record[0]->save();			$message = "New record createded in " . $tablename . " table";		}			}	 else $message = "Can't create new record. No create privilege";	}}if (isset($_POST['delete'])) { // delete Form has been submitted.	if($_POST['id'] != "" && $session->allow('delete')) {		$delrecord = new Country;		$delrecord->id = $_POST['id'];		$message = "Record ".$delrecord->full_name. " is deleted from " . $tablename . " table.";		$delrecord->delete();		unset($id);	}	else $message = "Record is not deleted. No delete privilege";}?><?php include_layout_template('header.php'); ?><?php echo output_message($message); ?>		<form action="editcountry.php" method="post">		  <table>		        <input type="hidden" name="id"  value="<?php echo $id; ?>" />		    <tr>		      <td>Code:</td>		      <td>		        <input type="text" name="country_abb" size = "4" maxlength="3" value="<?php echo htmlentities($current_record[0]->country_abb); ?>" />		      </td>		    </tr>		    <tr>		      <td>Country:</td>		      <td>		        <input type="text" name="country" size = "15" maxlength="30" value="<?php echo htmlentities($current_record[0]->country); ?>" />		      </td>		    </tr>		    <tr>		      <td>Region code:</td>		      <td>			<input list="regions" name="region_abb"  size = "3" maxlength="2" value="<?php echo htmlentities($current_record[0]->region_abb); ?>" />							      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="save" value="Save" />		      </td>			  <td colspan="1">		        <input type="submit" name="delete" value="Delete" />		      </td>		    </tr>		  </table>		  <datalist id="regions">	<?php $regions = Region::find_all();		asort($regions);		foreach($regions as $reg) echo "<option value = '" . $reg->region_code . "'>";	?>		  </datalist>		</form>		<a href="listcountries.php">Done</a><?php include_layout_template('admin_footer.php'); ?>		