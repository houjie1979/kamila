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

$table_name="name_address";
$exportSYLK_file="../exportSYLK/NameAddr";
$insertCountAtOnce=500;
$executeSQL=false;
if($executeSQL){
	$db = getDBClass();
}
/*
id
author_key
title
first_name
last_name
department
institution
street
city
state_code
zip
country_code
initials
phone
fax
email
emp_by_code
medi_number
rank_abb
verification

C;Y1;X1;K"TID002"
C;X2;K"Tidwell"
C;X3;K"J. H."
C;X4;K"Dr."
C;X5;K"Dept. Chem."
C;X6;K"MIT"
C;X7;K""
C;X8;K"Cambridge"
C;X9;K"MA"
C;X10;K"02139"
C;X11;K"USA"
C;X12;K"J.H."
C;X13;K""
C;X14;K""
C;X15;K""
C;X16;K"U"
C;X17;K""
C;X18;K"1"
C;X19;K""
C;X20;K0
 */
$data_reference=array("X0"=>"id","X1"=>"author_key","X2"=>"last_name","X3"=>"first_name","X4"=>"title",
"X5"=>"department","X6"=>"institution","X7"=>"street","X8"=>"city","X9"=>"state_code","X10"=>"zip",
"X11"=>"country_code","X12"=>"initials","X13"=>"phone","X14"=>"fax","X15"=>"email","X16"=>"emp_by_code",
"X17"=>"medi_number","X18"=>"rank_abb","X19"=>"verification","X20"=>"");
$last_colkey="X20";
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