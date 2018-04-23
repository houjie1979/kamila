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

$table_name="abbreviations";
$exportSYLK_file="../exportSYLK/Abbreviations";
$insertCountAtOnce=500;
$executeSQL=true;
/*
C;Y1;X1;K"1103"
C;X2;K"(12-4"
C;X3;K"cyclic 12-crown-5 ether"
 */
$data_reference=array("X0"=>"id","X1"=>"medi_num","X2"=>"abbr_code","X3"=>"definition");
$last_colkey="X3";
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