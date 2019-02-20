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


	$stmtCheckStatus=$con->prepare("SELECT status, outing_type, bonus,hostel FROM hosteler_data where user_id=:var_user_id LIMIT 1");
	$stmtCheckStatus->bindParam(':var_user_id', $GLOBALS['args']['user_id']);



	$stmtgetLeaveTicket=$con->prepare("SELECT * FROM active_leave where user_id=:var_user_id LIMIT 1");
	$stmtgetLeaveTicket->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

	$stmtuniqueInLeave=$con->prepare("SELECT otp FROM active_leave where otp=:var_otp LIMIT 1");
	$stmtuniqueInLeave->bindParam(':var_otp', $otp);

	$stmtuniqueInDayout=$con->prepare("SELECT otp FROM active_dayout where otp=:var_otp LIMIT 1");
	$stmtuniqueInDayout->bindParam(':var_otp', $otp);


	$stmtgetDayoutTicket=$con->prepare("SELECT * FROM active_dayout where user_id=:var_user_id LIMIT 1");
	$stmtgetDayoutTicket->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

	$stmtgetHostelOfUser=$con->prepare("SELECT hostel FROM hosteler_data where user_id=:var_user_id LIMIT 1");
	$stmtgetHostelOfUser->bindParam(':var_user_id', $GLOBALS['args']['user_id']);


	$stmtmakeDayoutRequest=$con->prepare("INSERT INTO active_dayout VALUES( '', :var_user_id, :var_otp, :var_hash, :var_phone, :var_place, :var_purpose, :var_going_with, :var_hostel_id, :var_date_of_apply, :var_time_of_apply, '', '', '', '', '', '', '2')");
	$stmtmakeDayoutRequest->bindParam(':var_place', $GLOBALS['args']['place']);
	$stmtmakeDayoutRequest->bindParam(':var_purpose', $GLOBALS['args']['purpose']);
	$stmtmakeDayoutRequest->bindParam(':var_going_with', $GLOBALS['args']['going_with']);
	$stmtmakeDayoutRequest->bindParam(':var_date_of_apply', $date_of_apply);
	$stmtmakeDayoutRequest->bindParam(':var_time_of_apply', $time_of_apply);
	$stmtmakeDayoutRequest->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtmakeDayoutRequest->bindParam(':var_phone', $GLOBALS['args']['phone']);
	$stmtmakeDayoutRequest->bindParam(':var_hostel_id', $GLOBALS['args']['hostel_id']); 
	$stmtmakeDayoutRequest->bindParam(':var_otp', $otp);
	$stmtmakeDayoutRequest->bindParam(':var_hash', $hash);

	$stmtupdateUserStatusToRequested=$con->prepare("UPDATE hosteler_data SET status ='1', outing_type=:var_outing_type where user_id=:var_user_id LIMIT 1");
	$stmtupdateUserStatusToRequested->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtupdateUserStatusToRequested->bindParam(':var_outing_type', $outing_type);

	$stmtupdateUserStatusToAccepted=$con->prepare("UPDATE hosteler_data SET status ='2', outing_type=:var_outing_type where user_id=:var_user_id LIMIT 1");
	$stmtupdateUserStatusToAccepted->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtupdateUserStatusToAccepted->bindParam(':var_outing_type', $outing_type);

	$stmtmakeLeaveRequest=$con->prepare("INSERT INTO active_leave VALUES( '', :var_user_id, :var_otp, :var_hash, :var_phone, :var_place, :var_purpose, :var_going_with, :var_hostel_id, :var_date_of_apply, :var_time_of_apply, :var_date_exp_out, :var_date_exp_in, :var_time_exp_out, :var_parent_no, '', '', '', '', '', '','','', '1')");
	$stmtmakeLeaveRequest->bindParam(':var_user_id', $GLOBALS['args']['user_id']);
	$stmtmakeLeaveRequest->bindParam(':var_otp', $otp);
	$stmtmakeLeaveRequest->bindParam(':var_hash', $hash);
	$stmtmakeLeaveRequest->bindParam(':var_phone', $GLOBALS['args']['phone']);
	$stmtmakeLeaveRequest->bindParam(':var_place', $GLOBALS['args']['place']);
	$stmtmakeLeaveRequest->bindParam(':var_purpose', $GLOBALS['args']['purpose']);
	$stmtmakeLeaveRequest->bindParam(':var_going_with', $GLOBALS['args']['going_with']);
	$stmtmakeLeaveRequest->bindParam(':var_hostel_id', $GLOBALS['args']['hostel_id']);
	$stmtmakeLeaveRequest->bindParam(':var_date_of_apply', $date_of_apply);
	$stmtmakeLeaveRequest->bindParam(':var_time_of_apply', $time_of_apply); 
	$stmtmakeLeaveRequest->bindParam(':var_date_exp_out', $GLOBALS['args']['date_exp_out']);
	$stmtmakeLeaveRequest->bindParam(':var_date_exp_in', $GLOBALS['args']['date_exp_in']);
	$stmtmakeLeaveRequest->bindParam(':var_time_exp_out', $GLOBALS['args']['time_exp_out']);
	$stmtmakeLeaveRequest->bindParam(':var_parent_no', $GLOBALS['args']['parent_no']);


	$stmtupdateUserStatusToNull=$con->prepare("UPDATE hosteler_data SET status ='0', outing_type='' where user_id=:var_user_id LIMIT 1");
	$stmtupdateUserStatusToNull->bindParam(':var_user_id', $GLOBALS['args']['user_id']); 

	$stmtcancelDayout=$con->prepare("DELETE FROM active_dayout where user_id=:var_user_id LIMIT 1");
	$stmtcancelDayout->bindParam(':var_user_id', $GLOBALS['args']['user_id']);


	$stmtcancelLeave=$con->prepare("DELETE FROM active_leave where user_id=:var_user_id LIMIT 1");
	$stmtcancelLeave->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

	$stmtLeaveHistory=$con->prepare("SELECT id,place,purpose,going_with,date_out,date_in,time_out,time_in,sec_out_id,sec_in_id,late,phone FROM history_leave where user_id=:var_user_id ORDER BY id DESC");
	$stmtLeaveHistory->bindParam(':var_user_id', $GLOBALS['args']['user_id']);


	$stmtDayoutHistory=$con->prepare("SELECT id,place,purpose,going_with,date_out,date_in,time_out,time_in,sec_out_id,sec_in_id,late,phone FROM history_dayout where user_id=:var_user_id ORDER BY id DESC");
	$stmtDayoutHistory->bindParam(':var_user_id', $GLOBALS['args']['user_id']);

}catch(PDOException $e){
	echo "Connection Failed".$e;
}



?>