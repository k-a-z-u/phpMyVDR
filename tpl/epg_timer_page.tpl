<div class="box">
	<div class="header">{*LANG_TIMER_LIST_HEADER}</div>
	<div class="content" id="timelineDiv"></div>
	<div class="footer"></div>
</div>

<script type="text/javascript">
	var tl = new Timeline();
	tl.setSize(900, 0);
	tl.setDisplayTime(1);
	tl.setTotalTime(3);
	var str = Helper.query('./ext/timer_timeline.php?days=3');
	tl.parse(str);
	tl.displayTo('timelineDiv');
</script>

<br/>

<div class="box">
	<div class="header">{*LANG_TIMER_LIST_HEADER}</div>
	<div class="content">
		<table class='epg_timers_tbl'>
			<tr>
				<th></th>
				<th>{*LANG_TIMER_TBL_ACTIVE}</th>
				<th>{*LANG_TIMER_TBL_CHANNEL}</th>
				<th>{*LANG_TIMER_TBL_DATE}</th>
				<th>{*LANG_TIMER_TBL_START}</th>
				<th>{*LANG_TIMER_TBL_END}</th>
				<th>{*LANG_TIMER_TBL_TITLE}</th>
				<th></th>
			</tr>
			{ENTRIES}
		</table>
	</div>
	<div class="footer">
		<a href='{URL_NEW_TIMER}'><input type="button" value="{*LANG_TIMER_NEW}"  /></a>
	</div>
</div>
