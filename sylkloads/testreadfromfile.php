<?php
// Setting the Content-Type header with charset
header('Content-Type: text/html; charset=utf-8');
	// 1. Create a database connection
	$connection = mysql_connect("localhost","STAFFUSER","DALIAN2018");
	if(!$connection)
	{	die("Database connection failed: ".mysql_error()); }
	// 2. Select a database to use
	$db_select = mysql_select_db("kamila",$connection);
	if(!$db_select)
	{ die("Database selection failed: ".mysql_error()); }
?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Reading from file rank</title>
</head>

<body>
<?php
echo"trying to write to wad database table sources<br/>";
mysql_set_charset('utf8',$connection);
/*		$query = 'INSERT INTO isotopes
            ( refcode, isocode, source, absorber, stemp, atemp, ishift, qsplit, ecomments, keywords)
            VALUES ( "xxx", "xxx", "xxx","xxx", "xxx",  "xxx", "111", "222", "x (x) x", "x &x x" ) ';
//            VALUES ( {$refcode}, {$isotope}, {$matrix}, {$absorber}, {$Stemp},  {$Atemp}, {$ishift}, {$qsplit}, {$comment}, {$key} )";
		$result = mysql_query($query, $connection);
		if(!$result)
		{ die("Database query failed: " . mysql_error()); }
*/
$previous_ref="";
$handle = fopen("Sources", "r");
if(!$handle) {echo "can not open file"; exit;}
while (($buffer = fgets($handle)) !== false) {
       echo $buffer."<br/>";
}