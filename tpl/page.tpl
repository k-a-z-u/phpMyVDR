<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={CHARSET}" />
		<title>{*LANG_TITLE}</title>
		{SCRIPTS}
	</head>
	
	<body>
		
		<div id="title">
			<div id="title_content">
				
			</div>
			{STATUSBAR}
			<span style="clear:both">&nbsp;</span>
		</div>
		
		<div class="box" id="menu">
			<div class="header" id="menu_title">{*LANG_MENU_HEADER}</div>
			<div class="content" id="menu_content">
				{MENU}
				<div class="menuEntry">
					<form method="post" action="?page=EPG">
						<input type="text" name="search_text" class="txtSearchDis" value="{*LANG_EPG_TXT_SEARCH}" onclick="this.value=''; this.className='txtSearchAct'" onblur="this.value='{*LANG_EPG_TXT_SEARCH}'; this.className='txtSearchDis';"/>
					</form>
				</div>
			</div>
			<div class="footer" id="menu_footer">&nbsp;</div>
		</div>
		
		<div id="main">
			{CONTENT}
		</div>
			
	</body>
	
</html>

