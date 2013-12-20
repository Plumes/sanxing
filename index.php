<?php
  session_start();
  // $_SESSION['uid']=1;
  if (isset($_SESSION['uid']))
  {
  	header('Location: /sanxing/mylists.php');
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<title>三省 - 吾日三省吾身</title>
	<?php require("html-include.php"); ?>
	<script src="./static/js/main.js"></script>
</head>
<body>
	<div id="index-bg">
		<div id="index-wrapper">
			<div id="slogan">
				<p id="slogan-content">To live is the rarest thing in the world. Most people exist, that is all.</p>
				<p id="slogan-author"><i>-- Oscar Wilde</i></p>
			</div>
			<div id="signin-wrapper">
					<form id="signin" style="display:block;">
						<span class="form-title">登 录</span>
						<a href="javascript:void(0)" onclick="nextform();" class="next-form">注册 <span class="glyphicon glyphicon-circle-arrow-right"></span></a>
						<input class="form-control" type="email" placeholder="邮箱" name="email" required />
						<input class="form-control" type="password" placeholder="密码" name="pwd" required />
						<input type="submit" id="signin-btn" class="btn btn-primary" value="登 录" />
					</form>
					<form id="signup" style="display:none;">
						<span class="form-title">注 册</span>
						<a href="javascript:void(0)" onclick="nextform();" class="next-form">登录 <span class="glyphicon glyphicon-circle-arrow-right"></span></a>
						<input class="form-control" type="email" placeholder="邮箱" name="email" required />
						<input class="form-control" type="text" placeholder="用户名" name="name" required />
						<input class="form-control" type="password" placeholder="密码" name="pwd" required />
						<input class="form-control" type="password" placeholder="再输一遍密码" name="rpwd" required />
						<input type="submit" id="signup-btn" class="btn btn-primary" value="注 册" />
					</form>
			</div>
		</div>
	</div>
</body>

<script type="text/javascript">
	var i=0;
	function nextform(){
		res_arr = ["inline-block","none"];
		$("#signin-wrapper form:first-child").css("display",res_arr[1-i]);
		$("#signin-wrapper form:last-child").css("display",res_arr[i]);
		i=1-i;
	}

	$("#signin").submit(function(event) {
		event.preventDefault();
		if($("#signin").find(".alert")){ $("#signin .alert").remove(); }
		//alert("hello");
		var $form = $( this ),
	    email = $form.find( "input[name='email']" ).val(),
	    pwd = $form.find( "input[name='pwd']" ).val(),
	    pwd = MD5(pwd);
	    url = "signin.php";
	    var posting = $.post( url, { 'email':email,'pwd':pwd  } );
	    posting.done(function( str ) {
	    	data = JSON.parse(str);
		    //console.log(str);
		    if (data['res'] === 0){
		    	location.href="./";
		    }
		    else {
		    	$("#signin").append('<div class="alert alert-danger"><a>'+data['msg']+'</a></div>');
		    }

  		});
	});

	$("#signup").submit(function(event) {
		event.preventDefault();
		//alert("hello");
		var $form = $( this ),
	    email = $form.find( "input[name='email']" ).val(),
	    name = $form.find( "input[name='name']" ).val(),
	    pwd = $form.find( "input[name='pwd']" ).val(),
	    rpwd = $form.find( "input[name='rpwd']" ).val(),
	    url = "signup.php";
	    if($("#signup").find(".alert")){ $("#signup .alert").remove(); }
	    //check password's length
	    if (pwd.length <  6) {$("#signup").append('<div class="alert alert-danger"><a>请输入长度至少为6的密码</a></div>'); return -1;}
	    //check if password is equal to repeat-password
	    if (pwd !== rpwd) {$("#signup").append('<div class="alert alert-danger"><a>两次密码输入不一致</a></div>'); return -1;}
	    pwd = MD5(pwd);
	    var posting = $.post( url, { 'email':email,'name':name,'pwd':pwd } );
	    posting.done(function( str ) {
	    	data = JSON.parse(str);
		    //console.log(str);
		    if (data['res'] === 0){
		    	location.href="./";
		    }
		    else {
		    	$("#signup").append('<div class="alert alert-danger"><a>'+data['msg']+'</a></div>');
		    }

  		});
	});
</script>
</html>