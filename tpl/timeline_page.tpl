<form method="post" action="">
	<div class="dropout">
		<table width="100%">
			<tr>
				<td>{*LANG_TIMELINE_TIME}:</td>
				<td>{CMB_TIME}</td>
				<td align="right"><input type="submit" value="{*LANG_EPG_OPTS_APPLY}" /></td>
			</tr>
		</table>
	</div>
</form>

<div style="height:20px;">&nbsp;</div>

<table style="width:100%;" class="epg_timeline" cellpadding="2" cellspacing="0">
	<tr>
		<th>channel</th>
		<th>
			<div class='epg_timeline_cur_time' style='margin-left:{CUR_TIME_POS}px'></div>
			shows
		</th>
	</tr>
	{ENTRIES}
</table>
