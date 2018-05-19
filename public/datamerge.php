<?php
require_once('../includes/initialize.php');

$resultMessages = array();
$selectedTables = array("refs", "datas");
$outputPath = SITE_ROOT . DIRECTORY_SEPARATOR . "outputs" . DIRECTORY_SEPARATOR;
$insertCountAtOnce = 500;

if (isset($_POST['tables']) && is_array($_POST['tables'])) {
	$selectedTables = $_POST['tables'];
	$startTime = microtime(true);
	set_time_limit(600);
	
	foreach ($_POST['tables'] as $tableName) {		
		$file = fopen($outputPath . "wad_".$tableName ."_".date("Y-m-d-H.i.s", time()) . ".sql", "w") or die("Unable to open file!");		
		$result = getTableResult($tableName);
		$rowCount = $database -> num_rows($result);		
		array_push($resultMessages, "export " . $rowCount . " rows for table " . $tableName);

		if ($tableName == "abbreviations") {
			fwrite($file, getCreateSQL($tableName) . "\n");
			fwrite($file, getDeleteSql("abbreviations") . "\n");			
			while ($row = $database -> fetch_array($result)) {				
				$abbreviation_code = addslashes($row['abbr_code']);
				$abbreviation_name = addslashes($row['definition']);
				$insertSql = "insert into abbreviations (abbreviation_code,abbreviation_name) values ('$abbreviation_code','$abbreviation_name');";	
				fwrite($file, $insertSql . "\n");
			}		
		} else if ($tableName == "datas") {
			$count = 1;
			$insertSql = "";
			$currentCount = 1;
						
			fwrite($file, getCreateSQL($tableName) . "\n");
			fwrite($file, getDeleteSql("data") . "\n");			
			while ($row = $database -> fetch_array($result)) {
				if($count == 1) {
					$insertSql = "insert into data (id,refcode,isocode,source,absorber,stemp,atemp,ishift,qsplit,ecomments,keywords) values \n";
				}
				
				$id = addslashes($row['id']);
				$refcode = addslashes($row['ref_key']);
				$isocode = addslashes($row['isotope_code']);
				$source = addslashes($row['source']);
				$absorber = addslashes($row['absorber']);
				$stemp = addslashes($row['source_temp']);
				$stemp = is_numeric(trim($stemp)) ? $stemp : "NULL";
				$atemp = addslashes($row['absorber_temp']);
				$atemp = is_numeric(trim($atemp)) ? $atemp : "NULL";
				$ishift = addslashes($row['isomer_shift']);
				$ishift = is_numeric(trim($ishift)) ? $ishift : "NULL";
				$qsplit = addslashes($row['quad_split']);
				$qsplit = is_numeric(trim($qsplit)) ? $qsplit : "NULL";
				$ecomments = addslashes($row['comments']);
				$keywords = addslashes($row['keywords']);
				$insertSql .= "($id,'$refcode','$isocode','$source','$absorber',$stemp,$atemp,$ishift,$qsplit,'$ecomments','$keywords')";
				
				if ($currentCount == $rowCount) {
					$insertSql .= ";\n";
					fwrite($file, $insertSql);
				} else if ($count == $insertCountAtOnce) {
					$insertSql .= ";\n";
					fwrite($file, $insertSql);	
					// reset
					$count = 1;
					$insertSql = "";
				} else {
					$insertSql .= ",\n";					
					$count++;
				}	
				$currentCount++;
			}
		} else if ($tableName == "keyword_code") {
			fwrite($file, getCreateSQL($tableName) . "\n");
			fwrite($file, getDeleteSql("keywords") . "\n");			
			while ($row = $database -> fetch_array($result)) {
				$keyword_code = addslashes($row['keyword_code']);
				$keyword_name = addslashes($row['definition']);
				$insertSql = "insert into keywords (keyword_code,keyword_name) values ('$keyword_code','$keyword_name');";
				fwrite($file, $insertSql . "\n");
			}
		} else if ($tableName == "isotope_code") {
			fwrite($file, getCreateSQL($tableName) . "\n");
			fwrite($file, getDeleteSql("isotopes") . "\n");			
			while ($row = $database -> fetch_array($result)) {
				$isotope_code = addslashes($row['isotope_code']);
				$isotope_name = addslashes($row['topic_abb']);
				$energy = addslashes($row['energy']);
				$insertSql = "insert into isotopes (isotope_code,isotope_name,energy) values ('$isotope_code','$isotope_name',$energy);";
				fwrite($file, $insertSql . "\n");
			}
		} else if ($tableName == "refs") {
			$count = 1;
			$insertSql = "";
			$currentCount = 1;
			
			fwrite($file, getCreateSQL($tableName) . "\n");
			fwrite($file, getDeleteSql("refs") . "\n");
			while ($row = $database -> fetch_array($result)) {
				if($count == 1) {
					$insertSql = "insert into refs (refcode,authors,journal,vol,issue,pages,year,title,lang,keywords) values \n";
				}
				
				$refcode = addslashes($row['ref_key']);
// 				$authors = addslashes($row['authors']);
				$authors = addslashes(getAuthors($row['ref_key']));
				$journal = addslashes($row['title_abb']);
				$vol = addslashes($row['vol']);
				$issue = addslashes($row['issue']);
				$pages = addslashes($row['pages']);
				$year = addslashes($row['year']);
				$title = addslashes($row['article_title']);
				$lang = addslashes($row['lang_code']);
				$keywords = addslashes($row['keywords']);
				$insertSql .= "('$refcode','$authors','$journal','$vol','$issue','$pages',$year,'$title','$lang','$keywords')";				
				
				if ($currentCount == $rowCount) {
					$insertSql .= ";\n";
					fwrite($file, $insertSql);
				} else if ($count == $insertCountAtOnce) {
					$insertSql .= ";\n";
					fwrite($file, $insertSql);
					// reset
					$count = 1;
					$insertSql = "";
				} else {
					$insertSql .= ",\n";					
					$count++;
				}
				$currentCount++;
			}
		}
		fclose($file);
	}
	array_push($resultMessages, "Total time: " . round(microtime(true) - $startTime) . " secounds");
}

function getTableResult($tableName) {
	global $database;
	$result = null;
	if (!empty($tableName)) {
		$sql = "select * from " . $tableName;
		if ($tableName == "datas") {
			$sql  = "select id,ref_key,isotope_code,source,absorber,source_temp,absorber_temp,isomer_shift,quad_split,comments,";
			$sql .= "(select group_concat(keyword_code order by keyword_code separator ' ') from keywords kw where kw.ref_key=d.ref_key and kw.data_key=d.dat_key) as keywords ";
			$sql .= "from datas d group by concat(d.ref_key,d.dat_key) order by d.ref_key, d.dat_key";
		} else if ($tableName == "refs") {	
			$sql  = "select r.ref_key,j.title_abb,r.vol,r.issue,r.pages,r.year,r.article_title,r.lang_code, ";
			$sql .= "(select group_concat(distinct(keyword_code) order by keyword_code separator ' ') from keywords kw where kw.ref_key=r.ref_key) as keywords ";
			$sql .= "from refs r left join journals j on r.journal_code=j.journal_code order by r.ref_key";
		} else if ($tableName == "abbreviations") {
			$sql .= " order by abbr_code";
		} else if ($tableName == "keyword_code") {			
			$sql = "select keyword_code,definition from keyword_code group by keyword_code order by keyword_code";
		} else if ($tableName == "isotope_code") {
			$sql .= " order by isotope_code";
		}
		$result = $database -> query($sql);
	}
	return $result;
}

function getAuthors($ref_key) {
	global $database;
	$authors = "";
	if (!empty($ref_key)) {
		$sql = "select group_concat(author_name separator ', ') as authors from(select a.ref_key,concat(na.initials,na.last_name,if(a.cauthor_flag='Y','*','')) as author_name from authors a left join name_address na on a.author_key=na.author_key where a.ref_key = '$ref_key') as auth";
		$result = $database -> query($sql);
		$row= $database -> fetch_array($result);
		if(!empty($row['authors'])) {
			$authors = $row['authors'];
		}
	}
	return $authors;
}

function getDeleteSql($tableName) {
	return "delete from " . $tableName . ";";
}

function getCreateSQL($tableName){
	$sql = "";
	if ($tableName == "abbreviations") {
		$sql = "CREATE TABLE IF NOT EXISTS `abbreviations` (
		  `abbreviation_code` varchar(6) NOT NULL,
		  `abbreviation_name` varchar(200) NOT NULL
		) ENGINE=MyISAM;";
	} else if ($tableName==="refs") {
		$sql = "CREATE TABLE IF NOT EXISTS `refs` (
		  `refcode` char(6) CHARACTER SET utf8 NOT NULL,
		  `authors` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `journal` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `vol` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `issue` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `pages` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `year` smallint(4) NOT NULL,
		  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `keywords` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
		) ENGINE=MyISAM;";
	} else if($tableName==="datas") {
		$sql="CREATE TABLE IF NOT EXISTS `data` (
		  `id` int(11) NOT NULL,
		  `refcode` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `isocode` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `source` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `absorber` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `stemp` float DEFAULT NULL,
		  `atemp` float DEFAULT NULL,
		  `ishift` float DEFAULT NULL,
		  `qsplit` float DEFAULT NULL,
		  `ecomments` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `keywords` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
		) ENGINE=MyISAM;";
	} else if ($tableName==="isotope_code") {
		$sql = "CREATE TABLE IF NOT EXISTS `isotopes` (
		  `isotope_code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `isotope_name` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `energy` float NOT NULL
		) ENGINE=MyISAM;";
	} else if ($tableName==="keyword_code") {
		$sql = "CREATE TABLE IF NOT EXISTS `keywords` (
		  `keyword_code` varchar(3) NOT NULL,
		  `keyword_name` varchar(200) NOT NULL
		) ENGINE=MyISAM;";
	}
	return $sql;
}

?>
<?php include_layout_template('header.php'); ?>
<style>
.pageLoading{
  	z-index: 10000;
	position:fixed;
	top:0;
	left:0;
	width:100%;
	height:100%;
	background-color:#eee;
	-moz-opacity:0.4;
	opacity:.40;
	filter:alpha(opacity=40);
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	main_page_init();
});

function main_page_init(){
	$("#exportSubmit").click(function(){
// 		$("#data").html("Running, Please Wait...");
		$("#resultMessages").html("");
		$("#pageLoading").show();		
		$("#exportSubmit").attr("disabled",true);
		$("#dataMergeForm").submit();
	});
}
</script>
<div id="leftcol">
	<h3>Data export</h3>
	<form action="datamerge.php" id="dataMergeForm" method="post">
		<table>			
			<tr><td><input type="checkbox" name="tables[]" value="refs" <?php if(in_array("refs", $selectedTables)) echo "checked=\"checked\""?>/></td><td>refs</td></tr>
			<tr><td><input type="checkbox" name="tables[]" value="datas" <?php if(in_array("datas", $selectedTables)) echo "checked=\"checked\""?>/></td><td>datas</td></tr>
			<tr><td><input type="checkbox" name="tables[]" value="abbreviations" <?php if(in_array("abbreviations", $selectedTables)) echo "checked=\"checked\""?>/></td><td>abbreviations</td></tr>
			<tr><td><input type="checkbox" name="tables[]" value="isotope_code" <?php if(in_array("isotope_code", $selectedTables)) echo "checked=\"checked\""?>/></td><td>isotope_code</td></tr>
			<tr><td><input type="checkbox" name="tables[]" value="keyword_code" <?php if(in_array("keyword_code", $selectedTables)) echo "checked=\"checked\""?>/></td><td>keyword_code</td></tr>			
			<tr><td></td><td><input id="exportSubmit" type="button" value="Export"/></td></tr>
		</table>
	</form>
</div>
<div id="data">
	<div id="pageLoading" class="pageLoading" style="display:none"><div align="center" style="margin-top:10%"><h1><image style='vertical-align:middle;' src='images/animated-progress.gif'/>Running, Please Wait...<h1></div></div>
	<div id="resultMessages">
	<?php 
		foreach ($resultMessages as $message) {
			echo $message . "<br/>";
		}
	?>
	</div>
</div>
<div id="searchblock" style="clear: both;"></div>
<?php include_layout_template('footer.php'); ?>