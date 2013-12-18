<?php
	include 'conn.php';
	$err_msg = array("成功","密码错误","该用户名不存在");
	$email = $mysqli->real_escape_string($_POST['email']);
	$pwd = $mysqli->real_escape_string($_POST['pwd']);
	
	$check_res = -1;
	$sql = "SELECT * FROM users WHERE `email`='$email'";
	if ( $result = $mysqli->query($sql) ) {
		if($result->num_rows == 1) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if(strcmp($pwd, $row["upwd"]) == 0)
			{
				$check_res = 0;
				session_start();
				$_SESSION['uid'] = $row['uid'];
				$_SESSION['uname'] = $row['uname'];
			}
			else {
				$check_res = 1;
			}
		}
		else {
			$check_res = 2;
		}
		$result->close();
	}	
	$mysqli->close();
	$resarr = array('res'=>$check_res,'msg'=>$err_msg[$check_res]);
	echo json_encode($resarr);
?>