<?phprequire_once('../includes/initialize.php');$per_page=50;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : "";if(isset($_POST['search'])) {	// search Form has been submitted	$country = trim($_POST['country']);	$beg_year = trim($_POST['beg_year']);	$end_year = (isset($_POST['end_year'])) ? trim($_POST['end_year']) : NULL;	$refsql = Names::active_people($country, $beg_year, $end_year);}$total_count = Names::count_in_full_sql($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Names::find_by_full_sql_lim($refsql, $per_page, $pagination->offset());?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Annual publication count</h3>		<form action="a_active_people.php" method="post">		  <table>		    <tr>		      <td>Country code:</td>		      <td>		        <input type="text" name="country" size="3" maxlength="3" value="<?php echo $country; ?>" />		      </td>		    <tr>		    <tr>		      <td>Year begin:</td>		      <td>		        <input type="text" name="beg_year" size="4" maxlength="4" value="<?php echo $beg_year; ?>" />		      </td>		    <tr>		      <td>Year End:</td>		      <td>		        <input type="text" name="end_year" size="4" maxlength="4" value="<?php echo $end_year; ?>" />		      </td>		    </tr>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Get statistics" />				</form>			   				</td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"a_active_people.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"a_active_people.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php //echo $message."</br>";if(isset($found_records))	foreach($found_records as $record){		echo "<table>				<tr>					<td width=650>".$record[0]." ".$record[1]."</td>				</tr>			</table>";		}?></div><?php include_layout_template('footer.php'); ?>		