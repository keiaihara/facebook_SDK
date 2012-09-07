<?php
require_once("../../config/config.php");

class methods
{
	/*************
	 * 
	 * TBカラム名取得
	 * 
	 *************/
	public function getColumns(){
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		$sql = "DESCRIBE ".DB_TB;
		$res = mysql_query($sql) or die("failed to send a query");
		while($row = mysql_fetch_assoc($res)){
			$rows[] = $row["Field"];
		}
		return $rows;
	}
	/************
	 * 
	 * Data追加
	 * 
	 ************/
	public function updateData($colums,$data)
	{
		//columnStr
		foreach($colums as $row){
			//ex columns delete
			if($row!=="id"&&$row!=="update_date"){
				$str .= $row.",";
			}
		}
		$string = rtrim($str , ",");
		
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		$error = 0;
		foreach($data as $row){
			$sql1 = "SELECT * FROM ".DB_TB." WHERE pageId = ".$row['id'];
			if(mysql_fetch_assoc(mysql_query($sql1))!==false)
			{
				//更新
				$sql  = "UPDATE ".DB_TB." SET access_token = '".$row["access_token"]."', update_date = '".date("Y-m-d H:i:s")."' WHERE pageId = '".$row["id"]."'";	
				//文字化け対策
				$sql1 = "SET NAMES utf8";
				mysql_query($sql1);
				$res = mysql_query($sql) or die("failed to Refreash Token");
				if($res===false){$error++;}
			}else{
				//新レコード追加
				$sql  = "INSERT INTO ".DB_TB."(".$string.") VALUES ('".$row["id"]."','".$row["access_token"]."','".$row["name"]."','".date('Y-m-d H:i:s')."')";
				//文字化け対策
				$sql1 = "SET NAMES utf8";
				mysql_query($sql1);
				$res = mysql_query($sql) or die("failed to INSERT a query");
				if($res===false){$error++;}
			}
		}
		if($error)return false;//error
		return true;
	}
	/************
	 * 
	 * 指定Data取得
	 * 
	 ************/
	public function getData($id)
	{	
		$rows = array();
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		
		//encode
		$sql1 = "SET NAMES utf8";
		mysql_query($sql1);
		
		//select
		//$sql  = mysql_query("SELECT * FROM facbookPages WHERE id = '".$id."'");
		$sql  = mysql_query("SELECT `id`, `pageId`, `access_token`, `pageName`,`albumId`, `create_date`, `update_date` FROM `".DB_TB."` WHERE id = '".trim($id)."'");
		while($row  = mysql_fetch_assoc($sql)){
			$rows = $row;
		}
		//NG
		if(!$rows)
		{
			return false;
  		}
		return $rows;
	}
	/************
	 * 
	 * 全Data取得
	 * 
	 ************/
	public function getAll($tb)
	{	
		$rows = array();
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		
		//encode
		$sql1 = "SET NAMES utf8";
		mysql_query($sql1);
		
		//select
		//$sql  = mysql_query("SELECT * FROM ".$tb) or die("failed a query");
		$sql  = mysql_query("SELECT id, post_date, text1, images1,img1,text2,images2,img2, update_date FROM ".$tb);
		while($row  = mysql_fetch_assoc($sql)){
			$rows[] = array_values($row);
		}
		//NG
		if(!$rows)
		{
			return false;
  		}
		return $rows;
	}
	/************
	 * 
	 * albumId挿入
	 * 
	 ************/
	public function setAlbum($pageId,$albumId)
	{	
		$rows = array();
		//DBopen
		$this->openDB();
		//encode
		$sql1 = "SET NAMES utf8";
		mysql_query($sql1);
		//update
		$sql  = "UPDATE ".DB_TB." SET albumId = '".$albumId."', update_date = '".date("Y-m-d H:j:s")."' WHERE pageId = '".$pageId."'";	
		if(mysql_query($sql)===false){return false;} 
		return true;
	}
	/************
	 * 
	 * adminData取得
	 * 
	 ************/
/*	public function getAdmin()
	{	
		$rows = array();
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		
		//encode
		$sql1 = "SET NAMES utf8";
		mysql_query($sql1);
		
		//select
		$sql  = mysql_query("SELECT * FROM admin");
		while($row  = mysql_fetch_assoc($sql)){
			$rows[] = $row;
		}
		//NG
		if(!$rows)
		{
			return false;
  		}
		return $rows;
	}*/
	/*****
	 * 
	 * テキスト生成
	 * 
	 *****/
	public function createText($fileName , $text)
	{	
		//FileName
		$str = $this->fileExist($fileName);
		//同時書き込み防止のためロック
		flock($str,LOCK_EX);
		//書き込み
		fputs($str,$text."\n");
		//解除&閉じ
		flock($str,LOCK_UN);
		fclose($str);
		return;
	}
	/**
	 * ファイルオープンモード
	 */
	public function fileExist($fileName)
	{
		if ( file_exists($fileName) !== false )
		{
			return @fopen($fileName,"a");//追記
		}else{
			return @fopen($fileName,"w");//新規作成
		}
	}
	/*****
	 * 
	 * 曜日取得関数 
	 * return 曜日
	 *****/
	public function getYoubi($date)
	{
	    $sday = strtotime($date);
	    $res = date("w", $sday);
	    $day = array("日", "月", "火", "水", "木", "金", "土");
	    return $day[$res];
	}
	/*******
	 * 
	 * fb.api result check
	 * 
	 *******/
	public function resCheck($res , $flg){
    	switch($flg):
			case 1:
				$this->updateAlbum($res);
				break;
			case 2:
				$this->photoUpload($res);
				break;
			default:
				$flg = false;
				break;
		endswitch;
	    return;
    }
    /*******
	 * 
	 * check albumId update
	 * 
	 *******/
	public function updateAlbum($res){
    	if($res === false){
    		$text = date("Y年m月d日　H時i分s秒")."　fb投稿エラー:アルバム作成失敗";
    		$this->mail($text);
    		exit;
	    }
	    return;
    }
    
    /*******
	 * 
	 * check photoUpload
	 * 
	 *******/
	public function photoUpload($res){
    	if($res === false){
    		$text = date("Y年m月d日　H時i分s秒")."　fb投稿エラー:画像アップロード失敗";
    		$this->mail($text);
    		exit;
    		//return;
   		 }
	    $str = date("Y年m月d日H:i:s")."　success to photoUpload"; 
		$fileName = "../log/success/success_".date("Ymd").".txt";
		$this->createText($fileName , $str);
	    return;
    }
	/****************
	 * 
	 * error mail
	 * 
	 ****************/
	public function mail($str){
		// 文字コード設定
		mb_language("ja");
		mb_internal_encoding("UTF-8");
		
		$fileName = "../log/error/error_".date("Ymd").".txt";
		$this->createText($fileName , date("Y年m月d日H:i:s").$str);
		mb_send_mail(PLUS,"fb投稿エラー通知",$str,HEADER);
		exit;
	}
}
?>