<?phprequire_once('../includes/initialize.php');//if (!$session->is_admin()) { redirect_to("../index.php"); }$per_page=20;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : " ORDER BY state ";if(isset($_POST['search'])) {	// search Form has been submitted  $entry['state_code'] = trim($_POST['state_code']);  $entry['state'] = trim($_POST['state']);  	$refsql = State::country_sql_where($entry['state_code'],$entry['state']);	$message = "Searched ".$refsql;}$total_count = State::count_in_sql_all($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = State::find_by_sql_lim($refsql, $per_page, $pagination->offset());//print_r($found_records);?><?php include_layout_template('header.php'); ?><div id="leftcol">		<form action="liststate.php" method="post">		  <table>		    <tr>		      <td>State code:</td>		      <td>		        <input type="text" name="state_code" size="12" maxlength="3" value="" />		      </td>		    </tr>		    <tr>		      <td>State:</td>		      <td>		        <input type="text" name="state" size="12" maxlength="20" value="" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Search" />				</form>			   				</td>			   <td colspan="1">				<form action="editstate.php" method="post">		        <input type="submit" name="new" value="Create new" />				</form>			  </td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"liststates.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"liststates.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php echo $message."</br>";foreach($found_records as $record){		echo "<form action='editstate.php' method='post'>			<table>				<tr>					<td width=350>".$record->full_name()."</td>						<td>						<input type='hidden' name='id' value=".						$record->id.">						<input type='submit' name='edit' value='Edit'>					</td>				</tr>			</table>			</form>";		}?></div><?php include_layout_template('footer.php'); ?>		