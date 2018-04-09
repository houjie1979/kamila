<?phprequire_once('../includes/initialize.php');//if (!$session->is_admin()) { redirect_to("../index.php"); }$per_page=20;$page =!empty($_GET['page']) ? (int)$_GET['page'] : 1;$refsql =!empty($_GET['sql']) ? $_GET['sql'] : " ORDER BY book_code ";if(isset($_POST['search'])) {	// search Form has been submitted  $entry['book_code'] = trim($_POST['book_code']);  $entry['author'] = trim($_POST['author']);  	$refsql = Books::books_sql_where($entry['book_code'],$entry['author']);	$message = "Searched ".$refsql;}$total_count = Books::count_in_sql_all($refsql);$pagination = new Pagination($page, $per_page, $total_count);$found_records = Books::find_by_sql_lim($refsql, $per_page, $pagination->offset());//print_r($found_records);?><?php include_layout_template('header.php'); ?><div id="leftcol">		<form action="listbooks.php" method="post">		  <table>		    <tr>		      <td>Book code:</td>		      <td>		        <input type="text" name="book_code" size="12" maxlength="6" value="" />		      </td>		    </tr>		    <tr>		      <td>Author:</td>		      <td>		        <input type="text" name="author" size="12" maxlength="20" value="" />		      </td>		    </tr>		    <tr>		      <td colspan="1">		        <input type="submit" name="search" value="Search" />				</form>			   				</td>			   <td colspan="1">				<form action="editbook.php" method="post">		        <input type="submit" name="new" value="Create new" />				</form>			  </td>    		</tr>		  </table><?php 	$current_page = $pagination->next_page()-1;	echo "Found - ". $total_count . "<br/>Page ". $current_page ." out of ". $pagination->total_pages() . "<br/>";	if($pagination->total_pages() > 1) {			if($pagination->has_previous_page()){			echo " <a href=\"listbooks.php?page=";			echo $pagination->previous_page();			echo "&sql=".$refsql."\">&laquo; Previous</a> ";		}			if($pagination->has_next_page()){			echo " <a href=\"listbooks.php?page=";			echo $pagination->next_page();			echo "&sql=".$refsql."\">Next &raquo; </a> ";		}		}?></div>		<div id="data"><?php echo $message."</br>";foreach($found_records as $record){		echo "<form action='editbook.php' method='post'>			<table>				<tr>					<td width=650>".$record->full_name()."</td>						<td>						<input type='hidden' name='id' value=".						$record->id.">						<input type='submit' name='edit' value='Edit'>					</td>				</tr>			</table>			</form>";		}?></div><?php include_layout_template('footer.php'); ?>		