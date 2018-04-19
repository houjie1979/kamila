<?php
include "../common/config.php";
include "../common/db_mysql.class.php";
include "../common/func.inc.php";
include "../common/func_db.inc.php";

// Setting the Content-Type header with charset
header('Content-Type: text/html; charset=utf-8');
$startTime = microtime(true);
set_time_limit(600);
$outputPath = PROJECT_ROOT_DIR. "outputs" . DIRECTORY_SEPARATOR;

$table_name="keyword_code";
$exportSYLK_file="../exportSYLK/Keyword_code";
$insertCountAtOnce=500;
$executeSQL=true;
if($executeSQL){
	$db = getDBClass();
}
/*
id
keyword_code
definition
subject_title
subject_flag
begin_use
not_kw1
not_kw2

C;Y1;X1;K"POT"
C;X2;K"POTTERY"
C;X3;K"Pottery:"
C;X4;K"N"
C;X5;K"6/74"
C;X6;K""
C;X7;K""
 */
$data_reference=array("X0"=>"id","X1"=>"keyword_code","X2"=>"definition","X3"=>"subject_title","X4"=>"subject_flag","X5"=>"begin_use",
"X6"=>"not_kw1","X7"=>"not_kw2");
$last_colkey="X7";
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