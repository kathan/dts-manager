<link rel="stylesheet" href="http://domestictransportsolutions.com/dts/bol_style.css" type="text/css" media="all">
<style media='print' type='text/css'>
{literal}
	#print_button{display:none}
{/literal}
</style>
<body>
<div id="bol">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="header row1 col1_a right_border">
			Bill of Lading - Short Form - Not Negotiable
		</td>
		<td class="header col2_a">
			BOL Number: {$load.ltl_number}
		</td>
	</tr>
	<tr>
		<td class="right_border">
			<div class="reverse row2">
				Ship From
			</div>
			<div class="ship_from">
				{$load.origin_name}<br />
				{$load.origin_address}<br />
				{$load.origin_city} {$load.origin_state}, {$load.origin_zip}<br />
				{$load.origin_notes} {$load.origin_contact}:{$load.origin_phone}
				{if $load.origin_fax}
				Fax:{$load.origin_fax}
				{/if}
				
			</div>
		</td>
		<td id="carrier_info" class="top_border">
			{*Carrier: {$load.carrier_name}<br />*}
			LTL Carrier: {$load.ltl_carrier}<br />
			Pro Number: {$load.pro_number}<br />
			Pick Up Date: {$load.pickup_date}<br />
			Due Date: {$load.delivery_date}
		</td>
	</tr>
	<tr>
		<td class="reverse col1_a row4">
			Ship To
		</td>
		<td class="row4 reverse">
			References
		</td>
	</tr>
	<tr>
		<td class="right_border row5">
			<div class="ship_to">
				{$load.dest_name}<br />
				{$load.dest_address}<br />
				{$load.dest_city} {$load.dest_state}, {$load.dest_zip}<br />
				{$load.dest_notes} {$load.dest_contact}:{$load.dest_phone}
				{if $load.dest_fax}
				Fax:{$load.dest_fax}
				{/if}
			</div>
			<div class="reverse row6">
				Freight Charges Third Party Billing Prepaid to:
			</div>
			<div class="third_party row7">
				DOMESTIC TRANSPORT SOLUTIONS<br />
				2420 E. Oakton St. Unit C<br />
				Elk Grove Township, IL 60005
			</div>
		</td>
		<td class="references">
			BOL#:{$load.ltl_number}<br />
			PickUp/PO#: {$load.pick_num}<br />
			Delivery/PO#: {$load.dest_num}
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
	<tr class="row row8">
		<td class=" special_instructions right_border">
			<div class="col1_b special_instructions_title">Special Instructions:</div>
				PLEASE DO NOT DOUBLE STACK!!!!! ANY QUESTIONS PLEASE CALL DTS @ 847.981.1400. Driver and consignee must sign and document any shortage or damage prior to driver leaving.<br />
				
		</td>
		<td class="col2_b freight_terms">
			<div class="freight_terms_title">Freight Terms:</div><br />
			Third Party PPD:____
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
	<tr class="top_border bottom_border row">
		<td class="t_head col1_c item_table bottom_border right_border">
			Qty
		</td>
		<td class="t_head col2_c item_table bottom_border right_border">
			Type
		</td>
		<td class="t_head col3_c item_table bottom_border right_border">
			Weight
		</td>
		<td class="t_head col4_c item_table bottom_border right_border">
			HM<br />(X)
		</td>
		<td class="t_head col5_c item_table bottom_border right_border">
			NMFC
		</td>
		<td class="t_head col6_c item_table bottom_border right_border">
			Item Description
		</td>
		<td class="t_head col7_c item_table bottom_border">
			LTL Class
		</td>
	</tr>
	
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			{$load.pallets}&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			{$load.weight}&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			{$load.commodity}&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			{$load.class}&nbsp;
		</td>
	</td>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="col1_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col2_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col3_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col4_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col5_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col6_c item_table bottom_border right_border">
			&nbsp;
		</td>
		<td class="col7_c item_table bottom_border">
			&nbsp;
		</td>
	</tr>
	
	<tr>
		<td class="col1_c item_table right_border top_border">
			{$load.pallets}&nbsp;
		</td>
		<td class="col2_c item_table right_border top_border">
			&nbsp;
		</td>
		<td class="col3_c item_table right_border top_border">
			{$load.weight}&nbsp;
		</td>
		<td class="col4_c item_table right_border top_border">
			&nbsp;
		</td>
		<td class="col5_c item_table right_border top_border">
			&nbsp;
		</td>
		<td class="col6_c item_table right_border top_border bold">
			Grand Total: {$load.weight} (lbs.)
		</td>
		<td class="col7_c item_table top_border">
			&nbsp;
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="row9 col1_d liability_limit">
			Where the rate is dependent on value, shipper are required to state specifically in writing the agreed or declared value of the property as follows. "The agreed or declared value of the property is specifically stated by the shipper to be not exceeding __________per__________."
		</td>
		<td class="cod_amount">
			COD Amount: $______________________<br />
			Fee Terms: Collect__, Prepaid__, Check Acceptable__
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="liability_note bottom_border" colspan="3">
			Note: Liability limitation for loss or damage in this shipment may be applicable. See 49 USC 14706(c)(1)(A) and (B).
		</td>
	</tr>
	<tr>
		<td class="col1_e agreement right_border">
			Received, subject to the agreement between the Carrier and DTS in effect on the date  of shipment. Carrier agrees that DTS is the sole payor of the corresponding freight bill. This Bill Of Lading is not subject to any tariffs or classification, whether indivdually deteremined or filed with any federal; or state regulatory agency, except specifically agreed to in writing by DTS and Carrier.
		</td>
		<td class="col2_e trailer_loaded right_border">
			Trailer Loaded:<br />
			__ By Shipper<br />
			__ Br Driver
		</td>
		<td class="col3_e freight_counted">
			Freight Counted:<br />
			__ By Shipper<br />
			__ Br Driver
		</td>
	</tr>
	<tr>
		<td class="col1_e shipper_sig right_border top_border">
			<div class="shipper_sig_title">Shipper Signature/Date</div>
				This is to certify that the above named materials are properly classified, packaged marked and labeled, and are in proper condition for transportation accoring to the applicable regulations of the DOT.<br /><br /><br />
				Shipper: _________________________ Date: _____________
		</td>
		<td class="col2_e carrier_sig top_border" colspan="2">
			<div class="carrier_sig_title">Carrier Signature/Pickup Date:</div>
				Carrier acknowledges receipt of packages and required placards. Carrier certified emergnecy reponse information was made available and/or carrier had the DOT emergency response guidebook or equivalent documentation in the vehicle. Property described above is received in good order, except as noted.<br />
				Carrier:_________________________ Date: _____________
		</td>
	</tr>
</table>
</div>
<input type="button" id="print_button" value="Print" onclick="print();" />
</body>