<?php
	require_once('src/facebook.php');
	header("Content-type: text/html; charset=utf-8");
	$facebook = new Facebook(array(
	  'appId'  => '272070756213573',
	  'secret' => 'd4b8f0f4087c0ad050b3e8cf5ed41b90',
	  'cookie' => true,
	));
	 
	$user = $facebook -> getUser();
	if ( !$user ) 
	{
	  $loginUrl = $facebook -> getLoginUrl(
	    array(
	      'canvas'    => 1,
	      'fbconnect' => 0,
	      //'scope'     => 'status_update,publish_stream,manage_pages,offline_access'
	      'scope'     => 'status_update,publish_stream,manage_pages'
	      )
	    );
	 
	  header( 'Location: ' . $loginUrl );
	  exit();
	}
	 
	try 
	{
	  $data = $facebook->api('/me/accounts');
	  print_r($data);
	} catch (FacebookApiException $e) {
	  echo  $e->getMessage();
	  exit();
}
?>