<?php
//include 'res/logger.php';
$GLOBALS['timeout']['connection']=15;
$GLOBALS['timeout']['operation']=20;
$GLOBALS['result']['error']=false;
$GLOBALS['result']['errorCode']="";
$GLOBALS['result']['errorMessage']="";
$GLOBALS['result']['outing_type']="null";
function dataCleaner($dirty){
	$clean=htmlspecialchars(strip_tags(addslashes(trim(filter_var($dirty, FILTER_SANITIZE_STRING)))));
	return $clean;
}


function checkToken(){
	require $_SERVER['DOCUMENT_ROOT']."/warden/cnxn/conxwarden.php";
	$stmtCheckToken->execute();
	$stmtCheckToken->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckToken->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['result']['access_token']=$result[0]['access_token'];
		return TRUE;
	}else{
		$GLOBALS['result']['error']=true;
		$GLOBALS['result']['errorCode']="IAT";
		$GLOBALS['result']['errorMessage']="Invalid AccessToken, what the fuck?";
		return FALSE;
	}
}

function cancelLeave(){
	require $_SERVER['DOCUMENT_ROOT']."/warden/cnxn/conxwarden.php";
	$stmtgetLeaveTicket->execute();
	$stmtgetLeaveTicket->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetLeaveTicket->fetchAll();
	if (count($result)>0) {
		$GLOBALS['args']['hosteler_id']=$result[0]['user_id'];
		if($stmtDeleteTicket->execute()){
			if($stmtupdateUserStatusToNull->execute()){
				$GLOBALS['result']['outing_type']="cancelled";
				return TRUE;	
			}

		}
	}
}

function allowLeave(){
	require $_SERVER['DOCUMENT_ROOT']."/warden/cnxn/conxwarden.php";
	$stmtgetLeaveTicket->execute();
	$stmtgetLeaveTicket->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetLeaveTicket->fetchAll();
	if (count($result)>0) {
		$GLOBALS['args']['hosteler_id']=$result[0]['user_id'];
		if($stmtAllowLeave->execute()){
			if($stmtupdateUserStatusToAccepted->execute()){
				$GLOBALS['result']['outing_type']="cancelled";
				return TRUE;	
			}

		}
	}
}


function checkHeaders(){
	//$validHeaders=array("auth", "info","personal_info", "opt_wing", "status", "status", "new_dayout","new_leave", "cancel", "history");
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if ($GLOBALS['headers']['reqty']=='leave_response') {
			return true;
		}
	}
	return false;
}


function getArgs(){
	$argsMissing=false;
	if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['pass_id'])&&!empty($_POST['pass_id'])&&isset($_POST['parent_no'])&&!empty($_POST['parent_no'])&&isset($_POST['remarks'])&&!empty($_POST['remarks'])&&isset($_POST['response'])&&!empty($_POST['response'])) {
		$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
		$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
		$GLOBALS['args']['pass_id']=dataCleaner($_POST['pass_id']);
		$GLOBALS['args']['parent_no']=dataCleaner($_POST['parent_no']);
		$GLOBALS['args']['remark']=dataCleaner($_POST['remarks']);
		$GLOBALS['args']['response']=dataCleaner($_POST['response']);
	}else{
		$argsMissing=true;
	}
	return !$argsMissing;
}


if (checkHeaders()) {
	if (getArgs()) {
		if (checkToken()) {
			if ($GLOBALS['args']['response']=='allow') {
				allowLeave();
			}else if($GLOBALS['args']['response']=='cancel'){
				cancelLeave();
			}
		}
	}else{
		$GLOBALS['result']['error']=true;
		$GLOBALS['result']['errorCode']="IA";
		$GLOBALS['result']['errorMessage']="Invalid Argument types";
	}
}else{

	$GLOBALS['result']['error']=true;
	$GLOBALS['result']['errorCode']="IH";
	$GLOBALS['result']['errorMessage']="Invalid Headers";
}


echo json_encode($GLOBALS['result']);