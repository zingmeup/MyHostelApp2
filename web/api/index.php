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
	//logger(1,"authenticate()", "start");
$url="http://uims.cuchd.in/CUServices/api/mobapp/UserCredentialValidation?UserName=".$GLOBALS['args']['uid']."&Password=".$GLOBALS['args']['pass']."&IPAddress=172.19.2.100&Source=Android";
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
	//logger(1,"authenticate()", "completed");
return $response;
}



function uidExistsInDB(){
	//logger(1,"uidExistsInDB", "start");
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	//logger(1,"uidExistsInDB", "trying");
	$checkUidExists->execute();
	//logger(1,"uidExistsInDB", "executed");
	$checkUidExists->setFetchMode(PDO::FETCH_ASSOC);
	$result=$checkUidExists->fetchAll();
	$con=null;
	if (count($result)>0) {
	//logger(1,"uidExistsInDB", "exists");
		return TRUE;
	}else{
	//logger(1,"uidExistsInDB", "not exists");
		return FALSE;
	}
}
function updateUserDetail(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$updateCredentials->execute();
	$con=null;
}

function updateBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$updateBasics->execute();
	$con=null;
}
function insertBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$insertBasics->execute();
	$con=null;
}

function fetchUserInfo(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentInfo?UserId=".$GLOBALS['result']['userInfo']['userID']."&AcessToken=".$GLOBALS['result']['userInfo']['accessToken'];
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
	$GLOBALS['result']['userInfo']['uid']=$response['Uid'];
	$GLOBALS['result']['userInfo']['name']=$response['Name'];
	$GLOBALS['result']['userInfo']['programName']=$response['ProgramName'];
	$GLOBALS['result']['userInfo']['batch']=$response['Batch'];
	$GLOBALS['result']['userInfo']['snap']=$response['Snap'];
	$GLOBALS['result']['userInfo']['mobile']=$response['Mobile'];
	$GLOBALS['result']['userInfo']['email']=$response['Email'];
	$GLOBALS['result']['userInfo']['accountNo']=$response['AccountNumber'];

}
function insertUserDetails(){
	require $_SERVER['DOCUMENT_ROOT']."/cxsn/cnct.php";
	$insertCredentials->execute();
	$con=null;

}

function fetchCourseDetails(){
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCurrentCourse?UserId=".$GLOBALS['result']['userInfo']['userID']."&AcessToken=".$GLOBALS['result']['userInfo']['accessToken'];
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
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentInfo?UserId=".$GLOBALS['args']['uid']."&AcessToken=".$GLOBALS['args']['accessToken'];
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
		if ($authentication['Message']=="Success") {
			////logger(2, "authentication", "Success");
			$GLOBALS['result']['userInfo']['userID']=$authentication['LoginId'];
			$GLOBALS['result']['userInfo']['accessToken']=$authentication['AccessToken'];
			$GLOBALS['args']['accessToken']=$authentication['AccessToken'];
			$GLOBALS['result']['userInfo']['userType']=$authentication['UserType'];
			updateTokenToDB();
			return TRUE;
		}else{
			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="ICP";
			$GLOBALS['result']['errorMessage']="The password you entered is incorrect";
			////logger(3, "Authentication failed", "WRONG PASSWORD CHANGED");
			return FALSE;

		}

		}else{
			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="IAT";
			$GLOBALS['result']['errorMessage']="Invalid AccessToken, what the fuck?";
			////logger(3, "checkDBforToken", "token dowsn;t exists, illegal thing");
		}
	}else{
			$GLOBALS['result']['userInfo']['userID']=$GLOBALS['args']['uid'];
			$GLOBALS['result']['userInfo']['accessToken']=$GLOBALS['args']['accessToken'];
		return TRUE;


	}

}

function getTimetable(){	
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentTimeTablewithLatestUpdate?UserId=".$GLOBALS['args']['uid']."&AcessToken=".$GLOBALS['args']['accessToken'];
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
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCurrentCourse?UserId=".$GLOBALS['args']['uid']."&AcessToken=".$GLOBALS['args']['accessToken'];
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
	////logger(1, "getAllAttendance", $responseJson);
	for ($i=0; $i <count($response) ; $i++) {
		$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCourseWiseAttendance?UserId=".$GLOBALS['args']['uid']."&AcessToken=".$GLOBALS['args']['accessToken']."&CourseCode=".$response[$i]['CourseCode'];
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
	$url="http://www.cuims.in/CUServices/api/mobapp/GetStudentCourseWiseAttendance?UserId=".$GLOBALS['args']['uid']."&AcessToken=".$GLOBALS['args']['accessToken']."&CourseCode=".$GLOBALS['args']['attCode'];
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

}
 
function checkHeaders(){
	$validHeaders=array("auth", "timetable", "attendance_one", "attendance_all", "user_info","course_info", "test_auth");
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
		//logger(1, "Header", "auth" );

	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['pass'])&&!empty($_POST['pass'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['pass']=dataCleaner($_POST['pass']);
		//logger(1, "All arguments valid", "Yes" );
	}else{
		//logger(3, "All arguments valid", "NO" );
		$argsMissing=true;
	}
	}

	if ($GLOBALS["headers"]['reqty']=="timetable") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
	}else{
		$argsMissing=true;
	}
	}

	if ($GLOBALS["headers"]['reqty']=="attendance_one") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])&&isset($_POST['attCode'])&&!empty($_POST['attCode'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
		$GLOBALS['args']['attCode']=dataCleaner($_POST['attCode']);
	}else{
		$argsMissing=true;
	}
	}

	if ($GLOBALS["headers"]['reqty']=="attendance_all") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
	}else{
		$argsMissing=true;
	}
	}
	if ($GLOBALS["headers"]['reqty']=="user_info") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
	}else{
		$argsMissing=true;
	}
	}

	if ($GLOBALS["headers"]['reqty']=="course_info") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
	}else{
		$argsMissing=true;
	}
	}
	if ($GLOBALS["headers"]['reqty']=="test_auth") {
	if (isset($_POST['uid'])&&!empty($_POST['uid'])&&isset($_POST['accessToken'])&&!empty($_POST['accessToken'])) {
		$GLOBALS['args']['uid']=dataCleaner($_POST['uid']);
		$GLOBALS['args']['accessToken']=dataCleaner($_POST['accessToken']);
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


echo hash_hmac("sha1", "My name is deepak", 'myKey12345');
echo "<br>";
$algolist=hash_algos();
for ($i=0; $i <count($algolist) ; $i++) { 
	echo $algolist[$i]."<br>";
}

if (checkHeaders()) {
if (getArgs()) {
	echo $GLOBALS["headers"];
	if ($GLOBALS["headers"]['reqty']=="auth") {
		//logger(1, "Flow", "trying auth");
		$authentication=authenticate();
		//logger(2, "Flow", "auth completed");
		if ($authentication['Message']=="Success") {
			//logger(2, "authentication", "sucess");
			$GLOBALS['result']['userInfo']['userID']=$authentication['LoginId'];
			$GLOBALS['result']['userInfo']['accessToken']=$authentication['AccessToken'];
			$GLOBALS['result']['userInfo']['userType']=$authentication['UserType'];	
			if (uidExistsInDB()) {
			//logger(2, "uid exists in db", "TRUE");
				updateBasics();
			}else{
			//logger(3, "uid exists in db", "TRUE");
				insertBasics();
			}	
		}else{
			//logger(3, "authentication", "failed");
			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="ICP";
			$GLOBALS['result']['errorMessage']="The password you entered is incorrect";
		}
	}else if($GLOBALS["headers"]['reqty']=="timetable"){
		if(checkToken()){
			getTimetable();
		}

	}else if($GLOBALS["headers"]['reqty']=="attendance_all"){
		if (checkToken()) {
			getAllAttendance();
		}


	}else if($GLOBALS["headers"]['reqty']=="attendance_one"){
		if (checkToken()) {
			getOneAttendance();
		}


	}else if($GLOBALS["headers"]['reqty']=="user_info"){
		if (checkToken()) {
			fetchUserInfo();
			if (uidExistsInDB()) {
				updateUserDetail();
			}
		}
	}else if($GLOBALS["headers"]['reqty']=="course_info"){
		if (checkToken()) {
			fetchCourseDetails();
		}


	}else if($GLOBALS["headers"]['reqty']=="test_auth"){
		if (checkToken()) {
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
	////logger(1,"getArgs","false");
}

}else{

			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="IH";
			$GLOBALS['result']['errorMessage']="Invalid Headers";
}
echo json_encode($GLOBALS['result']);
?>