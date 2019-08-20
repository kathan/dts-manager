	<img src="{$imgRoot}/dts.gif">
	<table class="center">
		<th colspan=2>Login</th>
		<FORM ACTION="" METHOD="POST" target="_top">
		<input type="hidden" name="referer" value="{if isset($smarty.server.HTTP_REFERER)}{$smarty.server.HTTP_REFERER}{/if}">
		<tr>
			<td class="lblstyle">
				User Name:
			</td>
			<td class="editstyle">
				<input TYPE="TEXT" NAME="username" VALUE="{if isset($smarty.cookies.COOKIE_USERNAME)}{$smarty.cookies.COOKIE_USERNAME}{/if}" SIZE="10" MAXLENGTH="15">
			</td>
		</tr>
		<tr>
			<td class="lblstyle">
				Password:
			</td>
			<td class="editstyle">
				<input TYPE="password" NAME="password" SIZE="10" MAXLENGTH="15">
			</td>
		</tr>
		<tr>
			<td class="editstyle"></td>
			<td class="editstyle">
				<input TYPE="SUBMIT" NAME="submit" VALUE="Login">
			</td>
		</tr>
		</FORM>
	</table>