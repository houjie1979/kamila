<?phprequire_once("../includes/initialize.php");if (isset($_POST['backup'])) {	  	$table = $_POST['table']; 	if($table == "Database") backup_tables();	else backup_tables($table);}	?><?php include_layout_template('header.php'); ?><div id="leftcol"><h3>Backup tables</h3></div>	<div id="data">		<form action="backup.php" method="post">		<table>		    <tr><td><input type="radio" name="table"  value="Database" checked /> Database </br></td></tr> 		    <tr><td><input type="radio" name="table"  value="refs"  /> References </br></td></tr>		    <tr><td><input type="radio" name="table"  value="datas"  /> Data </br></td></tr>		        <tr><td><input type="radio" name="table"  value="authors"  /> Authors </br></td></tr>		        <tr><td><input type="radio" name="table"  value="keywords"  /> Keywords </br></td></tr>		        <tr><td><input type="radio" name="table"  value="name_address"  /> Names-Address </br></td></tr>		        <tr><td><input type="radio" name="table"  value="holdings"  /> Holdings </br></td></tr>		        <tr><td><input type="radio" name="table"  value="journals"  /> Journals </br></td></tr>		        <tr><td><input type="radio" name="table"  value="books"  /> Books </br></td></tr>		        <tr><td><input type="radio" name="table"  value="translations"  /> Translations </br></td></tr>		        <tr><td><input type="radio" name="table"  value="abbreviations"  /> Abbreviations </br></td></tr>		        <tr><td><input type="radio" name="table"  value="country_region"  /> Countries </br></td></tr>		        <tr><td><input type="radio" name="table"  value="region_code"  /> Regions </br></td></tr>		        <tr><td><input type="radio" name="table"  value="isotope_code"  /> Isotopes </br></td></tr>		        <tr><td><input type="radio" name="table"  value="keyword_code"  /> Keyword codes </br></td></tr>		        <tr><td><input type="radio" name="table"  value="lang_code"  /> Languages </br></td></tr>		        <tr><td><input type="radio" name="table"  value="state_code"  /> States </br></td></tr>		        <tr><td><input type="radio" name="table"  value="subject_code"  /> Subjects </br></td></tr>		        <tr><td><input type="radio" name="table"  value="rank_code"  /> Ranks </br></td></tr>		        <tr><td><input type="radio" name="table"  value="flag_code"  /> Flags </br></td></tr>		        <tr><td><input type="radio" name="table"  value="library_code"  /> Libraries </br></td></tr>		        <tr><td><input type="radio" name="table"  value="sources"  /> Sources </br></td></tr>		        <tr><td><input type="radio" name="table"  value="users"  /> Users </br></td></tr>		        <tr><td><input type="submit" name="backup" value="Backup" /></td></tr>		</table>		</form></div><div id="searchblock" style="clear: both;"></div><?php include_layout_template('footer.php'); ?>