<link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="css/screen.css" />
 
<!--[if lte IE 7]>
<link type="text/css" rel="stylesheet" media="all" href="css/screen_ie.css" />
<![endif]-->

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="scripts/chat.js"></script>
<script type="text/javascript">
$(window).unload(function(e){
	
	$.ajax({async:false,url:'chat.php?action=unavailable'}, function(e){
		alert('gone');
	});
});
$(window).load(function(e){
	var d = Object();
	d['action']='available';
	//var n=chatBoxes.push(document.getElementById('online_users'));
	
	$.get('chat.php', d);
	window.setInterval(function(){
		get_users();
	}, 1000);
});

function get_users(){
	$.getJSON('chat.php?action=getUsers', function(d){
		var ou = document.getElementById('user_list');
		$(ou).html('');
		for(var i in d){
			var li = document.createElement('li');
			ou.appendChild(li);
			
			if(d[i]['available'] ==1){
				var a = '<a href="javascript:void(0)" onclick="javascript:chatWith(\''+d[i]['username']+'\')">'+d[i]['username']+'</a>';
			}else{
				var a = d[i]['username'];
			}
			//alert(a);
			$(li).html(a);
		}
		
	});
}
</script>
<div id="online_users">
Online Users
<ul id="user_list"></ul>
</div>
<style>
	#online_users ul{list-style:none;margin:3px;padding:0px;height:100px;overflow:scroll}
	#online_users{background-color:white;padding:3px;border:'#eee solid 1px';position:fixed;bottom:0px;right:0px;}
</style>