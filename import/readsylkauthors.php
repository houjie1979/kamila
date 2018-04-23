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

$table_name="authors";
$exportSYLK_file="../exportSYLK/Authors";
$insertCountAtOnce=500;
$executeSQL=false;
/*
id
ref_key
author_key
medi_num
cauthor_flag
author_position

C;Y1;X1;K"88F034"
C;X2;K"GON001"
C;X3;K"1305"
C;X4;K"N"
C;X5;K5
C;X6;K0
 */
$data_reference=array("X0"=>"id","X1"=>"ref_key","X2"=>"author_key","X3"=>"medi_num","X4"=>"cauthor_flag",
"X5"=>"author_position","X6"=>"");
$last_colkey="X6";
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