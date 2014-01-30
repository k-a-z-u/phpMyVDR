<tr {IF IS_TRUE CONFLICTS}class='conflicts'{ENDIF}>
	<td style="height:25px;">
		{IF IS_TRUE IS_RECORDING}<img src="{PATH}/recording2.gif" alt="recording"/>{ENDIF}
	</td>
	<td>{ACTIVE}</td>
	<td style='text-align:center;'>
		{IF IS_SET CHANNEL_ICON}
			<a href='{URL_WATCH}'><img src='{CHANNEL_ICON}' alt='{CHANNEL}' title='{CHANNEL}' /></a>
		{ELSE}
			<a href='{URL_WATCH}'>{CHANNEL}</a>
		{ENDIF}
	</td>
	<td>{DATE}</td>
	<td>{START}</td>
	<td>{END}</td>
	<td>{TITLE}</td>
	
	<td>
		<a href='{URL_EDIT}'><img src='{PATH}/edit.png' alt='{*LANG_EDIT}' title='{*LANG_EDIT}' /></a>
		<a href='{URL_DELETE}' onclick='return Helper.confirm("{*LANG_TIMER_ASK_DELETE}", "{JS_SAFE:TITLE}");'><img src='{PATH}/recycle.png' alt='{*LANG_DELETE}' title='{*LANG_DELETE}'/></a>
	</td>
</tr>