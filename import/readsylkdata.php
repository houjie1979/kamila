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

$table_name="datas";
$exportSYLK_file="../exportSYLK/Data";
$insertCountAtOnce=500;
$executeSQL=false;
if($executeSQL){
	$db = getDBClass();
}
/*
C;Y1;X1;K"95R015"
C;X2;K1
C;X3;K"1902"
C;X4;K"FE7"
C;X5;K"Pd(IS/Fe)"
C;X6;K""
C;X7;K"clay catalyst"
C;X8;K"v"
C;X9;K"--"
C;X10;K"--"
C;X11;K"Fe phases in clay catalysts, 4.2<T<300K"
C;X12;K"AMK"
C;X13;K""
C;X14;K0
 */
$data_reference=array("X0"=>"id","X1"=>"ref_key","X2"=>"dat_key","X3"=>"medi_number","X4"=>"isotope_code",
"X5"=>"source","X6"=>"source_temp","X7"=>"absorber","X8"=>"absorber_temp","X9"=>"isomer_shift","X10"=>"quad_split",
"X11"=>"comments","X12"=>"abstractor","X13"=>"prefix","X14"=>"");
$last_colkey="X14";
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