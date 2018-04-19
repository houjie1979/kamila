<?php
class dbstuff {
	var $version = '';
	var $querynum = 0;
	var $link;
	private	$g_dbhost = null;
	private $g_dbuser = null;
	private $g_dbpw = null;	
	private $g_dbname = null;

	function __construct( $dbhost,$dbuser,$dbpw,$dbname) {
		$this->g_dbhost = $dbhost;
		$this->g_dbuser = $dbuser;
		$this->g_dbpw = $dbpw;
		$this->g_dbname = $dbname;
	}
	
	function connect(){
		//Using DH database server....
//		$dbhost = DB_HOST;
//	    $dbuser = DB_USERNAME;
//	    $dbpw = DB_PASSWORD;
//		$dbname = DB_NAME;		

		$dbhost = $this->g_dbhost;
	    $dbuser = $this->g_dbuser;
	    $dbpw = $this->g_dbpw;
		$dbname = $this->g_dbname;	
				
	    $pconnect = 0;	
		$halt = TRUE;
		$dbcharset = 'utf8';
	
		$func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
		
		if(!$this->link = @$func($dbhost, $dbuser, $dbpw, 1)) {
			$halt && $this->halt('Can not connect to MySQL server');
		} else {
			if($this->version() > '4.1') {
				$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
			}
			$dbname && @mysql_select_db($dbname, $this->link);
		}

	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}

	function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}

	function query($sql, $type = '') {
		
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				$this->connect();
				$this->query($sql, 'RETRY'.$type);
			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	/**
	直接返查询结果(如：select count)
	*/
	function result($query, $row = 0) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysql_get_server_info($this->link);
		}
		return $this->version;
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		define('CACHE_FORBIDDEN', TRUE);
		require_once 'db_mysql_error.inc.php';
	}
	
	//mysql_query("BEGIN"); 
	//mysql_query("COMMIT"); 
	//mysql_query("ROLLBACK"); 
}

?>
