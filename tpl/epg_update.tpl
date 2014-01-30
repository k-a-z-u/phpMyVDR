<div class="box">
	<div class="header">{*LANG_EPG_UPDATE_OPTIONS}</div>
	<div class="content">
	
		<table>
			<tr>
				<td><b>{*LANG_EPG_UPDATE_STATUS}</b>:</td>
				<td>
					<div id="epg_update_status_not_running">{*LANG_EPG_UPDATE_STATUS_NOT_RUNNING}</div>
					<div id="epg_update_bar" class="bar1" style="width:290px;">
						<div id="epg_update_bar_bar" class="bar1_bar" style="width:2%;"></div>
					</div>
					
				</td>
			</tr>
		</table>
	
		
		
	</div>
	<div class="footer">
		<input id="epg_update_btn_start" type="button" value="{*LANG_EPG_UPDATE_START}" />
	</div>
</div>
	
<script>EPGupdate.initialize()</script>