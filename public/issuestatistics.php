<?phprequire_once('../includes/initialize.php');if(isset($_POST['search'])) {	// search Form has been submitted  $enter['medi_number'] = trim($_POST['medi']);  if($enter['medi_number'] != "") {    	$refsql = References::sql_where($enter);	$issue_count_ref = References::count_in_sql_all($refsql);	$issue_count_dat = Data::count_in_sql_all($refsql);	$refsql = References::ref_per_year_issue($enter['medi_number']);	$issue_ref_by_year = References::find_by_full_sql_lim($refsql,100,0);		$message = "Issue ".$enter['medi_number']. " statistics				</br> References: ".$issue_count_ref.				"</br> Data: ".$issue_count_dat."</br>";	$message .= "Year Publications</br>";	foreach($issue_ref_by_year as $record){		$message .= "</br>".$record[0]." ".$record[1];				}								$session->set_medi($enter['medi_number']);  }  else { $message = "No issue entered"; }	}else $enter['medi_number'] = $session->medi;?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Issue Stats</h3>		<form action="issuestatistics.php" method="post">		  <table>		    <tr>		      <td>MERDJ issue:</td>		      <td>		        <input type="text" name="medi" size="8" maxlength="4" value="<?php echo $enter['medi_number']; ?>" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Get statistics" />				</form>			   				</td>    		</tr>		  </table></div>		<div id="data"><?php echo $message."</br>";?></div><?php include_layout_template('footer.php'); ?>		