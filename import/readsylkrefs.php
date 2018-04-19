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

$table_name="refs";
$exportSYLK_file="../exportSYLK/Medc_References";
$insertCountAtOnce=500;
$executeSQL=false;
if($executeSQL){
	$db = getDBClass();
}
/*
id
ref_key
type_code
medi_number
journal_code
vol
pages
article_title
year
chem_abs
asca_top
biosis
inspec
phys
energy
scisearch
issue
lang_code
ldate
sdate
rdate
edate
abstractor
comments
see_ref_key
flag_code
abstract
unsol_flag
citations
index_terms

C;Y1;X1;K"58M001"
C;X2;K"1"
C;X3;K"0058"
C;X4;K"ZEPYAA"
C;X5;K"151"
C;X6;K""
C;X7;K"124-43"
C;X8;K"Kernresonanzfluoreszenz von Gammastrahlung in Ir191"
C;X9;K"1958"
C;X10;K"GER"
C;X11;K""
C;X12;K""
C;X13;K""
C;X14;K""
C;X15;K""
C;X16;K""
C;X17;K""
C;X18;K""
C;X19;K""
C;X20;K""
C;X21;K""
C;X22;K"???"
C;X23;K""
C;X24;K""
C;X25;K""
C;X26;K"0"
C;X27;K"N"
C;X28;K""
C;X29;K""
C;X30;K0

 */
$data_reference=array("X0"=>"id","X1"=>"ref_key","X2"=>"type_code","X3"=>"medi_number","X4"=>"journal_code",
"X5"=>"vol","X6"=>"issue","X7"=>"pages","X8"=>"article_title","X9"=>"year","X10"=>"chem_abs",
"X11"=>"asca_top","X12"=>"biosis","X13"=>"inspec","X14"=>"phys","X15"=>"energy","X16"=>"scisearch",
"X17"=>"lang_code","X18"=>"ldate","X19"=>"sdate","X20"=>"rdate",
"X21"=>"edate","X22"=>"abstractor","X23"=>"see_ref_key","X24"=>"comments","X25"=>"flag_code",
"X26"=>"abstract","X27"=>"unsol_flag","X28"=>"citations","X29"=>"index_terms","X30"=>"",
);
$last_colkey="X30";
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
		if($col_key=="X18" || $col_key=="X19" || $col_key=="X20" || $col_key=="X21"){
			if(($col_value != "000000")&&($col_value != "")){//C;X20;K"910422 C;X21;K"850621"
				$mdy=date_parse_from_format("mdy",$col_value);
				$record_data[$col_name]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));
			}else{
				$record_data[$col_name]= null;
			}			
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
