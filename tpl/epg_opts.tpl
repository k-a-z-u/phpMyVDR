<form method="post" action="">
	<div class="dropout">
		<table>
			<tr>
			
				{IF IS_TRUE EN_OPT_BY_CHANNEL}
				<td>{*LANG_EPG_OPTS_CHANNEL}:</td>
				<td>{CMB_CHANNEL}</td>
				{ENDIF}
				
				{IF IS_TRUE EN_OPT_TIME}
				<td>{*LANG_EPG_OPTS_TIME}:</td>
				<td>{CMB_TIME}</td>
				{ENDIF}
				
				{IF IS_TRUE EN_OPT_SEARCH}
				<td>{*LANG_EPG_OPTS_SEARCH}</td>
				<td><input type="text" name="search_text" value="{SEARCH_TEXT}" /></td>
				{ENDIF}
				
				{IF IS_TRUE EN_OPT_SORT}
				<td>{*LANG_EPG_OPTS_SORT}:</td>
				<td>{CMB_SORT}</td>
				{ENDIF}
				
				{IF IS_TRUE EN_OPT_CHANNELFILTER}
				<td>{*LANG_EPG_OPTS_USER_CHANNELFILTER}:</td>
				<td>{CMB_USERFILTER}</td>
				{ENDIF}
				
				<td>
					<a title="{*LANG_EPG_OPTS_GET_RSS}" class="rssLink" href="{URL_RSS}"><img src="{PATH}/rss.png" alt="" /></a>
					<input type="submit" value="{*LANG_EPG_OPTS_APPLY}" />&nbsp;
				</td>
				
			</tr>
	
		</table>
	</div>
</form>
<div style="height:10px;">&nbsp;</div>
