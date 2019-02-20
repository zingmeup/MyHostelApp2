<?php
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
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
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
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
	if($stmtUpdateToken->execute()){
		return TRUE;
	}else{
		return FALSE;
	}
}


function userExistsInDB(){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
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

function registerHostel($hostelName, $type){
	require $_SERVER['DOCUMENT_ROOT']."/hosteler/cnxn/conxhosteler.php";
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

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Style all input fields */
input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
}

/* Style the submit button */
input[type=submit] {
  background-color: #329eda;
  color: white;
}

/* Style the container for inputs */
.container {
  background-color: #f1f1f1;
  padding: 20px;
}

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
</head>
<body style="background-color : #3a3a3a">

<div class="container" style="
	margin-top: 30px;"><h1 style="font-size: 40px; color: #212121"><img src="logo.jpg" width="50" height="100"> My Hostel App</h1>
	
</div>

<div class="container">
  <form action="/action_page.php">
    <label for="user_id">Username</label>
    <input type="text" id="user_id" name="user_id" required>

    <label for="pass">Password</label>
    <input type="password" id="pass" name="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
    
    <input type="submit" value="Submit">
  </form>
</div>

<div id="message">
  <h3>Password must contain the following:</h3>
  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
  <p id="number" class="invalid">A <b>number</b></p>
  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
</div>
				
<script>
var myInput = document.getElementById("pass");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 5) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>

</body>
</html>
