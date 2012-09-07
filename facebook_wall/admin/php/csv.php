<?php
require_once dirname(__FILE__)."/plus/plus_model.php";
$plusTable = new plus();
$flg = $_POST["id"];
if($_FILES['upfile'])$plusTable->csvImport($_FILES['upfile']);
	switch($flg):
		case 2:
			$plusTable->csvExport();
			break;
		default:
			break;
	endswitch;
?>
