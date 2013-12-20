<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="./static/css/test.css">
	<?php
	include 'html-include.php';
?>
</head>
<body>
	<div id="test">
		<?php 
			$sday = "2013-12-18";$sday = explode("-", $sday);$sday = GregorianToJD($sday[1], $sday[2], $sday[0]);
			$today = GregorianToJD(Date('m'), Date('d'), Date('Y'));
			echo $today;
		?>
	</div>
</body>
</html>
