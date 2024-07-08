
<!-- Region User list start -->
<div class="tableContainer" id="tableContainer" style="height:400px;background-color:white;overflow: auto;">
		<ul style="padding:0px;height:100%;list-style:none">
			<li class="button{if isset($smarty.get.region_id) && isset($region_users[i].region_id) && $region_users[i].region_id eq $smarty.get.region_id} selected{/if}{if isset($region_users[i].user_id) && $region_users[i].user_id eq $smarty.get.user_id} selected{/if}">
				<a style="font-weight:bold" href="?page={$smarty.get.page}&{array2query array=$smarty.get exclude=['region_id','user_id','page']}">DTS</a>
			</li>
		{section name=i loop=$region_users}
			<li class="button{if isset($smarty.get.region_id) && isset($region_users[i].region_id) && $region_users[i].region_id eq $smarty.get.region_id} selected{/if}{if isset($region_users[i].user_id) && $region_users[i].user_id eq $smarty.get.user_id} selected{/if}">
			{if isset($region_users[i].region_id)}
				<a style="font-weight:bold" href="?page={$smarty.get.page}&region_id={$region_users[i].region_id}&{array2query array=$smarty.get exclude=['region_id','user_id','page']}">{$region_users[i].name}</a>
			{elseif isset($region_users[i].user_id)}
				<a href="?page={$smarty.get.page}&user_id={$region_users[i].user_id}&{array2query array=$smarty.get exclude=['region_id','user_id','page']}">{$region_users[i].name}</a>
			{else}
				{$region_users[i].name}
			{/if}
			</li>
		{/section}
	</ul>
</div>
<!-- Region User list end -->
