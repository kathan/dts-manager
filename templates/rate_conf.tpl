<!-- rate conf start -->
{literal}
		<style media="print" type="text/css">
			body{font-size:.9em}
			table{font-size:10pt;}
			#print_button{display:none}
		</style>
		<style>
			.bold{font-weight:bold;}
			.heavy_frame{border:1px solid black}
			.right{text-align:right;}
			.center{text-align:center;}
			table{font-size:9pt;}
			.bottom_border{border-top:solid black 1px}
		</style>
 {/literal}
		<div style="font-size:.8em;width:7in;font-family:sans-serif;">
			<table style="width:100%" border="0">
				<tr>
					<td style="text-align:left" width="1%">
						<center>Domestic Transport Solutions Carrier Confimation Load #</center>
					</td>
				</tr>
				<tr>
					<td>
						<table border=0 width=100%>
							<tr>
								<td style="vertical-align:middle" width=1%>
									<img src="images/dts.gif">
								</td>
								<td style="vertical-align:middle;text-align:center;">
								<div style="margin-right:auto;margin-left:auto;font-size:16pt;padding:3pt;width:4em;" class="bold heavy_frame">{$load.load_id}</div>
								</td>
								<td width=30% style="vertical-align:middle">   
		{if $load.ltl_number}
									<div style="text-align:center;margin-right:auto;margin-left:auto;padding:6pt;" class="bold heavy_frame">{$load.pro_number}</div>
									<div style="text-align:center;padding:6pt;font-size:.8em" class="bold heavy_frame">{$load.ltl_number}</div>   
		{/if}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="text-align:center">
						Driver must call Domestic Transport Solutions<br />
						For distpatch at 847-981-1400 and ask for load # {$load.load_id}<br />Domestic Transport Solutions has 24 hour dispatch. Call anytime.
					</td>
				</tr>
			</table>
			<hr />   
			<!-- start origin -->
			<table style="margin-left:auto;margin-right:auto;width:80%" border="0">
				<tr>
					<td colspan="4">Our Contract Carrier Agreement is amended as follows:</td>
				</tr>   
		
		{section name=i loop=$pick}
				{$sep}
				<tr>
					<td>Shipper:</td><td class="bold">{$pick[i].origin_name}</td>
					<td width="84px"></td>
					<td>Pick&nbsp;Up&nbsp;Date:</td>
					<td class="bold">{$pick[i].pickup_date}</td>
				</tr>
				<tr>
					<td rowspan="2">Address:</td>
					<td rowspan="2" class="bold">{$pick[i].origin_address}<br />{$pick[i].origin_city} {$pick[i].origin_state} {$pick[i].origin_zip}</td>
					<td width="84px"></td>
					<td>Pick Up Time:</td><td class="bold">{$pick[i].pickup_time}</td>
				</tr>
				<tr>
					<td width="84px"></td>
					<td>Pick #:</td><td class="bold">{$pick[i].pick_num}</td>
				</tr>
				<tr>
					<td>Phone:</td><td class="bold">{$pick[i].origin_phone}</td>
					<td width="84px"></td>
					<td>Notes:</td><td class="bold">{$pick[i].origin_notes}</td>
				</tr>   
				{assign var=sep value='
				<tr>
					<td colspan="5"><hr /></td>
				</tr>'}
		{/section}
		
				<!-- end origin -->
				<!-- start dest -->   
		
		{section name=i loop=$drop}
				{$sep}
				<tr>
					<td>CONS:</td><td class="bold">{$drop[i].dest_name}</td>
					<td width="84px"></td>
					<td>Delivery Date:</td><td class="bold">{$drop[i].delivery_date}</td>
				</tr>
				<tr>
					<td rowspan="2">Address:</td><td rowspan="2" class="bold">{$drop[i].dest_address}<br />{$drop[i].dest_city} 	{$drop[i].dest_state} {$drop[i].dest_zip}</td>
					<td width="84px"></td>
					<td>Delivery&nbsp;Time:</td><td class="bold">{$drop[i].delivery_time}</td>
				</tr>
				<tr>
					<td width="84px"></td>
					<td>Dest #:</td><td class="bold">{$drop[i].dest_num}</td>
				</tr>
				<tr>
					<td>Phone:</td><td class="bold">{$drop[i].dest_phone}</td>
					<td width="84px"></td>
					<td>Notes:</td><td class="bold">{$drop[i].dest_notes}</td>
				</tr>   
		{/section}
			</table>
			<hr />
			<!-- end dest -->   

			<!-- start body -->
			<div >Driver must ask for and receive:
				<table style="margin-left:auto;margin-right:auto;width:80%">
					<tr>
						<td>Commodity</td>
						<td>Est Weight</td>
						<td>Size</td>
						<td>Class</td>
						<td>Pallets</td>
						<td></td>
					</tr>
					<tr>
						<td class="bold heavy_frame">{$load.commodity}</td>
						<td class="bold heavy_frame">{$load.weight}</td>
						<td class="bold heavy_frame">{$load.size}</td>
						<td class="bold heavy_frame">{$load.class}</td>
						<td class="bold heavy_frame">{$load.pallets}</td>
						<td></td>
					</tr>
				</table>
			</div>
			<br />
			<br />
			Any loading or unloading fees must be negotiated prior to invoicing and driver must get bill signed and obtain a lumper receipt. ALL DRIVERS MUST CALL IN FOR DISPATCH , WHEN LOADED AND EMPTY WITH A VERBAL POD TO INSURE NO PENALTIES. DRIVERS MUST CHECK CALL DAILY WITH DTS BETWEEN THE HOURS OF 7:00AM AND 10:00AM CENTRAL TIME. FAILURE TO MEET ANY OF THE ABOVE REQUIREMENTS WILL RESULT IN A $50.00 PENALTY PER INSTANCE.
			<br />
			<br />
			<div style="text-align:center">
				<table style="margin-left:auto;margin-right:auto;width:60%" cellspacing="0">
					<tr>
						<td></td>
						<td>Amount</td>
						<td>Rate</td>
						<td>Extended</td>
					</tr>
					<tbody style="border:1px solid black">   
		
		{*top right bottom left*}
		{if $load.line_haul_total > 0}
						<tr style="">
							<td class="bold">Line Haul</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.line_haul_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 0px 1px 1px;">${$load.carrier_line_haul|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$line_haul_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
		{if $detention_total > 0}
						<tr style="">
							<td class="bold">Detention</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.detention_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 0px 1px 1px;">${$load.carrier_detention|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$detention_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
		{if $tonu_total > 0}
						<tr style="">
							<td class="bold ">TONU</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.tonu_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 0px 1px 1px;">${$load.carrier_tonu|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$tonu_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
		{if $unload_load_total > 0}
						<tr style="">
							<td class="bold ">Unload/Load</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.unload_load_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 0px 1px 1px;">${$load.carrier_unload_load|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$unload_load_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
		{if $fuel_total > 0}
						<tr style="">
							<td class="bold ">Fuel</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.fuel_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 0px 1px 1px;">${$load.carrier_fuel|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$fuel_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
		{if $other_total > 0}
						<tr style="">
							<td class="bold ">Other</td>
							<td class="bold center" style="border:solid black;border-width: 1px 0px 1px 1px;">{$load.other_amount|string_format:"%.2f"}</td>
							<td class="bold right" style="border:solid black;border-width: 0px 0px 1px 1px;">${$load.carrier_other|string_format:"%.2f"} </td>
							<td class="bold right" style="border:solid black;border-width: 1px 1px 1px 1px;">${$other_total|string_format:"%.2f"}</td>
						</tr>   
		{/if}
		
						<tr style="">
							<td class=""></td>
							<td class=""></td>
							<td class="bold left" style="text-align:left;border: solid black;border-width: 0px 0px 1px 1px;">TOTAL</td>
							<td class="bold right" style="border: solid black;border-width: 0px 1px 1px 1px;">${$grand_total|string_format:"%.2f"}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- end body -->   
		
			<!-- start carrier -->
			<table style="margin-left:auto;margin-right:auto;width:80%;" cellspacing="0">
				<tr>
					<td>Attention:</td><td class="bold">{$load.contact_name}</td>
				</tr>
				<tr>
					<td>Carrier ID:</td><td class="bold">S{$load.carrier_id}</td>
					<td>Equipment:</td><td class="bold">{$load.trailer_type}</td>
				</tr>
				<tr>
					<td>Carrier:</td><td class="bold">{$load.carrier_name}</td>
					<td>Booked With:</td><td class="bold">{$load.booked_with}</td>
				</tr>
				<tr>
					<td>Phone:</td><td class="bold">{$load.carrier_phone}</td>
					<td>Booked Sales:</td><td class="bold">{$load.booked_salesperson}</td>
				</tr>
				<tr>
					<td>Fax:</td><td class="bold">{$load.carrier_fax}</td>
					<td class="bold" style="border:solid black;border-width: 1px 1px 1px 1px;">Driver Name:</td>
					<td class="bold" style="border:solid black;border-width: 1px 1px 1px 0px;">{$load.driver_name}</td>
				</tr>
				<tr>
					<td>Driver Cell:</td><td class="bold">{$load.cell_number}</td>
					<td class="bold" style="border:solid black;border-width: 0px 1px 1px 1px;">Trac/Trail#:</td>
					<td class="bold" style="border:solid black;border-width: 0px 1px 1px 0px;">{$load.tractor_number}</td>
					<td class="bold" style="border:solid black;border-width: 1px 1px 1px 0px;">{$load.trailer_number}</td>
				</tr>
			</table>
		
		<!-- end carrier -->   

		<!-- start legal -->
		<br /><br /><br />
		<div style="text-align:left;font-size:.7em">
			***Failure to notify Domestic Transport Solutions of inability to meet requested delivery time will result in settlement being held pending payment by customer. Any changes from service failure on part of the carrier are the repsonsibility of the carrier. If any extra charges occur, DTS must be notified immediately to receive approval for re-imbursement. Domestic Transport Solutions will not pay any additional charges w/o previous approval by our office and written acknowledgement of the services on the original Bill of Lading. Domestic Transport Solutions payment terms are 30 days from receipt of invoice, original proof of delivery and a copy of the signed rate confirmation.
		</div>
		<!-- end legal -->   
				<!-- start footer -->
				<table width="100%" border=0>
					<tr>
						<td width="50%" valign="top">
							<div style="height:20px;border-bottom:1px solid black;margin:3px"></div>
							Carrier Representative Signature/Date
						</td>
						<td rowspan=2 style="font-size:.9em">Thank You,<br />{$load.booked_name}<br />Domestic Transport Solutions<br />847-981-1400 phone<br />847-981-1411 fax<br />2420 E. Oakton St Unit C<br />Elk Grove Township, IL 60005</td>
					</tr>
					<tr>
						<td valign="bottom">
							<div style="height:20px;border-bottom:1px solid black;margin:3px"></div>
							Print Name And Title
						</td>
					</tr>
				</table>
			</div>   
		<input type="button" id="print_button" value="Print" onclick="print();"/>   