<?phprequire_once('../includes/initialize.php');if(isset($_POST['getname'])) {	// get name Form has been submitted    $name = trim($_POST['name']);	$found_records = Names::get_like_name($name);	$message = "Found ". count($found_records). " authors for ". $name;}if(isset($_POST['save'])) {	// save reference Form has been submitted	$message = "Saved record ".$refkey;}?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Name check</h3>		<form action="checkname.php" method="post">			<input type="hidden" name="medi" value="<?php echo $medi; ?>" />		  <table>		    <tr>		      <td>Name:</td>		      <td>		        <input type="text" name="name" size="10" maxlength="15" value="<?php echo $name; ?>" />		      </td>		    </tr>			<tr>		      <td >		        <input type="submit" name="getname" value="Get" />			  </td>		</table>		</form>		  </br>		  </br><?php	echo $message."</br>"; ?><?php ?></div>		<div id="data"> <?php	echo "<table cellpadding = 2>";	if(isset($found_records)) {		echo "<tr><th>author key</th><th>first name</th><th>					last name</th><th>institution</th><th>					country</th><th>email</th></tr>"; 		foreach($found_records as $rec) {		echo  "<form action='editname.php' method='post'";    		echo "<tr><td>".$rec->author_key."</td><td>".$rec->first_name."</td><td>".					$rec->last_name."</td><td>".$rec->institution."</td><td>".					$rec->country_code."</td><td>".$rec->email."</td><td>".					"<input type='hidden' name='id' value='".$rec->id."'/>".					"<input type='submit' name='edit' value='Edit here'/>					</td></form><td>".					"<form><input type='button' onclick=\"OpenInNewTab('editname.php?id=".$rec->id.					"');\" value='ed in win'/></form></td></tr>";						}	}	    echo "</table>";	?></div><?php include_layout_template('footer.php'); ?>		