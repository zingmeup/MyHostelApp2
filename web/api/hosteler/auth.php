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
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
	if($stmtUpdateToken->execute()){
		return TRUE;
	}else{
		return FALSE;
	}
}

function userExistsInDB(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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
		if (array_key_exists('HostelName', $response)){

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

		}else{
			return FALSE;
		}
		return TRUE;
	}else{
		return FALSE;
	}
}

function registerHostel($hostelName, $type){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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


function insertBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
	if($stmtInsertBasics->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
	
}

function updateBasics(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
	if($stmtUpdatePersonal->execute()){
		$con=null;
		return TRUE;
	}else{
		$con=null;
		return FALSE;
	}
}



function checkToken(){
	require $_SERVER['DOCUMENT_ROOT']."/api/hosteler/cnxn/546956327.php";
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
		if ($GLOBALS['headers']['reqty']=='auth') {
			return true;
		}
	}
	return false;
}

function getArgs(){
	$argsMissing=false;
	if (isset($_POST['user_id'])&&!empty($_POST['user_id'])&&isset($_POST['pass'])&&!empty($_POST['pass'])) {
		$GLOBALS['args']['user_id']=dataCleaner($_POST['user_id']);
		$GLOBALS['args']['pass']=dataCleaner($_POST['pass']);
	}else{
		$argsMissing=true;
	}
	return !$argsMissing;
}

if (checkHeaders()) {
	if (getArgs()) {
		$authentication=authenticate();
		if ($authentication['Message']=="Success") {
			$GLOBALS['result']['user_id']=$authentication['LoginId'];
			$GLOBALS['result']['access_token']=$authentication['AccessToken'];
			$GLOBALS['args']['access_token']=$authentication['AccessToken'];
			$GLOBALS['result']['user_type']=$authentication['UserType'];
			if ($authentication['UserType']=='S') {
				if (userTokenExists()) {
					updateToken();
				}else{
					insertToken();
				}
				if (!getUserBasics()) {
					$GLOBALS['result']['error']=true;
					$GLOBALS['result']['errorCode']="EUI";
					$GLOBALS['result']['errorMessage']="There is some problem in fetching info. try again";
				}else{
					if (userExistsInDB()){
						updateBasics();
					}else{
						insertBasics();
					}
					if (!getUserPersonal()) {
						$GLOBALS['result']['error']=true;
						$GLOBALS['result']['errorCode']="EUI";
						$GLOBALS['result']['errorMessage']="There is some problem in fetching info. try again";
					}else{
						if (userExistsInDB()){
							updatePersonal();
						}

					}
				}
			}
		}else{
			$GLOBALS['result']['error']=true;
			$GLOBALS['result']['errorCode']="ICP";
			$GLOBALS['result']['errorMessage']="The password you entered is incorrect";
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