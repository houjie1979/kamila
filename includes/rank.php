<?php// duplicate of ranks.php// If it's going to need the database, then it's // probably smart to require it before we start.require_once(LIB_PATH.DS.'database.php');class Ranks extends DatabaseObject {		protected static $table_name="rank_code";	protected static $db_fields = array('id', 'rank_code', 'rank');		public $id;	public $rank_code;	public $rank;		  public function code_name() {    if(isset($this->rank_code) && isset($this->rank)) {      return $this->rank_code . "  " . $this->rank;    } else {      return "";    }  }public static function keys_sql_where($keycode, $keyname){						$sql = "";		if($keycode !="") {$sql .=" AND rank_code= '{$keycode}' "; }		if($keyname !="") {$sql .=" AND LOCATE('{$keyname}',rank) ";}	return $sql;	}	public static function is_keyword($key) {	  global $database;	$sql = "SELECT COUNT(*) FROM keywords WHERE keyword_code = '{$key}'";	$result_set = $database->query($sql);	$row = $database->fetch_array($result_set);	echo $key." ".array_shift($row);	return array_shift($row);	}	public static function authenticate($rank_code="") {    global $database;    $state_code = $database->escape_value($rank_code);    $sql  = "SELECT * FROM rank_code ";    $sql .= "WHERE rank_code = '{$rank_code}' ";    $sql .= "LIMIT 1";    $result_array = self::find_by_sql($sql);		return !empty($result_array) ? array_shift($result_array) : false;	}public static function rank_err(){	global $database;	$select_sql = "SELECT COUNT(*) FROM rank_code WHERE rank_code = '' OR rank ='' ";	$result_set = $database->query($select_sql);	$rows = array_shift($database->fetch_array($result_set));	if(!empty($rows)) {	return $rows; } else return '0';	}	}?>