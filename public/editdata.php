<?phprequire_once('../includes/initialize.php');$refkey = strtoupper(trim($_POST['refkey']));if(isset($_POST['getref'])) {    // get data record	if(References::is_in_file($refkey)) {   		if(!empty($_POST['datkey'])) $datakey = trim($_POST['datkey']); else $datakey = 1;		$found_records = Data::get_by_refkey($refkey,$datakey);		$dat = $found_records[0];		if(isset($dat)) $message = "Found data ".$refkey."(".$datakey.")"; 		else { 			// create new data			$message = "Data not found for ".$refkey."(".$datakey.")</br>Creating new";			$dat = new Data;			$dat->ref_key = $refkey;			$dat->dat_key = $datakey;			$dat->abstractor = $session->username;			$dat->medi_number = $session->medi;			$dat->isotope_code = "FE7";			$dat->source = "Rh(IS/Fe)";			$dat->comments = "discussion of previous data";	}	$found_keys = Keywords::get_by_refkey($refkey,$datakey);	$keywordlist = "";	if(isset($found_keys)) foreach($found_keys as $key) {$keywordlist .= $key->keyword_code." ";}		}	else $message = "Reference ".$refkey." does not exist";}if(isset($_POST['save'])) {	// save data Form has been submitted 	if($session->allow('update')) {		$ndat['id'] = trim($_POST['id']);		$ndat['ref_key'] = trim($_POST['ref_key']);		$ndat['dat_key'] = trim($_POST['dat_key']);		$ndat['medi_number'] = trim($_POST['medi_number']);		$ndat['isotope_code'] = strtoupper(trim($_POST['isotope_code']));		$ndat['source'] = trim($_POST['source']);		$ndat['source_temp'] = trim($_POST['source_temp']);		$ndat['absorber'] = trim($_POST['absorber']);		$ndat['absorber_temp'] = trim($_POST['absorber_temp']);		$ndat['isomer_shift'] = trim($_POST['isomer_shift']);		$ndat['quad_split'] = trim($_POST['quad_split']);		$ndat['comments'] = trim($_POST['comments']);		$ndat['abstractor'] = $session->username;		$dat = Data::instantiateit($ndat);		$session->set_medi($dat->medi_number);		if(Isotopes::is_isotope($dat->isotope_code)) {			$keywordlist =  strtoupper(trim($_POST['keywords']));					if($keywordlist != "") $allkeywords = Keycodes::parse_keyw_list($keywordlist);			if(Subjects::subject_present($allkeywords)) {  //   subject key is included				if($dat->save()) $message = "Saved record ".$dat->ref_key."(".$dat->dat_key.")";				$res = Keywords::replace_keywords($dat->ref_key,$dat->dat_key,$allkeywords);				$saved = array_values($res);				$deleted = array_keys($res);				$message .= "</br> Keywords saved - ".$saved[0];				$message .= "</br> Keywords deleted - ".$deleted[0];				$dat->dat_key++;				$refkey = $dat->ref_key;				$datakey = $dat->dat_key;				$found_records = Data::get_by_refkey($refkey,$datakey);				if(count($found_records) == 1) $dat = $found_records[0];				else $message = "New data record ";			}			else $message = "Subject keyword not included";		}		else $message = "Wrong isotope code - ".$dat->isotope_code; 	} 	else $message = "Update is not allowed";}if (isset($_POST['newdata'])) {  // create new data after move hold to ref has been submitted	$newref = $_POST['nrefkey'];	$dat = new Data;	$dat->ref_key = $refkey = $newref;	$found_records = Data::get_by_refkey($refkey,'');	if(isset($found_records)) { 		$lastrec = array_pop($found_records);		$dat->dat_key = $lastrec->dat_key +1;		}	else	$dat->dat_key = 1;	$datakey = $dat->dat_key;	$dat->abstractor = $session->username;	$dat->medi_number = $session->medi;	$dat->isotope_code = "FE7";	$dat->source = "Rh(IS/Fe)";	$dat->comments = "discussion of previous data";	$message = "New data record is created for ".$dat->ref_key;	}if(isset($_POST['delete'])) {	// delete reference Form has been submitted 	if($session->allow('delete')) {		if(isset($_POST['id'])) {			$dat['id'] = $_POST['id'];			$deldat = Data::instantiateit($dat);			if($deldat->delete()) {				$k = 0;				$message = "Data record deleted ";				$refkey = trim($_POST['ref_key']);				$datkey = trim($_POST['dat_key']);				$found_keys = Keywords::get_by_refkey($refkey,$datkey);				foreach($found_keys as $key) if($key->delete()) $k++;				$message .= "</br>".$k." keywords deleted";			}		}		else $message = "Could not delete ";	}	else $message = "Delete is not allowed";}?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Edit Data</h3>		<form action="editdata.php" method="post">			<input type="hidden" name="medi" value="<?php echo $medi; ?>" />		  <table>		    <tr>		      <td>Ref code:</td>		      <td>		        <input type="text" name="refkey" size="6" maxlength="6" value="<?php echo $refkey; ?>" />		      </td>		    </tr>		    <tr>		      <td>Data code:</td>		      <td>		        <input type="text" name="datkey" size="6" maxlength="3" value="<?php echo $datakey; ?>" />		      </td>		    </tr>			<tr>		      <td >		        <input type="submit" name="getref" value="Get" />			  </td>	  </table>		  </br>		  </br><?php	echo $message."</br>"; ?></div>		<div id="data">		<form action="editdata.php" method="post">		  <table>		        <input type="hidden" name="id"  value="<?php echo $dat->id; ?>" />		        <input type="hidden" name="medi_number"  value="<?php echo $dat->medi_number; ?>" />		    <tr>		      <td>Refkey:</td>		      <td>		        <input type="text" name="ref_key" size="6" maxlength="6" value="<?php echo $dat->ref_key; ?>" />		      </td>		      <td>Data key:</td>		      <td>		        <input type="text" name="dat_key" size="1" maxlength="1" value="<?php echo $dat->dat_key; ?>" />		      </td>		      <td>MERDJ number:</td>		      <td><?php echo $dat->medi_number; ?></br>		      <td>Abstractor:</td>		      <td><?php echo $dat->abstractor; ?></td>		    </tr>		    <tr>		      <td>Isotope code:</td>		      <td>		        <input type="text" name="isotope_code" size="3" maxlength="3" value="<?php echo $dat->isotope_code; ?>" />		      </td>		    </tr>		    <tr>		      <td>Source:</td>		      <td colspan="9">		        <input type="text" name="source" size="100" maxlength="150" value="<?php echo $dat->source; ?>" />		      </td>		    </tr>		    <tr>		      <td>Source temp:</td>		      <td>		        <input type="text" name="source_temp" size="6" maxlength="6" value="<?php echo $dat->source_temp; ?>" />		      </td>		    </tr>		    <tr>		      <td>Absorber:</td>		      <td colspan="9">		        <input type="text" name="absorber" size="100" maxlength="150" value="<?php echo $dat->absorber; ?>" />		      </td>		    </tr>		    <tr>		      <td>Absorber temp:</td>		      <td>		        <input type="text" name="absorber_temp" size="6" maxlength="6" value="<?php echo $dat->absorber_temp; ?>" />		      </td>		    </tr>		    <tr>				<td>Isomer shift:</td>		      <td>		        <input type="text" name="isomer_shift" size="8" maxlength="8" value="<?php echo $dat->isomer_shift; ?>" />		      </td>		      <td>Quad split:</td>		      <td>		        <input type="text" name="quad_split" size="8" maxlength="8" value="<?php echo $dat->quad_split; ?>" />		      </td>		    </tr>		    <tr>		      <td>Comments:</td>		      <td colspan="9">		        <input type="text" name="comments" size="100" maxlength="200" value="<?php echo $dat->comments; ?>" />		      </td>		    </tr>		    <tr>		      <td>Keywords:</td>		      <td colspan="9">		        <textarea rows="4" cols="72" name="keywords"><?php echo $keywordlist; ?> </textarea>  		      </td>		    </tr>		    <tr>			  <td colspan="2">		        <input type="submit" name="delete" value="Delete" />		      </td>		      <td colspan="2">		        <input type="submit" name="save" value="Save" />		      </td>		      		      <td colspan="2">			<form action=''> <input type="button" onclick="OpenInNewTab('editabbreviations.php');" value="new abbreviation"/></form>		      </td>		    </tr>		  </table>		</form></div><?php include_layout_template('footer.php'); ?>		