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
					{if $field_name != 'load_id'}
   					<th>{$field_name|replace:'_':' '|capitalize}</th>
   					{/if}
				{/foreach}
				</tr>
			{/if}
			<tr>
			{foreach from=$week item=field key=field_name}
				{if $field_name != "load_id"}
   				<td class="{$field_name}"><a href="?action=Edit&page=load&load_id={$week.load_id}">{$field}</a></td>
   				{/if}
			{/foreach}
			</tr>
		{/foreach}
		</tbody>