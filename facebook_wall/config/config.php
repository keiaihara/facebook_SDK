<?php
// 環境設定ファイル
define("__CONTENTS_TITLE", "facebookPages_admin");
date_default_timezone_set("Asia/Tokyo");
if(strpos(__FILE__,"/home/users/2/lolipop.jp-msa/web")===false){
	define("CONTENTS_TITLE", __CONTENTS_TITLE);
	
	// 開発環境
	define('DB_SERVER' ,"localhost");
	define('DB_PORT'   ,"");
	define('DB_USER'   ,"root");
	define('DB_PASS'   ,"");
	define('DB_NAME'   ,"facebook");
	define('DB_CHARSET',"utf8");
}else{
	// 本番環境
	define('DB_SERVER' ,".com.jp");
	define('DB_PORT'   ,"");
	define('DB_USER'   ,"LA09959773");
	define('DB_PASS'   ,"passward");
	define('DB_NAME'   ,"facebook");
	define('DB_CHARSET',"utf8");
}
//mail
define("HEADER" , "From:"."<nijibox_plus@docomo.ne.jp>");
//Apps
define("APP_ID" , 'xxxxxxx');
define("SECRET" , 'eeeee');
define("PLUS_TB", "rrrstationary");
define('DB_TB'  , "facebookpages");
 
?>