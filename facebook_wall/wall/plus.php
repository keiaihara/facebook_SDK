#!/usr/local/php5.3/bin/php
<?php
	//Load
	require_once("../config/config.php");
	require_once("../src/facebook.php");
	require_once("../model/model_base.php");
	require_once("../model/plus.php");
	//setting
	define("FIRST" ,1);
	define("SECOND",2);
	define("PAGE"  ,6);
	//fb.SDK => Apps_Instance
	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => SECRET,
	  "cookie" => false
	));
	$user_id = $facebook->getUser();
	//DBよりデータ取得
	$methods = new methods();
	$plus    = new plus();

	//SET Post Page
	//$page   = $methods->getData(PAGE);//本番
	$page = $methods->getData(3);//TESTページ

	//曜日・祝日判定
	$date  = date("Ymd");
	$youbi = $methods->getYoubi($date);
	if(trim($youbi) === "土" || trim($youbi) === "日" )exit;
	if($methods->dateChk($date) !== false)exit;
	
	$row  = $plus->getData(date("Y-m-d"));
	$num  = $plus->timeJudge();//投稿タイミング
	if($num===false)exit;
	
	//fbPageInfoSETTING
	$facebook->setAccessToken($page["access_token"]);
	$facebook->setFileUploadSupport(true);
	$pagesId = $page["pageId"];
	
	//dataSetting
	$post = (object)array(
						"pageId"       => $pagesId,
						"message"      => $row["text".$num]
	);
	/**************
	 * 
	 * wall投稿
	 * 
	 **************/
	
	$albumId = $page["albumId"];
	//attached images
	if($row["images".$num]==false && $row["img".$num] != null ) {
		if(!$page["albumId"]){
			//createAlbum
			$album = array("name"=> "ウォールの画像");//AlbumName
			$res = $facebook->api("/".$post->pageId."/albums", "post", $album);
			$methods->resCheck($plus->setAlbum($post->pageId , $res["id"]) , FIRST);
	 		$albumId = $res["id"];
		}
		//uploadPhotos
		$facebook->setFileUploadSupport(true);
		$path = dirname(__FILE__)."/../images/plus/".$row["img".$num];
		
		$data = array( 
					"message"      => $post->message,
					"source"        => "@".realpath($path)
		);
		$methods->resCheck($facebook->api("/".$albumId."/photos", "post", $data) , SECOND);
	}else{
		//no attached images
		if($num==FIRST)$plus->confirm($num);
		$data = array("message" => $post->message);
		$methods->resCheck($facebook->api("/$pagesId/feed", "post", $data) , SECOND);
	}
?>