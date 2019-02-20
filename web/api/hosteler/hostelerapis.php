<?php
include 'res/logger.php';
$GLOBALS['timeout']['connection']=15;
$GLOBALS['timeout']['operation']=20;
$GLOBALS['result']['error']=false;
$GLOBALS['result']['errorCode']="";
$GLOBALS['result']['errorMessage']="";
function dataCleaner($dirty){
	$clean=htmlspecialchars(strip_tags(addslashes(trim(filter_var($dirty, FILTER_SANITIZE_STRING)))));
	return $clean;
}

function authenticate(){
	$url="http://cuims.in/cuservices/api/mobapp/UserCredentialValidation?UserName=".$GLOBALS['args']['user_id']."&Password=".$GLOBALS['args']['pass']."&ipaddress=172.19.2.100&Source=HostelApp";     
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	return $response;
}

function userTokenExists(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$stmtCheckTokenExists->execute();
	$stmtCheckTokenExists->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckTokenExists->fetchAll();
	$con=null;
	if (count($result)>0) {
		return TRUE;
	}else{
		return FALSE;
	}
}

function insertToken(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if ($stmtInsertToken->execute()) {
		$con=null;
		return TRUE;
	}
	else{
		$con=null;
		return FALSE;
	}
}

function updateToken(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtUpdateToken->execute()){
		return TRUE;
	}else{
		return FALSE;
	}
}


function userExistsInDB(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$stmtCheckUseridExists->execute();
	$stmtCheckUseridExists->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckUseridExists->fetchAll();
	$con=null;
	if (count($result)>0) {
		return TRUE;
	}else{
		return FALSE;
	}
}

function checkToken(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
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

function registerHostel($hostelName, $type){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$stmtCheckHostelExists->execute();
	$stmtCheckHostelExists->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckHostelExists->fetchAll();
	if (!count($result)>0) {
		$stmtAddHostel->execute();
		$id=$con->lastInsertId();
		return $id;

	}else{
		return $result[0]['id'];
	}
}

function getUserBasics(){
	$url="http://cuims.in/cuservices/api/mobapp/GetHostelInfo?userid=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token'];     
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	if ($response['Message']!="Invalid Acess Token") {
		echo "getUserBasics";
		
		$GLOBALS['result']['user_info']['user_id']=$response['UID'];
		$GLOBALS['result']['user_info']['user_type']=$response['Type'];
		$GLOBALS['result']['user_info']['gender']=$response['Gender'];
		$Program=explode("(", $response['ProgramName']);
		$GLOBALS['result']['user_info']['branch']=$Program[0];
		$GLOBALS['result']['user_info']['course']=str_replace(")", "", $Program[1]);
		$GLOBALS['result']['user_info']['section']=$response['SECTION'];
		$GLOBALS['result']['user_info']['name']=$response['Name'];
		$GLOBALS['result']['user_info']['hostel']=$response['HostelName'];
		$GLOBALS['result']['user_info']['hostel_id']=registerHostel($response['HostelName'], $response['Gender'][0]);
		$GLOBALS['result']['user_info']['room']=$response['RoomNo'];
		return TRUE;
	}else{
		return FALSE;
	}
}


function insertBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtInsertBasics->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
	
}

function updateBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtUpdateBasics->execute()){
		return TRUE;
	}else{
		return FALSE;
	}
}

function getUserPersonal(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentInfo?userid=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token'];     
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	if ($response['Message']!="Invalid Acess Token") {
		echo "getUserPersonal";
		$GLOBALS['result']['user_info']['img']=$response['Snap'];
		$GLOBALS['result']['user_info']['email']=$response['Email'];
		$GLOBALS['result']['user_info']['phone']=$response['Mobile'];
		//echo json_encode($GLOBALS['result']);
		return TRUE;
	}else{
		return FALSE;
	}
}
function updatePersonal(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtUpdatePersonal->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
}

function optWing(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtUpdateWing->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
}

function checkStatus(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$stmtCheckStatus->execute();
	$stmtCheckStatus->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckStatus->fetchAll();
	$con=null;
	if (count($result)>0) {
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
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
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


function newRequestStatus(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$stmtCheckStatus->execute();
	$stmtCheckStatus->setFetchMode(PDO::FETCH_ASSOC);
	$result=$stmtCheckStatus->fetchAll();
	$con=null;
	if (count($result)>0) {
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

function makeDayoutRequest(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$date_of_apply=date('Y-m-d');
	$time_of_apply=date('h:i:s');
	$otp=generateOTP();
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
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
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

function getHostelyId(){
	
	return 
}


function makeLeaveRequest(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$date_of_apply=date('Y-m-d');
	$time_of_apply=date('h:i:s');
	$otp=generateOTP();
	$outing_type='L';
	$hash=md5($GLOBALS['args']['user_id'].":".$otp);
	if($stmtmakeLeaveRequest->execute()){
		if($stmtupdateUserStatusToRequested->execute()){
			$GLOBALS['result']['outing_type']="requested";
			return TRUE;	
		}
	}else{
		$con=null;
		return FALSE;
	}
}


/*function updateUserDetail(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$updateCredentials->execute();
	$con=null;
}

function updateBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	$updateBasics->execute();
	$con=null;
}
function insertBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if($stmtInsertBasics->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
	
}

function getUserBasics(){
	$url="api/hostelUserInfo.json";
	$responseJson =file_get_contents($url);
	$response=json_decode($responseJson, true);
	if ($response['auth']=="success") {
		echo "getUserBasics";
		$GLOBALS['result']['user_info']['user_id']=$response['user_id'];
		$GLOBALS['result']['user_info']['user_type']=$response['user_type'];
		$GLOBALS['result']['user_info']['gender']=$response['gender'];
		$GLOBALS['result']['user_info']['branch']=$response['branch'];
		$GLOBALS['result']['user_info']['course']=$response['course'];
		$GLOBALS['result']['user_info']['section']=$response['section'];
		$GLOBALS['result']['user_info']['phone']=$response['phone'];
		$GLOBALS['result']['user_info']['email']=$response['email'];
		$GLOBALS['result']['user_info']['name']=$response['name'];
		$GLOBALS['result']['user_info']['hostel']=$response['hostel'];
		$GLOBALS['result']['user_info']['room']=$response['room'];
		$GLOBALS['result']['user_info']['wing']=$response['wing'];
		$GLOBALS['result']['user_info']['img']=$response['img'];
		return TRUE;
	}else{
		return FALSE;
	}
	
}
function insertUserDetails(){
	require $_SERVER['DOCUMENT_ROOT']."/cnxn/conxhosteler.php";
	if ($stmtInsertBasics->execute()) {
		$con=null;
		return TRUE;
	}
	else{
		$con=null;
		return FALSE;
	}
}


function fetchCourseDetails(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCurrentCourse?UserId=".$GLOBALS['result']['user_info']['user_id']."&AcessToken=".$GLOBALS['result']['user_info']['access_token'];
	echo "fetchCourseDetails";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	$GLOBALS['result']['courseInfo']=$response;
}

function checkDBforToken(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$checkToken->execute();
	$checkToken->setFetchMode(PDO::FETCH_ASSOC);
	$result=$checkToken->fetchAll();
	$con=null;
	if (count($result)>0) {
		$GLOBALS['args']['pass']=$result[0]['pass'];
		return TRUE;
	}else{
		return FALSE;
	}
}

function updateTokenToDB(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$updateAccessToken->execute();
	$con=null;
}

function checkToken(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentInfo?UserId=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	if (isset($response['Message'])&&$response['Message']=="Invalid Acess Token"){
		if(checkDBforToken()){
			$authentication=authenticate();
			if ($authentication['Message']=='success') {
				echo "checkToken";
				$GLOBALS['result']['user_info']['user_id']=$authentication['user_id'];
				$GLOBALS['result']['user_info']['access_token']=$authentication['access_token'];
				$GLOBALS['args']['access_token']=$authentication['access_token'];
				$GLOBALS['result']['user_info']['user_type']=$authentication['user_type'];
				updateTokenToDB();
				return TRUE;
			}else{
				$GLOBALS['result']['error']=true;
				$GLOBALS['result']['errorCode']="ICP";
				$GLOBALS['result']['errorMessage']="The password you entered is incorrect";
				return FALSE;

			}

		}else{
			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="IAT";
			$GLOBALS['result']['errorMessage']="Invalid AccessToken, what the fuck?";
		}
	}else{
		echo "checkToken";
		$GLOBALS['result']['user_info']['user_id']=$GLOBALS['args']['user_id'];
		$GLOBALS['result']['user_info']['access_token']=$GLOBALS['args']['access_token'];
		return TRUE;


	}

}

function getTimetable(){	
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentTimeTablewithLatestUpdate?UserId=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	$GLOBALS['result']['timetable']=$response;

}
function getAllAttendance(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCurrentCourse?UserId=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	for ($i=0; $i <count($response) ; $i++) {
		$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCourseWiseAttendance?UserId=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token']."&CourseCode=".$response[$i]['CourseCode'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
		curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
		$responseJsonFromCW = curl_exec($ch);
		curl_close($ch);
		$responseFromCW=json_decode($responseJsonFromCW, true);
		$GLOBALS['result']['attendance'][$i]=$responseFromCW;
	}

}

function getOneAttendance(){		
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCourseWiseAttendance?UserId=".$GLOBALS['args']['user_id']."&AcessToken=".$GLOBALS['args']['access_token']."&CourseCode=".$GLOBALS['args']['attCode'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['timeout']['connection']); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['timeout']['operation']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
	$responseJson = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($responseJson, true);
	$GLOBALS['result']['attendance']=$response;

}*/

function checkHeaders(){
	$validHeaders=array("auth", "info","personal_info", "opt_wing", "status", "status", "new_dayout","new_leave", "cancel", "history");
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if (in_array($GLOBALS['headers']['reqty'], $validHeaders)) {
			return true;
		}
	}
	return false;
}

function getArgs(){
	$argsMissing=false;
	if ($GLOBALS["headers"]['reqty']=="auth") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['pass'])&&!empty($_POST['pass'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['pass']=dataCleaner($_POST['pass']);
		}else{
			$argsMissing=true;
		}
	}

	if ($GLOBALS["headers"]['reqty']=="info") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
		}else{
			$argsMissing=true;
		}
	}
	if ($GLOBALS["headers"]['reqty']=="personal_info") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
		}else{
			$argsMissing=true;
		}
	}

	if ($GLOBALS["headers"]['reqty']=="opt_wing") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['wing'])&&!empty($_POST['wing'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['wing']=dataCleaner($_POST['wing']);
		}else{
			$argsMissing=true;
		}
	}

	if ($GLOBALS["headers"]['reqty']=="status") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
		}else{
			$argsMissing=true;
		}
	}
	if ($GLOBALS["headers"]['reqty']=="new_dayout") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['place'])&&!empty($_POST['place'])&&isset($_POST['purpose'])&&!empty($_POST['purpose'])&&isset($_POST['going_with'])&&!empty($_POST['going_with'])&&isset($_POST['phone_no'])&&!empty($_POST['phone_no'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['place']=dataCleaner($_POST['place']);
			$GLOBALS['args']['purpose']=dataCleaner($_POST['purpose']);
			$GLOBALS['args']['going_with']=dataCleaner($_POST['going_with']);
			$GLOBALS['args']['phone']=dataCleaner($_POST['phone_no']);
		}else{
			$argsMissing=true;
		}
	}
	if ($GLOBALS["headers"]['reqty']=="new_leave") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['place'])&&!empty($_POST['place'])&&isset($_POST['purpose'])&&!empty($_POST['purpose'])&&isset($_POST['going_with'])&&!empty($_POST['going_with'])&&isset($_POST['phone_no'])&&!empty($_POST['phone_no'])&&isset($_POST['parent_no'])&&!empty($_POST['parent_no'])&&isset($_POST['date_exp_out'])&&!empty($_POST['date_exp_out'])&&isset($_POST['date_exp_in'])&&!empty($_POST['date_exp_in'])&&isset($_POST['time_exp_out'])&&!empty($_POST['time_exp_out'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['place']=dataCleaner($_POST['place']);
			$GLOBALS['args']['purpose']=dataCleaner($_POST['purpose']);
			$GLOBALS['args']['going_with']=dataCleaner($_POST['going_with']);
			$GLOBALS['args']['phone']=dataCleaner($_POST['phone_no']);
			$GLOBALS['args']['parent_no']=dataCleaner($_POST['parent_no']);
			$GLOBALS['args']['date_exp_out']=dataCleaner($_POST['date_exp_out']);
			$GLOBALS['args']['date_exp_in']=dataCleaner($_POST['date_exp_in']);
			$GLOBALS['args']['time_exp_out']=dataCleaner($_POST['time_exp_out']);
		}else{
			$argsMissing=true;
		}
	}
	if ($GLOBALS["headers"]['reqty']=="cancel") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['pass_id'])&&!empty($_POST['pass_id'])&&isset($_POST['outing_type'])&&!empty($_POST['outing_type'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['pass_id']=dataCleaner($_POST['pass_id']);
			$GLOBALS['args']['outing_type']=dataCleaner($_POST['outing_type']);
		}else{
			$argsMissing=true;
		}
	}
	if ($GLOBALS["headers"]['reqty']=="history") {
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['access_token'])&&!empty($_POST['access_token'])&&isset($_POST['outing_type'])&&!empty($_POST['outing_type'])) {
			$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
			$GLOBALS['args']['access_token']=dataCleaner($_POST['access_token']);
			$GLOBALS['args']['outing_type']=dataCleaner($_POST['outing_type']);
		}else{
			$argsMissing=true;
		}
	}


	unset($_POST);unset($_GET);
	if ($argsMissing) {
		return false;
	}else{
		return true;
	}
}
//("auth", "opt_wing", "dayout_status", "leave_status", "new_dayout","new_leave", "cancel", "history")
if (checkHeaders()) {
	if (getArgs()) {
		if ($GLOBALS["headers"]['reqty']=="auth") {
			$authentication=authenticate();
			if ($authentication['Message']=="Success") {
				$GLOBALS['result']['user_id']=$authentication['LoginId'];
				$GLOBALS['result']['access_token']=$authentication['AccessToken'];
				$GLOBALS['result']['user_type']=$authentication['UserType'];
				if (userTokenExists()) {
					updateToken();
				}else{
					insertToken();
				}
			}else{
				$GLOBALS['result']['error']=true;
				$GLOBALS['result']['errorCode']="ICP";
				$GLOBALS['result']['errorMessage']="The password you entered is incorrect";
			}

		}else if($GLOBALS["headers"]['reqty']=="info"){
			if(checkToken()){
				if (!getUserBasics()) {
					$GLOBALS['result']['error']=true;
					$GLOBALS['result']['errorCode']="EUI";
					$GLOBALS['result']['errorMessage']="There is some problem in fetching info. try again";
				}
				if (userExistsInDB()){
					updateBasics();
				}else{
					insertBasics();
				}
			}
		}else if($GLOBALS["headers"]['reqty']=="personal_info"){
			if(checkToken()){
				if (!getUserPersonal()) {
					$GLOBALS['result']['error']=true;
					$GLOBALS['result']['errorCode']="EUI";
					$GLOBALS['result']['errorMessage']="There is some problem in fetching info. try again";
				}
				if (userExistsInDB()){
					updatePersonal();
				}
			}
		}else if($GLOBALS["headers"]['reqty']=="opt_wing"){
			if(checkToken()){
				optWing();
				$GLOBALS['result']['request']="success";
			}

		}else if($GLOBALS["headers"]['reqty']=="status"){
			if (checkToken()) {
				checkStatus();
			}


		}else if($GLOBALS["headers"]['reqty']=="new_dayout"){
			if (checkToken()) {
				if(newRequestStatus()){
					makeDayoutRequest();
				}
			}
		}else if($GLOBALS["headers"]['reqty']=="new_leave"){
			if (checkToken()) {
				if(newRequestStatus()){
					makeLeaveRequest();
				}
			}
		}else if($GLOBALS["headers"]['reqty']=="cancel"){
			if (checkToken()) {
				cancelRequest();

			}


		}else if($GLOBALS["headers"]['reqty']=="history"){
			if (checkToken()) {
				returnHistory();
			}

		}else{

			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="IRT";
			$GLOBALS['result']['errorMessage']="Invalid Request types";
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
?>