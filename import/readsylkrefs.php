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
"X5"=>"vol","X6"=>"issue","X7"=>"pages","X8"=>"article_title","X9"=>"year","X10"=>"lang_code",
"X11"=>"chem_abs","X12"=>"asca_top","X13"=>"biosis","X14"=>"inspec","X15"=>"phys","X16"=>"energy",
"X17"=>"scisearch","X18"=>"ldate","X19"=>"sdate","X20"=>"rdate",
"X21"=>"edate","X22"=>"abstractor","X23"=>"comments","X24"=>"see_ref_key","X25"=>"flag_code",
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
<?php require_once "commonReadSYLK.php" ?>
</body>
</html>
