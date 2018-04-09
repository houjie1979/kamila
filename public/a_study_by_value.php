<?phprequire_once('../includes/initialize.php');$per_page=60;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : "";if(isset($_POST['search'])) {	// search Form has been submitted	$isot = (isset($_POST['isot'])) ? strtoupper(trim($_POST['isot'])) : NULL;	$minis = (isset($_POST['minis'])) ? trim($_POST['minis']) : NULL;	$maxis = (isset($_POST['maxis'])) ? trim($_POST['maxis']) : NULL;	$minqs = ($_POST['minqs'] != '') ? trim($_POST['minqs']) : NULL;	$maxqs = ($_POST['maxqs'] != '') ? trim($_POST['maxqs']) : NULL;		$refsql = Data::data_by_value($isot, $minis, $maxis, $minqs, $maxqs);//	$message = $refsql;}$total_count = Data::count_in_full_sql($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Data::find_by_full_sql_lim($refsql, $per_page, $pagination->offset());?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Study count</h3>		<form action="a_study_by_value.php" method="post">		  <table>		    <tr>		      <td>Isotope code:</td>		      <td>		        <input type="text" name="isot" size="5" maxlength="3" value="<?php echo $isot; ?>" />		      </td>		    </tr>		    <tr>		      <td>Min IS:</td>		      <td>		        <input type="text" name="minis" size="5" maxlength="5" value="<?php echo $minis; ?>" />		      </td>		    </tr>		    <tr>		      <td>Max IS:</td>		      <td>		        <input type="text" name="maxis" size="5" maxlength="5" value="<?php echo $maxis; ?>" />		      </td>		    </tr>		    <tr>		      <td>Min QS:</td>		      <td>		        <input type="text" name="minqs" size="5" maxlength="5" value="<?php echo $minqs; ?>" />		      </td>		    </tr>		    <tr>		      <td>Max QS:</td>		      <td>		        <input type="text" name="maxqs" size="5" maxlength="5" value="<?php echo $maxqs; ?>" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Get data" />				</form>			   				</td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"a_study_by_value.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"a_study_by_value.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php //echo $message."</br>";if(isset($found_records))	foreach($found_records as $record){		echo "<table>				<tr>		<td width=650>".$record[0]." ".$record[1]." ".$record[2]." ".$record[3]." ".$record[4]." ".		$record[5]." ".$record[6]." ".$record[7]." ".$record[8]." ".$record[9]." "."</td>				</tr>			</table>";		}?></div><?php include_layout_template('footer.php'); ?>		