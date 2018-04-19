<?php
include "../common/config.php";
include "../common/db_mysql.class.php";
include "../common/func.inc.php";
include "../common/func_db.inc.php";

// Setting the Content-Type header with charset
header('Content-Type: text/html; charset=utf-8');
//$db = getDBClass();
$startTime = microtime(true);
set_time_limit(600);
$outputPath = PROJECT_ROOT_DIR. "outputs" . DIRECTORY_SEPARATOR;

$table_name="journals";
$exportSYLK_file="../exportSYLK/Journals";
$insertCountAtOnce=500;
$executeSQL=true;
if($executeSQL){
	$db = getDBClass();
}
/*
id
journal_code
title_abb
title
lang_code
lib_congress
trans_code
comments
unca_flag
library_code
flag_code
year_discontinued
old_code
new_code
year_started
first_vol
last_vol
issn_code

C;Y1;X1;K"JPASBN"
C;X2;K"Middle East Tech. Univ. Pure Appl. Sci."
C;X3;K"Middle East Tech. Univ. Pure Appl. Sci."
C;X4;K"VAR"
C;X5;K""
C;X6;K""
C;X7;K"vol 15 in 1982"
C;X8;K"N"
C;X9;K"?"
C;X10;K""
C;X11;K""
C;X12;K""
C;X13;K""
C;X14;K""
C;X15;K""
C;X16;K""
C;X17;K""
 */
$data_reference=array("X0"=>"id","X1"=>"journal_code","X2"=>"title_abb","X3"=>"title","X4"=>"lang_code",
"X5"=>"lib_congress","X6"=>"trans_code","X7"=>"comments","X8"=>"unca_flag","X9"=>"library_code","X10"=>"flag_code",
"X11"=>"year_discontinued","X12"=>"old_code","X13"=>"new_code","X14"=>"year_started",
"X15"=>"first_vol","X16"=>"last_vol","X17"=>"issn_code",);
$last_colkey="X17";
?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Reading from file <?php echo $table_name ?>></title>
</head>

<body>
<?php
echo"trying to write to kamila database table {$table_name}<br/>";
$previous_ref="";
$handle = fopen("$exportSYLK_file", "r");
//if(!isset($handle)) echo "can not open file";
if(!$handle) {echo "can not open file"; exit;}
echo "Start time:".date("Y-m-d-H.i.s", time())."<br>";
if($executeSQL){
	echo "clear {$table_name}<br>";
	commonClear($table_name,$db);
}
echo "scan {$table_name} data<br>";
$outputfile = fopen($outputPath . "kamila_".$table_name . date("Y-m-d-H.i.s", time()) . ".sql", "w") or die("Unable to open file!");
//clear table
$fileLine=commonDeleteSQL($table_name,null);	
fwrite($outputfile, $fileLine. ";\n");
$recordCount=0;
$fileLine="";
while (($buffer = fgetCRstr($handle)) !== false) {    	
	list($pos1,$pos2,$pos3,$pos4) = explode(";", $buffer);
	if( $pos1 == "C" )
	{ 
		$col_key="";
		$col_value="";
		if(substr($pos2,0,1)=="Y"){
			$recno = substr($pos2,1);
			$record_data=array();
			$record_data[$data_reference["X0"]]=$recno;	
			$col_key=$pos3;
			$col_value=inquotes($pos4);		
		}else{
			$col_key=$pos2;
			$col_value=inquotes($pos3);
		}
		$col_name=$data_reference[$col_key];
		if(!empty($col_name)){
			$record_data[$col_name]=$col_value;
		}
		if($col_key==$last_colkey){
			$record_data=myaddslashes($record_data);
			if($executeSQL){
				$result=commonInsert($table_name,$db, $record_data);
			}
//			echo $result["msg"]."<br>";
			$insertPair=getInsertPair($record_data);
			$recordCount++;
			if($recordCount%$insertCountAtOnce==1){
				$fileLine="insert into {$table_name} ({$insertPair[0]}) values \n";	
				$fileLine.="($insertPair[1])";			
			}else if($recordCount%$insertCountAtOnce==0){
				$fileLine.=",\n($insertPair[1]);\n";
				fwrite($outputfile, $fileLine);	
			}else{
				$fileLine.=",\n($insertPair[1])";
			}						
		}
	}	// of if pos1==C
}	//of while
$fileLine.=";\n";
fwrite($outputfile, $fileLine);	
echo $recno;
fclose($handle);
fclose($outputfile);
if($executeSQL){
	$db->close();
}
echo "<br>"."End time:".date("Y-m-d-H.i.s", time());
?>
</body>
</html>