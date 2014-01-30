<tr>
	<td title="{DETAILS}">{TITLE}</td>
	<td style="text-align:right">
		{IF IS_SET CHANNEL_ICON}
			<a href='{URL_WATCH}'><img src='{CHANNEL_ICON}' alt='{CHANNEL}' title='{CHANNEL}' /></a>
		{ELSE}
			<a href='{URL_WATCH}'>{CHANNEL}</a>
		{ENDIF}
	</td>
</tr>
<tr>
	<td colspan="2"><i>{DATETIME}</i></td>
</tr>
