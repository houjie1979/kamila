<?php// Setting the Content-Type header with charsetheader('Content-Type: text/html; charset=utf-8');	// 1. Create a database connection	$connection = mysql_connect("localhost","STAFFUSER","DALIAN2018");	if(!$connection)	{	die("Database connection failed: ".mysql_error()); }	// 2. Select a database to use	$db_select = mysql_select_db("kamila",$connection);	if(!$db_select)	{ die("Database selection failed: ".mysql_error()); }?><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Reading from file rank</title></head><body><?phpecho"trying to write to wad database table rank<br/>";mysql_set_charset('utf8',$connection);$previous_ref="";$handle = fopen("Rank_code", "r");if(!$handle) {echo "can not open file"; exit;}    while (($buffer = fgetCRstr($handle)) !== false) {       echo $buffer."<br/>";		list($pos1,$pos2,$pos3,$pos4) = explode(";", $buffer);		if( $pos1 == "C" )		{ //			echo $pos1. "___".$pos2."___".$pos3."___".$pos4. "<br/>"; 			if(substr($pos2,0,1)=="Y")			{				$recno = substr($pos2,1);			//	echo "<br/>"."record". $recno;				if ($pos3=="X1") $ref_code=$pos4;	//			$reference[$recno]["ref_code"]= $pos4;				$reference["refcode"]=inquotes($ref_code);//				echo $reference["refcode"];//				echo "_".$recno."_";			}			else 			{				switch ($pos2)				{					case "X1": echo "field1___";					break;					case "X2": 						$authors=inquotes($pos3);						$reference["authors"]=$authors;//						echo $reference["authors"]."___";						$query = "INSERT INTO rank_code            					( rank_code, rank)            					VALUES (\"{$reference['refcode']}\",								 \"{$reference['authors']}\"		  )";		//				echo $query;						if($previous_ref != $reference['refcode']) {							$result = mysql_query($query, $connection);		 	//					echo "<br/> {$result} <br/>";							if(!$result)	{ die("Database query failed: " . mysql_error()); }												else { $previous_ref = $reference['refcode'];}						} // if $previous						else echo "DUPLICATE=".$reference['refcode']."_";					break;				}	// of switch							}	// of else		}	// of if pos1==C    }	//of whileecho $recno . " records imported";fclose($handle);mysql_close($connection);function inquotes($instring){	$outstring=substr($instring,2);	$outstring=substr($outstring,0,strpos($outstring,"\""));	return $outstring;}function fgetCRstr($h){	$line = ""; 	$CR = FALSE;	$EOF = FALSE;	if( ! feof($h)) {		while( ! $CR && ! $EOF ) { 			if( ! feof($h)) {				$ch = fgetc($h); 				if($ch != chr(13)) $line .= $ch; else $CR = TRUE; 			}			else $EOF = TRUE;		}		return $line;	} else return FALSE;}?></body></html>