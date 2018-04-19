<?php
/***************************************************************
					数据库通用方法
****************************************************************/
function getDBClass($dbType = "kamila") {
	global $DB_INSTANCE_ARRAY;
	$db_array = $DB_INSTANCE_ARRAY[$dbType];
	$dbclass = new dbstuff($db_array["dbhost"], $db_array["dbuser"], $db_array["dbpw"], $db_array["dbname"]);
	$dbclass->connect();
	return $dbclass;
}

function getDictData($dict_key){
	global $CONST_DICT;
	return $CONST_DICT[$dict_key];
}

function getDictData2($type_key,$db=null){
	$cache_file = CACHE_DIR."/dictdata.".$type_key.CACHE_FILE_SUFFIX;
	if(!file_exists($cache_file)){
		$dict_array = array();	
		if(empty($db)){
			$db = getDBClass();
		}
		$dict_data_str = $db->result_first("select dict_data from sys_dict where dict_key='$type_key'");
		$dict_data_str = str_replace("\r","",$dict_data_str);
		$dict_data_str = str_replace("\n\n","\n",$dict_data_str);
		$dict_data_array_tmp = explode("\n",$dict_data_str);
		foreach($dict_data_array_tmp as $item){
			if(!empty($item)){
				$dict_data_item = explode("|",$item);
				$dict_array[$dict_data_item[0]] = array($dict_data_item[1],$dict_data_item[2]);
			}
		}
		cache_write_array($cache_file,$dict_array);
	}
	else{
		$dict_array = include $cache_file;
	}
	return $dict_array;	
}

///////////////////////////////common//////////////////////////
$SQL_OPERATOR_ARRAY=array(
	"eq"=>"=",
	"not"=>"<>",
	"gt"=>">",
	"lt"=>"<",
	"ge"=>">=",
	"le"=>"<=",	
	"lk"=>"like",
	"in"=>"in"
);

function getInsertStr($recordObj,$is_null_value_empty=true) {
	$txtStr = ""; //insert into t_user (password,name) values('$password','$name')" 
	$fieldSrr = "";
	$valueStr = "";
	foreach ($recordObj as $key => $value) {
		//echo $key."=>".$value;
		if ($value === null) {
			if($is_null_value_empty){
				$value = "";
			}
		}
		$fieldSrr .= $key . ",";
//		if(is_numeric($value)){
//			$valueStr .= $value . ",";
//		}else{
//			$valueStr .= "'" . $value . "',";
//		}	
		if ($value !== null){
			$valueStr .= "'" . $value . "',";
		}else{
			$valueStr .= "NULL,";
		}
	}
	if (strrpos($fieldSrr, ",") !==false) {
		$fieldSrr = substr($fieldSrr, 0, strrpos($fieldSrr, ","));
		$valueStr = substr($valueStr, 0, strrpos($valueStr, ","));
		$txtStr = "(" . $fieldSrr . ") values (" . $valueStr . ")";
	}
	return $txtStr;
}

function getInStr($record_array){
	$in_str="";
	if(!empty($record_array) && is_array($record_array)){
		/*
		foreach($record_array as $recordObj){
			$in_str.="'" . $recordObj . "',";
		}		
		if (strrpos($in_str, ",") !==false) {
			$in_str = substr($in_str, 0, strrpos($in_str, ","));
		}*/
		$in_str=joinArrayValue($record_array,",","'","'");	
	}
	return $in_str;
}

/*
 * $cond_array: array(column_name=>array(column_value,operator_type))
 * operator_type default is: eq. 
 * 1)$cond_array=array("totur_id"=>array($_REQUEST["id"],"not")); ==>where totur_id<>'xxx'
 * 2)column_name=>array(column_value) ==> where column_name='xxx'
 * 3)column_name=> column_value ==> where column_name='xxx'
 */
function getCondStr($cond_array,$hasAnd=false) {
	global $SQL_OPERATOR_ARRAY;
	$txtStr = ""; //where a='' and b='' and c=''
	if(empty($cond_array)){
		return $txtStr;
	}		
	//fitst relation_type must be "and"
	$index=0;
	foreach ($cond_array as $column_name => $column_data) {
		$column_value=null;
		$operator_type=null;
		$relation_type="";
		
		if($index>0){
			$relation_type="and";
		}
		
		$param_length=0;						
		if(is_array($column_data)){
			$param_length=count($column_data);
			$column_value=$column_data[0];
			$operator_type=$column_data[1];
		}else{
			$param_length=1;
			$column_value=$column_data;
		}
		if($param_length<=2 && empty($column_value)){			
			continue;
		}
				
		$operator=null;
		if(!empty($column_value)){
			if(empty($operator_type) || empty($SQL_OPERATOR_ARRAY[$operator_type])){
				$operator=$SQL_OPERATOR_ARRAY["eq"];
			}else{
				$operator=$SQL_OPERATOR_ARRAY[$operator_type];
			}	
			if($operator_type=="lk"){
				$txtStr .= "{$relation_type} {$column_name} {$operator} '%{$column_value }%' ";			
			}else if($operator_type=="in"){
				$txtStr .= "{$relation_type} {$column_name} {$operator} ({$column_value}) ";
			}else{
				$txtStr .= "{$relation_type} {$column_name} {$operator} '{$column_value}' ";
			}
			$index++;
		}
		if($param_length==4 && $column_data[2]!=null && !empty($column_data[3]) ){	
			$column_value=$column_data[2];
			$operator=$SQL_OPERATOR_ARRAY[$column_data[3]];
			if(!empty($txtStr)){
				$txtStr .= " and {$column_name} {$operator} '{$column_value}' ";
			}else{
				$txtStr .= " {$column_name} {$operator} '{$column_value}' ";
			}
			$index++;
		}
	}

	if($hasAnd && !empty($txtStr)){
		$txtStr=" and ".$txtStr;
	}
	return $txtStr;
}

function getUpdateStr($recordObj,$is_null_value_empty=true) {
	$txtStr = ""; //set a='',b='',c='' 
	foreach ($recordObj as $key => $value) {
		if ($value === null) {
			if($is_null_value_empty){
				$value = "";				
			}
		}	
		if($value!==null){
			$txtStr .= $key . "='" . $value . "',";
		}else{
			$txtStr .= $key . "=NULL,";
		}
	}
	if (strrpos($txtStr, ",") >= 0) {
		$txtStr = "set ".substr($txtStr, 0, strrpos($txtStr, ","));
	}
	return $txtStr;
}

/*
 * $limit_array: 1)array(x,y)=> limit x,y
 * 2)number=>limit 0,number
 * 3)array(page_size=>50,page_no=>3) => limit 100, 50
 */
function getLimitStr($limit_array){
	$limit_str="";
	if(!is_array($limit_array)){
		$limit_str= " limit 0,".$limit_array;
	}else{
		if(array_key_exists("pageSize",$limit_array) && array_key_exists("pageNumber",$limit_array)){
			$pageSize=getArrayValue($limit_array,"pageSize");
			$pageNumber=getArrayValue($limit_array,"pageNumber");			
			if(!empty($pageSize) && !empty($pageNumber)){
				$limit_start=($pageNumber-1)*$pageSize;
				$limit_str.=" limit ".$limit_start.",".$pageSize;
			}
		}else{
			$limit_str=" limit ".$limit_array[0].",".$limit_array[1];	
		}
	}
	return $limit_str;
}

function get_record_paging($dbclass,$sql,$count_sql,$curr_page_no,$page_count){	
	//分页处理
	$list = array();
	$total_rec_count = $dbclass->result_first($count_sql);
	if($total_rec_count>0){
		if (empty($curr_page_no)){
			$page_no = 1;
		} else {
			$page_no = $curr_page_no;
		}
		if($page_no>$total_rec_count){
			$page_no=$total_rec_count;
		}else if($page_no<=0){
			$page_no=1;
		}
				
		$start = ($page_no-1)*$page_count;
		if($start<0){
			$start=0;
		}
		$total_page = ceil($total_rec_count/$page_count);
			
		$sql .= " limit $start,".$page_count;
			
		
		$result = $dbclass->query($sql);
		while ($value = $dbclass->fetch_array($result)) {   
			$list[] = $value;
		}
		$pre_page = $page_no-1;
		$next_page = $page_no+1;
		if($next_page>=$total_page){
			$next_page = $total_page;
		}
		if($pre_page<=0){
			$pre_page = 1;
		}
		$pager=array("curr_page_no"=>$page_no,"pre_page"=>$pre_page,"next_page"=>$next_page,"total_page"=>$total_page);
	}else{
		$pager=array("curr_page_no"=>0,"pre_page"=>0,"next_page"=>0,"total_page"=>0);
	}
	/*
	$pagerHTML = "<div class=\"pager\">
		$page_no / $total_page  total $total_rec_count records
		<a href=\"javascript:goto_page(1);\">1st page</a>
		<a href=\"javascript:goto_page($pre_page);\">Previous</a>
		<a href=\"javascript:goto_page($next_page);\">Next</a>
		<a href=\"javascript:goto_page($total_page);\">Last page</a>
		</div>";
	*/
	
	return array($list,$pager);	
}

function commonInsert($table_name,$dbclass, $data_array){
	$sql = "insert into {$table_name} ";
	$result_array = null;
	$insert_str=getInsertStr($data_array);
	if(empty($insert_str)){
		$result_array=getResultArray("201");		
	}else{
		$sql.=$insert_str;	
		$result = $dbclass->query($sql);
		if($result){
			$result_array=getResultArray("000",$sql);	
		}else{
			$result_array=getResultArray("200","insert failed:".$sql);			
		}	
	}
	return $result_array;	
}

function commonUpdate($table_name,$dbclass, $data_array,$cond_array){
	$sql = "update {$table_name} ";
	$result_array=null;
	$update_str=getUpdateStr($data_array);
	if(empty($update_str)){
		$result_array=getResultArray("201",$sql);		
	}else{
		$cond_str=getCondStr($cond_array,false);
		if(empty($cond_str)){
			$result_array=getResultArray("202",$sql);		
		}else{
			$sql.=$update_str;
			$sql.=" where ".$cond_str;	
			//echo "commonUpdate:".$sql;
			$result = $dbclass->query($sql);
			if($result){
				$result_array=getResultArray();	
			}else{
				$result_array=getResultArray("200","update failed:".$sql);			
			}	
		}		
	}
	
	return $result_array;
}

function commonDelete($table_name,$dbclass, $cond_array){
	$sql = "delete FROM {$table_name} where 1=1 ";	
	$result_array=null;
	$cond_sql=getCondStr($cond_array,true);
	if(empty($cond_sql)){
		$result_array=getResultArray("201");
	}else{
		$sql.=$cond_sql;	
		$result = $dbclass->query($sql);
		if($result){
			$result_array=getResultArray("000",$sql);	
		}else{
			$result_array=getResultArray("200","delete failed:".$sql);			
		}	
	}
	return $result_array;
}

function commonClear($table_name,$dbclass){
	$sql = "delete FROM {$table_name} where 1=1 ";	
	$result_array=null;	
	$result = $dbclass->query($sql);
	if($result){
		$result_array=getResultArray("000",$sql);	
	}else{
		$result_array=getResultArray("200","delete failed:".$sql);			
	}	
	return $result_array;
}

function commonInsertSQL($table_name,$data_array){
	$sql = "insert into {$table_name} ";
	$insert_str=getInsertStr($data_array);
	$sql.=$insert_str;
	return 	$sql;		
}

function getInsertPair($recordObj,$is_null_value_empty=true) {
	$txtStr = ""; //insert into t_user (password,name) values('$password','$name')" 
	$fieldSrr = "";
	$valueStr = "";
	foreach ($recordObj as $key => $value) {
		//echo $key."=>".$value;
		if ($value === null) {
			if($is_null_value_empty){
				$value = "";
			}
		}
		$fieldSrr .= $key . ",";	
		if ($value !== null){
			$valueStr .= "'" . $value . "',";
		}else{
			$valueStr .= "NULL,";
		}
	}
	if (strrpos($fieldSrr, ",") !==false) {
		$fieldSrr = substr($fieldSrr, 0, strrpos($fieldSrr, ","));
		$valueStr = substr($valueStr, 0, strrpos($valueStr, ","));
	}
	return array($fieldSrr,$valueStr);
}

function commonUpdateSQL($table_name,$data_array,$cond_array){
	$sql = "update {$table_name} where 1=1 ";
	$update_str=getUpdateStr($data_array);
	$cond_str=getCondStr($cond_array,true);
	$sql.=$update_str;
	if(!empty($cond_str)){
		$sql.=$cond_str;		
	}	
	return $sql;
}

function commonDeleteSQL($table_name,$cond_array){
	$sql = "delete FROM {$table_name} where 1=1 ";	
	$cond_sql=getCondStr($cond_array,true);
	if(!empty($cond_str)){
		$sql.=$cond_str;		
	}	
	return $sql;
}

function data_list($sql,$count_sql,$db){
	$total_rec_count = $db->result_first($count_sql);
	$list = array();
	$result = $db->query($sql);
	while ($value = $db->fetch_array($result)) {   
		$list[] = $value;
	}	
	return array($list,$total_rec_count);
}
/*****************lesson term*****************************/
function getTerm($dbclass, $cond_array=null, $limit_array=null){
	$sql = "SELECT * FROM lesson_term where 1=1 ";
	if(!empty($cond_array)){
		$sql.=getCondStr($cond_array,true);
	}
	$sql.=" order by date_from desc";	
	if(!empty($limit_array)){
		$sql.=" ".getLimitStr($limit_array);
	}	
	//echo "sql is:".$sql;
	$result = $dbclass->query($sql);
	return $result;		
}

function getTermSQL($cond_array=null,$limit_array=null){
	$sql = "SELECT * FROM lesson_term where 1=1 ";	
	$count_sql = "SELECT count(1) num FROM lesson_term where 1=1 ";
	$cond_sql=getCondStr($cond_array,true);
	if(!empty($cond_sql)){
		$sql.=$cond_sql;		
		$count_sql.=$cond_sql;		
	}
	$sql.=" order by date_from desc";
	if(!empty($limit_array)){
		$sql.=" ".getLimitStr($limit_array);
	}	
	return array($sql,$count_sql);
}

function insertTerm($dbclass, $data_array){
	$table_name="lesson_term";
	return commonInsert($table_name,$dbclass, $data_array);
}

function updateTerm($dbclass, $data_array,$cond_array=null){
	$table_name="lesson_term";
	return commonUpdate($table_name,$dbclass, $data_array,$cond_array);
}

function deleteTerm($dbclass, $cond_array=null){
	$table_name="lesson_term";
	return commonDelete($table_name,$dbclass, $cond_array);
}

/*****************Rental Product*****************************/
function getRentalProduct($dbclass, $cond_array=null, $limit_array=null){
	$required_column_array=array("base.prod_id","base.cat_id","base.root_cat_id","base.prod_status","base.supplier_id","base.ship_rule_id","base.prod_name","base.prod_subname","base.prod_tag","base.prod_brand","base.brand_id","base.barcode","base.barcode2","base.recommend_level","base.prod_abs","base.prod_desc","base.cover_img","base.prod_images","base.create_user","base.last_update_user","base.create_time","base.last_update_time",
	"detail.prod_id","detail.contract_num","detail.material","detail.area","detail.size","detail.weight","detail.model","detail.pack_content",
	"supplier.short_name"
	);	
	$column_str=joinArrayValue($required_column_array,",");	
	$sql = "SELECT {$column_str} from product_base base inner join product_rental detail on base.prod_id=detail.prod_id inner join supplier_common supplier on base.supplier_id=supplier.supplier_id where 1=1 ";		
	if(!empty($cond_array)){
		$sql.=getCondStr($cond_array,true);
	}
//	$sql.=" order by base.create_time desc ";	
	if(!empty($limit_array)){
		$sql.=" ".getLimitStr($limit_array);
	}
//	echo "sql is:".$sql;
	$result = $dbclass->query($sql);
	return $result;		
}

function getRentalProductSQL($cond_array=null,$limit_array=null){
	$required_column_array=array("base.prod_id","base.cat_id","base.root_cat_id","base.prod_status","base.supplier_id","base.ship_rule_id","base.prod_name","base.prod_subname","base.prod_tag","base.prod_brand","base.brand_id","base.barcode","base.barcode2","base.recommend_level","base.prod_abs","base.prod_desc","base.cover_img","base.prod_images","base.create_user","base.last_update_user","base.create_time","base.last_update_time",
	"detail.prod_id","detail.contract_num","detail.material","detail.area","detail.size","detail.weight","detail.model","detail.pack_content",
	"supplier.short_name"
	);	
	$column_str=joinArrayValue($required_column_array,",");	
	$sql = "SELECT {$column_str} from product_base base inner join product_rental detail on base.prod_id=detail.prod_id inner join supplier_common supplier on base.supplier_id=supplier.supplier_id where 1=1 ";		
	$count_sql = "SELECT count(1) from product_base base inner join product_rental detail on base.prod_id=detail.prod_id inner join supplier_common supplier on base.supplier_id=supplier.supplier_id where 1=1 ";		
	$cond_sql=getCondStr($cond_array,true);
	if(!empty($cond_sql)){
		$sql.=$cond_sql;
		$count_sql.=$cond_sql;
	}
//	$sql.=" order by base.create_time desc ";
	if(!empty($limit_array)){
		$sql.=" ".getLimitStr($limit_array);
	}	
	return array($sql,$count_sql);
}

function insertRentalProduct($dbclass, $base_data_array,$detail_data_array){
	$sql_base = "insert into product_base ";
	$sql_detail = "insert into product_rental ";
	$result_array = null;
	$insert_str=array(getInsertStr($base_data_array),getInsertStr($detail_data_array));
	if(empty($insert_str[0]) || empty($insert_str[1])){
		$result_array=getResultArray("201");		
	}else{
		//insert base
		$sql_base.=$insert_str[0];		
		$result = $dbclass->query($sql_base);
		//insert lesson
		$detail_data_array["prod_id"]=$dbclass->insert_id();
		$insert_str[1]=getInsertStr($detail_data_array);
		$sql_detail.=$insert_str[1];
		$result = $dbclass->query($sql_detail);
		
		$result_array=getResultArray();	
		$result_array["prod_id"]=$detail_data_array["prod_id"];
	}
	return $result_array;
}

function updateRentalProduct($dbclass, $base_data_array,$detail_data_array,$cond_array){
	$sql_base = null;
	$sql_detail=null;
	$result_array=null;
	$update_str=getUpdateStr($base_data_array);
	$cond_str=getCondStr($cond_array,false);
	if(!empty($update_str) && !empty($cond_str)){
		$sql_base="update product_base {$update_str} where {$cond_str}";
		$result = $dbclass->query($sql_base);
	}	
	if(!empty($cond_array["prod_id"][0])){
		$update_str=getUpdateStr($detail_data_array);
		$sql_detail="update product_rental {$update_str} where prod_id='{$cond_array["prod_id"][0]}'";
		$result = $dbclass->query($sql_detail);
	}	
	$result_array=getResultArray();		
	return $result_array;
}

?>