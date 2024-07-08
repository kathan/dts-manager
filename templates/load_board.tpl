<h2>Load Board</h2>
	
	<div style="width:100%">
		<table style="width:100%">
			<tr>
				<td style="border:1px solid black">
					{$region_users}
				</td>
				<td>
					<div style="text-align:center;">
						{if $admin}Daily profit: ${$daily_profit}{/if}
					</div>
					<div>
						<table style="width:100%">
							<tr>
								<td style="width:10%;">
									<a href="?page={$smarty.get.page}&day={$nav.prev_day}&month={$nav.prev_month}&year={$nav.prev_year}&{array2query array=$smarty.get exclude=['year','month','day','page']}">Previous Day</a>
								</td>
								<td style="text-align:center;">
									{$cal_navigator}
								</td>
								<td style="width:10%;text-align:right;">
									<a href="?page={$smarty.get.page}&day={$nav.next_day}&month={$nav.next_month}&year={$nav.next_year}">Next Day</a>
								</td>
							</tr>
						</table>
					</div>
					<div>
						<table style="width:100%">
							<tr>
								<td style="border:1px solid black;width:16%">
									Ordered
								</td>
								<td style="border:1px solid black;width:16%">
									Booked
								</td>
								<td style="border:1px solid black;width:16%">
									Loaded
								</td>
								<td style="border:1px solid black;width:16%">
									Delivered
								</td>
							</tr>
							<tr>
								<td style="border:1px solid black;vertical-align:top">
									{$ordered}
								</td>
								<td style="border:1px solid black;vertical-align:top">
									{$booked}
								</td>
								<td style="border:1px solid black;vertical-align:top">
									{$loaded}
								</td>
								<td style="border:1px solid black;vertical-align:top">
									{$delivered}
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
			