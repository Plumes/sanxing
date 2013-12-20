<?php
	session_start();
	// $_SESSION['uid']=1;
	if (!isset($_SESSION['uid']))
	{
		header('Location: ./');
	}
	$uid=$_SESSION['uid'];
	$gid = $_GET['goal_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<title>三省 - 吾日三省吾身</title>
	<?php require("html-include.php"); ?>
</head>
<body>
	<div id="container">
		<div class="row">
			<div id="menu-wrapper">
				<div id="user-info">
					<div id="user-icon"><img src="./static/img/icon.jpg" alt="<? echo $_SESSION['uname'];?>"></div>
				</div>
				<div id="list-wrapper">
					<div class="list-btn"><? echo $_SESSION['uname'];?><span class="tail glyphicon glyphicon-chevron-down"></span></div>
					<div class="mylist">
						<ul>
							<!-- <li class="list-item"><a href="setting.php">修改设置</a></li> -->
							<li class="list-item"><a href="logout.php">注销</a></li>
						</ul>
					</div>
				
					<div class="list-btn">我的目标列表<span class="tail glyphicon glyphicon-chevron-down"></span></div>
					<div class="mylist" id="goal_list">
						<ul>
							<?php 
								include 'conn.php';
								$sql = "SELECT `id`, `name` FROM `goal_list` WHERE `uid`=$uid ";
								//echo $sql;
								$result = $mysqli->query($sql);
								while($row = $result->fetch_array(MYSQLI_ASSOC) )
								{
									$tid = $row['id'];
									if(!isset($gid)) {$gid=$tid;}
									if ($gid === $tid) echo "<li class=\"list-item list-selected\" ><a href=\"?goal_id=".$tid."\">".$row['name'];
									else echo "<li class=list-item><a href=\"?goal_id=".$tid."\">".$row['name'];
									echo "</a> <span class=\"glyphicon glyphicon-remove del-goal-btn\" value=\"$tid\"  ></span> </li>";
								}
								$result->close();
							?>
						</ul>
					</div>
					<div class="list-btn" id="add-list" data-toggle="modal" data-target="#add-goal-modal">增加目标</div>
					<div class="list-btn" id="report-job" data-toggle="modal" data-target="#report-job-modal">今日任务</div>
				</div>
			</div>
			<div id="content-wrapper">
				<div id="page-title">
					<?php
						$sql = "SELECT * FROM `goal_list` WHERE `uid`=$uid AND `id`=$gid ";
						$result = $mysqli->query($sql);
						$row = $result->fetch_array(MYSQLI_ASSOC);
						echo '<p id="gname">'.$row['name'].'</p>';
						echo '开始时间: <a id="stime">'.$row['start'].'</a>';
						echo '截止时间: <a id="etime">'.$row['end'].'</a>';
						echo '间隔时间: <a id="itime">'.$row['interval'].' 天</a>';
						$result->close();
						$d = date_format(date_create(Date()), "m-d-Y");
						//echo $d."<br>";	
						$d_arr = explode("-",$d);
						
					?>
					
				</div>
				<div id="calendar-wrapper">
					
					<div id="date-selector">
						<span class="glyphicon glyphicon-circle-arrow-left" id="prev-month"></span>
						<a id="year"><? echo $d_arr[2]; ?></a> 年<a id="month"> <? echo $d_arr[0]; ?></a> 月
						<span class="glyphicon glyphicon-circle-arrow-right" id="next-month"></span>
					</div>
					
					<table id="list-calendar">
					<tr>
						<th>星期日</th>
						<th>星期一</th>
						<th>星期二</th>
						<th>星期三</th>
						<th>星期四</th>
						<th>星期五</th>
						<th>星期六</th>
					</tr>

						
					</table>
				</div>	
			</div>
			<!--  add goal Modal -->
	<div class="modal fade" id="add-goal-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Add a goal</h4>
	      </div>
	      <div class="modal-body">
	        <form class="form-horizontal" role="form" id="new-goal">
	        	<div class="input-group col-xs-8">
	        		<span class="input-group-addon">目标名称</span>
	        		<input type="text" class="form-control" id="new-name" required>
	        	</div>
	        	<div class="input-group col-xs-8 date-input">
	        		<span class="input-group-addon">开始日期</span>
	        		<input class="form-control" type="text" value="" id="start-time" readonly required>
	        		<span class="input-group-addon glyphicon glyphicon-th"></span>
	        	</div>
	        	<div class="input-group col-xs-8 date-input">
	        		<span class="input-group-addon">终止日期</span>
	        		<input class="form-control" type="text" value="" id="end-time" readonly required>
	        		<span class="input-group-addon glyphicon glyphicon-th"></span>
	        	</div>
	        	<div class="input-group col-xs-8">
	        		<span class="input-group-addon">间隔时间</span>
	        		<input type="text" class="form-control" id="interval-time" required>
	        	</div>
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" id="save-goal">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div> 
	<!--  add goal Modal ends -->
	<!--  report job Modal -->
<div class="modal fade" id="report-job-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">今天需要完成的任务</h4>
      </div>
      <div class="modal-body">
        <form id="today-job-form">
        	<?php
        		$sql = "SELECT * FROM `goal_list` WHERE `uid`=$uid ";
        		$_SESSION['today_job'] = array();
        		if ($result = $mysqli->query($sql)){
        			while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        				//选出所有属于该用户的goal_id
        				//print_r($row);
        				$sday = $row['start'];$sday = explode("-", $sday);$sday = GregorianToJD($sday[1], $sday[2], $sday[0]);
        				$eday = $row['end'];$eday = explode("-", $eday);$eday = GregorianToJD($eday[1], $eday[2], $eday[0]);
        				$today = GregorianToJD(Date('m'), Date('d'), Date('Y'));
        				//将数据库中的日期转换为 Julian 天数
        				$m_gid = $row['id'];$m_name=$row['name'];
        				if ($today >= $sday && $today <= $eday) {

        					if (($today-$sday)%(((int)$row['interval'])+1) === 0){
        						//检查今天是否需要完成该目标事件
        						//echo $row['interval'];
        						$sql = "SELECT * FROM `calendar` WHERE `goal_id`=".$row['id']." AND `year`=".Date('Y'). 
        						" AND `month`=".Date('m')." AND `day`=".Date('d');
        						$result2 = $mysqli->query($sql);
        						//echo $sql;
        						if ($result2->num_rows == 0){
        							array_push($_SESSION['today_job'],$m_gid);
        							echo "<div class=job-item> <input type=checkbox name=today-job[] value=$m_gid><label>".$m_name."</lable></div>";
        						}
        					}
        				}
        			}

        		}
        		$result->close();$result2->close();$mysqli->close();
        	?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="report-job-btn">所选任务已完成</button>
      </div>
    </div>
  </div>
</div>
<!--  report job Modal ends-->
		</div>
	</div>
	
</body>
<script src="./static/js/fill-calendar.js"></script>
<script>
	var today = new Date();
	get_calendar(<? echo $gid.',' ?> today.getFullYear(),today.getMonth()+1);
	$("#prev-month").click(function(){
		var year = parseInt($("#year").text());
		var month = parseInt($("#month").text())-1;
		if(month===0) {
			month = 12;
			year -= 1;
			$("#year").text(String(year));
		}
		$("#month").text(String(month));
		$("tr[id*='week']").remove();
		get_calendar(<? echo $gid.',' ?> year,month);
	});
	$("#next-month").click(function(){
		var year = parseInt($("#year").text());
		var month = parseInt($("#month").text())+1;
		if(month===13) {
			month = 1;
			year += 1;
			$("#year").text(String(year));
		}
		$("#month").text(String(month));
		$("tr[id*='week']").remove();
		get_calendar(<? echo $gid.',' ?> year,month);
	});
	
	$(".list-btn").click(function(){
		$($(this).next(".mylist")).slideToggle();
		var str="";
		if ($($(this).children("span")).attr("style"))
		{
			str = $($(this).children("span")).attr("style");
		}
		var now_deg = 0;
		if (str.length > 0){
			str = str.split("(");
			now_deg = parseInt(str[1]);
		}
		now_deg = 90 - now_deg;
		$($(this).children("span")).css({
          '-moz-transform':'rotate('+now_deg+'deg)',
          '-webkit-transform':'rotate('+now_deg+'deg)',
          '-o-transform':'rotate('+now_deg+'deg)',
          '-ms-transform':'rotate('+now_deg+'deg)',
          'transform': 'rotate('+now_deg+'deg)'
     }); 
	});
	//delete a goal
	$("#goal_list>ul>.list-item").hover(function(){
		//alert($(this).text());
		$($(this).children("span.glyphicon.glyphicon-remove")).css("display","block");
	});
	$("#goal_list>ul>.list-item").mouseleave(function(){
		//alert($(this).text());
		$($(this).children("span.glyphicon.glyphicon-remove")).css("display","none");
	});
	$(".del-goal-btn").click(function(){
		//alert($(this).attr("value"));
		var gid = $(this).attr("value");
		var par_item = $(this).parent();
		if(confirm("确认删除？")){
			var posting = $.post("delgoal.php",{'gid':gid});
			posting.done(function(str){
				data=JSON.parse(str);
				if(data['res'] === 0)
				{
					//alert($(par_item).text());
					$(par_item).hide();
				}
			});
		}
	});


	$("#add-list").click(function(){
		//$('#myModal').modal("toggle");
	});
	$(".date-input input").datepicker({
		format: "yyyy-mm-dd",
        pickerPosition: "bottom-left"
	});
	$("#save-goal").click(function(){
		if($("#new-goal").find(".alert")){ $("#new-goal .alert").remove(); }
		var $form = $("#new-goal"),
	    name = $form.find( "input[id='new-name']" ).val(),
	    stime = $form.find( "input[id='start-time']" ).val(),
	    etime = $form.find( "input[id='end-time']" ).val(),
	    itime = parseInt($form.find( "input[id='interval-time']" ).val()),
	    url = "addgoal.php";
	    now = new Date();
	    if (new Date(stime+":23:59:59") < now  || new Date(stime) >= new Date(etime) ){
	    	$("#new-goal").append('<div class="alert alert-danger"><a>日期选择错误</a></div>');
	    	return -1;
	    }
	    if(itime < 0) {return -1;} 
	    //console.log(name+new Date(stime)+new Date(etime)+itime);
	    var posting = $.post(url,{'name':name, 'stime':stime, 'etime':etime, 'itime':itime })
	    posting.done( function(str){
	    	data = JSON.parse(str);
	    	console.log(data['res']);
	    	if(data['res'] === 0) {
	    		location.href="./";
	    	}
	    });

	});
	$("#report-job-btn").click(function(){
		var check_arr = $("#today-job-form>.job-item>.checked>input");
		var res_arr = new Array();
		for(var i=0;i<check_arr.length;i++)
		{
			res_arr.push(parseInt(check_arr[i].value));
		}
		//res_arr.push(1000);
		console.log(res_arr);
		var posting = $.post("report_job.php",{"res_arr[]":res_arr});
		posting.done( function(str){
	    	data = JSON.parse(str);
	    	//console.log(data['msg']);
		//if(data['res'] === 0) {
	    		location.href="./";
	    	//}
	    });
	});

	$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});
</script>
</html>