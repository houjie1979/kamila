<?phprequire_once('../includes/initialize.php');//if (!$session->is_admin()) { redirect_to("../index.php"); }$per_page=20;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : " ORDER BY abbr_code ";if(isset($_GET['search'])) {	// search Form has been submitted  $entry['abbr_code'] = trim($_GET['abbr_code']);  $entry['definition'] = trim($_GET['definition']);    $entry['medi_num'] = trim($_GET['medi_num']);  	$refsql = Abbreviations::abb_sql_where($entry['abbr_code'],$entry['definition'],$entry['medi_num']);	$message = "Searched ".$refsql;}if(isset($_GET['check'])) {  // check button pressed  $entry['abbr_code'] = trim($_GET['abbr_code']);  $entry['definition'] = trim($_GET['definition']);  $refsql = Abbreviations::abb_sql_or($entry['abbr_code'],$entry['definition']); $message = " Checked " .$refsql; }$total_count = Abbreviations::count_in_sql_all($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Abbreviations::find_by_sql_lim($refsql, $per_page, $pagination->offset());//print_r($found_records);echo $_GET['check'] . $_GET['abbr_code'];?><?php include_layout_template('header.php'); ?><div id="leftcol">		<form action="listabbreviations.php" method="get">		  <table>		    <tr>		      <td>Abbr code:</td>		      <td>		        <input type="text" name="abbr_code" size="10" maxlength="6" value="" />		      </td>		    </tr>		    <tr>		      <td>Definition:</td>		      <td>		        <input type="text" name="definition" size="12" maxlength="40" value="" />		      </td>		    </tr>		    <tr>		      <td>Medi number:</td>		      <td>		        <input type="text" name="medi_num" size="4" maxlength="4" value="" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Search" />				</form>			   				</td>			   <td colspan="1">				<form action="editabbreviations.php" method="get">		        <input type="submit" name="new" value="Create new" />				</form>			  </td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"listabbreviations.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"listabbreviations.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php echo $message."</br>";foreach($found_records as $record){		echo "<form action='editabbreviations.php' method='post'>			<table>				<tr>					<td width=750>".$record->code_name()."</td>						<td>						<input type='hidden' name='id' value=".						$record->id.">						<input type='submit' name='edit' value='Edit'>					</td>				</tr>			</table>			</form>";		}?></div><?php include_layout_template('footer.php'); ?>		