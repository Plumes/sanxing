<?php
	include 'conn.php';
	$check_res =-1;
	session_start();
	// $_SESSION['uid']=1;
	if (!isset($_SESSION['uid']))
	{
		header('Location: ./');
	}
	$uid=$_SESSION['uid'];

	$gid = $mysqli->real_escape_string($_POST['gid']);
	$year = $mysqli->real_escape_string($_POST['year']);
	$month = $mysqli->real_escape_string($_POST['month']);
	$day_arr = array();

	if($month > 0 && $month <13){
		$sql = "SELECT `day` FROM `calendar` WHERE `goal_id`=$gid AND `year`=$year AND `month`=$month
		 ORDER BY `day` ASC";
		if($result = $mysqli->query($sql)){
			
			while($row = $result->fetch_array(MYSQL_ASSOC)){
				array_push($day_arr, (int)$row['day']);
			}
			$result->close();
		}
		else{
			$check_res = 2;
		}
	}
	else {
		$check_res = 1;
	}
	$mysqli->close();

	$resarr = array('res'=>$check_res,'day_arr'=>$day_arr );
	//var_dump($day_arr);
	echo json_encode($resarr);

?>