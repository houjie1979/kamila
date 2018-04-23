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

$table_name="isotope_code";
$exportSYLK_file="../exportSYLK/Isotope_Code";
$insertCountAtOnce=500;
$executeSQL=true;
/*
id
isotope_code
topic_abb
subject_code
energy
subject_flag

C;Y1;X1;K"PT5"
C;X2;K"Pt-195"
C;X3;K"PT"
C;X4;K"98.80"
C;X5;K"N"
 */
$data_reference=array("X0"=>"id","X1"=>"isotope_code","X2"=>"topic_abb","X3"=>"subject_code","X4"=>"energy","X5"=>"subject_flag");
$last_colkey="X5";
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