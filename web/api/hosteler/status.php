<?php
//include 'res/logger.php';
$GLOBALS['timeout']['connection']=15;
$GLOBALS['timeout']['operation']=20;
$GLOBALS['result']['error']=false;
$GLOBALS['result']['errorCode']="";
$GLOBALS['result']['errorMessage']="";
function dataCleaner($dirty){
	$clean=htmlspecialchars(strip_tags(addslashes(trim(filter_var($dirty, FILTER_SANITIZE_STRING)))));
	return $clean;
}

function checkStatus(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/conxhosteler.php";
	$stmtCheckStatus->execute();
	$stmtCheckStatus->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckStatus->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['result']['bonus']=$result[0]['bonus'];
		if ($result[0]['status']>'0') {
			if($result[0]['outing_type']=='L'){
				$GLOBALS['result']['outing_type']='L';
				getLeaveTicket();
			}else if($result[0]['outing_type']=='D'){
				$GLOBALS['result']['outing_type']='D';
				getDayoutTicket();
			}
		}else if($result[0]['status']=='0'){
			$GLOBALS['result']['outing_type']=NULL;

		}else if($result[0]['status']=='-1'){
			$GLOBALS['result']['outing_type']='block';

		}
	}
	return FALSE;
}

function getLeaveTicket(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/conxhosteler.php";
	$stmtgetLeaveTicket->execute();
	$stmtgetLeaveTicket->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetLeaveTicket->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['result']['outing']=$result[0];
		return TRUE;
	}else{
		return FALSE;
	}
}

function getDayoutTicket(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/conxhosteler.php";
	$stmtgetDayoutTicket->execute();
	$stmtgetDayoutTicket->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetDayoutTicket->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['result']['outing']=$result[0];
		return TRUE;
	}else{
		return FALSE;
	}
}


function checkToken(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/conxhosteler.php";
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

function checkHeaders(){
	//$validHeaders=array("auth", "info","personal_info", "opt_wing", "status", "status", "new_dayout","new_leave", "cancel", "history");
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if ($GLOBALS['headers']['reqty']=='status') {
			return true;
		}
	}
	return false;
}


function getArgs(){
	$argsMissing=false;

	if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])) {
		$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
		$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
	}else{
		$argsMissing=true;
	}

	return !$argsMissing;
}

if (checkHeaders()) {
	if (getArgs()) {
		if (checkToken()) {
			checkStatus();
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