<tr>
	<td>
	{IF IS_SET CHANNEL_ICON}
		<a href='{URL_WATCH}'><img src='{CHANNEL_ICON}' alt='{CHANNEL}' title='{CHANNEL}'/></a>
	{ELSE}
		<a href='{URL_WATCH}'>{CHANNEL}</a>
	{ENDIF}
	</td>
	<td>{SHOWS}</td>
</tr>