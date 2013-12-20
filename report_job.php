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
	$goal_arr = ($_POST['res_arr']);

	foreach ($goal_arr as $value) {
		# code...
		$gid = (int)($mysqli->real_escape_string($value));
		if (in_array($gid, $_SESSION['today_job'])){
			$sql = "INSERT INTO `calendar` VALUES ($gid,".Date('Y').",".Date('m').",".Date('d').")";
			if($result = $mysqli->query($sql))
			{
				$check_res = 0;
			}
			//$result->close();
		}
	}
	$mysqli->close();
	//print_r($goal_arr);

	$return_arr = array('res'=>$check_res,'msg'=>$_SESSION['today_job']);
	echo json_encode($return_arr);
?>