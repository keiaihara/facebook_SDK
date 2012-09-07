<?php
require_once "model_base.php";
class plus extends methods
{
	/*************
	 * 
	 * DBopen
	 * 
	 *************/
	public function openDB(){
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
	}
	/*************
	 * 
	 * TBカラム名取得
	 * 
	 *************/
	public function getColumns(){
		//DBopen
		$this->openDB();
		$sql = "DESCRIBE ".PLUS_TB;
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
		$this->openDB();
		$error = 0;
		foreach($data as $row){
			$sql1 = "SELECT * FROM ".PLUS_TB." WHERE pageId = ".$row['id'];
			if(mysql_fetch_assoc(mysql_query($sql1))!==false)
			{
				//更新
				$sql  = "UPDATE ".PLUS_TB." SET access_token = '".$row["access_token"]."', update_date = '".date("Y-m-d H:j:s")."' WHERE pageId = '".$row["id"]."'";	
				//文字化け対策
				$sql1 = "SET NAMES utf8";
				mysql_query($sql1);
				$res = mysql_query($sql) or die("failed to Refreash Token");
				if($res===false){$error++;}
			}else{
				//新レコード追加
				$sql  = "INSERT INTO ".PLUS_TB."(".$string.") VALUES ('".$row["id"]."','".$row["access_token"]."','".$row["name"]."','".date('Y-m-d H:j:s')."')";
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
	 * Data取得
	 * 
	 ************/
	public function getData($post_date)
	{	
		$rows = array();
		//DBopen
		$this->openDB();
		//encode
		$sql1 = "SET NAMES utf8";
		mysql_query($sql1);
		
		//select
		//$sql  = "SELECT id, post_date, text1, images1, img1,text2,images2,img2 FROM ".PLUS_TB." WHERE 'post_date' = '".trim($post_date)."'<br>";
		$sql  = "SELECT * FROM ".PLUS_TB." WHERE post_date = '".trim($post_date)."'";
		$res  = mysql_query($sql) or die("failed to GETDATA a query");
		while($row  = mysql_fetch_assoc($res) ){
			$rows = $row;
		}
		//NG
		if(!$rows)
		{
			return false;
  		}
		return $rows;
	}
	/*******
	 * 
	 * Judge Post time
	 * 
	 *******/
	public function timeJudge(){
    	switch(date("H")):
			case 15:
				$flg = FIRST;
				break;
			case 16:
				$flg = SECOND;
				break;
			default:
				$flg = false;
				break;
		endswitch;
		return $flg;
    }
	/****************
	 * 
	 * 前日確認メール送信
	 * 
	 ****************/
	public function confirm($num){
		// 文字コード設定
		mb_language("ja");
		mb_internal_encoding("UTF-8");

		$youbi = parent::getYoubi(date("Ymd"));
		if($youbi!=="金" && $num == FIRST){
		//if($youbi!=="金"){
			$tommorrow = $this->getData( date("Y-m-d", strtotime("+1 day")) );
			$subject = "【".date("Y年m月d日", strtotime("+1 day"))."投稿内容確認】";
		}else{
			$tommorrow = $this->getData( date("Y-m-d", strtotime("+3 day")) );
			$subject = "【".date("Y年m月d日", strtotime("+3 day"))."投稿内容確認】";
		}
		for($i=1; $i<3; $i++){
			$body[] = "【第".$i."回目投稿】".$tommorrow["text".$i];
			$body[] = "【第".$i."回目画像】　http://example/fb/images/plus/".$tommorrow["img".$i];
		}
		$mail = implode("\n\n",$body);
		if($num==FIRST){
			if(!mb_send_mail(PLUS,$subject,$mail,HEADER))return;
		}
		return;
	}
}
?>