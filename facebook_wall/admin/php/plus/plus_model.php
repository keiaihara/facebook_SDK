<?php
require_once(dirname(__FILE__)."/../../../config/config.php");
class plus
{
	/*************
	 * 
	 * TBカラム名取得
	 * 
	 *************/
	public function getPlusColumns()
	{
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
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
	public function updatePlusData($tb,$data)
	{	
		$error = 0;
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		//$sql1 = "SELECT * FROM ".PLUS_TB." WHERE id = ".$data['id'];
		$sql1 = "SELECT * FROM ".PLUS_TB." WHERE post_date = '".$data["post_date"]."'";
		if(mysql_fetch_assoc(mysql_query($sql1)) != false)
		{
			//更新 id , post_date, text1, images1,img1,text2,images2,img2, update_date
			$sql  = "UPDATE ".PLUS_TB." SET post_date = '".$data["post_date"]."', text1 = '".mysql_real_escape_string($data["text1"])."',images1 = '".intval($data["images1"])."',img1 = '".$data["img1"]."',text2 = '".mysql_real_escape_string($data["text2"])."', images2 = '".intval($data["images2"])."', img2 = '".$data["img2"]."' ,update_date = '".date("Y-m-d H:i:s")."' WHERE post_date = '".$data["post_date"]."'";
			//文字化け対策
			$sql2 = "SET NAMES utf8";
			mysql_query($sql2);
			$res  = mysql_query($sql) or die("failed to UPDATE : ".$data["post_date"]."データで失敗");
			if($res===false){$error++;}
		}else{
			//新レコード追加
			$sql  = "INSERT INTO ".PLUS_TB."(post_date, text1, images1, img1, text2, images2, img2, create_date) VALUES ('".$data["post_date"]."','".mysql_real_escape_string($data["text1"])."',".intval($data["images1"]).",'".$data["img1"]."','".mysql_real_escape_string($data["text2"])."',".$data["images2"].",'".$data["img2"]."','".date("Y-m-d H:i:s")."')";
			//文字化け対策
			$sql2 = "SET NAMES utf8";
			mysql_query($sql2);
			$res  = mysql_query($sql) or die("failed to INSERT");
			if($res===false){$error++;}
		}
		if($error)return false;//error
		return true;
	}
	/************
	 * 
	 * Data削除
	 * 
	 ************/
	public function deletePlusData($tb,$data)
	{	
		$error = 0;
		//DBopen
		$link = mysql_pconnect(DB_SERVER,DB_USER,DB_PASS);
		if( !$link ) {die("failed to DBconnect");}	
		if( mysql_select_db(DB_NAME,$link) == false ) {die("failed to DBselect");}
		
		$sql  = "DELETE FROM ".$tb." WHERE id = " .$data["id"];
		
		$res  = mysql_query($sql) or die("failed to DELETE");
		if($res===false){$error++;}
		if($error)return false;//error
		
		return true;
	}
	/************
	 * 
	 * 指定Data取得
	 * 
	 ************/
	public function getPlusData($id)
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
		$sql  = mysql_query("SELECT 'id' , 'post_date', 'text1', 'images1','img1','text2','images2','img2', 'update_date' FROM `".PLUS_TB."` WHERE id = '".trim($id)."'");
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
	public function getPlusAll($tb)
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
		$sql  = mysql_query("SELECT id, post_date, text1, images1,img1,text2,images2,img2, create_date,update_date FROM ".$tb);
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
	// -------------------------------------------------------------
	// [月別テーマ]：CSVエクスポート
	// -------------------------------------------------------------
	public function csvExport()
	{
		//csvファイル名セット
		$csv_file = PLUS_TB.".csv";
		//csvデータの初期化
		$csv_data = "";
		//csvに書き出すデータ取得
		$colomns = $this->getPlusColumns();//columns
		$plusTBL = $this->getPlusAll(PLUS_TB);//columns
		//csvデータ作成
		$csv_data .= implode("," , $colomns)."\"\n";
		for($i=0;$i<count($plusTBL);$i++)
		{
			$csv_data .= '"'.implode('","', $plusTBL[$i])."\"\n";
		}
		//mimeタイプ作成/ファイル名の表示
		header( "Content-Type: text/tab-separated-values" );
		header( "Content-Disposition: attachement; filename=".$csv_file );
		//データ出力
		echo mb_convert_encoding($csv_data, 'SJIS', 'UTF-8');
		//echo $csv_data;
	}

	// -------------------------------------------------------------
	// [月別テーマ]：CSVアップロード
	// -------------------------------------------------------------

	public function csvImport($files)
	{
		$upload_file = dirname(__FILE__).'/upload_file.csv';
		if (move_uploaded_file($files['tmp_name'], $upload_file) == FALSE){
			error_message('アップロードに失敗しました('.$files['error'].')');
			exit;
		}
		$csvlist = $this->get_csv_array($upload_file);
		foreach($csvlist as $row){
			$this->updatePlusData(PLUS_TB,$row);
		}
		@unlink($upload_file);
		$message = $cnt.'件のテンプレートを追加・更新しました。';
		header("Location: " . $_SERVER['PHP_SELF']."../../../index.php");
	}
	/**
	 * CSVファイルを連想配列として取得
	 *
	 * @param string $filepath
	 * @return array
	 */
	public function get_csv_array($filepath)
	{
		$i = 0;
		$csvlist = array();

		$handle  = fopen($filepath, "r");
		$columns = $this->getPlusColumns();
		while (($data = $this->fgetcsv_reg($handle)) !== false) {
			$i++;
			mb_convert_variables('UTF-8', 'SJIS', $data);

			$num = count($data);
			$row2 = array();
			for($j=0 ; $j < $num ; $j++){
				$row2[$columns[$j]] = $data[$j];
			}
			$csvlist[] = $row2;
		}
		fclose($handle);
		return $csvlist;
	}

	/**
     * ファイルポインタから行を取得し、CSVフィールドを処理する
     * @param resource handle
     * @param int length
     * @param string delimiter
     * @param string enclosure
     * @return ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
     */
    public function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
            $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }
}
?>