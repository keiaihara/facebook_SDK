#!/usr/local/php5.3/bin/php
<?php
	//アプリ
	require_once("../src/facebook.php");
	$facebook = new Facebook(array(
	  "appId"  => "272070756213573",
	  "secret" => "d4b8f0f4087c0ad050b3e8cf5ed41b90",
	  "cookie" => false,
	));
	 
	//アクセストークン
	//$user_token = "AAAD3cmjHI0UBAMUfXcGoCUgFntadAke8UsCo3nZCFSZB0LmOVVxxiYBBaGRnEZCz8SGOmqosZCugRzdi6SV9pivPqUv6JVaTkFdxfdSGT79HSzQ5Cy7b";
	$access_token = "AAAD3cmjHI0UBANeDHU2o5w3qn9K610kPrPZBb6ng0AueJZCKYNl175ERldLomn2OCT57WHwa0SFb4tjRnOnCQ8z1BS7wwOutEEa7V0fu5uwW8RQvOO";
	
	//投稿FacebookページID
	$pagesId = "167578746647749";//テスト用
	
	//ログイン判定
	$user = $facebook->getUser();
	if($user) {
	  $str = "loggined user";
	} else {
	  $str = "not loggined user";
	}
	
	$data = array( 
		"access_token" => $access_token,
		"message"      => "testMessage",
		"link"         => "http://www.yahoo.co.jp/",
		//"picture"      => "http://msa.lolipop.jp/aihara/wall/images/images.jpg",
		//"name"         => "picture",
		"caption"     => $str,
	);
	$statusUpdate = $facebook->api("/$pagesId/feed", "post", $data);
?>