<?php
$add= $_SERVER['HTTP_HOST'].':8080';
echo "<script type=\"text/javascript\">";
echo "alert($add);";
echo "</script>";
//header('Location: http://'.$add);
?>
<html>
	<head>
		<title>e-classroom</title>
		<script src="/socket.io/socket.io.js"></script>
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>-->
		<script src="jquery.js"></script>
		<style type="text/css">
			body{
				background:url('back5.jpg');
			}
			img.headlogo{
				width:90px;
				height:60px;
				float:left;
				-moz-animation:myfirst 2s linear 2s infinite alternate;
				-webkit-animation:myfirst 2s linear 2s infinite alternate;
			}
			@-moz-keyframes myfirst /* Firefox */
			{
				from{
					-moz-transform:rotateY(0deg);
				}
				to{
					-moz-transform:rotateY(360deg);
				}
			}
			@-webkit-keyframes myfirst /* Chrome */
			{
				from{
					-webkit-transform:rotateY(0deg);
				}
				to{
					-webkit-transform:rotateY(360deg);
				}
			}
		</style>

	</head>
	<body>
		<a style="text-decoration:none" href="http://iiit.ac.in" target="_blank"><img src="iiitlogo.gif" class="headlogo"/></a>
		<div style="float:left;clear:both;width:10%;border-right:3px solid black;height:55%;overflow:auto;padding:10px;color:black">
			<b>USERS</b>
			<div id="users" style="height:300px;overflow:auto;background-color:white;opacity:0.6;"></div>
		</div>

		<div style="float:left;width:800px;height:550px;padding:0;background:none;">
			<video id="vid" width="800" height="340">
			<source src="/test.mp4" type="video/mp4">
			<source src="/test.ogv" type="video/ogg">
			<source src="/test.webm" type="video/webm">
			not supported
			</video>
			<br><br><br>	

		</div>
		<div id="par" style="float:right;width:25%;height:56%;overflow:auto;padding:10px;color:black;border-left:3px solid black; ">
			<div id="conversation" style="height:300px;overflow:auto;background-color:white;opacity:0.6"></div>
			<input id="data" style="width:281px;"/>
			<input type="button" id="datasend" value="send"/>
		</div>
		<br><br>
		<canvas id="canvas" width=750px height=180px style="border-radius:10px;position:absolute;top:450px;left:20px;opacity:0.6;border:1px solid #000000;background-color:white;border:4px solid black;float:left;"></canvas>
		<div id="copyright" style="background-color:#00FFFF;float:right;clear:right;margin-top:50px;padding:20px;border-radius:10px;border:10px solid black;">
			&copy;
			<a href="http://web.iiit.ac.in/~mayank.natani">Mayank Natani</a><br/>
			&copy;
			<a href="http://web.iiit.ac.in/~prateek.sachdev">Prateek Sachdev</a><br/>
			&copy;
			<a href="http://web.iiit.ac.in/~yashashvi.girdhar">Yashashvi Girdhar</a><br/>
			&copy;
			<a href="http://web.iiit.ac.in/~mohit.aggarwal">Mohit Aggarwal</a><br/>
		</div>
		<br/><br/><br/>
		<br/><br/><br/>
		<br/><br/><br/>
		<br/><br/><br/>
		<br/><br/><br/>
		<input type="button" id="zing" value="toggle" style="position:relative;left:-60px;top:-30px"</input>
		<br/><br/><br/>
		<input type="button" id="erase" value="eraser" style="position:relative;left:-160px"/>
		<br/><br/>
		<input type="button" id="pen" value="Back to drawing" style="position:relative;left:-160px"/>
		<script>
			//var clients=[];
			var socket = io.connect('http://10.1.40.236:8082');

			// on connection to server, ask for user's name with an anonymous callback
			socket.on('connect', function(){

				//clients.push(client); 
				// call the server-side function 'adduser' and send one parameter (value of prompt)
				socket.emit('adduser', socket.username=prompt("What's your name?"));
				var r=Math.random() * 89+10;
				var g=Math.random() * 89+10;
				var b=Math.random() * 89+10;
				r=Math.round(r);
				g=Math.round(g);
				b=Math.round(b);
				var rgb="#"+r.toString()+g.toString()+b.toString();
				colorb=rgb;
				store_color=rgb;
				//alert(colorb);
			});

			// listener, whenever the server emits 'updatechat', this updates the chat body
			socket.on('updatechat', function (a,b,colorc) {
				//      var datalen=data.length;
				//alert(a[0]+"  "+b[0]);
				redraw_c(a,b,colorc);

			});

			socket.on('updatechat1', function (a,b,preva,prevb,colorc) {
				//      var datalen=data.length;
				//alert(a[0]+"  "+b[0]);
				redraw1_c(a,b,preva,prevb,colorc);

			});

			socket.on('updatechat2', function (username, data) {
				if(data.length>0)
				$('#conversation').append('<b>'+username + ':</b> ' + data + '<br>');
						var elem=document.getElementById('conversation');
					//	alert(elem.scrollHeight+5);
						elem.scrollTop=elem.scrollHeight;
						elem.scrollTop=elem.scrollHeight;
			});



			socket.on('updatevideo', function(videoTime){
				var video=document.getElementById("vid");
				//	if(videoTime!=0)
				video.currentTime=videoTime;
				//video.currentTime=prevTime;
				$("p").text(videoTime);
				video.play();
			});

			// listener, whenever the server emits 'updateusers', this updates the username list
			socket.on('updateusers', function(data) {
				var video=document.getElementById("vid");
				//alert(video.src);		
				//video.play();
				var videoTime=video.currentTime;
				//$("p").text(videoTime);
				loda=socket.username;
				if(loda!='server'){
					$('#zing').hide();
				}
				if(socket.username=='server')
				socket.emit('sendvideo',videoTime);
				//$('textarea#ta').val(chat);
				$('#users').empty();
				//alert(data[0]+"  "+data[1]+"  "+data[2]);
				$.each(data, function(key, value) {
					$('#users').append('<div>' + value + '</div>');

				});
			});


			socket.on('pause',function(){
				if(document.getElementById('vid').paused)
				document.getElementById('vid').play();
				else		
				document.getElementById('vid').pause();
			});



			// on load of page
			$(function(){
				$('#canvas').mousedown(function(e) {
					var mouseX = e.pageX - this.offsetLeft;
					var mouseY = e.pageY - this.offsetTop;
					paint = true;
					//addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
					redraw_b(mouseX,mouseY,0);              
					socket.emit('sendchat',mouseX,mouseY,colorb);
				});
				// when the client clicks SEND
				$('#datasend').click( function() {
					var message = $('#data').val();
					$('#data').val('');
					// tell server to execute 'sendchat' and send along one parameter
					socket.emit('sendchat2', message);
				});

				// when the client hits ENTER on their keyboard
				$('#data').keypress(function(e) {
					if(e.which == 13) {
						$(this).blur();
						$('#datasend').focus().click();
						$('#data').focus();
					}
				});

				$('#zing').click(function(){
					//		document.getElementById('vid').pause();
					socket.emit('pausing');
				});

			});



		</script>
		<script>
			var context = document.getElementById('canvas').getContext("2d");       

			var clickX = new Array();
			var clickY = new Array();
			var clickDrag = new Array();
			var paint;
			var prevX;
			var prevY;
			var count=0; 
			//var color=new Array("ff0000","df4b27","#0000ff","#ff8c00","#808000");
			var colorb="#000000";
			var store_color;

			$('#erase').click(function (){
				colorb="#FFFFFF";
			});

			$('#pen').click(function (){
				colorb=store_color;
			});


			$('#canvas').mousemove(function(e){
				if(paint){

					mouseX=e.pageX - this.offsetLeft;
					mouseY=e.pageY - this.offsetTop;
					socket.emit('sendchat1',mouseX,mouseY,colorb);
					redraw1(mouseX,mouseY);
				}
			});

			$('#canvas').mouseup(function(e){
				paint = false;
			});

			$('#canvas').mouseleave(function(e){
				paint = false;
			});



			function addClick(x, y, dragging)
			{
				clickX.push(x);
				clickY.push(y);
				clickDrag.push(dragging);
			}



			function redraw_b(mouseX,mouseY){

				if(count==0) {  
					canvas.width = canvas.width; // Clears the canvas
					count++;
				}
				context.strokeStyle = colorb;
				context.lineJoin = "round";
				if(colorb=="#ffffff" || colorb=="#FFFFFF")
				context.lineWidth=15;
				else
				context.lineWidth = 5;

				context.beginPath();

				context.moveTo(mouseX-1, mouseY);

				context.lineTo(mouseX,  mouseY);
				prevX=mouseX;
				prevY=mouseY;
				context.closePath();
				context.stroke();  


				//redraw();

			}

			function redraw_c(mouseX,mouseY,colorc){

				if(count==0) {  
					canvas.width = canvas.width; // Clears the canvas
					count++;
				}
				context.strokeStyle = colorc;
				context.lineJoin = "round";
				if(colorc=="#ffffff" || colorc=="#FFFFFF")
				context.lineWidth=15;
				else
				context.lineWidth = 5;


				context.beginPath();

				context.moveTo(mouseX-1, mouseY);

				context.lineTo(mouseX,  mouseY);
				context.closePath();
				context.stroke();  


				//redraw();

			}
			function redraw1(mouseX,mouseY){
				context.strokeStyle = colorb;
				context.lineJoin = "round";
				if(colorb=="#ffffff" || colorb=="#FFFFFF")
				context.lineWidth=15;
				else
				context.lineWidth = 5;


				context.beginPath();

				context.moveTo(prevX, prevY);

				context.lineTo(mouseX,  mouseY);
				prevX=mouseX;
				prevY=mouseY;
				context.closePath();
				context.stroke();  


			}

			function redraw1_c(mouseX,mouseY,preva,prevb,colorc){
				context.strokeStyle = colorc;
				context.lineJoin = "round";
				if(colorc=="#ffffff" || colorc=="#FFFFFF")
				context.lineWidth=15;
				else
				context.lineWidth = 5;


				context.beginPath();

				context.moveTo(preva, prevb);

				context.lineTo(mouseX,  mouseY);
				context.closePath();
				context.stroke();  


			}
		</script>
	</body>
</html>
