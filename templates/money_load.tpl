<!-- money_load.tpl start -->
<table width="100%" border=0 class="content" id="money">
	<tr>
		<td></td><td></td>
		<td class="right bold">Customer Total:</td>
		<td class="faux_edit">
			$<span id="customer_total" class="left bold">{$customer_total}</span>
		</td>
		<td class="right bold">Carrier Total:</td>
		<td class="faux_edit">
			$<span id="carrier_total" class="left bold">{$carrier_total}</span>
		</td>
		<td class="right bold">GP:</td>
		<td class="faux_edit">
			$<span id="gp" class="bold">{$gp}</span>
		</td>
		{*<td class="right bold">GPP:</td>
		<td class="faux_edit">
			<span id="gpp" class="bold">{$gpp}</span>%
		</td>*}
	</tr>
{*</table>
<table width="100%" border=0 class="content">*}
	<tr>
		<td class="right bold">
			DLS-LTL
		</td>
		<td class="left bold">
		
			{if $load.wc_active}
				<input type="checkbox" name="wc_active" id="action=update&table=load&load_id={$load.load_id}&wc_active=" onchange="db_save(this);column_updated(this);wc_change(this);" checked="checked" />
				
				
			{else}
				{assign var=class value="hidden"}
				<input type="checkbox" name="wc_active" id="action=update&table=load&load_id={$load.load_id}&wc_active=" onchange="db_save(this);column_updated(this);wc_change(this);" />
			{/if}
		</td>
		<td class="right bold">
			DLS-LTL%
		</td>
		<td class="faux_edit">
			<span id="wc_percent" class="left bold">{$load.wc_percent}</span>
		</td>
		<td class="right bold">
			DLS-LTL:
		</td>
		<td class="faux_edit">
			$<span id="wcp" class="left bold {$class}">{$wcp}</span>
		</td>
		<td class="right bold">
			DTSP:
		</td>
		<td class="faux_edit">
			$<span id="dtsp" class="left bold {$class}">{$dtsp}</span>
		</td>
	</tr>
	<tr>
		<td class="right bold">
			DLS
		</td>
		<td class="left bold">
		
			{if $load.dls_active}
				<input type="checkbox" name="dls_active" id="action=update&table=load&load_id={$load.load_id}&dls_active=" onchange="db_save(this);column_updated(this);dls_change(this);" checked="checked" />
				
				
			{else}
				{assign var=class value="hidden"}
				<input type="checkbox" name="dls_active" id="action=update&table=load&load_id={$load.load_id}&dls_active=" onchange="db_save(this);column_updated(this);dls_change(this);" />
			{/if}
		</td>
		<td class="right bold">
			DLS%
		</td>
		<td class="faux_edit">
			<span id="dls_percent" class="left bold">{$load.dls_percent}</span>
		</td>
		<td class="right bold">
			DLSP:
		</td>
		<td class="faux_edit">
			$<span id="dlsp" class="left bold {$class}">{$dlsp}</span>
		</td>
	</tr>
</table>
<!-- money_load.tpl end -->