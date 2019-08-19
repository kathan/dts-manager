<!-- weekly start -->
		<tr class="weekly_head">
			<td colspan="6">
					Week of {$start_month}/{$start_week_day} thru {$start_month}/{$end_week_day}
			</td>
		</tr>
		<tbody class="weekly">
		{foreach from=$weeks item=week name=i}
			{if $smarty.foreach.i.first}
				<tr>
				{foreach from=$week item=field key=field_name}
					{if $field_name != 'load_id' && $field_name != "carrier_rep_id" && $field_name != "sales_rep_id"}
   					<th>{$field_name|replace:'_':' '|capitalize}</th>
   					{/if}
				{/foreach}
				</tr>
			{/if}
			<tr>
			{foreach from=$week item=field key=field_name}
				{if $field_name != "load_id" && $field_name != "carrier_rep_id" && $field_name != "sales_rep_id"}
   				<td class="{$field_name}">
   					{if $user_id}
   						{if ($field_name == 'carrier_rep') && $user_id != $week.carrier_rep_id}
   							&nbsp;
   						{else}
   							{if ($field_name == 'sales_rep') && $user_id != $week.sales_rep_id}
   								&nbsp;
   							{else}
   							<a href="?action=Edit&page=load&load_id={$week.load_id}">{$field}</a>
   							{/if}
   						{/if}
   					{else}
   						<a href="?action=Edit&page=load&load_id={$week.load_id}">{$field}</a>
   					{/if}
   				</td>
   				{/if}
			{/foreach}
			</tr>
		{/foreach}
		</tbody>
<!-- weekly end -->