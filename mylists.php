<?php
	session_start();
	// $_SESSION['uid']=1;
	if (!isset($_SESSION['uid']))
	{
		header('Location: ./');
	}
	$uid=$_SESSION['uid'];
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
							<li class="list-item"><a href="setting.php">修改设置</a></li>
							<li class="list-item"><a href="#" onclick="logout();">注销</a></li>
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
									$gid = $row['id'];
									echo "<li class=list-item><a href=\"?goal_id=".$gid."\">".$row['name'].
									"</a> <span class=\"glyphicon glyphicon-remove del-goal-btn\" value=\"$gid\"  ></span> </li>";
								}
							
							?>
						</ul>
					</div>
					<div class="list-btn" id="add-list" data-toggle="modal" data-target="#myModal">增加目标</div>
				</div>
			</div>
			<div id="content-wrapper">
				<div class="panel panel-primary today-wrapper">
				<div class="panel-heading">今日需要完成的任务</div>
				<div class="panel-body">
					<form id="today-list">
						<p><input type="checkbox">背单词</p>
						<p><input type="checkbox">背单词2</p>
					</form>
				</div>
				</div>
			</div>
			<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
		</div>

	</div>
	
</body>
<script>
	var now_deg=0;
	$(".list-btn").click(function(){
		$($(this).next(".mylist")).slideToggle();
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
</script>
</html>