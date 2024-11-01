<?php
	error_reporting(0);
	include_once ('../../../wp-config.php');
	function saqClickInfoJson($parm) {
		global $wpdb;
		
		$urls=explode("^", urldecode(stripcslashes ( $parm ) ));
		
		$result = array();
		
		foreach ($urls as $url){
			$result[] =  "'". $url  ."'";
		} 
		
		$source = $_SERVER["HTTP_REFERER"];
		$target = $_POST["target"]; 
		$source = $wpdb->escape($source);
		$target = $wpdb->escape($target);
		$dt = gmdate('Y-m-d H:i:s', (time() + (get_option('gmt_offset') * 3600))); 
		$res = $wpdb->get_results("SELECT URL, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks." where URL IN (".implode(',', $result).") GROUP BY URL");
 		return ($res);
	}
	header('Content-type: application/json');
	if(isset($_POST['urls'])) {
		echo json_encode( saqClickInfoJson($_POST['urls']) );
	} else {
		echo "[]";
	}
?>