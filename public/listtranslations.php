<?phprequire_once('../includes/initialize.php');//if (!$session->is_admin()) { redirect_to("../index.php"); }$per_page=20;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : " ORDER BY ref_key ";if(isset($_POST['search'])) {	// search Form has been submitted  $entry['ref_key'] = trim($_POST['ref_key']);  $entry['issue'] = trim($_POST['issue']);  	$refsql = Translations::trans_sql_where($entry['ref_key'],$entry['issue']);	$message = "Searched ".$refsql;}$total_count = Translations::count_in_sql_all($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Translations::find_by_sql_lim($refsql, $per_page, $pagination->offset());//print_r($found_records);?><?php include_layout_template('header.php'); ?><div id="leftcol">		<form action="listtranslations.php" method="post">		  <table>		    <tr>		      <td>Reference key:</td>		      <td>		        <input type="text" name="ref_key" size="12" maxlength="6" value="" />		      </td>		    </tr>		    <tr>		      <td>Issue:</td>		      <td>		        <input type="text" name="issue" size="12" maxlength="4" value="" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Search" />				</form>			   				</td>			   <td colspan="1">				<form action="edittranslation.php" method="post">		        <input type="submit" name="new" value="Create new" />				</form>			  </td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"listtranslations.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"listtranslations.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php echo $message."</br>";foreach($found_records as $record){		echo "<form action='edittranslation.php' method='post'>			<table>				<tr>					<td width=350>".$record->code_name()."</td>						<td>						<input type='hidden' name='id' value=".						$record->id.">						<input type='submit' name='edit' value='Edit'>					</td>				</tr>			</table>			</form>";		}?></div><?php include_layout_template('footer.php'); ?>		