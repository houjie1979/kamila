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
<?php require_once "commonReadSYLK.php" ?>
</body>
</html>