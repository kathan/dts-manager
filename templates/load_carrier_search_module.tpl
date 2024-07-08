<script type="text/javascript" src="js/db_save.js"/>
<script type="text/javascript">
	{literal}		
			function get_portal(table, params){
				try
				{
				var d = document.getElementById(table+'_portal');
				//alert(table+'1');
				d.innerHTML = 'Loading '+table;
				//alert(table+'2');
					var url = '?page=load&amp;portal='+table+'&amp;action=portal&amp;sml_view&amp;'+params;
					var portal = getFromURL(url);
				}catch(e){
					alert('Error in get_portal:'+e.description + ' url:' + url);
				}
				d.innerHTML = '';
				d.innerHTML = portal;
				
			}
				</script><script type="text/javascript">
					
			function get_module(name, params){
				var d = document.getElementById(name+'_module');
				//alert(table+'1');
				d.innerHTML = 'Loading '+name;
				//alert(table+'2');
				try
					{
					var url = '?page=load&amp;module='+name+'&amp;sml_view&amp;'+params;
					//var url = 'http://domestictransportsolutions.com/dts/?page=load&amp;module='+name+'&amp;action=module&amp;sml_view&amp;'+params;
				var module = getFromURL(url);
				
				}catch(e){
						alert('Error in module_script:'+e.description + ' url:' + url);
					}
					d.innerHTML = '';
				d.innerHTML = module;
				
			}
				</script><script type="text/javascript">
								
				function popUp(URL, id, width, height){
					if(!width){
						width=600;
					}
					if(!height){
						height = 600;
					}
					if(!id){
						day = new Date();
						id = day.getTime();
					}
					eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,location=0,statusbar=0,menubar=0,resizable=1,width="+width+",height="+height+",left = 0,top = 0');");
				}
{/literal}
</script>
<div id="content" class="content load_content">
	<table class="edit_class">
		<form enctype="multipart/form-data" name="" action="/dts/index.php" method="GET"/>
			<input type="hidden" value="" id="" name="sml_view"/>
			<input type="hidden" value="load" id="" name="page"/>
			<input type="hidden" value="{$load_id}" id="" name="load_id"/>
		<tbody>
			<tr>
				<td class="label_class">
					Carrier Id:
				</td>
				<td class="edit_class">
					<input type="text" value="{if isset($smarty.get.carrier_id)}{$smarty.get.carrier_id}{/if}" maxlength="11" size="11" id="" name="carrier_id"/>
				</td>
			</tr>
			<tr>
				<td class="label_class">
					Name:
				</td>
				<td class="edit_class">
					<input type="text" value="{if isset(smarty.get.name)}{$smarty.get.name}{/if}" maxlength="30" id="" name="name"/>
				</td>
			</tr>
			<tr>
				<td class="label_class">
					Phys City:
				</td>
				<td class="edit_class">
					<input type="text" value="{if isset(smarty.get.phys_city)}{$smarty.get.phys_city}{/if}" maxlength="100" id="" name="phys_city"/>
				</td>
			</tr>
			<tr>
				<td class="label_class">
					Phys State:
				</td>
				<td class="edit_class">
					<input type="text" value="{if isset(smarty.get.phys_state)}{$smarty.get.phys_state}{/if}" maxlength="2" size="2" id="" name="phys_state"/>
				</td>
			</tr>
			<tr>
				<td class="label_class"></td>
				<td class="edit_class">
					<input type="submit" value="carrier_search_result" id="" name="action"/>
				</td>
			</tr>
		</tbody>
	</table>
<h3>Carrier Search Results</h3>
<script type="text/javascript">
	{literal}
					function check_carrier(insurance_expires){
						
						ins_exp_str = insurance_expires.split('-');
						ins_exp = new Date();
						ins_exp.setYear(ins_exp_str[0]);
						ins_exp.setMonth(ins_exp_str[1]);
						ins_exp.setDate(ins_exp_str[2]);
						today = new Date();
						
						if(ins_exp &gt; today){
							return true;
						}else
						{
							return false;
						}
					}
					function add_load_carrier(carrier_id, insurance_expires){
						if(check_carrier(insurance_expires)){
							var param_str = 'table=load_carrier&amp;carrier_id='+carrier_id+'&amp;load_id=';
							var obj = {};
							obj.id  = param_str;
							obj.value = {/literal}{$load_id}{literal};
							db_save(obj);
							//db_save(param_str,1017472);
							refresh_close();
						}else{
							alert("Carrier's insurance expired on "+insurance_expires+".")
						}
					}
					function refresh_close(){
						window.opener.update_carrier_portal();
					}
					{/literal}
</script>
<div style="height: 295px; overflow: auto; margin: 0pt auto;" id="tableContainer" class="tableContainer">
	<table style="width: 99%; border: medium none; background-color: rgb(247, 247, 247);" id="_portal" class="view list scrollTable">
		<thead>
			<tr>
				<th>Carrier Id</th>
				<th>Name</th>
				<th>Phys City</th>
				<th>Phys State</th>
				<th>Add</th>
			</tr>
		</thead>
		<tbody style="overflow-y: auto; overflow-x: hidden;">
		{section name=i loop=$carriers}
			<tr class="{cycle values="normalRow,altRow"}" id="">
				<td style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;" class="list_data carrier_id">
					{$carriers[i].carrier_id}
				</td>
				<td style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;" class="list_data name">
					{$carriers[i].name}
				</td>
				<td style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;" class="list_data phys_city">
					{$carriers[i].phys_city}
				</td>
				<td style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;" class="list_data phys_state">
					{$carriers[i].phys_state}
				</td>
				<td style="padding-right: 2px; text-align: left; font-weight: bold; font-size: 12px; color: black;" class="list_data add">
					{*ie:{$carriers[i].insurance_expires} str:{$carriers[i].insurance_expires|strtotime} now:{$smarty.now*}
					{if $carriers[i].insurance_expires|strtotime > $smarty.now}
						{if !$carriers[i].do_not_load}
							
							<input type="button" value="Add" onclick="add_load_carrier('{$carriers[i].carrier_id}', '{$carriers[i].insurance_expires}')" />
						{else}
							Do Not Load
						{/if}
					{else}
						Insurance Expired
					{/if}
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>
		<style>
		{literal}
			.normalRow
			{
				background-color:white;
				color:color;
			}
			.altRow
			{
				background-color:silver;
				color:color;
			}
			{/literal}
		</style>
	<input type="button" value="Close" onclick="window.close();"/>