<?php
	require_once "createText.php";
	//define("FILE","../log/holidayList.csv");
	
	$model = new methods();
	$model->fileLoad();
	$holidays_url = sprintf(
		        'http://www.google.com/calendar/feeds/%s/public/full-noattendees?start-min=%s&start-max=%s&max-results=%d&alt=json' ,
		        'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com' , // 'japanese@holiday.calendar.google.com' ,
		        '2012-07-01' ,  // 取得開始日
		        '2017-12-31' ,  // 取得終了日
		        500             // 最大取得数
	        );
	if ( $results = file_get_contents($holidays_url) ) {
	        $results  = json_decode($results, true);
	        $holidays = array();
	        foreach ($results['feed']['entry'] as $val ) {
	                $date  = $val['gd$when'][0]['startTime'];
	                $title = $val['title']['$t'];
	                $holidays[$date] = $title;
	        }
	        ksort($holidays);
	}
	foreach($holidays as $date => $name){
	//	createText(FILE , $date.":".$name);
	}
?>