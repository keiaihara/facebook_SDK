<?php
require_once("plus_model.php");
	$val = $_POST;
	/*$val = array(
					"post_date" => "2012-09-10",
					"text1" => "ssss",
					"images1" => 1,
					"img1" => "dfadfa",
					"text2" => "ssdddss",
					"images2" => 0,
					"img2" => "dfadfa",
					"update_date" => "2012-09-09 12:02:02");*/
	//$val = json_decode(array("id"=>1));
	//exit;
	
	$tb = "plusstationary";//外部より入力
	$plus = new plus();
	$data = $plus->updatePlusData($tb,$val);
	if($data!==false){
		json_encode(array("result" => true));
		exit;
	}
?>