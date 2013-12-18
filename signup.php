<?php
	$err_msg = array("成功","该邮箱已经被占用");
	include 'conn.php';
	$email = $mysqli->real_escape_string($_POST['email']);
	$name = $mysqli->real_escape_string($_POST['name']);
	$pwd = $mysqli->real_escape_string($_POST['pwd']);
	$check_res = -1;
	$test="test";

	$sql = "SELECT * FROM users WHERE `email`='$email'";
	$test = $sql;
	if ( $result = $mysqli->query($sql) ) {
		if($result->num_rows == 0) {
			$sql = "INSERT INTO users (`email`, `uname`, `upwd`) VALUES ('$email', '$name', '$pwd') ";			
			$test = $sql;
			if ( $mysqli->query($sql) ){
				$check_res = 0;

				$sql = "SELECT * FROM users WHERE `email`='$email'";
				$result2 = $mysqli->query($sql);
				$row = $result2->fetch_array(MYSQLI_ASSOC);

				session_start();
				$_SESSION['uid'] = $row['uid'];
				$_SESSION['uname'] = $row['uname'];
			}
		}
		else {
			$check_res = 1;
		}
		$result->close();
	}	
	$mysqli->close();
	$resarr = array('res'=>$check_res,'msg'=>$err_msg[$check_res]);
	echo json_encode($resarr);
?>