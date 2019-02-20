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


function getHostelOfUser(){
	require $_SERVER['DOCUMENT_ROOT']."/warden/cnxn/conxwarden.php";
	$stmtgetHostelOfUser->execute();
	$stmtgetHostelOfUser->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetHostelOfUser->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['args']['hostel_id']=$result[0]['hostel'];
	}

}

function getHostelInfo(){
	require $_SERVER['DOCUMENT_ROOT']."/warden/cnxn/conxwarden.php";
	$stmtgetHostelInfo->execute();
	$stmtgetHostelInfo->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtgetHostelInfo->fetchAll();
	$con=null;
	$totalCount=count($result);
	$onDayout=0;
	$onLeave=0;
	$requestedLeave=0;
	$inHostel=0;
	$potentialOuting=0;

	if ($totalCount>0) {
		for ($i=0; $i <$totalCount ; $i++) {
			if ($result[$i]['outing_type']=='D') {
				if ($result[$i]['status']=='3') {
					$onDayout++;
				}
			}else if($result[$i]['outing_type']=='L'){
				if ($result[$i]['status']=='3') {
					$onLeave++;
				}else if ($result[$i]['status']=='1') {
					$requestedLeave++;
				}
			}

			if($result[$i]['status']=='0'){
				$inHostel++;
			}else if($result[$i]['status']=='2'){
				$potentialOuting++;
			}
		}
	}
	$GLOBALS['result']['hostel_info']['total_count']=$totalCount;
	$GLOBALS['result']['hostel_info']['on_dayout']=$onDayout;
	$GLOBALS['result']['hostel_info']['on_leave']=$onLeave;
	$GLOBALS['result']['hostel_info']['requested_leave']=$requestedLeave;
	$GLOBALS['result']['hostel_info']['in_hostel']=$inHostel;
	$GLOBALS['result']['hostel_info']['potential_outing']=$potentialOuting;
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

function checkHeaders(){
	//$validHeaders=array("auth", "info","personal_info", "opt_wing", "status", "status", "new_dayout","new_leave", "cancel", "history");
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if ($GLOBALS['headers']['reqty']=='hostel_info') {
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
			getHostelOfUser();
			getHostelInfo();
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