<?php
$servername="localhost";
$username="root";
$password="";
$dbname="myhostelapp";

$con=NULL;
try{
	$con=new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	$stmtCheckUseridExists=$con->prepare("SELECT user_id FROM hosteler_data where user_id=:var_user_id LIMIT 1");
	$stmtCheckUseridExists->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

	$stmtCheckHostelExists=$con->prepare("SELECT id FROM hostels where name=:var_name LIMIT 1");
	$stmtCheckHostelExists->bindParam(':var_name', $hostelName);

	$stmtGetHostelById=$con->prepare("SELECT * FROM hostels where name=:var_name LIMIT 1");
	$stmtGetHostelById->bindParam(':var_name', $hostel);

	$stmtAddHostel=$con->prepare("INSERT INTO hostels values('', :var_type,:var_name, '10')");
	$stmtAddHostel->bindParam(':var_type', $type);
	$stmtAddHostel->bindParam(':var_name', $hostelName);

	$stmtCheckTokenExists=$con->prepare("SELECT * FROM tokens_table where user_id=:var_user_id LIMIT 1");
	$stmtCheckTokenExists->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

	$stmtUpdateToken=$con->prepare("UPDATE tokens_table SET access_token =:var_access_token where user_id=:var_user_id LIMIT 1");
	$stmtUpdateToken->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtUpdateToken->bindParam(':var_access_token', $GLOBALS['result']['access_token']);

	$stmtInsertToken=$con->prepare("INSERT INTO tokens_table values(:var_user_id,:var_access_token)");
	$stmtInsertToken->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtInsertToken->bindParam(':var_access_token', $GLOBALS['result']['access_token']);

	$stmtCheckToken=$con->prepare("SELECT * FROM tokens_table where user_id=:var_user_id AND access_token=:var_access_token LIMIT 1");
	$stmtCheckToken->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtCheckToken->bindParam(':var_access_token', $GLOBALS['args']['access_token']);


	$stmtUpdateBasics=$con->prepare("UPDATE hosteler_data SET user_id =:var_user_id, gender =:var_gender, branch =:var_branch, course =:var_course, section =:var_section,  name =:var_name, hostel =:var_hostel, room =:var_room where user_id=:var_user_id LIMIT 1");
	$stmtUpdateBasics->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtUpdateBasics->bindParam(':var_gender', $GLOBALS['result']['user_info']['gender']);
	$stmtUpdateBasics->bindParam(':var_branch', $GLOBALS['result']['user_info']['branch']);
	$stmtUpdateBasics->bindParam(':var_course', $GLOBALS['result']['user_info']['course']);
	$stmtUpdateBasics->bindParam(':var_section', $GLOBALS['result']['user_info']['section']);
	$stmtUpdateBasics->bindParam(':var_name', $GLOBALS['result']['user_info']['name']);
	$stmtUpdateBasics->bindParam(':var_hostel', $GLOBALS['result']['user_info']['hostel_id']);
	$stmtUpdateBasics->bindParam(':var_room', $GLOBALS['result']['user_info']['room']);

	$stmtUpdatePersonal=$con->prepare("UPDATE hosteler_data SET phone =:var_phone, email =:var_email, img =:var_img where user_id=:var_user_id LIMIT 1");
	$stmtUpdatePersonal->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtUpdatePersonal->bindParam(':var_phone', $GLOBALS['result']['user_info']['phone']);
	$stmtUpdatePersonal->bindParam(':var_email', $GLOBALS['result']['user_info']['email']);
	$stmtUpdatePersonal->bindParam(':var_img', $GLOBALS['result']['user_info']['img']);


	$stmtInsertBasics=$con->prepare("INSERT INTO hosteler_data VALUES( :var_user_id, :var_gender, :var_name, :var_branch, :var_course, :var_section, :var_hostel, :var_room, '', '', '', '', 0, '', 0)");
	$stmtInsertBasics->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtInsertBasics->bindParam(':var_gender', $GLOBALS['result']['user_info']['gender']);
	$stmtInsertBasics->bindParam(':var_branch', $GLOBALS['result']['user_info']['branch']);
	$stmtInsertBasics->bindParam(':var_course', $GLOBALS['result']['user_info']['course']);
	$stmtInsertBasics->bindParam(':var_section', $GLOBALS['result']['user_info']['section']);
	$stmtInsertBasics->bindParam(':var_name', $GLOBALS['result']['user_info']['name']);
	$stmtInsertBasics->bindParam(':var_hostel', $GLOBALS['result']['user_info']['hostel_id']);
	$stmtInsertBasics->bindParam(':var_room', $GLOBALS['result']['user_info']['room']);



}catch(PDOException $e){
	echo "Connection Failed".$e;
}



?>