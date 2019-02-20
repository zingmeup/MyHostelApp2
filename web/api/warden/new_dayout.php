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


function checkToken(){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
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

function newRequestStatus(){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	$stmtCheckStatus->execute();
	$stmtCheckStatus->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckStatus->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['args']['hostel_id']=$result[0]['hostel'];
		if ($result[0]['status']>'0') {
			$GLOBALS['result']['outing_type']='pending';
			return FALSE;
		}else if($result[0]['status']=='0'){
			$GLOBALS['result']['outing_type']=NULL;
			return TRUE;
		}else if($result[0]['status']=='-1'){
			$GLOBALS['result']['outing_type']='block';
			return FALSE;

		}
	}
	return FALSE;
}
function getHostelById(){

}
function getHostelOfUser(){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	$stmtgetHostelOfUser->execute();
	$stmtgetHostelOfUser->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetHostelOfUser->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['args']['hostel_id']=$result[0]['hostel'];
	}

}
function makeDayoutRequest(){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	$date_of_apply=date('Y-m-d');
	$time_of_apply=date('h:i:s');
	$otp=generateOTP();
	getHostelOfUser();
	$outing_type='D';
	$hash=md5($GLOBALS['args']['user_id'].":".$otp);
	if($stmtmakeDayoutRequest->execute()){
		if($stmtupdateUserStatusToAccepted->execute()){
			$GLOBALS['result']['outing_type']="success";
			return TRUE;	
		}
	}else{
		$con=null;
		return FALSE;
	}
}
function foundInLeave($otp){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	$stmtuniqueInLeave->execute();
	$stmtuniqueInLeave->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtuniqueInLeave->fetchAll();
	$con=null;
	if (count($result)>0) {
		return TRUE;
	}else{
		return FALSE;
	}
}
function foundInDayout($otp){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	$stmtuniqueInDayout->execute();
	$stmtuniqueInDayout->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtuniqueInDayout->fetchAll();
	$con=null;
	if (count($result)>0) {
		return TRUE;
	}else{
		return FALSE;
	}
}

function generateOTP(){
	$otp=rand(100000,999999);
	while ((foundInLeave($otp))&&(foundInDayout($otp))) {
		$otp=rand(100000,999999);
	}
	return $otp;
}


function checkHeaders(){
	//$validHeaders=array("auth", "info","personal_info", "opt_wing", "status", "status", "new_dayout","new_leave", "cancel", "history");
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if ($GLOBALS['headers']['reqty']=='new_dayout') {
	return true;
		}
	}
return false;
}


function getArgs(){
	$argsMissing=false;
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['place'])&&!empty($_POST['place'])&&isset($_POST['purpose'])&&!empty($_POST['purpose'])&&isset($_POST['going_with'])&&!empty($_POST['going_with'])&&isset($_POST['phone'])&&!empty($_POST['phone'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['place']=dataCleaner($_POST['place']);
			$GLOBALS['args']['purpose']=dataCleaner($_POST['purpose']);
			$GLOBALS['args']['going_with']=dataCleaner($_POST['going_with']);
			$GLOBALS['args']['phone']=dataCleaner($_POST['phone']);
		}else{
			$argsMissing=true;
		}
		return !$argsMissing;
}


if (checkHeaders()) {
	if (getArgs()) {
			if (checkToken()) {
				if(newRequestStatus()){
					makeDayoutRequest();
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