<div class='epg_entry'>
	<div class='epg_entry_header'>
		<div class='epg_entry_title'>{TITLE}&nbsp;</div>
		{IF IS_SET GENRE_MINOR}<div class='epg_entry_genre'>({GENRE_MINOR})</div>{ENDIF}
		<div class='epg_entry_imgs'>{IMGS}</div>
		&nbsp;
		<div class='epg_entry_channel' style='display:inline; float:right;'>
			{IF IS_SET CHANNEL_ICON}
				<a href='{URL_WATCH}'><img src='{CHANNEL_ICON}' alt='{CHANNEL}' title='{CHANNEL}' /></a>
			{ELSE}
				<a href='{URL_WATCH}'>{CHANNEL}</a>
			{ENDIF}
		</div>
	</div>
	<div class='epg_entry_content'>
		<div class='epg_entry_options'>
			{IF IS_TRUE HAS_DATA}<a href='{URL_REPEATS}'><img src='{PATH}/repeat.png' alt='' /></a>{ENDIF}
			{IF IS_SET DESC_LONG}<a href='javascript:Helper.toggleVisibility("desc_long_{DB_ID}");'><img src='{PATH}/info.png' alt='' /></a>{ELSE}<img src='{PATH}/info_dis.png' alt='' />{ENDIF}
			{IF IS_TRUE IS_RECORDABLE}<a href='{URL_CREATE_TIMER}'><img src='{PATH}/record.png' alt='' /></a>{ENDIF}
		</div>
		<div class='epg_entry_time'>{TIME}</div>
		<div class='epg_entry_desc_short'>{DESC_SHORT}&nbsp;</div>
		{IF IS_SET DESC_LONG}
			<div id='desc_long_{DB_ID}' class='epg_entry_desc_long' style='display:none;'>
				{DESC_LONG}
				<div id='desc_imdb_{DB_ID}' class='epg_entry_imdb'><a href='javascript:IMDB.queryTo("{JS_SAFE:TITLE}", "desc_imdb_{DB_ID}");'>{*LANG_EPG_OPTS_IMDB_QUERY}</a></div>
			</div>
		{ENDIF}
	</div>
</div>
