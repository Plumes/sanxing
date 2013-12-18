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

	$gid = intval($mysqli->real_escape_string($_POST['gid']));
	$sql = "DELETE FROM `goal_list` WHERE `uid`=$uid AND `id`=$gid ";
	if($result = $mysqli->query($sql))
	{
		$check_res = 0;
	}
	$res_arr = array('res'=>$check_res,'msg'=>"");
	echo json_encode($res_arr);

?>