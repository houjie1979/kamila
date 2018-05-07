<?php
/*********************
重定向网页
msg_type:  info   error
msg: 操作提示信息
forward: 要定向的网页
***********************/

/********************************
	cache：数据库写入数组文件
********************************/
function cache_write_array($file, $string, $type = 'array'){
   if(is_array($string)){
		$type = strtolower($type);
		if($type == 'array'){
			$string = "<?php\n return ".var_export($string,TRUE).";\n?>";
		}	
		elseif($type == 'constant'){
			$data='';
			foreach($string as $key => $value)
				$data .= "define('".strtoupper($key)."','".addslashes($value)."');\n";
				$string = "<?php\n".$data."\n?>";
		}
   }
   
   $strlen = file_put_contents($file, $string);   
   chmod($file, 0755);
   return $strlen;
}

function create_phpmailer($username,$password,$fromname){
	$php_mailer = new PHPMailer(); //建立邮件发送类  
	$php_mailer->IsSMTP(); // 使用SMTP方式发送  
	
	$php_mailer->Host = "localhost"; // 您的企业邮局域名  
	$php_mailer->SMTPAuth = false; // 启用SMTP验证功能  
	$php_mailer->SMTPKeepAlive = true;
	$php_mailer->Port = 25;
	if(!empty($username)){
		$php_mailer->Username = $username; // 邮局用户名(请填写完整的email地址)  
		$php_mailer->Password = $password; // 邮局密码  
		$php_mailer->From = $username; //邮件发送者email地址  
		$php_mailer->FromName = $fromname;
	}else{
		$php_mailer->Username = "service@musicart.ae"; // 邮局用户名(请填写完整的email地址)  
		$php_mailer->Password = "xxxxxx"; // 邮局密码  
		$php_mailer->From = "service@musicart.ae"; //邮件发送者email地址  
		$php_mailer->FromName = "Musicart Service";		
	}
	$php_mailer->IsHTML(true);  
	$php_mailer->CharSet = "utf-8";
	$php_mailer->SMTPDebug = false;	
	$php_mailer->Debugoutput="html";
	return 	$php_mailer;
}

function createIMIDevMailer(){
	return create_phpmailer("service@imig.ae","imiservice2015","IMIService");
}

function createIMIProdMailer(){
	return create_phpmailer("no-reply@musicart.ae","qSB9d0#VQOEr","Musicart.ae");
}

function createLocalMailer(){
	$php_mailer = new phpmailer(); //建立邮件发送类  
	$php_mailer->IsSMTP(); // 使用SMTP方式发送  
	$php_mailer->Host = "smtp.163.com"; // 您的企业邮局域名  
	$php_mailer->SMTPAuth = true; // 启用SMTP验证功能  
	$php_mailer->Username = "citos2006@163.com"; // 邮局用户名(请填写完整的email地址)  
	$php_mailer->Password = "xxxxxx"; // 邮局密码  
	  
	$php_mailer->From = "citos2006@163.com"; //邮件发送者email地址  
	$php_mailer->FromName = "IMITest ";
	$php_mailer->IsHTML(true);  
	$php_mailer->CharSet = "utf-8";
	$php_mailer->SMTPDebug = false;	
	return 	$php_mailer;
}

//发送Email
function send_email($php_mailer=null,$email_to,$email_subject,$email_body,$email_cc=null,$email_bcc=null){
	global $CONST_PHP_MAIN_FUNNAME;
	$dir_name=ROOT_DIR."lib/";
	include_once($dir_name."phpmailer/1.60/class.phpmailer.php");
	include_once($dir_name."phpmailer/1.60/class.smtp.php");
		
	if(empty($php_mailer)){
		$php_mailer=$CONST_PHP_MAIN_FUNNAME();
	}
	$email_array=explode(",",$email_to);
	foreach($email_array as $email_address){
		if(!empty($email_address)){
			$php_mailer->AddAddress($email_address,"");
		}
	}
	$php_mailer->Subject = $email_subject;
	$php_mailer->Body = $email_body;
	if(!empty($email_cc)){
		$email_array=explode(",",$email_cc);
		foreach($email_array as $email_address){
			if(!empty($email_address)){
				$php_mailer->AddCC($email_address,"");
			}
		}		
	}
	if(!empty($email_bcc)){
		$email_array=explode(",",$email_bcc);
		foreach($email_array as $email_address){
			if(!empty($email_address)){
				$php_mailer->AddBCC($email_address,"");
			}
		}		
	}	
	$mail_result=array("status"=>"","msg"=>"");
	$mail_result["status"]=$php_mailer->Send();
	$mail_result["msg"]=$php_mailer->ErrorInfo;
	return $mail_result["status"];
}

function sendMusicartEmail($mail_type,$mail_entry){	
	$email_action_switcher=array("confirm_payment"=>true,"order_create"=>true,"order_dispatch"=>true,"registration"=>true);
	if($email_action_switcher[$mail_type]==true){
		$mail_template=getMailTemplate($mail_type);
		$dir_name=$mail_template["dir"];
		$mail_template_name=$mail_template["name"];
		$email_body=include "{$dir_name}{$mail_template_name}";
		if($mail_template["has_promotion"]){
			$email_promotion=include "{$dir_name}promotion_20170411.inc.php";
			$email_body.=$email_promotion;
		}		
	//	echo "email_body is:".$email_body;
		return send_email(null,$mail_entry["email_to"],$mail_entry["email_subject"],$email_body,null,null);
	}
}

function getMailTemplate($mail_type){
	$dir_name=ROOT_DIR."include/mail/";
	$mail_template_name="mail_{$mail_type}.inc.php";
	$result_array=array("dir"=>$dir_name,"name"=>$mail_template_name,"has_promotion"=>true);
	if($mail_type==="order_dispatch"){
		$result_array["has_promotion"]=false;
	}
	return 	$result_array;
}

function getPrintTemplate($print_type){
	$dir_name=ROOT_DIR."include/print/";
	$print_template_name="{$print_type}.inc.php";
	return 	array("dir"=>$dir_name,"name"=>$print_template_name);	
}

function getRentalContract($contract_no){
	$dir_name=ROOT_DIR."include/contract/rental/";
	$template_name="{$contract_no}.inc.php";
	$template_body=include "{$dir_name}{$template_name}";
	return $template_body;
}
/*********************************************************
					日期处理相关操作
**********************************************************/
// 计算date2比date1晚多少天
function getBetweenDays($date1, $date2){
	$d1=strtotime($date1);
	$d2=strtotime($date2);
	return round(($d2-$d1)/3600/24);
}

function getWeekDayName($date_str){
	$weekDay = date("l", strtotime($date_str));
	return $weekDay;	
}

function getNextDate($curr_date,$days_interval){
	$time1 = strtotime($curr_date);	
	return date("Y-m-d",$time1+$days_interval*3600*24);
}

function getNextWeekDay($date_str,$day_of_week,$is_self_count=false,$data_format="Y-m-d",$next_num=1){
	$src_time=strtotime($date_str);
	$src_day_of_week=date("N",$src_time);//1-7
	$day_of_week=$day_of_week%7;
	if($day_of_week==0){$day_of_week=7;}
	$days_interval=0;
	
	if($src_day_of_week<$day_of_week){
		$days_interval=$day_of_week-$src_day_of_week;
	}else if($src_day_of_week==$day_of_week){
		$days_interval=0;
		if($is_self_count==false){
			$days_interval=7;
		}
	}else{
		$days_interval=7-($src_day_of_week-$day_of_week);
	}		
	if($next_num>1){
		$days_interval+=($next_num-1)*7;
	}
	return date($data_format,$src_time+$days_interval*3600*24);
}

function addDate_old($date_str,$date_array,$date_format="Y-m-d H:i:s"){
      $cd = strtotime($date_str);
      $newdate = date($date_format, mktime(date('h',$cd)+$date_array["hour"],
	    date('i',$cd)+$date_array["min"], date('s',$cd)+$date_array["sec"], date('m',$cd)+$date_array["month"],
	    date('d',$cd)+$date_array["day"], date('Y',$cd)+$date_array["year"]));
      return $newdate;
}

function addDate($date_str,$date_array,$date_format="Y-m-d H:i:s"){
	$cd = strtotime($date_str);
	$expr="";
	foreach($date_array as $key=>$value){
		$expr.="+{$value} {$key} ";
	}
	return date($date_format,strtotime($expr,$cd));
}

function getDateDiffMinute($start_date,$end_date){
	$start_time=strtotime($start_date);
	$end_time=strtotime($end_date);
	return round(($end_time-$start_time)/60);
}

function getUsDateStr($timestamp){
	return date('m/d/Y',$timestamp);
}

function getUaeDateStr($timestamp){
	return date('d/m/Y',$timestamp);
}

function getUaeDateStr_charmonth($timestamp){
	return date('d/M/Y',$timestamp);
}

function uaeDateStrToDB($date_str){	
	$date_str = str_replace("/","-",$date_str);
	return date('Y-m-d',strtotime($date_str));
}

function getWeekDay($timestmap){
	$weekDay = date("N",$timestmap);
	$weekDayStr = "";
	switch($weekDay){
		case 1: $weekDayStr = "Mon";break;
		case 2: $weekDayStr = "Tue";break;
		case 3: $weekDayStr = "Wed";break;
		case 4: $weekDayStr = "Thur";break;
		case 5: $weekDayStr = "Fri";break;
		case 6: $weekDayStr = "Sat";break;
		case 7: $weekDayStr = "Sun";break;
		default:$weekDayStr = "N/A";
	}
	return $weekDayStr;
}

// 取date前几天的日期
function beforeDate($currTime,$daysBefore) {
	$originTimeStamp = $currTime;
    $afterTimeStamp = $originTimeStamp - ($daysBefore*3600*24);
	return date("Y-m-d",$afterTimeStamp);
}

//判断是否周末，weekend_def: 5,6,7
function isWeekend($date_str,$weekend_def) {
	$week_day = date("N", strtotime($date_str));
	if(strpos($weekend_def,$week_day)!==false){
		return true;
	}
	else{
		return false;
	}
}

//$currDate:date string
function getWeekRange($currDate,$next_weeks=0){
	$currentTime=strtotime($currDate);
	$firstDayOfWeek=0; //表示每周开始日期 ,周日是 0 周一到周六是 1 - 6 		
	$day_of_week=date("w",$currentTime); 	//获取当前周的第几天 周日是 0 周一到周六是 1 - 6 	
	$w2=$day_of_week-$firstDayOfWeek;
	if($w2<0){
		$w2=$w2+7;
	}
	//获取本周开始日期
	$week_start=date("Y-m-d",strtotime($currDate." -".$w2." days"));			
	//本周(或N周后)结束日期 	
	$next_days=6+$next_weeks*7;
	$week_end=date("Y-m-d",strtotime("{$week_start} +{$next_days} days"));
	return array($week_start,$week_end);
}

//$currDate:date string
function getMonthRange($currDate){
	$timeArray=array();	
	$currentTime=strtotime($currDate);
	$this_month_first=strtotime(date("Y-m-01",$currentTime));
	$this_month_last=strtotime(date("Y-m-d",$this_month_first)." +1 month -1 day");
	return 	array($this_month_first,$this_month_last);;	
}

function return301($url){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$url);
	exit();
}

function return404(){
	header("Location: ".ROOT_URL."404.html");
	die;
}

//算术验证码 -- 适用手机
function get_verify_code_math($w, $h) {
	$im = imagecreate($w, $h);

	//imagecolorallocate($im, 14, 114, 180); // background color
	$red = imagecolorallocate($im, 255, 0, 0);
	$white = imagecolorallocate($im, 255, 255, 255);

	$num1 = rand(1, 20);
	$num2 = rand(1, 20);

	$_SESSION['verify_code_math'] = $num1 + $num2;

	$gray = imagecolorallocate($im, 118, 151, 199);
	$black = imagecolorallocate($im, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

	//画背景
	imagefilledrectangle($im, 0, 0, 80, 24, $black);
	//在画布上随机生成大量点，起干扰作用;
	for ($i = 0; $i < 80; $i++) {
		imagesetpixel($im, rand(0, $w), rand(0, $h), $gray);
	}

	imagestring($im,4, 3, 1, $num1, $red);
	imagestring($im,4, 20, 5, "+", $red);
	imagestring($im,4, 31, 7, $num2, $red);
	imagestring($im,4, 50, 3, "=", $red);
	imagestring($im,4, 60, 2, "?", $white);

	header("Content-type: image/png");
	imagepng($im);
	imagedestroy($im);
}

//字符串校验
function valid_char($str){
	return preg_match("/^[a-zA-Z0-9\-\|\ \@\,\#\$\!\%\&\*\/\~\_\-]+$/", $str);
}

//Google类似的验证码，4个字母
function get_verify_code_gg() {
	$char_length = 4;
	$str="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$text="";
	for($i=0;$i<$char_length;$i++){
		$num[$i]=rand(0,51);
		$text.=$str[$num[$i]];
	}
	$_SESSION["verify_code_gg"] = $text;

	$im_x = 110;
	$im_y = 35;
	$im = imagecreatetruecolor($im_x,$im_y);
	$text_c = ImageColorAllocate($im, mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
	$tmpC0=mt_rand(100,255);
	$tmpC1=mt_rand(100,255);
	$tmpC2=mt_rand(100,255);
	$buttum_c = ImageColorAllocate($im,$tmpC0,$tmpC1,$tmpC2);
	imagefill($im, 16, 13, $buttum_c);

	$font = 'verify_code_font_detective.ttf';

	for ($i=0;$i<strlen($text);$i++)
	{
		$tmp =substr($text,$i,1);
		$array = array(-1,1);
		$p = array_rand($array);
		$an = $array[$p]*mt_rand(1,10);//角度
		$size = 20;
		imagettftext($im, $size, $an, 5+$i*$size,24, $text_c, $font, $tmp);
	}


	$distortion_im = imagecreatetruecolor ($im_x, $im_y);

	imagefill($distortion_im, 16, 13, $buttum_c);
	for ( $i=0; $i<$im_x; $i++) {
		for ( $j=0; $j<$im_y; $j++) {
			$rgb = imagecolorat($im, $i , $j);
			if( (int)($i+20+sin($j/$im_y*2*M_PI)*10) <= imagesx($distortion_im)&& (int)($i+20+sin($j/$im_y*2*M_PI)*10) >=0 ) {
				imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.1)*4) , $j , $rgb);
			}
		}
	}
	//加入干扰象素;
	$count = 160;//干扰像素的数量
	for($i=0; $i<$count; $i++){
		$randcolor = ImageColorallocate($distortion_im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		imagesetpixel($distortion_im, mt_rand()%$im_x , mt_rand()%$im_y , $randcolor);
	}

	$rand = mt_rand(5,30);
	$rand1 = mt_rand(15,25);
	$rand2 = mt_rand(5,10);
	for ($yy=$rand; $yy<=+$rand+1; $yy++){
		for ($px=-80;$px<=80;$px=$px+0.1)
		{
			$x=$px/$rand1;
			if ($x!=0)
			{
				$y=sin($x);
			}
			$py=$y*$rand2;

			imagesetpixel($distortion_im, $px+80, $py+$yy, $text_c);
		}
	}

	//设置文件头;
	Header("Content-type: image/JPEG");

	//以PNG格式将图像输出到浏览器或文件;
	ImagePNG($distortion_im);

	//销毁一图像,释放与image关联的内存;
	ImageDestroy($distortion_im);
	ImageDestroy($im);
}

//获取真实IP地址
function get_real_ip(){
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

function getCurrentTimestamp($format="Y-m-d H:i:s"){
	return date($format, time());	
}

function getCurrentDateStr($format="Y-m-d"){
	return date($format, time());	
}

function getCurrentUserAccount(){
	if(empty($_SESSION["bs_account"])){
		return "sys_admin";
	}else{
		return $_SESSION["bs_account"];
	}
}

function getAge($birthday){
 	$now_time=time();
 	$birthday_date=strtotime($birthday);
	$age = date('Y', $now_time) - date('Y', $birthday_date) - 1;  
	if (date('m', $now_time) == date('m', $birthday_date)){  	  
	    if (date('d', $now_time) > date('d', $birthday_date)){  
	    	$age++;  
	    }  
	}else if (date('m', $now_time) > date('m', $birthday_date)){  
	    $age++;  
	}  
	return $age;
}

/*
 * $dict_array:array("grade1"=>array("Grade 1","coment1"),"grade2"=>array("Grade 2","coment2"),"grade3"=>array("Grade 3","coment3"))
 * $dict_key:grade1  ==> Grade 1
 * $dict_key:grade1,grade2  ==> Grade 1,Grade 2
 */
function getDictValueByKey($dict_array,$dict_key,$seperator=",",$is_trim=false){
	$dict_value="";
	if(empty($dict_array) || empty($dict_key)){
		return "";
	}
	$dict_key_array=explode($seperator,$dict_key);
	$all_key_array=array_keys($dict_array);
	
	foreach($dict_key_array as $key){
		if(in_array($key,$all_key_array)){
			if($is_trim){
				$dict_value.=trim($dict_array[$key][0]).$seperator;
			}else{
				$dict_value.=$dict_array[$key][0].$seperator;
			}
		}
	}
	if (strrpos($dict_value, $seperator) !==false) {
		$dict_value = substr($dict_value, 0, strrpos($dict_value, $seperator));
	}	
	return $dict_value;	
}

function getArrayValue($arrayObj, $key, $default_value = null) {
	$value = null;
	if(!isset($arrayObj)){
		return $default_value;
	}
	if (isset ($arrayObj[$key])) {
		$value = $arrayObj[$key];
	} else
		if ($default_value != null) {
			$value = $default_value;
		} else {
			$value = null;
		}
	return $value;
}

function cn_strlen($str){
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

function cn_substr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
	if (function_exists("mb_substr")) {

		if (mb_strlen($str, $charset) <= $length)
			return $str;

		$slice = mb_substr($str, $start, $length, $charset);

	} else {

		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";

		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";

		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";

		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

		preg_match_all($re[$charset], $str, $match);

		if (count($match[0]) <= $length)
			return $str;

		$slice = join("", array_slice($match[0], $start, $length));
	}
	if ($suffix){
		$slice.="…";
	}
	return $slice;
}

/*
(
    [0] => <img alt="" src="/upload/zhenggong/201409/images/2013-2(1).png" />
    [1] => /upload/zhenggong/201409/images/2013-2(1).png
)
 */
function getFirstImage($text){
	$pattern="/<img.*src=[\"](.*?)[\"].*?>/i";
	$match=array();
	$isFound=preg_match($pattern,$text,$match);
	//print_r($match);
	if($isFound){
		return $match[1];
	}else{
		return null;
	}	
}

function response_json_data($data_array=array()){
	$json_result=json_encode($data_array);	
	header("Content-type:text/html;charset=utf-8");
	echo $json_result;	
}

/*
 * array("value1","value2","value3");,$seperator=",",$prefix="'",$suffix="'",$end_with_seperator=false ==>"'value1','value2','value3'"
 * array("value1","value2","value3");,$seperator=",",$prefix="'",$suffix="'",$end_with_seperator=true ==>"'value1','value2','value3',"
 */
function joinArrayValue($valueArray,$seperator="",$prefix=null,$suffix=null,$end_with_seperator=false){
	$valueStr="";
	if(!empty($valueArray) && is_array($valueArray)){
		if($prefix!=null){$seperator=$seperator.$prefix;}//,'
		if($suffix!=null){$seperator=$suffix.$seperator;}//',
		$valueStr = implode($seperator,$valueArray);
		if(!empty($valueStr)){
			if($prefix!=null){$valueStr=$prefix.$valueStr;}
			if($suffix!=null){$valueStr=$valueStr.$suffix;}
			if($end_with_seperator){
				$valueStr.=$seperator;
			}				
		}	
	}
	return $valueStr;
}

/*
 * "value1,value2,value3" ==>array("value1","value2","value3")
 */
function splitString($str,$seperator="",$specify_index=false){
	$value_array=null;
	if(!empty($str)){
		$value_array=explode($seperator,$str);
	}else{
		$value_array= array();
	}
	if($specify_index==false){
		return $value_array;
	}else{
		return $value_array[$specify_index];
	}	
}

function splitStringToFieldArray($str,$seperator="",$fieldName=null){
	$field_array=array();
	if(!empty($str)){
		$value_array=explode($seperator,$str);
		foreach($value_array as $value){
			array_push($field_array,array($fieldName=>$value));
		}
	}
	return $field_array;
}

function getSysMsg($code,$additionMsg=null){
	global $SYS_MESSAGE;
	$message=$SYS_MESSAGE[$code];	
	if(!empty($additionMsg)){
		if(!empty($message)){
			$message.=" - {$additionMsg}";
		}else{
			$message.="{$additionMsg}";
		}	
		
	}
	return $message;
}

function getResultArray($code="000",$additionMsg=null){
	$msg_array=array();
	$msg_array["code"]=$code;
	$msg_array["msg"]=getSysMsg($code,$additionMsg);
	return $msg_array;
}

function getSimpleResultArray($code="000",$msg=null){
	$msg_array=array();
	$msg_array["code"]=$code;
	$msg_array["msg"]=$msg;
	return $msg_array;
}

function isShopProdByRootCat($root_cat_id){
	global $CONST_SHOPPROD_CAT;
	return in_array($root_cat_id,$CONST_SHOPPROD_CAT);
}

function isNoStockProdByRootCat($root_cat_id){
	global $CONST_NOSTOCK_PROD_CAT;
	return in_array($root_cat_id,$CONST_NOSTOCK_PROD_CAT);
}

function isShopRoot(){
	return $_SESSION["bs_role_id"]=="19";
}

function isValidMusicartDesignateTo($designate_to){
	global $CONST_MUSICART_STORE_ARRAY;
	return in_array($designate_to,$CONST_MUSICART_STORE_ARRAY);
}
/*
 * data_array: array("grade1"=>array("Grade 1","comment1"),"grade2"=>array("Grade 2","comment2"),"grade3"=>array("Grade 3","comment3"))
 * $textField: "textField", $valueField:"valueField"
 * $value_array: array("grade2","grade3");
 * return $json_array: array(
 * array("textField"=>"Grade 1","valueField"=>"grade1",checked=>false)
 * array("textField"=>"Grade 2","valueField"=>"grade2",checked=>true)
 * array("textField"=>"Grade 3","valueField"=>"grade3",checked=>true)
 * )
 */
function getEasyUIDataArrayByDict($data_array,$textField,$valueField,$value_array=array(),$groupField=null){
	$json_array=array();
	if(!empty($data_array)){
		foreach ($data_array as $key => $text) {//key:fieldValue,
			$fieldChecked=false;
			if(!empty($value_array) && in_array($key,$value_array)){
				$fieldChecked=true;
			}
			$row_array=array($textField=>$text[0],$valueField=>$key,"checked"=>$fieldChecked);
			if(!empty($groupField)){
				$row_array["group"]=$groupField;
			}
			array_push($json_array,$row_array);
		}
	}
	return $json_array;
}

/*
 * data_array: array(array("grade"=>"Grade 1","comment"=>"comment1"),array("grade"=>"Grade 2","comment"=>"comment2"),array("grade"=>"Grade 3","comment"=>"comment3"))
 * $textField: "textField", $valueField:"valueField"
 * $value_array: array("grade2","grade3");
 * return $json_array: array(
 * array("textField"=>"Grade 1","valueField"=>"grade1",checked=>false)
 * array("textField"=>"Grade 2","valueField"=>"grade2",checked=>true)
 * array("textField"=>"Grade 3","valueField"=>"grade3",checked=>true)
 * )
 */
function getEasyUIDataArrayByRecord($data_array,$textField,$valueField,$value_array=array(),$groupField=null){
	$json_array=array();
	if(!empty($data_array)){
		foreach ($data_array as $record) {//key:fieldValue,
			$fieldChecked=false;
			if(!empty($value_array) && in_array($record[$valueField],$value_array)){
				$fieldChecked=true;
			}
			$row_array=array($textField=>$record[$textField],$valueField=>$record[$valueField],"checked"=>$fieldChecked);
			if(!empty($groupField)){
				$row_array["group"]=$groupField;
			}			
			array_push($json_array,$row_array);
		}
	}
	return $json_array;
}

function getRecordByField($record,$field_array,$is_return_all=true){
	$record_1=array();
	$record_2=array();		
	foreach($field_array as $field){
		if(isset($record[$field])){
			$record_1[$field]=$record[$field];
		}else{
			$record_2[$field]=$record[$field];
		}
	}
	if($is_return_all){
		return array($record_1,$record_2);
	}else{
		return $record_1;
	}
}

/*
 * data[0]: has specified fields
 * data[1]: left fields
 */
function getRecordsByField($record_array,$field_array,$is_return_all=true){
	$data=array(array(),array());
	foreach($record_array as $record){
		$record_1=array();
		$record_2=array();		
		foreach($field_array as $field){
			if(isset($record[$field])){
				$record_1[$field]=$record[$field];
			}else{
				$record_2[$field]=$record[$field];
			}
		}
		array_push($data[0],$record_1);
		array_push($data[1],$record_2);
	}
	if($is_return_all){
		return $data;
	}else{
		return $data[0];
	}	
}


//------------ string start or end with----------------
function startWith($str, $needle) {
    return strpos($str, $needle) === 0;
}

function endWith($haystack, $needle) {   

      $length = strlen($needle);  
      if($length == 0)
      {    
          return true;  
      }  
      return (substr($haystack, -$length) === $needle);
}
 
function show_message($message_type,$message_title,$message_detail,$forward_to){
	global $page_to_forward_after_login; //登录成功后的跳转到最初目的地页面的URL，比如未登录时访问订单详情，先跳转到登录页，登录成功后再跳到page_to_forward_after_login
	include "message.view.php";
	exit();
} 

function generate_pager($curr_page_no,$total_page){
	$max_show_pages = 10; //一次最多显示的页数
	$times = floor($curr_page_no/$max_show_pages);
	$pager_html = "<ul class=\"pagination\">";
	
	//生成左侧箭头
	if($times == 0){
		$pager_html.="<li><a href=\"#\">&laquo;</a></li>";
	}
	else{
		$pager_html.="<li><a href=\"javascript:gotoPage(".($times*10-1).");\">&laquo;</a></li>";
	}
	
	//生成页码
	for($i=($times*10);$i<=(($times+1)*10-1);$i++){
		if($i<=$total_page && $i>0){
			if($curr_page_no == $i){						
				$pager_html.="<li class=\"active\"><a href=\"#\">$i</a></li>";
			}
			else{
				$pager_html.="<li><a href=\"javascript:gotoPage('$i');\">$i</a></li>";
			}
		}
	}
	
	//生成右侧箭头
	if($total_page >= ($times+1)*10){
		$pager_html.="<li><a href=\"javascript:gotoPage(".(($times+1)*10).");\">&raquo;</a></li>";
	}
	else{
		$pager_html.="<li><a href=\"#\">&raquo;</a></li>";
	}	
	$pager_html .= "</ul><div class=\"clear_float\"></div>";	
	echo $pager_html;
}

function inject_check($sql_str) { 
	return eregi('select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
}

function valid_email($email){
	//return preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z-]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $email);
	return eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email);
}

function valid_alphanum($str){
	return preg_match("/^[0-9a-zA-Z]/",$str);
}

//生成随机字符串
function gen_rand_str($length) {     
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  
    for($i = 0; $i < $length; $i++ ){  
		$rand_str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
    }
    return $rand_str;  
}

function getDataEntryByFields($dataEntry,$requiredFields){
	$new_entry=array();
	foreach($requiredFields as $requiredField){
		$new_entry[$requiredField]=$dataEntry[$requiredField];
	}
	return $new_entry;
}

/*
UPLOAD_ERR_OK
其值为 0，没有错误发生，文件上传成功。

UPLOAD_ERR_INI_SIZE
其值为 1，上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。

UPLOAD_ERR_FORM_SIZE
其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。

UPLOAD_ERR_PARTIAL
其值为 3，文件只有部分被上传。

UPLOAD_ERR_NO_FILE
其值为 4，没有文件被上传。

UPLOAD_ERR_NO_TMP_DIR
其值为 6，找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进。

UPLOAD_ERR_CANT_WRITE
其值为 7，文件写入失败。PHP 5.1.0 引进。
 */
function getUploadErrorMessage($code) { 
    switch ($code) { 
    	case UPLOAD_ERR_OK:
             $message = ""; 
            break;    		
        case UPLOAD_ERR_INI_SIZE: 
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
            break; 
        case UPLOAD_ERR_FORM_SIZE: 
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
            break; 
        case UPLOAD_ERR_PARTIAL: 
            $message = "The uploaded file was only partially uploaded"; 
            break; 
        case UPLOAD_ERR_NO_FILE: 
            $message = "No file was uploaded"; 
            break; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            $message = "Missing a temporary folder"; 
            break; 
        case UPLOAD_ERR_CANT_WRITE: 
            $message = "Failed to write file to disk"; 
            break; 
        case UPLOAD_ERR_EXTENSION: 
            $message = "File upload stopped by extension"; 
            break; 

        default: 
            $message = "Unknown upload error"; 
            break; 
    } 
    return $message; 
} 

function getFileExtension($fileName) { 
	return pathinfo($fileName, PATHINFO_EXTENSION); 
} 

function alert_and_forward($message,$forward){
	if(!empty($forward)){
		echo "<script>alert('$message');location.href='$forward'</script>";	
	}
	else{
		echo "<script>alert('$message');history.back();</script>";	
	}	
	exit;
}

function hasPrivilege($privilege,$role){
	$hasPro=false;
	global $SYS_PRIVILEGE_ARRAY;
//	if($role>=100){
//		return true;
//	}
	$role_privileges=$SYS_PRIVILEGE_ARRAY[$role];
	if(!empty($role_privileges)){
		$hasPro=in_array($privilege,$role_privileges);
	}
	return $hasPro;
}

function inquotes($instring)
{
	$pos1=strpos($instring,"K\"");
	if($pos1!==false && $pos1==0){
		$outstring=substr($instring,2,strlen($instring)-3);
	}else{
		$outstring=substr($instring,1);
	}
	return $outstring;
}
function fgetCRstr($h)
{
	$line = "";
 	$CR = FALSE;
	$EOF = FALSE;
	if( ! feof($h)) {
		while( ! $CR && ! $EOF ) { 
			if( ! feof($h)) {
				$ch = fgetc($h); 
				if($ch != chr(13)) $line .= $ch; else $CR = TRUE; 
			}
			else $EOF = TRUE;
		}
		return $line;
	} else return FALSE;
}

function readSYLKData($table_config,$db){
	$table_name=$table_config["table_name"];
	$sylk_file=$table_config["sylk_file"];
	$execute_sql=$table_config["execute_sql"];
	$data_reference=$table_config["data_reference"];
	$last_colkey=$table_config["last_col"];
	$output_file=$table_config["output_file"];	
	$insertCountAtOnce=$table_config["insertCountAtOnce"];
	$result=null;
	$handle = fopen($sylk_file, "r");
	if(!$handle) {
		$result=getResultArray("100","Can not open {$sylk_file}");
		return;
	}
	if($execute_sql){
		commonClear($table_name,$db);
	}
	$outputfile = fopen($output_file, "w") or die("Unable to open file!");
	//clear table
	$fileLine=commonDeleteSQL($table_name,null);	
	fwrite($outputfile, $fileLine. ";\n");
	$recordCount=0;
	$fileLine="";
	while (($buffer = fgetCRstr($handle)) !== false) {    	
		list($pos1,$pos2,$pos3,$pos4) = explode(";", $buffer);
		if( $pos1 == "C" )
		{ 
			$col_key="";
			$col_value="";
			if(substr($pos2,0,1)=="Y"){
				$recno = substr($pos2,1);
				$record_data=array();
				$record_data[$data_reference["X0"]]=$recno;	
				$col_key=$pos3;
				$col_value=inquotes($pos4);		
			}else{
				$col_key=$pos2;
				$col_value=inquotes($pos3);
			}
			$col_name=$data_reference[$col_key];
			if(!empty($col_name)){
				$record_data[$col_name]=$col_value;
			}
			if($table_name=="refs" && ($col_key=="X18" || $col_key=="X19" || $col_key=="X20" || $col_key=="X21")){
				if(($col_value != "000000")&&($col_value != "")){//C;X20;K"910422 C;X21;K"850621"
					$mdy=date_parse_from_format("mdy",$col_value);
					$record_data[$col_name]=date("Y-m-d",mktime(0,0,0,$mdy[month],$mdy[day],$mdy[year]));
				}else{
					$record_data[$col_name]= null;
				}			
			}		
			if($col_key==$last_colkey){
				$record_data=myaddslashes($record_data);
				if($execute_sql){
					$result=commonInsert($table_name,$db, $record_data);
				}
				$insertPair=getInsertPair($record_data);
				$recordCount++;
				if($recordCount%$insertCountAtOnce==1){
					$fileLine="insert into {$table_name} ({$insertPair[0]}) values \n";	
					$fileLine.="($insertPair[1])";			
				}else if($recordCount%$insertCountAtOnce==0){
					$fileLine.=",\n($insertPair[1]);\n";
					fwrite($outputfile, $fileLine);	
				}else{
					$fileLine.=",\n($insertPair[1])";
				}						
			}
		}	// of if pos1==C
	}	//of while
	$fileLine.=";\n";
	fwrite($outputfile, $fileLine);	
	fclose($handle);
	fclose($outputfile);
	$result=getSimpleResultArray("000","Table name - {$table_name}, record number - {$recno}");
	return $result;	
}
?>
