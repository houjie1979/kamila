<?php
if (! defined ( 'MVC_ACCESS' )) {
	exit ();
}
set_time_limit(600);
$db=getDBClass();
$outputPath = PROJECT_ROOT_DIR. "outputs/";
$sylk_file_path=PROJECT_ROOT_DIR. "exportSYLK/";
$insertCountAtOnce=500;
$kamila_table_list=array(
	array("table_name"=>"refs","selected"=>true),
	array("table_name"=>"keywords","selected"=>true),
	array("table_name"=>"authors","selected"=>true),
	array("table_name"=>"name_address","selected"=>true),
	array("table_name"=>"journals","selected"=>true),
	array("table_name"=>"datas","selected"=>true),
	array("table_name"=>"abbreviations","selected"=>false),
	array("table_name"=>"keyword_code","selected"=>false),
	array("table_name"=>"isotope_code","selected"=>false),

);
$kamila_table_config=array(
	//refs	
	"refs"=>array("table_name"=>"refs","sylk_file"=>"Medc_References","execute_sql"=>false,"last_col"=>"X30","data_reference"=>array("X0"=>"id","X1"=>"ref_key","X2"=>"type_code","X3"=>"medi_number","X4"=>"journal_code",
	"X5"=>"vol","X6"=>"issue","X7"=>"pages","X8"=>"article_title","X9"=>"year","X10"=>"lang_code",
	"X11"=>"chem_abs","X12"=>"asca_top","X13"=>"biosis","X14"=>"inspec","X15"=>"phys","X16"=>"energy",
	"X17"=>"scisearch","X18"=>"ldate","X19"=>"sdate","X20"=>"rdate",
	"X21"=>"edate","X22"=>"abstractor","X23"=>"comments","X24"=>"see_ref_key","X25"=>"flag_code",
	"X26"=>"abstract","X27"=>"unsol_flag","X28"=>"citations","X29"=>"index_terms","X30"=>"")),
	//keywords	
	"keywords"=>array("table_name"=>"keywords","sylk_file"=>"Keywords","execute_sql"=>false,"last_col"=>"X4","data_reference"=>array("X0"=>"id","X1"=>"ref_key","X2"=>"data_key","X3"=>"keyword_code","X4"=>"")),		
	//authors
	"authors"=>array("table_name"=>"authors","sylk_file"=>"Authors","execute_sql"=>false,"last_col"=>"X6","data_reference"=>array("X0"=>"id","X1"=>"ref_key","X2"=>"author_key","X3"=>"medi_num","X4"=>"cauthor_flag","X5"=>"author_position","X6"=>"")),
	//name_address	
	"name_address"=>array("table_name"=>"name_address","sylk_file"=>"NameAddr","execute_sql"=>false,"last_col"=>"X20","data_reference"=>array("X0"=>"id","X1"=>"author_key","X2"=>"last_name","X3"=>"first_name","X4"=>"title",
	"X5"=>"department","X6"=>"institution","X7"=>"street","X8"=>"city","X9"=>"state_code","X10"=>"zip",
	"X11"=>"country_code","X12"=>"initials","X13"=>"phone","X14"=>"fax","X15"=>"email","X16"=>"emp_by_code",
	"X17"=>"medi_number","X18"=>"rank_abb","X19"=>"verification","X20"=>"")),
	//journals	
	"journals"=>array("table_name"=>"journals","sylk_file"=>"Journals","execute_sql"=>true,"last_col"=>"X17","data_reference"=>array("X0"=>"id","X1"=>"journal_code","X2"=>"title_abb","X3"=>"title","X4"=>"lang_code",
	"X5"=>"lib_congress","X6"=>"trans_code","X7"=>"comments","X8"=>"unca_flag","X9"=>"library_code","X10"=>"flag_code",
	"X11"=>"year_discontinued","X12"=>"old_code","X13"=>"new_code","X14"=>"year_started",
	"X15"=>"first_vol","X16"=>"last_vol","X17"=>"issn_code")),
	//datas
	"datas"=>array("table_name"=>"datas","sylk_file"=>"Data","last_col"=>"X14","execute_sql"=>false,"data_reference"=>array("X0"=>"id","X1"=>"ref_key","X2"=>"dat_key","X3"=>"medi_number","X4"=>"isotope_code",
	"X5"=>"source","X6"=>"source_temp","X7"=>"absorber","X8"=>"absorber_temp","X9"=>"isomer_shift","X10"=>"quad_split",
	"X11"=>"comments","X12"=>"abstractor","X13"=>"prefix","X14"=>"")),
	//abbreviations
	"abbreviations"=>array("table_name"=>"abbreviations","sylk_file"=>"Abbreviations","execute_sql"=>true,"last_col"=>"X3","data_reference"=>array("X0"=>"id","X1"=>"medi_num","X2"=>"abbr_code","X3"=>"definition")),
	//isotope_code	
	"isotope_code"=>array("table_name"=>"isotope_code","sylk_file"=>"Isotope_Code","execute_sql"=>true,"last_col"=>"X5","data_reference"=>array("X0"=>"id","X1"=>"isotope_code","X2"=>"topic_abb","X3"=>"subject_code","X4"=>"energy","X5"=>"subject_flag")),
	//keyword_code	
	"keyword_code"=>array("table_name"=>"keyword_code","sylk_file"=>"Keyword_code","execute_sql"=>true,"last_col"=>"X7","data_reference"=>array("X0"=>"id","X1"=>"keyword_code","X2"=>"definition","X3"=>"subject_title","X4"=>"subject_flag","X5"=>"begin_use",
	"X6"=>"not_kw1","X7"=>"not_kw2")),

);
//list管理操作
if($curr_action["op"] == "view"){
	include "{$curr_action["dir"]}/{$curr_action["obj"]}_{$curr_action["op"]}.php";	
}
//read SYLK 操作
else if($curr_action["op"] == "process"){
	$json_array = array();
	$is_param_invalid=false;
	$param_name=null;
	$kamila_tables=$_POST["kamila_tables"];
	if(empty($kamila_tables)){
		$json_array["result"]=getResultArray("100","No kamila_tables selected.");
		response_json_data($json_array);
		exit;
	}
	//check $kamila_tables config
	$invalid_param_name="";
	foreach($kamila_tables as $kamila_table){
		if(empty($kamila_table_config[$kamila_table])){
			$invalid_param_name=$kamila_table;
			break;
		}
	}
	if(!empty($invalid_param_name)){
		$json_array["result"]=getResultArray("100","Can not find configuration for table[{$invalid_param_name}] selected.");
		response_json_data($json_array);
		exit;		
	}
	$message="";
	foreach($kamila_tables as $kamila_table){
		$table_config=$kamila_table_config[$kamila_table];		
		$table_config["sylk_file"]=$sylk_file_path.$table_config["sylk_file"];
		$table_config["output_file"]=$outputPath . "kamila_".$kamila_table ."_". date("Y-m-d-H.i.s", time()) . ".sql";
		$table_config["insertCountAtOnce"]=$insertCountAtOnce;
		$result=readSYLKData($table_config,$db);
		$message.=$result["msg"]."<br>";
	}	
	$db->close();
	$json_array["result"] = getSimpleResultArray("000",$message);	
	response_json_data($json_array);																
}
else if($curr_action["op"] == "delete"){

}
?>