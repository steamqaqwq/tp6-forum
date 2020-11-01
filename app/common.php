<?php
// 应用公共文件
// 1803010136 格式化时间为 xx前
function dateInit($date){
	$now = time();
	$remain = $now-$date;
	$m = 24*60*60*30;
	$d = 24*60*60;
	if($remain<60){
		return $remain.'秒前';
	}elseif($remain<3600){
		return floor($remain/60).'分钟前';
	}elseif($remain<$d){
		return floor($remain/3600).'小时前';
	}elseif($remain<$m){
		return floor($remain/$d).'天前';
	}elseif($remain<12*$m){
		return floor($remain/$m).'月前';
	}else{
		return date("Y-m-d",$date);
	}
}