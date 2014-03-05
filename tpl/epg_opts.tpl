<form method="post" action="">
	<div class="box">
		<div class="header">{*LANG_EPG_OPTS}</div>
		<div class="content">
			<table>
				<tr>
				
					{IF IS_TRUE EN_OPT_BY_CHANNEL}
					<td>{*LANG_EPG_OPTS_CHANNEL}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_TIME}
					<td>{*LANG_EPG_OPTS_TIME}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_SEARCH}
					<td>{*LANG_EPG_OPTS_SEARCH}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_SORT}
					<td>{*LANG_EPG_OPTS_SORT}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_CHANNELFILTER}
					<td>{*LANG_EPG_OPTS_USER_CHANNELFILTER}</td>
					{ENDIF}
					
				</tr>
				<tr>
					
					{IF IS_TRUE EN_OPT_BY_CHANNEL}
					<td>{CMB_CHANNEL}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_TIME}
					<td>{CMB_TIME}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_SEARCH}
					<td><input type="text" name="search_text" value="{SEARCH_TEXT}" /></td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_SORT}
					<td>{CMB_SORT}</td>
					{ENDIF}
					
					{IF IS_TRUE EN_OPT_CHANNELFILTER}
					<td>{CMB_USERFILTER}</td>
					{ENDIF}
				
				</tr>
			</table>
		</div>
		<div class="footer">
			<a title="{*LANG_EPG_OPTS_GET_RSS}" style="float:right; margin-top:1px; margin-right:1px;" href="{URL_RSS}"><img src="{PATH}/rss.png" alt="" /></a>
			<input type="submit" value="{*LANG_EPG_OPTS_APPLY}" />&nbsp;
		</div>
	</div>
</form>
