<?php
	
	if(!$_GET["id"]){
		header("Location: https://www.facebook.com/plus.sta");
		exit;
	}
	require_once('../src/facebook.php');
	//require_once('../model/facebookPages.php');
	require_once("../model/model_base.php");
	header("Content-type: text/html; charset=utf-8");

	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => SECRET,
	  'cookie' => true,
	));
	$user = $facebook -> getUser();
	if (!$user) {
	  $loginUrl = $facebook -> getLoginUrl(
	    array(
	      'canvas'    => 1,
	      'fbconnect' => 0,
	      'scope'     => 'status_update,publish_stream,manage_pages,user_photos,friends_photos'
	      )
	    );
	  header( 'Location: ' . $loginUrl );
	  exit();
	}
	
	//呼び出し
	$data = $facebook->api('/me/accounts');

	//update/insert
	$methods = new methods();
  	if($methods->updateData($methods->getColumns() , $data["data"]) !== false) echo "success to Refreash!";

?>