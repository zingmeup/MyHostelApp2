<?php
$starting=1842;
$course="BCS";
$startingyear=16;
$ending=1940;
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

function getProfileImage($encryptedValue, $uid){
	$url='https://uims.cuchd.in/UIMS/DisplayImage.ashx?UserId='.$encryptedValue;
	$save_to='images/pp/';
	$filename=basename($url);
	$image=file_get_contents($url);
	if(strlen($image)!=2155&&strlen($image)>0){
		if ($image) {
			file_put_contents($save_to.$uid.'.jpg', $image);
			echo "<h3 style=\"color:red;\">FOUND: $uid</h3>";
		}
	}else{
		if (strlen($image)==2155) {
			$nullImage=file_get_contents($save_to.'null/null.jpg');
			if(strcmp($image, $nullImage)!=0){
				if ($image) {
					file_put_contents($save_to.$uid.'.jpg', $image);
					echo "<h3 style=\"color:red;\">BUGGY: $uid</h3>";
				}
			}
		}
	}
}
for ($i=$starting; $i < $ending; $i++) { 
	$uid=$startingyear.$course.$i;
	getProfileImage(getEncryption($uid), $uid);
}