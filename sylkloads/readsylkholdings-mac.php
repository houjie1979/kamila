<?php// Setting the Content-Type header with charsetheader('Content-Type: text/html; charset=utf-8');	// 1. Create a database connection	$connection = mysql_connect("localhost","STAFFUSER","DALIAN2018");	if(!$connection)	{	die("Database connection failed: ".mysql_error()); }	// 2. Select a database to use	$db_select = mysql_select_db("kamila",$connection);	if(!$db_select)	{ die("Database selection failed: ".mysql_error()); }?><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Reading from file holdings</title></head><body><?phpecho"trying to write to kamila database table holdings<br/>";mysql_set_charset('utf8',$connection);/*		$query = 'INSERT INTO isotopes            ( refcode, isocode, source, absorber, stemp, atemp, ishift, qsplit, ecomments, keywords)            VALUES ( "xxx", "xxx", "xxx","xxx", "xxx",  "xxx", "111", "222", "x (x) x", "x &x x" ) ';//            VALUES ( {$refcode}, {$isotope}, {$matrix}, {$absorber}, {$Stemp},  {$Atemp}, {$ishift}, {$qsplit}, {$comment}, {$key} )";		$result = mysql_query($query, $connection);		if(!$result)		{ die("Database query failed: " . mysql_error()); }*/$previous_ref="";$handle = fopen("References_Hold", "r");if(!$handle) {echo "can not open file"; exit;}    while (($buffer = fgetCRstr($handle)) !== false) {//       echo $buffer."<br/>";//		list($pos1,$pos2,$pos3,$pos4) = explode(";", $buffer);		list($pos1,$pos2,$pos3,$pos4) = parse_sylk($buffer);//		$title = utf8_encode($title);		if($pos1=="C")		{ //			echo $pos1. "___".$pos2."___".$pos3."___".$pos4. "<br/>"; 			if(substr($pos2,0,1)=="Y")			{				$recno = substr($pos2,1);			//	echo "<br/>"."record". $recno;				if ($pos3=="X1") $ref_code=$pos4;				$reference["ind"]=inquotes($ref_code);			}			else 			{				switch ($pos2)				{					case "X1": echo "field1___";					break;					case "X2": 						$authors=inquotes($pos3);						$reference["abstract"]=$authors;					break;								case "X3": 						$journal=inquotes($pos3);						$reference["refcode"]=$journal;					break;					case "X4": 						$vol=inquotes($pos3);						$reference["type"]=$vol;					break;					case "X5": 						$issue=inquotes($pos3);						$reference["journal"]=$issue;					break;					case "X6": 						$pages=inquotes($pos3);						$reference["vol"]=$pages;					break;					case "X7": 						$year=inquotes($pos3);						$reference["pages"]=$year;					break;					case "X8": 						$title=inquotes($pos3);						$reference["title"]=$title;					break;					case "X9": 						$lang=inquotes($pos3);						$reference["year"]=$lang;					break;					case "X10": 						$lang=inquotes($pos3);						$reference["fauthor"]=$lang;					break;					case "X11": 						$lang=inquotes($pos3);						$reference["cauthor"]=$lang;					break;					case "X12": 						$lang=inquotes($pos3);						$reference["chem"]=$lang;					break;					case "X13": 						$lang=inquotes($pos3);						$reference["asca"]=$lang;					break;					case "X14": 						$lang=inquotes($pos3);						$reference["bio"]=$lang;					break;					case "X15": 						$lang=inquotes($pos3);						$reference["inspec"]=$lang;					break;					case "X16": 						$lang=inquotes($pos3);						$reference["phys"]=$lang;					break;					case "X17": 						$lang=inquotes($pos3);						$reference["energy"]=$lang;					break;					case "X18": 						$lang=inquotes($pos3);						$reference["sci"]=$lang;					break;					case "X19": 						$lang=inquotes($pos3);						$reference["issue"]=$lang;					break;					case "X20": 						$lang=inquotes($pos3);						$reference["lang"]=$lang;					break;					case "X21": 						$lang=inquotes($pos3);						if(($lang != "00/00/00")&&($lang != ""))						{								$mdy=date_parse_from_format("m/d/y",$lang);							$reference["ldate"]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));						}						else $reference["ldate"] = NULL;					break;					case "X22": 						$lang=inquotes($pos3);						if(($lang != "00/00/00")&&($lang != ""))						{								$mdy=date_parse_from_format("m/d/y",$lang);							$reference["sdate"]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));						}						else $reference["sdate"] = NULL;					break;					case "X23": 						$lang=inquotes($pos3);						if(($lang != "00/00/00")&&($lang != ""))						{								$mdy=date_parse_from_format("m/d/y",$lang);							$reference["rdate"]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));						}						else $reference["rdate"] = NULL;					break;					case "X24": 						$lang=inquotes($pos3);						if(($lang != "00/00/00")&&($lang != ""))						{								$mdy=date_parse_from_format("m/d/y",$lang);							$reference["edate"]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));						}						else $reference["edate"] = NULL;					break;					case "X25": 						$lang=inquotes($pos3);						$reference["abs"]=$lang;					break;					case "X26": 						$lang=inquotes($pos3);						$reference["com"]=$lang;					break;					case "X27": 						$lang=inquotes($pos3);						$reference["icom"]=$lang;					break;					case "X28": 						$lang=inquotes($pos3);						$reference["see"]=$lang;					break;					case "X29": 						$lang=inquotes($pos3);						$reference["unsol"]=$lang;					break;					case "X30": 						$lang=inquotes($pos3);						$reference["flag"]=$lang;					break;					case "X31": 						$lang=substr($pos3,1);						$reference["pos"]=$lang;					break;					case "X32": 						$lang=inquotes($pos3);						$reference["cit"]=$lang;					break;					case "X33": 						$keywords=inquotes($pos3);						$reference["last"]=$keywords;						$query = "INSERT INTO holdings            					( hold_key, type_code, journal_code, vol, pages,							 article_title, year, fauthor_code, cauthor_code, chem_abs,							asca_top, biosis, inspec, phys, energy, scisearch,							issue, lang_code, ldate, sdate, rdate, edate,							abstractor, comments, intercomments, see_ref_key,							unsol_flag, flag_code, cauthor_position, citations,							indexterms, abstract)            					VALUES (\"{$reference['refcode']}\",								 \"{$reference['type']}\",								 \"{$reference['journal']}\",								 \"{$reference['vol']}\",								 \"{$reference['pages']}\",								 \"{$reference['title']}\",								 \"{$reference['year']}\",								 \"{$reference['fauthor']}\",								 \"{$reference['cauthor']}\",								 \"{$reference['chem']}\",								 \"{$reference['asca']}\",								 \"{$reference['bio']}\",								 \"{$reference['inspec']}\",								 \"{$reference['phys']}\",								 \"{$reference['energy']}\",								 \"{$reference['sci']}\",								 \"{$reference['issue']}\",								 \"{$reference['lang']}\", ";								 if($reference['ldate'] != NULL) $query .= " \"{$reference['ldate']}\", ";								 else $query .= " NULL, ";								 if($reference['sdate'] != NULL) $query .= " \"{$reference['sdate']}\", ";								 else $query .= " NULL, ";								 if($reference['rdate'] != NULL) $query .= " \"{$reference['rdate']}\", ";								 else $query .= " NULL, ";								 if($reference['edate'] != NULL) $query .= " \"{$reference['edate']}\", ";								 else $query .= " NULL, ";								$query .= " \"{$reference['abs']}\",								 \"{$reference['com']}\",								 \"{$reference['icom']}\",								 \"{$reference['see']}\",								 \"{$reference['unsol']}\",								 \"{$reference['flag']}\",								 \"{$reference['pos']}\",								 \"{$reference['cit']}\",								 \"{$reference['ind']}\",								 \"{$reference['abstract']}\"	  )";	//					echo $query;						if($previous_ref != $reference['refcode']) {							$result = mysql_query($query, $connection);		 	//					echo "<br/> {$result} <br/>";							if(!$result)	{ die("Database query failed: " . mysql_error()); }												else { $previous_ref = $reference['refcode'];}						}						else echo "DUPLICATE=".$reference['refcode']."_";					break;				}							}		}		/*		$query = "INSERT INTO refs            ( refcode, authors, journal, vol, issue, pages, year, title, lang, keywords)            VALUES (\"{$refcode}\", \"{$authors}\", \"{$journal}\", \"{$vol}\", \"{$issue}\", \"{$pages}\", \"{$year}\", \"{$title}\", \"{$lang}\", \"{$key}\" )";			echo $query;		$result = mysql_query($query, $connection); 	echo "<br/> {$result} <br/>";		if(!$result)	{ die("Database query failed: " . mysql_error()); }*///		mysql_query(SELECT LAST_INSERT_ID());//		echo fetch_result/*		if ($ishift != '') 		{			$query = "UPDATE";		}*/    }echo $recno;fclose($handle);mysql_close($connection);function inquotes($instring){	$outstring=substr($instring,2);	$len=strlen($outstring)-1;	$outstring=addslashes(substr($outstring,0,$len));	$outstring=addcslashes($outstring,';');	return $outstring;}function parse_sylk($line){	if(substr($line,0,1) == "C")	{		$out[0] = "C";		$line = substr($line,2);		$out[1] = substr($line,0,strpos($line,";"));		$line = substr($line,strpos($line,";")+1);		if(substr($out[1],0,1) == "Y")		{			$out[2] = substr($line,0,strpos($line,";"));			$out[3] = substr($line,strpos($line,";")+1);		}		else{ 	$out[2] = $line; $out[3] = NULL;}	}	else $out = NULL;	return $out;}function fgetCRstr($h){	$line = ""; 	$CR = FALSE;	$EOF = FALSE;	if( ! feof($h)) {		while( ! $CR && ! $EOF ) { 			if( ! feof($h)) {				$ch = fgetc($h); 				if($ch != chr(13)) $line .= $ch; else $CR = TRUE; 			}			else $EOF = TRUE;		}		return $line;	} else return FALSE;}?></body></html>