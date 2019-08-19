<!-- start overdue load mod-->
<!--script type="text/javascript" src="jquery.js"></script-->
<script type="text/javascript">
	$(function(){
		$('#od_load_mod_close').click(function(){
			hide_od_load_mod();
		});
		/*get_od_loads();
		get_approach_loads();*/
		window.setInterval(function()
		{
			get_od_loads();
			get_approach_loads();
		//}, 15000);//15 seconds
		}, 900000);//15 minutes
	});
	
	function get_od_loads()
	{
		$.getJSON('overdue_loads.php',function(data)
		{
			document.getElementById('od_load_mod').overdue_count = data.length;
			if(data.length > 0)
			{
				show_od_load(data);
			}else
			{
				$('#od_load_mod_msg').html('');
			}
		});
	}
	
	function get_approach_loads()
	{
		$.getJSON('approaching_loads.php',function(data)
		{
			$('#od_load_mod').approach_count = data.length;
			if(data.length > 0)
			{
				show_approach_load(data);
			}else
			{
				$('#od_approach_mod_msg').html('');
				if(document.getElementById('od_load_mod').overdue_count == 0)
				{
					hide_od_load_mod();
				}
				
			}
		});
	}
	
	function show_approach_load(data)
	{
		$('#od_load_mod').css('display', 'block');
		var e;
		data.length == 1 ? e = 'event' : e = 'events';
		$('#od_approach_mod_msg').html('<a target="dts_od" href="?page=overdue">You have '+data.length+' approaching '+e+'.</a>');
	}
	
	function show_od_load(data)
	{
		$('#od_load_mod').css('display', 'block');
		var e;
		data.length == 1 ? e = 'event' : e = 'events';
		$('#od_load_mod_msg').html('<a target="dts_od" href="/dts/?page=overdue">You have '+data.length+' overdue '+e+'.</a>');
		
	}

	function hide_od_load_mod()
	{
		
		$('#od_load_mod').css('display', 'none');
	}
	function locate_od_load_mod()
	{
		var llm = $('#od_load_mod');
		llm.css('top', $(window).height() / 2);
	}
</script>
<style>
#od_load_mod{bottom:0px;position:fixed;display:none;text-align:right;border-top:#f55 solid 1px;border-right:#f55 solid 1px}
#llm_hilight{background-color:red;padding:3px;border-top:#f77 solid 1px;border-right:#f77 solid 1px}
#od_load_mod_close{margin-bottom:2px;cursor:pointer}
#od_load_mod_msg, #od_approach_mod_msg{padding:3px;background-color:white}
</style>
<div id="od_load_mod">
	<div id="llm_hilight">
		<img id="od_load_mod_close" src="images/CloseIcon.gif" />
		<div id="od_load_mod_msg"></div>
		<div id="od_approach_mod_msg"></div>
		<div id="od_load_mod_list"></div>
		<div id="od_approach_mod_list"></div>
	</div>
</div>
<!-- end overdue load mod-->