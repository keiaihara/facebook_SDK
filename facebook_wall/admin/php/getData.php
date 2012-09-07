<?php 
define("MAX",10);//pageRecords
require_once("model_base.php");
	
	$id    = htmlspecialchars($_POST["id"]);
	$pages = array("","plusstationary");
	unset($pages[0]);
	
	//$tb = "plusstationary";//外部より入力
	$methods = new methods();
	$data    = $methods->getAll($pages[$id]);
	//$data    = $methods->getAll("plusstationary");
	for($i=0;$i<count($data);$i++){
		$json["rows"][] = $data[$i];
	}
	$columns = array("id","投稿日","①投稿内容","①複数枚有無<br>(0:なし,1:あり)","①画像ファイル名","②投稿内容","②複数枚有無<br>(0:なし,1:あり)","②画像","更新日時");
	foreach($columns as $name){
		$json["columns"][] = $name;
	}
	//paging
	$all  = count($data);
	$rest = $all / MAX;
	$val  = isInt($rest);
	
	$json["all"]  = $all;//gross
	$json["page"] = $val;//page
	$json["max"]  = MAX;
	//viewへ
	echo json_encode($json);
	
	//正数判定
	function isPosi($val){
		if( 1<$val ){
			return intval(floor($val + 1));
		}
		return $val=1;
	}
	//整数判定
	function isInt($val){
		if( is_int($val)===false ){
			return isPosi($val);
		}
		return intval($val);
	}
?>