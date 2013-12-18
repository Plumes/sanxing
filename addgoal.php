<?php
	include "conn.php";
	$check_res =-1;
	session_start();
	// $_SESSION['uid']=1;
	if (!isset($_SESSION['uid']))
	{
		header('Location: ./');
	}
	$uid=$_SESSION['uid'];

	$g_name = $mysqli->real_escape_string($_POST['name']);
	$s_time = $mysqli->real_escape_string($_POST['stime']);
	$e_time = $mysqli->real_escape_string($_POST['etime']);
	$i_time = ($mysqli->real_escape_string($_POST['itime']));
	if (strlen($g_name) < 1) $check_res = 1;
	else {
		$str = Date($s_time." 23:59:59");
		$str1 = Date($e_time." 23:59:59");
		$interval = intval($i_time);
		if($str < Date() || $str >= $str1) $check_res = 2;
		else {
			if ($interval<0 || $interval >365) $check_res = 3;
			else {
				$sql = "INSERT INTO `goal_list` (`uid`, `name`, `start`, `end`, `interval`) VALUES (
					'$uid','$g_name','$s_time','$e_time',$i_time)";
				if($result = $mysqli->query($sql) ){
					$check_res = 0;
				}
			}
		}
	}

	$resarr = array('res'=>$check_res,'msg'=>$sql );
	echo json_encode($resarr);
?>