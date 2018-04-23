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

$table_name="journals";
$exportSYLK_file="../exportSYLK/Journals";
$insertCountAtOnce=500;
$executeSQL=true;
/*
id
journal_code
title_abb
title
lang_code
lib_congress
trans_code
comments
unca_flag
library_code
flag_code
year_discontinued
old_code
new_code
year_started
first_vol
last_vol
issn_code

C;Y1;X1;K"JPASBN"
C;X2;K"Middle East Tech. Univ. Pure Appl. Sci."
C;X3;K"Middle East Tech. Univ. Pure Appl. Sci."
C;X4;K"VAR"
C;X5;K""
C;X6;K""
C;X7;K"vol 15 in 1982"
C;X8;K"N"
C;X9;K"?"
C;X10;K""
C;X11;K""
C;X12;K""
C;X13;K""
C;X14;K""
C;X15;K""
C;X16;K""
C;X17;K""
 */
$data_reference=array("X0"=>"id","X1"=>"journal_code","X2"=>"title_abb","X3"=>"title","X4"=>"lang_code",
"X5"=>"lib_congress","X6"=>"trans_code","X7"=>"comments","X8"=>"unca_flag","X9"=>"library_code","X10"=>"flag_code",
"X11"=>"year_discontinued","X12"=>"old_code","X13"=>"new_code","X14"=>"year_started",
"X15"=>"first_vol","X16"=>"last_vol","X17"=>"issn_code",);
$last_colkey="X17";
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