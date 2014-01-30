<form method="post" action="">

	<input type="hidden" name="timer_index" value="{INDEX}" />

	<div class="box">
		<div class="header">{ACTION}</div>
		<div class="content">
			<table>
			
				<tr>
					<td><b>{*LANG_TIMER_EDIT_ACTIVE}:</b></td>	
					<td>
						<input type="radio" name="timer_active" value="1" {SEL_ACTIVE_YES}/>{*LANG_TIMER_EDIT_ACTIVE_YES}
						<input type="radio" name="timer_active" value="0" {SEL_ACTIVE_NO}/>{*LANG_TIMER_EDIT_ACTIVE_NO}
					</td>
				</tr>
				
				<tr>
					<td><b>{*LANG_TIMER_EDIT_CHANNEL}:</b></td>
					<td>{CMB_CHANNELS}</td>
				</tr>
				
				<tr>
					<td><b>{*LANG_TIMER_EDIT_DATE}:</b></td>
					<td><input size="10" maxlength="10" type="text" name="timer_date" id="timer_date" value="{DATE}" /> {*LANG_TIMER_EDIT_OR}</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="checkbox" id="timer_day_monday" value="monday" {SEL_MONDAY} />{*LANG_DAY_MONDAY}
						<input type="checkbox" id="timer_day_tuesday" value="tuesday" {SEL_TUESDAY} />{*LANG_DAY_TUESDAY}
						<input type="checkbox" id="timer_day_wednesday" value="wednesday" {SEL_WEDNESDAY} />{*LANG_DAY_WEDNESDAY}
						<input type="checkbox" id="timer_day_thursday" value="thursday" {SEL_THURSDAY} />{*LANG_DAY_THURSDAY}
						<input type="checkbox" id="timer_day_friday" value="friday" {SEL_FRIDAY} />{*LANG_DAY_FRIDAY}
						<input type="checkbox" id="timer_day_saturday" value="saturday" {SEL_SATURDAY} />{*LANG_DAY_SATURDAY}
						<input type="checkbox" id="timer_day_sunday" value="sunday" {SEL_SUNDAY} />{*LANG_DAY_SUNDAY}
					</td>
				</tr>
				
				<tr>
					<td><b>{*LANG_TIMER_EDIT_TIME_START}:</b></td>
					<td>
						<input type="text" size="2" maxlength="2" name="timer_start_hour" value="{START_HOUR}" /> :
						<input type="text" size="2" maxlength="2" name="timer_start_minute" value="{START_MINUTE}" /> (hh:mm)
					</td>
				</tr>
				
				<tr>
					<td><b>{*LANG_TIMER_EDIT_TIME_END}:</b></td>
					<td>
						<input type="text" size="2" maxlength="2" name="timer_end_hour" value="{END_HOUR}" /> :
						<input type="text" size="2" maxlength="2" name="timer_end_minute" value="{END_MINUTE}" /> (hh:mm)
					</td>
				</tr>
				
				<tr>
					<td><b>{*LANG_TIMER_EDIT_TITLE}:</b></td>
					<td><input size="40" type="text" value="{TITLE}" name="timer_title" /></td>
				</tr>
				
			</table>
		</div>
		<div class="footer">
			<input type="submit" value="{*LANG_SAVE}" />
			<input type="button" value="{*LANG_CANCEL}" onclick='history.back();' />
		</div>
	</div>

		
</form>

<script>Timer.init();</script>