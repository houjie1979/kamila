<?phprequire_once('../includes/initialize.php');//if (!$session->is_admin()) { redirect_to("../index.php"); }$per_page=20;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : "";if(isset($_POST['search'])) {	// search Form has been submitted  $entry['subject_code'] = trim($_POST['subject_code']);  $entry['definition'] = trim($_POST['definition']);  	$refsql = Subjects::sub_sql_where($entry['subject_code'],$entry['definition']);	$message = "Searched ".$refsql;}$total_count = Subjects::count_in_sql_all($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Subjects::find_by_sql_lim($refsql, $per_page, $pagination->offset());//print_r($found_records);?><?php include_layout_template('header.php'); ?><div id="leftcol">		<form action="listsubjects.php" method="post">		  <table>		    <tr>		      <td>Subject code:</td>		      <td>		        <input type="text" name="subject_code" size="12" maxlength="3" value="" />		      </td>		    </tr>		    <tr>		      <td>Definition:</td>		      <td>		        <input type="text" name="definition" size="12" maxlength="20" value="" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Search" />		      </td>		      </form>		      <td colspan="1">				<form action="editsubject.php" method="post">		        <input type="submit" name="new" value="Create new" />				</form>		      </td>	    	</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"listsubjects.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"listsubjects.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php echo $message."</br>";foreach($found_records as $record){		echo "<form action='editsubject.php' method='post'>			<table>				<tr>					<td width=450>".$record->full_name()."</td>						<td>						<input type='hidden' name='id' value=".						$record->id.">						<input type='submit' name='edit' value='Edit'>					</td>				</tr>			</table>			</form>";		}?></div><?php include_layout_template('footer.php'); ?>		