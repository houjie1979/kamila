String.prototype.trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.ltrim = function () {
    return this.replace(/(^\s*)/g, "");
}
String.prototype.rtrim = function () {
    return this.replace(/(\s*$)/g, "");
}
String.prototype.equal = function (str) {  
    if (this.length != str.length) {  
        return false;  
    }  
    else {  
        for (var i = 0; i < this.length; i++) {  
            if (this.charAt(i) != str.charAt(i)) {  
                return false;  
            }  
        }  
        return true;  
    }  
} 
String.prototype.equalIgnoreCase = function (str) {  
    var temp1 = this.toLowerCase();  
    var temp2 = str.toLowerCase();  
    //return temp1.equal(temp2);
    return temp1==temp2;
}  
String.prototype.startWith=function(str){     
	  var reg=new RegExp("^"+str);     
	  return reg.test(this);        
	}  
String.prototype.endWith=function(str){     
  var reg=new RegExp(str+"$");     
  return reg.test(this);        
}

function isEmail(str){ 
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
	return reg.test(str); 
} 

//"yyyy-MM-dd";
if ($.fn.datebox){
	$.fn.datebox.defaults.currentText = 'Today';
	$.fn.datebox.defaults.closeText = 'Close';
	$.fn.datebox.defaults.okText = 'OK';
	$.fn.datebox.defaults.formatter = function(date){
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
	};
	$.fn.datebox.defaults.parser = function(s){
		if (!s) return new Date();
		var ss = s.split('-');
		var y = parseInt(ss[0],10);
		var m = parseInt(ss[1],10);
		var d = parseInt(ss[2],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
			return new Date(y,m-1,d);
		} else {
			return new Date();
		}
	};
}

//"yyyy-MM-dd hh:mm:ss";
if ($.fn.datetimebox){
	$.fn.datetimebox.defaults.formatter = function(date){
		var year = date.getFullYear();
		var mohth = date.getMonth()+1;
		var day = date.getDate();
		var hour=date.getHours();
		var minute=date.getMinutes();
		var second=date.getSeconds();
		if(mohth<10){
			mohth="0"+mohth;
		}
		if(day<10){
			day="0"+""+day;
		}	
		if(hour<10){
			hour="0"+hour;
		}
		if(minute<10){
			minute="0"+""+minute;
		}
		if(second<10){
			second="0"+""+second;
		}		
		return year+"-"+mohth+"-"+day+" "+hour+":"+minute+":"+second;
	}
}

//global constants
var DEFAULT_PAGE_SIZE=50;

var DEFAULT_DIALOG_OPTIOINS={
	zIndex:9000,
    closed: true,
    cache: false,
    modal: true,
    resizable:true,
    draggable:true   
};

var DEFAULT_DATAGRID_OPTIONS={
	pagination:true,
	rownumbers:true,
	singleSelect:true,
	selectOnCheck:false,
	checkOnSelect:false,	
	fitColumns:true,
	autoSizeColumn:true,
	fit:false,
	showHeader:true,
	striped:true,
	onSelect:function(index,row) {
    }, 
    onBeforeLoad:function(data) {
    } ,
	onLoadSuccess:function(data) {
    } ,
    onLoadError:function(){    	
    }
};

var DEFAULT_PAGER_OPTIONS={
	layout:["list","sep","first","prev","sep","manual","sep","next","last","sep","refresh"],
	pageSize: DEFAULT_PAGE_SIZE,
	pageList:[DEFAULT_PAGE_SIZE,100,200],
    onSelectPage:function(pageNumber, pageSize){
    	if(pageNumber==0){pageNumber=1;}
    	queryTopPage(pageNumber, pageSize);//defined in view		
	}	    		
};

var DEFAULT_BSDIALOG_OPTIONS={
	backdrop: "static", 
	keyboard: false,
	show:false,			
};

var BOOTSTRAP_VALIDATOR_RULE={
    email:{
        validators:{
            notEmpty:{
                message:'邮箱地址不能为空'
            },
            emailAddress:{
                message:'请输入正确的邮箱地址'
            }
        }
    },
    cell_no:{
        validators:{
            stringlength:{
                min:11,
                max:11,
                message:'请输入11位手机号码'
            },
            regexp:{
                regexp:/^1[3|5|8]{1}[0-9]{9}$/,
                message:'请输入正确的手机号码'
            }
        }
    },			
	passwordFieldName:"password",	
	password:{
        message:'密码非法',
        validators:{
            notEmpty:{
                message:'密码不能为空'
            },
            stringLength:{
                min:3,
                max:20,
                message:'密码长度必须位于3到20之间'
            },
            regexp:{
                regexp:/^[a-zA-Z0-9_\.]+$/,
                message:'密码由数字字母下划线和.组成'
            }
        }
    },
    repassword:{
        message:'密码非法',
        validators:{
            notEmpty:{
                message:'密码不能为空'
            },
            stringLength:{
                min:3,
                max:20,
                message:'密码长度必须位于3到20之间'
            },
            identical:{
                field:"password",
                message:'两次密码输入不一致'
            },
            regexp:{
                regexp:/^[a-zA-Z0-9_\.]+$/,
                message:'密码由数字字母下划线和.组成'
            }
        }
    }
};

;(function($){
	if($.fn.datetimepicker){
		$.fn.datetimepicker.dates['zh-CN'] = {
				days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
				daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
				daysMin:  ["日", "一", "二", "三", "四", "五", "六", "日"],
				months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
				monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
				today: "今天",
				suffix: [],
				meridiem: ["上午", "下午"]
		};
	}
}(jQuery));

/*
0 or 'hour' for the hour view
1 or 'day' for the day view
2 or 'month' for month view (the default)
3 or 'year' for the 12-month overview
4 or 'decade' for the 10-year overview. Useful for date-of-birth datetimepickers.
 */
var DEDAULT_BOOTSTRAP_DATETIMEPICKER_OPTIONS={
	format: "yyyy-mm-dd hh:ii",//显示格式
	todayHighlight: 1,//今天高亮
	minView: "hour",
	startView:2,
	minuteStep:5,
	forceParse: 0,
	showMeridian: 1,
	language: "zh-CN",
	autoclose: true//选择后自动关闭		
};

var DEDAULT_BOOTSTRAP_DATEPICKER_OPTIONS={
	format: "yyyy-mm-dd",//显示格式
	todayHighlight: 1,//今天高亮
	minView: "month",//设置只显示到月份
	startView:2,
	forceParse: 0,
	showMeridian: 1,
	language: "zh-CN",
	autoclose: true//选择后自动关闭		
};

function getAjaxObj(){
	var ajaxObj=new Object();
	ajaxObj.url="controller.php";
	ajaxObj.async=false;
	ajaxObj.type="post";
	ajaxObj.dataType="text";
	return ajaxObj;
}

/******************AJAX common***********************/
function sendAjaxRequest( ajaxObj,succeedFun,failFun,alwaysDoFun){
	var ajax_obj= $.ajax({
		url:ajaxObj.url,
		async:ajaxObj.async,
		type:ajaxObj.type,
		dataType:ajaxObj.dataType,		
		data:ajaxObj.ajaxData,
		success:function(response_text, textStatus, jqXHR){			
			processAJAXResponse(response_text,ajaxObj.dataType,succeedFun);
			if(alwaysDoFun!=null){
				alwaysDoFun();
			}	
		},
		error:function(jqXHR, textStatus, errorThrown){
			alert("error occur:"+errorThrown);
			if(failFun!=null){
				failFun();
			}
			if(alwaysDoFun!=null){
				alwaysDoFun();
			}			
			return;
		}
	});		
}

//parse ajax response, show datagrid
function processAJAXResponse(response_text,dataType,callbackFun) {		
	var JSONobj = null;
	try{		
		if(dataType=="text"){
			JSONobj=eval('(' + response_text + ')');
		}else{
			JSONobj=response_text;
		}
	}catch(err){
		JSONobj=null;
		alert("error occur:"+response_text);
		return JSONobj;
	}		
	if(JSONobj==null){
		alert("JSON parse error"); 
		return JSONobj;
	}	
	if(callbackFun!=null){
		callbackFun(JSONobj);
	}	
	return JSONobj;
}

function dialogAjaxPost(ajaxObj,callbackFun,refreshFun){
	sendAjaxRequest(ajaxObj,
		function(JSONobj){			
			var code = JSONobj.result.code;
			var msg = JSONobj.result.msg; 
			if(code!="000"){
				$.messager.alert("Message",msg,"warning");
				return;
			}else{
				if(refreshFun==null){
					$.messager.alert("Message","Operation succeed","info",function(){
						queryTopPage();
					});	
				}else{
					refreshFun(JSONobj);					
				}
				if(callbackFun!=null){
					callbackFun(JSONobj);
				}
			}
		},function(){},function(){}
	);	
}

function show_ezdialog(dialogObj,queryParams){
	var dialogURL="controller.php";
	var paramObj={href:dialogURL,"queryParams":queryParams};
	dialogObj.dialog(paramObj).dialog("center").dialog("open");
}

function show_bsdialog(dialogId,queryParams){
	var dialogURL="controller.php";
	var dialogObj=$("#"+dialogId);
	//alert("check dialog content elements:"+$("#pid").val());
	dialogObj.empty();//double confirm clean last time modal dialog content
	dialogObj.html("Loading...").modal("show");		
	dialogObj.load(dialogURL,queryParams,function(response,status,xhr){
//		if(status=="success"){
//			setTimeout(function(){
//				dialog_init();
//				$.parser.parse(dialogObj);//render easyui
//			},100);			
//		}
	});
}

/***************************************************************
-- UI List View common function
****************************************************************/
function getPaginationParam(tableId,pageNumber,pageSize){
	var pagerParam=new Object();	
	if(pageNumber!=null && pageSize!=null){
		pagerParam.pageNumber=(pageNumber==0)? 1:pageNumber;
		pagerParam.pageSize=pageSize;		
	}else{
		var pager=$("#"+tableId).datagrid("getPager");
		if(pager!=null){
			var options=pager.pagination("options");
			pagerParam.pageNumber=(options.pageNumber==0)? 1:options.pageNumber;
			pagerParam.pageSize=options.pageSize;		
		}else{
			pagerParam.pageNumber=1;
			pagerParam.pageSize=DEFAULT_PAGE_SIZE;
		}
	}
	return pagerParam;
} 

function getFieldsDataObj(dataObj,fieldsObj){
	var errors=new Array();	
	var fieldsArray=new Array();
	fieldsArray.push(fieldsObj.requiredFields);
	fieldsArray.push(fieldsObj.optionalFields);
	var hasFieldPrefix=(fieldsObj.fieldPrefix!=null && $.trim(fieldsObj.fieldPrefix)!="");
	for(var i=0;i<fieldsArray.length;i++){
		for(var j=0;j<fieldsArray[i].length;j++){
			var fieldName=fieldsArray[i][j].fieldName;
			var fieldType=fieldsArray[i][j].fieldType;
			var fieldId=fieldName;
			if(hasFieldPrefix){
				fieldId=fieldsObj.fieldPrefix+fieldName;
			}
			var fieldValue=null;
			var fieldObj=$("#"+fieldId);
			if(fieldType==""){
				fieldValue=$("#"+fieldId).val();
			}else if(fieldType=="textbox"){
				fieldValue=fieldObj.textbox("getValue");
			}else if(fieldType=="combobox"){
				fieldValue=fieldObj.combobox("getValue");
			}else if(fieldType=="datebox"){
				fieldValue=fieldObj.datebox("getValue");
			}else if(fieldType=="datetimebox"){
				fieldValue=fieldObj.datetimebox("getValue");
			}else if(fieldType=="numberbox"){
				fieldValue=fieldObj.numberbox("getValue");
			}else if(fieldType=="timespinner"){
				fieldValue=fieldObj.timespinner("getValue");
			}else if(fieldType=="numberspinner"){
				fieldValue=fieldObj.numberspinner("getValue");
			}else if(fieldType=="datetimespinner"){
				fieldValue=fieldObj.datetimespinner("getValue");
			}else{
				fieldValue=fieldObj.val();
			}
			dataObj[fieldName]=fieldValue;	
			if(i==0 && (fieldValue==null || $.trim(fieldValue)=="")){
				errors.push(fieldName);
				if(fieldsObj.break_on_error){
					i=fieldsArray.length;
					break;
				}			
			}
		}
	}
	 var resultObj=new Object();
	 resultObj.errors=errors;
	 resultObj.data=dataObj;
	 return resultObj;
}

function isTableContain(tableId,colName,str){
	var isContain=false;
	var tableData=$("#"+tableId).datagrid("getRows");
	if(tableData!=null && tableData.length>0){
		for(var i=0;i<tableData.length;i++){
			if(tableData[i][colName]!=null && tableData[i][colName].equalIgnoreCase(str)){
				isContain=true;
				break;
			}
		}
	}
	return isContain;
}

function removeTableCheckedRow(tableId){
	var tableObj=$("#"+tableId);
	var checkedRow=tableObj.datagrid("getChecked");
	if(checkedRow!=null && checkedRow.length>0){
		for(var i=0;i<checkedRow.length;i++){
			var rowIndex=tableObj.datagrid("getRowIndex",checkedRow[i]);
			tableObj.datagrid("deleteRow",rowIndex);
		}
	}
}
