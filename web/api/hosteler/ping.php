<?php
//include 'res/logger.php';
function checkHeaders(){
	$GLOBALS['headers']=getallheaders();
	if (isset($GLOBALS['headers']['reqty'])&&!empty($GLOBALS['headers']['reqty'])){
		if ($GLOBALS['headers']['reqty']=='ping') {
			return true;
		}
	}
	return false;
}

if (checkHeaders()) {
	$GLOBALS['result']['ping']='success';
}else{

	$GLOBALS['result']['ping']='failed';
}


echo json_encode($GLOBALS['result']);