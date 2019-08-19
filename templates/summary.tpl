<!-- summary start -->
	<tbody class="summary">
		{foreach from=$summary item=summary_item}
		<tr>
			{foreach from=$summary_item item=field key=field_name}
				<td class="{$field_name}">{$field}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
	<!-- summary end -->