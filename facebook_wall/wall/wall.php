#!/usr/local/php5.3/bin/php
<?php
	//アプリ
	require_once("../src/facebook.php");
	
	$facebook = new Facebook(array(
	  'appId'  => '233189910122713',
	  'secret' => '5fff7bd0f2a8d6e535e89fcb62b89015',
	  "cookie" => false
	));
	
	//DBよりデータ取得
	
	//アクセストークン取得//2012.05.22
	$access_token = "AAAD3cmjHI0UBABilVlpngej9EFhI0cEkIU0jjmjcvs0ZCV0oRMt2dekxRTV0Fn9cM3oRsSZChGRgvlHu4rYbkhFuxnrPQWVOF11gepJnZBQ7QXEPyRZB";

	//各投稿FacebookページID
	$pagesId = "167578746647749";

	//dataSetting
	$data = (object)array(
							"access_token" => $access_token,
							"pagesId"      => $pagesId,
							"message"      => "ss"
	);
	 /*************
	 * 
	 * wall投稿
	 * 
	 **************/
	$msg = array( 
					"access_token" => $data->access_token,
					"message"      => $data->message,
					"link"         => "http://www.yahoo.co.jp/",
					"picture"      => "http://msa.lolipop.jp/aihara/wall/images/images.jpg",
					//"name"         => "picture",
					//"caption"     => $str,
				);
	$statusUpdate = $facebook->api("/$data->pagesId/feed", "post", $msg);*/
	/**
	 * 
	 * 画像複数枚でのwall投稿
	 * 
	 */
//	$path = 'C:/xampp/htdocs/wall/images/1804220.JPG';
/*    $facebook->setFileUploadSupport(true);
	//for($i=0;$i<3;$i++){
		$data = array( 
					"access_token" => $access_token,
					"message"      => "photos",
					"picture"      => mb_convert_encoding("@{$path}", "SJIS", "UTF-8"),
					"name"         => "ALBUM",
		);
		//$res = $facebook->api("/$pagesId/photos", "post", $data);
		$res = $facebook->api('/'. $ALBUM_ID . '/photos', 'post', $data);
		
	}
	
	
	/******
	 * 
	 * albumCreate
	 * @param 
	 * @return albumId
	 * 
	 *****/
/*	function createAlbum($album_name , $description){
		$data = array( 
					"access_token" => $access_token,
					"name"         => $album_name,
					"message"      => $description
					
		);
		$res = $facebook->api("/$pagesId/albums", "post", $data);
		return $res["id"];
	}*//*
	$data = array( 
					"access_token" => $access_token,
					"name"         => "ALBUM99",
					"message"      => "createAlbum by API on Cron"
			);*/
	//$res = $facebook->api("/$pagesId/albums", "post", $data);
	//$albumId = $res["id"];
	
	//$albumId = "313390782066544";
	//exit;
	/*****
	 * 
	 * photos
	 * 
	 *****/
	//画像パス指定
	//$path = 'C:\xampp\htdocs\wall/images/1804220.JPG';
	//相対パス指定
	//$path = '../images/1804220.JPG';
    //$facebook->setFileUploadSupport(true);
 /*	for($i=0;$i<3;$i++){
		$data = array( 
					"access_token" => $access_token,
					"message"      => "photos_caption",
					"picture"      => mb_convert_encoding("@{$path}", "SJIS", "UTF-8"),
					//"image"      => "@".realpath($path),
					"name"         => "PHOTO_".$i."\nhttps://lolipop-msa.ssl-lolipop.jp/aihara/wall/images/images.jpg",
		);
	//	$res = $facebook->api("/$albumId/photos", "post", $data);
		var_dump($res);
	}*/
    
	
	
?>