<?php 
require_once 'admin.php';
require_once 'pager.php';
require_once 'lib/csv.php';
require_once 'lib/csv.lib.php';
require_once '../KumaronTweet.php';

// 初期値
$messageList = array();
$mode = $_GET['mode'];
if(!$mode) $mode = 'theme_list'; //デフォルト画面

// 処理
switch($mode):

	// -------------------------------------------
	// [つぶやき管理]：削除
	// -------------------------------------------

	case 'theme_remove':
		
		$id = $_GET['id'];
		
		//削除
		deleteData($id);
		
		$mode = 'theme_list';
		break;

	// -------------------------------------------
	// [つぶやき管理]：CSVインポート★
	// -------------------------------------------

	case 'theme_upload':
		$table  = new kumaronTweet();
		$fields = array('id', 'text1', 'img1','text2','img2','text3','img3','text4','img4', 'create_date','update_date','public_date');
		
		$data = setTemplatesForAdmin( $table, $fields );
	
		header("Location: " . $_SERVER['PHP_SELF']."?mode=theme_list");
		break;

	// -------------------------------------------
	// [つぶやき管理]：CSVエクスポート★
	// -------------------------------------------

	case 'theme_mst_csv':
		$table  = new KumaronTweet();
		$params = array();
		$params = $_GET;
		$params['filename'] = "tweetList";		// ファイル名の指定

		getTemplatesForAdmin($table,0, 10000,$params);
		break;
		
exit;
endswitch;
?>