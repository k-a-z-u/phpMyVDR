<form method="post" action="">
	<div class="box">
		<div class="header">{*LANG_RSS_HEADER}</div>
		<div class="content2">
			<table>
				<tr>
					<td><b>{*LANG_RSS_SEARCH}</b></td>
					<td><input type="text" name="request" onkeyup="hrefSearch.href = hrefSearch.innerHTML = RSS.getLinkSearch(this.value)"/></td>
				</tr>
				<tr>
					<td><b>{*LANG_RSS_LINK}</b></td>
					<td width="300"><a id="hrefSearch" href=""></a></td>
				</tr>
			</table>
		</div>
		<div class="footer"></div>
	</div>
</form>
<br/>