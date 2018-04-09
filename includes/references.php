<?php// If it's going to need the database, then it's // probably smart to require it before we start.require_once(LIB_PATH.DS.'database.php');class References extends DatabaseObject {		protected static $table_name = "refs";	protected static $db_fields = array('id', 'ref_key', 'type_code', 'medi_number', 				 'journal_code', 'vol', 'issue', 'pages', 'year', 'article_title',	   			 'lang_code', 'chem_abs', 'asca_top', 'biosis', 'inspec', 'phys', 				 'energy', 'scisearch', 'ldate', 'sdate', 'rdate', 'edate', 				 'abstractor', 'comments', 'see_ref_key', 'flag_code', 'abstract', 				 'unsol_flag', 'citations', 'index_terms');		public $id;	public $ref_key;	public $type_code;	public $medi_number;	public $journal_code;	public $vol;	public $issue;	public $pages;	public $year;	public $article_title;	public $lang_code;	public $chem_abs;	public $asca_top;	public $biosis;	public $inspec;	public $phys;	public $energy;	public $scisearch;	public $ldate;	public $sdate;	public $rdate;	public $edate;	public $abstractor;	public $comments;	public $see_ref_key;	public $flag_code;	public $abstract;	public $unsol_flag;	public $citations;	public $index_terms;		public $journal_name;	public $book_name;	public $authors;	public $lang;//add by jiehou	public static function get_by_refkey($refkey){		$sql = "SELECT * FROM refs WHERE ref_key = '{$refkey}' LIMIT 1 ";		return self::find_by_sql($sql);		}	public static function next_code($hold,$year){	  	global $database;		$hold_ye = substr($hold,0,2);		$ye = substr($year,2,2);		if($ye == $hold_ye) {			$codebase = strtoupper(substr($hold,0,3));			$sql = "SELECT ref_key FROM refs WHERE ref_key LIKE '{$codebase}%' ORDER BY ref_key DESC LIMIT 1 ";			$result_set = $database->query($sql); 			$last_code = array_shift($database->fetch_array($result_set));			$last_number = (int) substr($last_code, 3, 3);					$next_number = (string) ($last_number+1);			$next_code = $codebase.str_pad($next_number, 3, "00", STR_PAD_LEFT);						}		else {			$codebase = $ye . substr($hold,2,1);			$sql = "SELECT ref_key FROM refs WHERE ref_key LIKE '{$codebase}%' ORDER BY ref_key DESC LIMIT 1 ";			$result_set = $database->query($sql);			$last_code = array_shift($database->fetch_array($result_set));			$last_number = (int) substr($last_code, 3, 3);			$next_number = (string) $last_number+1;			$next_code = $codebase.str_pad($next_number, 3, "00", STR_PAD_LEFT);										}		return $next_code;		}		public function record_exists(){	  	global $database;		$select_sql = "SELECT COUNT(*) FROM refs WHERE ref_key = '{$this->refcode}'";		$result_set = $database->query($select_sql);		$rows = array_shift($database->fetch_array($result_set));		if(!empty($rows)) {	return true; } else return false;	}		public static function is_in_file($refcode){	  	global $database;		$select_sql = "SELECT COUNT(*) FROM refs WHERE ref_key = '{$refcode}'";		$result_set = $database->query($select_sql);		$rows = array_shift($database->fetch_array($result_set));		if(!empty($rows)) {	return true; } else return false;	}				  public function code_name() {    if(isset($this->ref_key)) {      return $this->ref_key . " " . $this->article_title . " " . $this->journal_code . " " .	  		$this->vol . " " . $this->issue . " " . $this->pages . " " .			$this->year . " " . $this->lang ;    } else {      return "";    }  }	public function compose_author_index($medi){		if(strlen($medi) == 2) $medi .= "%%";		$sql = "SELECT n.author_key, n.last_name, n.initials, d.ref_key, d.keyword_code FROM name_address n			JOIN (SELECT a.author_key, a.ref_key, kr.keyword_code FROM authors a			      JOIN (SELECT DISTINCT(CONCAT(k.ref_key,k.keyword_code)) AS rk, r.ref_key, k.keyword_code FROM refs r				    JOIN keywords k                                    ON r.ref_key = k.ref_key				    WHERE r.medi_number LIKE '35%%'                                    AND (k.keyword_code IN (SELECT isotope_code FROM isotope_code) OR k.keyword_code IN ('THY','IST','GEN','PSL'))) kr                               ON kr.ref_key = a.ref_key ) d                                   ON d.author_key = n.author_key                           ORDER BY n.last_name, n.initials, n.author_key, d.keyword_code, d.ref_key ";		return self::find_by_sql($sql);		}	public function print_ref(){		$output = $this->ref_key.",".$this->type_code.",E,\"".$this->authors."\",\"".$this->journal_name;		$output .="\",\"".$this->vol."\",\""."\",\""."\",\""."\",\"";  		if($this->issue != "") $output .= "(".$this->issue. "), ";		$output .= $this->pages.",";		$output .=" (".$this->year.") ".$this->article_title;		if($this->chem_abs != "") $output .= " (Chem.Abstr. ".$this->chem_abs. ")";		$output .= "\"".chr(10);		return $output;	}	public function export_ref(){		$tb=chr(9);		$cr=chr(13);		$lf=chr(10);				$output = $this->ref_key.$tb.$this->authors.$tb.$this->journal_name.$tb.$this->vol.$tb;		$output .= $this->issue.$tb.$this->pages.$tb.$this->year.$tb.$this->article_title.$tb;		//$output .= $this->lang_code.$tb.$this->index_terms.$cr.$f;//change by jiehou		$output .= $this->lang_code.$tb.$this->index_terms.$cr.$lf;		return $output;	}	public function form_dupli_out(){		$output = $this->ref_key.chr(9).$this->journal_code.chr(9).$this->vol.chr(9);		$output .= $this->issue.chr(9).$this->year.chr(9).$this->pages.chr(9);		$output .= chr(9).chr(9).$this->medi_number.chr(9).$this->article_title.chr(10);		return $output;	}		public static function get_like_page($page){		$sql = "SELECT * FROM refs WHERE pages = '{$page}' ORDER BY year DESC ";		return self::find_by_sql($sql);	}	    public static function compose_issue_refs($medi){		if(strlen($medi) == 2) $medi .= '%%'; 		$sql = "SELECT * FROM refs ";		if($medi !="") { $sql .=" WHERE medi_number LIKE '{$medi}' ORDER BY year, ref_key ";			return self::find_by_sql($sql);		}		else return NULL;	   }  public static function compose_hb_refs($kwd1, $kwd2){ 		$sql = "SELECT GROUP_CONCAT(' ', n.initials, n.last_name ORDER BY ar.author_position) AS authors,";		$sql .=" ar.article_title, ar.title_abb AS journal_name, ar.title AS book_name, ar.vol, ar.issue, ";		$sql .=" ar.pages, ar.year, ar.ref_key, ar.journal_code FROM name_address n ";		$sql .=" JOIN (SELECT DISTINCT(r.ref_key), r.article_title, j.title_abb, b.title, r.vol, r.issue, ";		$sql .=" r.pages, a.author_position, a.author_key, r.year, j.journal_code, b.book_code FROM refs r ";		$sql .=" LEFT OUTER JOIN journals j ON r.journal_code=j.journal_code ";				$sql .=" LEFT OUTER JOIN books b ON r.journal_code=b.book_code ";				$sql .=" RIGHT OUTER JOIN authors a ON r.ref_key=a.ref_key ";		$sql .=" JOIN (SELECT k1.ref_key FROM keywords k1 ";				if($kwd2 != '') { $sql .="  JOIN (SELECT * FROM keywords WHERE keyword_code = '{$kwd2}') k2 ";						$sql .="  ON k1.ref_key=k2.ref_key AND k1.data_key=k2.data_key	";}			$sql .="  WHERE k1.keyword_code = '{$kwd1}'  ) AS ak ";		$sql .="  ON r.ref_key=ak.ref_key ) AS ar ON ar.author_key=n.author_key ";		$sql .="  GROUP BY ar.ref_key ORDER BY ar.year, ar.ref_key ";		return self::find_by_sql($sql);	   }public function add_definitions(){		global $database;		$sql = "SELECT title_abb FROM journals WHERE journal_code = '".$this->journal_code. "' LIMIT 1";    	$result_set = $database->query($sql);		$row = $database->fetch_array($result_set);		$this->journal_name = $row['title_abb'];		$sql = "SELECT a.initials, a.last_name, b.cauthor_flag FROM name_address a 				JOIN (SELECT author_key, ref_key, author_position, cauthor_flag  				FROM authors WHERE ref_key = '{$this->ref_key}' ) b 				ON a.author_key = b.author_key 				ORDER BY b.author_position ";    	$result_set = $database->query($sql);	    $this->authors = "";    	while ($row = $database->fetch_array($result_set)) {	      		$this->authors .= $row['initials']." ".$row['last_name'];			if($row['cauthor_flag'] == "Y") $this->authors .="*";			$this->authors .=", ";    	}	$sql = 	"SELECT GROUP_CONCAT(DISTINCT(keyword_code) ORDER BY keyword_code SEPARATOR ' ') 		 AS index_terms FROM keywords WHERE ref_key = '{$this->ref_key}'		 GROUP BY ref_key ";	$result_set = $database->query($sql);	$row = $database->fetch_array($result_set);	$this->index_terms = $row['index_terms'];}	public function find_dupli_page(){		$sql = "SELECT * FROM refs WHERE pages = '".$this->pages."' 					AND ref_key <> '".$this->ref_key."' ORDER BY year DESC ";		return self::find_by_sql($sql);	}		public function find_dupli_title(){		$shorttitle = substr($this->article_title,0,50)."%";		$sql = "SELECT * FROM refs WHERE article_title LIKE '{$shorttitle}' 					AND ref_key <> '".$this->ref_key."' ORDER BY year DESC ";		return self::find_by_sql($sql);	}	    public static function ref_sql_where($refcode, $authors, $journal, $vol, 			$issue, $pages, $year, $title, $language, $keywords){				$spars=0;				$sql = "";		if($refcode !="") {$sql .=" AND ref_key= '{$refcode}' "; $spars++;}		if($authors !=""){			if($spars>0) $sql .=" AND LOCATE( '{$authors}', authors) ";			else $sql .=" LOCATE( '{$authors}', authors) ";			$spars++;		}		if($journal !=""){			if($spars>0) $sql .=" AND LOCATE( '{$journal}', journal) ";			else $sql .=" LOCATE( '{$journal}', journal) ";			$spars++;		}		if($vol !=""){			if($spars>0) $sql .=" AND vol= '{$vol}' ";			else $sql .=" vol= '{$vol}' ";			$spars++;		}		if($issue !=""){			if($spars>0) $sql .=" AND medi_number= '{$issue}' ";			else $sql .=" AND medi_number= '{$issue}' ";			$spars++;		}		if($pages !=""){			if($spars>0) $sql .=" AND LOCATE( '{$pages}', pages) ";			else $sql .=" LOCATE( '{$pages}', pages) ";			$spars++;		}		if($year !=""){			if($spars>0) $sql .=" AND year= '{$year}' ";			else $sql .=" year= '{$year}' ";			$spars++;		}		if($title !=""){			if($spars>0) $sql .=" AND LOCATE('{$title}',title) ";			else $sql .=" LOCATE('{$title}',title) ";			$spars++;		}		if($language !=""){			if($spars>0) $sql .=" AND lang= '{$language}' ";			else $sql .=" lang= '{$language}' ";			$spars++;		}		if($keywords !=""){			if($spars>0) $sql .=" AND keywords= '{$keywords}' ";			else $sql .=" LOCATE('{$keywords}', keywords) ";			$spars++;		}	return $sql;	}public static function ref_per_year($year1, $year2, $kwd){		$sql = " SELECT r.year, COUNT(r.year) FROM refs r";		if($kwd !="") {$sql .= " JOIN (SELECT DISTINCT(ref_key) FROM keywords WHERE keyword_code = '{$kwd}' ) k ON r.ref_key = k.ref_key ";}		$sql .= " WHERE 1 ";		if($year1 !="") {$sql .=" AND year>= '{$year1}' ";}		if($year2 !="") {$sql .=" AND year<= '{$year2}' ";}		$sql .= " GROUP BY r.year ORDER BY r.year ";		return $sql;	}public static function lang_pub_per_year($lang, $year1, $year2){		$sql = " SELECT r.year, l.language, COUNT(r.year) FROM refs r ";		$sql .= " JOIN lang_code l ON l.lang_code = r.lang_code WHERE 1 ";		if($lang !="") {$sql .=" AND r.lang_code = '{$lang}' ";}		if($year1 !="") {$sql .=" AND r.year>= '{$year1}' ";}		if($year2 !="") {$sql .=" AND r.year<= '{$year2}' ";}		$sql .= " GROUP BY r.year, l.language ORDER BY r.year, COUNT(r.year) DESC ";		return $sql;	}public static function pub_by_keyword($key1, $key2, $key3, $key4, $key5){		$sql = " SELECT GROUP_CONCAT(' ', n.initials, n.last_name ORDER BY a.author_position), ";		$sql .= " setr.article_title, setr.title_abb, setr.vol, setr.issue, setr.title, CONCAT('(',setr.year,')') ";		$sql .= " FROM authors a JOIN ";		$sql .= " (SELECT DISTINCT r.ref_key, r.article_title, j.title_abb, b.title, r.vol, r.issue, r.pages, r.year ";		$sql .= " FROM keywords k JOIN refs r ON r.ref_key = k.ref_key ";				if($key2 !="") {			$sql .= " JOIN (SELECT r2.ref_key FROM keywords k2 JOIN refs r2";			$sql .= " ON r2.ref_key = k2.ref_key WHERE k2.keyword_code = '{$key2}') AS set2 ";			$sql .= " ON set2.ref_key = r.ref_key "; }		if($key3 !="") {			$sql .= " JOIN (SELECT r3.ref_key FROM keywords k3 JOIN refs r3";			$sql .= " ON r3.ref_key = k3.ref_key WHERE k3.keyword_code = '{$key3}') AS set3 ";			$sql .= " ON set3.ref_key = r.ref_key "; }		if($key4 !="") {			$sql .= " JOIN (SELECT r4.ref_key FROM keywords k4 JOIN refs r4";			$sql .= " ON r4.ref_key = k4.ref_key WHERE k4.keyword_code = '{$key4}') AS set4 ";			$sql .= " ON set4.ref_key = r.ref_key "; }		if($key5 !="") {			$sql .= " JOIN (SELECT r5.ref_key FROM keywords k5 JOIN refs r5";			$sql .= " ON r5.ref_key = k5.ref_key WHERE k5.keyword_code = '{$key5}') AS set5 ";			$sql .= " ON set5.ref_key = r.ref_key "; }		$sql .= " LEFT JOIN journals j ON r.journal_code = j.journal_code ";		$sql .= " LEFT JOIN books b ON r.journal_code = b.book_code ";		$sql .= " WHERE k.keyword_code = '{$key1}' ) AS setr ";		$sql .= " ON setr.ref_key = a.ref_key ";		$sql .= " JOIN name_address n ON n.author_key = a.author_key ";				$sql .= " GROUP BY setr.ref_key ORDER BY setr.year DESC ";		return $sql;	}public static function pub_by_titleword($key1, $key2, $key3){	if($key1 != '') $key1 = '%'.$key1.'%';	if($key2 != '') $key2 = '%'.$key2.'%';	if($key3 != '') $key3 = '%'.$key3.'%';		$sql = " SELECT GROUP_CONCAT(' ', n.initials, n.last_name ORDER BY a.author_position), ";		$sql .= " setr.article_title, setr.title_abb, setr.vol, setr.issue, setr.title, CONCAT('(',setr.year,')') ";		$sql .= " FROM authors a JOIN ";		$sql .= " (SELECT r.ref_key, r.article_title, j.title_abb, b.title, r.vol, r.issue, r.pages, r.year ";		$sql .= " FROM refs r  LEFT JOIN journals j ON r.journal_code = j.journal_code ";		$sql .= " LEFT JOIN books b ON r.journal_code = b.book_code WHERE 1 ";				if($key1 !="") { $sql .= " AND r.article_title LIKE '{$key1}' "; }		if($key2 !="") { $sql .= " AND r.article_title LIKE '{$key2}' "; }		if($key3 !="") { $sql .= " AND r.article_title LIKE '{$key3}' "; }		$sql .= " ) AS setr ";		$sql .= " ON setr.ref_key = a.ref_key ";		$sql .= " JOIN name_address n ON n.author_key = a.author_key ";				$sql .= " GROUP BY setr.ref_key ORDER BY setr.year DESC ";		return $sql;	}	public static function ref_per_year_issue($issue){		$sql = " SELECT r.year y, COUNT(r.year) c FROM refs r ";		$sql .= " WHERE r.medi_number = $issue ";		$sql .= " GROUP BY r.year ORDER BY r.year ";		return $sql;	}	public static function authenticate($ref_key="") {    global $database;    $ref_key = $database->escape_value($ref_key);    $sql  = "SELECT * FROM refs ";    $sql .= "WHERE ref_key = '{$ref_key}' ";    $sql .= "LIMIT 1";    $result_array = self::find_by_sql($sql);	return !empty($result_array) ? array_shift($result_array) : false;	}	public static function count_ref_key_err(){	global $database;	$select_sql = "SELECT COUNT(*) FROM refs WHERE SUBSTR(ref_key,1,2) NOT BETWEEN '0' AND '99'";	$select_sql .= " OR SUBSTR(ref_key,3,1) NOT BETWEEN 'A' AND 'Z' ";	$select_sql .= " OR SUBSTR(ref_key,4,3) NOT BETWEEN '0' AND '999' ";		$result_set = $database->query($select_sql);		$rows = array_shift($database->fetch_array($result_set));		if(!empty($rows)) {	return $rows; } else return '0';	}public static function journal_code_err(){	global $database;	$select_sql = "SELECT COUNT(*) FROM refs WHERE SUBSTR(ref_key,1,2) NOT BETWEEN '0' AND '99'";	$select_sql .= " OR SUBSTR(ref_key,3,1) NOT BETWEEN 'A' AND 'Z' ";	$select_sql .= " OR SUBSTR(ref_key,4,3) NOT BETWEEN '0' AND '999' ";		$result_set = $database->query($select_sql);		$rows = array_shift($database->fetch_array($result_set));		if(!empty($rows)) {	return $rows; } else return '0';	}public static function ref_err() {		global $database;		$sql  = "SELECT * FROM refs WHERE SUBSTR(ref_key,1,2) NOT BETWEEN '0' AND '99' ";		$sql  .= " OR SUBSTR(ref_key,3,1) NOT BETWEEN 'A' AND 'Z' ";		$sql  .= " OR SUBSTR(ref_key,4,3) NOT BETWEEN '0' AND '999' ";		$sql  .= " OR (journal_code NOT IN (SELECT journal_code FROM journals)   ";		$sql  .= "     AND journal_code NOT IN (SELECT book_code FROM books))";		$sql  .= " OR article_title = '' OR year = ''  ORDER BY ref_key";		$result_array = self::find_by_sql($sql);		return !empty($result_array) ? $result_array : false;		}}?>