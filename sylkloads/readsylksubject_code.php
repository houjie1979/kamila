<?php// Setting the Content-Type header with charsetheader('Content-Type: text/html; charset=utf-8');	// 1. Create a database connection	$connection = mysql_connect("localhost","STAFFUSER","DALIAN2018");	if(!$connection)	{	die("Database connection failed: ".mysql_error()); }	// 2. Select a database to use	$db_select = mysql_select_db("kamila",$connection);	if(!$db_select)	{ die("Database selection failed: ".mysql_error()); }?><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Reading from file subject</title></head><body><?phpecho"trying to write to kamila database table subject<br/>";mysql_set_charset('utf8',$connection);/*		$query = 'INSERT INTO isotopes            ( refcode, isocode, source, absorber, stemp, atemp, ishift, qsplit, ecomments, keywords)            VALUES ( "xxx", "xxx", "xxx","xxx", "xxx",  "xxx", "111", "222", "x (x) x", "x &x x" ) ';//            VALUES ( {$refcode}, {$isotope}, {$matrix}, {$absorber}, {$Stemp},  {$Atemp}, {$ishift}, {$qsplit}, {$comment}, {$key} )";		$result = mysql_query($query, $connection);		if(!$result)		{ die("Database query failed: " . mysql_error()); }*/$previous_ref="";$handle = fopen("Subject_code", "r");if(!$handle) {echo "can not open file"; exit;}    while (($buffer = fgetCRstr($handle)) !== false) {//       echo $buffer."<br/>";		list($pos1,$pos2,$pos3,$pos4) = explode(";", $buffer);//		echo "exploded___".$pos1. "___".$pos2."___".$pos3."___".$pos4. "<br/>";//		echo "repeat___".$pos1. "<br/>";//		$title = utf8_encode($title);//		echo $pos1;//		echo substr($pos1,1)."---";		if( $pos1 == "C" )		{ //			echo $pos1. "___".$pos2."___".$pos3."___".$pos4. "<br/>"; 			if(substr($pos2,0,1)=="Y")			{				$recno = substr($pos2,1);			//	echo "<br/>"."record". $recno;				if ($pos3=="X1") $ref_code=$pos4;	//			$reference[$recno]["ref_code"]= $pos4;				$reference["refcode"]=inquotes($ref_code);	//			echo $reference["refcode"];				echo "_".$recno."</br>";			}			else 			{				switch ($pos2)				{					case "X1": echo "field1___";					break;					case "X2": 						$authors=inquotes($pos3);						$reference["authors"]=$authors;	//					echo $reference["authors"]."___";											break;							case "X3": 						$journal=inquotes($pos3);						$reference["journal"]=$journal;	//					echo $reference[$recno]["journal"]."___";					break;					case "X4": 						$vol=inquotes($pos3);						$reference["vol"]=$vol;		//				echo $reference[$recno]["vol"]."___";							$query = "INSERT INTO subject_code            					( subject_code, definition, begin_use, subject_flag)            					VALUES (\"{$reference['refcode']}\",								 \"{$reference['authors']}\",								 \"{$reference['journal']}\",								 \"{$reference['vol']}\"		  )";		//				echo $query;						if($previous_ref != $reference['refcode']) {							$result = mysql_query($query, $connection);		 	//					echo "<br/> {$result} <br/>";							if(!$result)	{ die("Database query failed: " . mysql_error()); }												else { $previous_ref = $reference['refcode'];}						} // if $previous						else echo "DUPLICATE=".$reference['refcode']."_";																break;						/*					case "X5": 						$issue=inquotes($pos3);						$reference["issue"]=$issue;	//					echo $reference[$recno]["issue"]."___";										break;					case "X6": 						$pages=inquotes($pos3);						$reference["pages"]=$pages;		//				echo $reference[$recno]["pages"]."___";										break;					case "X7": 						$year=inquotes($pos3);						$reference["year"]=$year;			//			echo $reference[$recno]["year"]."___";										break;					case "X8": 						$title=inquotes($pos3);						$reference["title"]=$title;				//		echo $reference[$recno]["title"]."___";										break;					case "X9": 						$lang=inquotes($pos3);						$reference["lang"]=$lang;			//			echo $reference[$recno]["lang"]."___";										break;					case "X10": 						$keywords=inquotes($pos3);						$reference["keywords"]=$keywords;			//			echo $reference[$recno]["keywords"]."___";							$query = "INSERT INTO refs            					( refcode, authors, journal, vol, issue, pages, year, title, lang, keywords)            					VALUES (\"{$reference['refcode']}\",								 \"{$reference['authors']}\",								 \"{$reference['journal']}\",								 \"{$reference['vol']}\",								 \"{$reference['issue']}\",								 \"{$reference['pages']}\",								 \"{$reference['year']}\",								 \"{$reference['title']}\",								 \"{$reference['lang']}\",								 \"{$reference['keywords']}\"		  )";		//				echo $query;						if($previous_ref != $reference['refcode']) {							$result = mysql_query($query, $connection);		 	//					echo "<br/> {$result} <br/>";							if(!$result)	{ die("Database query failed: " . mysql_error()); }												else { $previous_ref = $reference['refcode'];}						} // if $previous						else echo "DUPLICATE=".$reference['refcode']."_";					break;*/				}	// of switch							}	// of else		}	// of if pos1==C//		else echo "???___";		/*		$query = "INSERT INTO refs            ( refcode, authors, journal, vol, issue, pages, year, title, lang, keywords)            VALUES (\"{$refcode}\", \"{$authors}\", \"{$journal}\", \"{$vol}\", \"{$issue}\", \"{$pages}\", \"{$year}\", \"{$title}\", \"{$lang}\", \"{$key}\" )";			echo $query;		$result = mysql_query($query, $connection); 	echo "<br/> {$result} <br/>";		if(!$result)	{ die("Database query failed: " . mysql_error()); }*///		mysql_query(SELECT LAST_INSERT_ID());//		echo fetch_result/*		if ($ishift != '') 		{			$query = "UPDATE";		}*/    }	//of whileecho $recno;fclose($handle);mysql_close($connection);function inquotes($instring){	$outstring=substr($instring,2);	$outstring=substr($outstring,0,strpos($outstring,"\""));	return $outstring;}function fgetCRstr($h){	$line = ""; 	$CR = FALSE;	$EOF = FALSE;	if( ! feof($h)) {		while( ! $CR && ! $EOF ) { 			if( ! feof($h)) {				$ch = fgetc($h); 				if($ch != chr(13)) $line .= $ch; else $CR = TRUE; 			}			else $EOF = TRUE;		}		return $line;	} else return FALSE;}?></body></html>