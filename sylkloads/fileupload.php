<?phprequire_once('../includes/initialize.php');header('Content-Type: text/html; charset=utf-8');//if (!$session->is_admin()) { redirect_to("../index.php"); }if(isset($_FILES['inputfile']['name'])){	$inputfile = UPLOAD_DIR.DS.$_FILES['inputfile']['name'];	if(move_uploaded_file($_FILES['inputfile']['tmp_name'],$inputfile)) {		$handle = fopen($inputfile, "r");		//if(!isset($handle)) echo "can not open file";		if(!$handle) {echo "can not open file"; exit;}    		while (($buffer = fgets($handle)) !== false) {		$rec = explode(chr(9), $buffer);	if(Isotopes::is_isotope($rec[1]) != 0) {//readdata		$record['refcode'] = $rec[0];		$record['isocode'] = $rec[1];		$record['source'] = $rec[2];		$record['stemp'] = $rec[3];		$record['absorber'] = $rec[4];		$record['atemp'] = $rec[5];		$record['ishift'] = $rec[6];		$record['qsplit'] = $rec[7];		$record['ecomments'] = $rec[8];		$record['keywords'] = $rec[9];		$dat = Data::instantiateit($record);		$dat->create();		}		else {			//read refs		$record['refcode'] = $rec[0];		$record['authors'] = $rec[1];		$record['journal'] = $rec[2];		$record['vol'] = $rec[3];		$record['issue'] = $rec[4];		$record['pages'] = $rec[5];		$record['year'] = $rec[6];		$record['title'] = $rec[7];		$record['lang'] = $rec[8];		$record['keywords'] = $rec[9];		$ref = References::instantiateit($record);		if(!$ref->record_exists()) $ref->create(); 		}			}	// of while			fclose($handle);				unset($_FILES['inputfile']['name']);			}  // if move_uploaded			else{echo "upload file not found.";}		}	// if is_set	?><?php include_layout_template('admin_header.php'); ?><ul id="navlinks">	<li><a href="../index.php"> User menu</a> </li>	<li><a href="listusers.php">List users</a></li>	<li><a href="edituser.php?newuser='new'" >New user</a></li>	<li><a href="fileupload.php" >File upload</a></li></ul>	<form action="fileupload.php" enctype="multipart/form-data" method="post">		<input type="hidden" name="MAX_FILE_SIZE" value="50000" />		<p> File name:</p>		<input type="file" name="inputfile" size="20" />		<input type="submit" value="Upload" />						</form><?php include_layout_template('admin_footer.php'); ?>		