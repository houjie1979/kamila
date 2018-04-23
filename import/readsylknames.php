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
<?php require_once "commonReadSYLK.php" ?>
</body>
</html>