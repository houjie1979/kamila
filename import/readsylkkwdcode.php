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
<?php require_once "commonReadSYLK.php" ?>
</body>
</html>