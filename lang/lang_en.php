<?php
	
	setlocale(LC_ALL, 'en_EN');
	
	/* dates */
	define('LANG_DATE_DATE', '%d.%m.%Y');
	define('LANG_DATE_TIME', '%H:%M:%S');
	define('LANG_DATE_HOURMINUTE', '%H:%M');
	define('LANG_DATE_DATETIME', '%d.%m.%Y %H:%M:%S');
	define('LANG_DATE_DAYMONTH', '%d.%m.');
	define('LANG_DATE_DAYNAME', '%A');
	define('LANG_DATE_DAYNAMES', 'monday,tuesday,wednesday,thursday,friday,saturday,sunday');
	define('LANG_DATE_DAYNAMES_SHORT', 'mon,tue,wed,thu,fri,sat,sun');
	
	define('LANG_DATE_SHOW_TIME', 'H:i');
	define('LANG_DATE_SHOW_DATETIME', "l jS: H:i");
	
	define('LANG_DATE_MINUTES', 'minutes');
	
	define('LANG_DAY_MONDAY', 'monday');
	define('LANG_DAY_TUESDAY', 'tuesday');
	define('LANG_DAY_WEDNESDAY', 'wednesday');
	define('LANG_DAY_THURSDAY', 'thursday');
	define('LANG_DAY_FRIDAY', 'friday');
	define('LANG_DAY_SATURDAY', 'saturday');
	define('LANG_DAY_SUNDAY', 'sunday');
	
	
	/* main */
	define('LANG_TITLE', 'phpMyVDR');
	
	/* menu */
	define('LANG_MENU_HEADER', 'MENU');
	define('LANG_MENU_START', 'Startpage');
	define('LANG_MENU_EPG_NOW', 'Currently running');
	define('LANG_MENU_EPG_BY_PROGRAM', 'By Program');
	define('LANG_MENU_EPG_TIMELINE', 'Timeline');
	define('LANG_MENU_EPG_UPDATE', 'EPG-Update');
	define('LANG_MENU_TIMER', 'Timer');
	define('LANG_MENU_CHANNELS', 'Channels');
	define('LANG_MENU_SVDRP', 'SVDRP Commands');
	define('LANG_MENU_RSS', 'RSS');
	
	/* rss */
	define('LANG_RSS_HEADER', 'create an RSS link');
	define('LANG_RSS_SEARCH', 'search');
	define('LANG_RSS_LINK', 'link');
	
	/* channels */
	define('LANG_CHANNELS_ASK_DELETE', 'are you sure you want to delete the selected channel?');
	define('LANG_CHANNELS_HEADER', 'list of all channels');
	define('LANG_CHANNELS_NAME', 'name');
	define('LANG_CHANNELS_INDEX', 'index');
	define('LANG_CHANNELS_CODE', 'code');
	define('LANG_CHANNELS_MSG_DELETED', 'the selected channel has been successfully deleted\n\nYou must now trigger an EPG-update to commit the changed channel- and EPG-data to the internal database!');
	
	
	/* svdrp */
	define('LANG_SVDRP_REQUEST_HEADER', 'send a SVDRP-request');
	define('LANG_SVDRP_RESPONSE_HEADER', 'the server\'s response');
	define('LANG_SVDRP_REQUEST', 'request');
	define('LANG_SVDRP_SEND', 'send');
	
	/* epg */
	define('LANG_EPG_TXT_SEARCH', 'click for EPG search');
	define('LANG_EPG_OPTS', 'options');
	define('LANG_EPG_OPTS_TIME', 'time');
	define('LANG_EPG_OPTS_TIME_NOW', 'now');
	define('LANG_EPG_OPTS_TIME_NEXT', 'next');
	define('LANG_EPG_OPTS_TIME_2015', '20:15');
	define('LANG_EPG_OPTS_TIME_2200', '22:00');
	define('LANG_EPG_OPTS_SORT', 'sort');
	define('LANG_EPG_OPTS_SORT_BY_CHANNEL', 'by channel name');
	define('LANG_EPG_OPTS_SORT_BY_DAY_AND_CHANNEL', 'by day and channel name');
	define('LANG_EPG_OPTS_SORT_BY_TIME', 'by time');
	define('LANG_EPG_OPTS_SORT_BY_DURATION', 'by duration');
	define('LANG_EPG_OPTS_USER_CHANNELFILTER', 'channel-filter');
	define('LANG_EPG_OPTS_APPLY', 'apply');
	define('LANG_EPG_OPTS_SEARCH', 'search');
	define('LANG_EPG_OPTS_GET_RSS', 'get an RSS-feed for the current view');
	define('LANG_EPG_OPTS_CHANNEL', 'channel');
	define('LANG_EPG_OPTS_IMDB_QUERY', 'query IMDB');
	define('LANG_EPG_NO_DATA', 'no EPG data available');
	
	
	/* epg update */
	define('LANG_EPG_UPDATE_STATUS_NOT_RUNNING', 'there is currently no update running');
	define('LANG_EPG_UPDATE_OPTIONS', 'options');
	define('LANG_EPG_UPDATE_START', 'start');
	define('LANG_EPG_UPDATE_STATUS', 'status');
	
	/* timer */
	define('LANG_TIMER_TBL_ACTIVE', 'active');
	define('LANG_TIMER_TBL_CHANNEL', 'channel');
	define('LANG_TIMER_TBL_DATE', 'date');
	define('LANG_TIMER_TBL_START', 'start');
	define('LANG_TIMER_TBL_END', 'end');
	define('LANG_TIMER_TBL_TITLE', 'title');
	define('LANG_TIMER_ACTIVE_YES', 'yes');
	define('LANG_TIMER_ACTIVE_NO', 'no');
	define('LANG_TIMER_LIST_HEADER', 'list of all timers');
	define('LANG_TIMER_NEW', 'create new timer');
	define('LANG_TIMER_ASK_DELETE', 'are you sure you want to delete the selected timer?');
	
	define('LANG_TIMER_EDIT_EDIT', 'edit timer');
	define('LANG_TIMER_EDIT_NEW', 'create a new timer');
	define('LANG_TIMER_EDIT_ACTIVE', 'active');
	define('LANG_TIMER_EDIT_TITLE', 'record\'s title');
	define('LANG_TIMER_EDIT_CHANNEL', 'channel');
	define('LANG_TIMER_EDIT_ACTIVE_YES', 'yes');
	define('LANG_TIMER_EDIT_ACTIVE_NO', 'no');
	define('LANG_TIMER_EDIT_DATE', 'day of record');
	define('LANG_TIMER_EDIT_TIME_START', 'start time');
	define('LANG_TIMER_EDIT_TIME_END', 'end time');
	define('LANG_TIMER_EDIT_OR', 'or');
	
	/* timeline */
	define('LANG_TIMELINE_RUNNING_SINCE', 'running since');
	define('LANG_TIMELINE_STARTING_IN', 'starting in');
	
	/* general */
	define('LANG_SAVE', 'save');
	define('LANG_CANCEL', 'cancel');
	define('LANG_BACK', 'back');
	define('LANG_EDIT', 'edit');
	define('LANG_DELETE', 'delete');
	
	/* femon */
	define('LANG_FEMON_HEADER', 'Current Status');
	define('LANG_FEMON_CARD', 'Card');
	define('LANG_FEMON_SIGNAL_STRENGTH', 'Signal Strength');
	define('LANG_FEMON_SIGNAL_NOISE', 'Signal/Noise Ratio');

	
	
	/* exception */
	define('LANG_MSG_ERROR', 'error');
	define('LANG_EX_TIMER_WRONG_PARAMETERS', 'An error occurred while storing the timer.\n\nPerhaps you used wrong parameters?\nThe date must have the format DD or YYYY-MM-DD');
	define('LANG_EX_SVDRP_WRONG_CODE', 'The SVDRP request to the server returned a wrong code\n.We expected {EXP} but received {RCV}');
	define('LANG_EX_SVDRP_FAILED', 'The SVDRP request to the server failed.\nThe given error message was:\n{MSG}');
	define('LANG_EX_SVDRP_PLUGIN_NOT_FOUND', 'The SVDRP request to the server failed because the requested plugin "{PLUG}" was not found.');
	
	/* genres */
	//http://www.etsi.org/deliver/etsi_en/300400_300499/300468/01.12.01_40/en_300468v011201o.pdf P. 41 ff
	define('LANG_GENRE_MOVIE', 'movie');
	define('LANG_GENRE_MOVIE_0', 'movie');
	define('LANG_GENRE_MOVIE_1', 'detective/thriller');
	define('LANG_GENRE_MOVIE_2', 'adventure/western/war');
	define('LANG_GENRE_MOVIE_3', 'scify/fantasy/horror');
	define('LANG_GENRE_MOVIE_4', 'comedy');
	define('LANG_GENRE_MOVIE_5', 'soap');
	define('LANG_GENRE_MOVIE_6', 'romance');
	define('LANG_GENRE_MOVIE_7', 'classical/historical');
	define('LANG_GENRE_MOVIE_8', 'adult');
	
	define('LANG_GENRE_NEWS', 'news');
	define('LANG_GENRE_NEWS_0', 'news');
	define('LANG_GENRE_NEWS_1', 'news/weather');
	define('LANG_GENRE_NEWS_2', 'news magazine');
	define('LANG_GENRE_NEWS_3', 'documentary');
	define('LANG_GENRE_NEWS_4', 'discussion/interview');
		
	define('LANG_GENRE_SHOW', 'show');
	define('LANG_GENRE_SHOW_0', 'show');
	define('LANG_GENRE_SHOW_1', 'game show/contest');
	define('LANG_GENRE_SHOW_2', 'variety show');
	define('LANG_GENRE_SHOW_3', 'talk show');
	
	define('LANG_GENRE_SPORTS', 'sports');
	define('LANG_GENRE_SPORTS_0', 'sports');
	define('LANG_GENRE_SPORTS_1', 'special event sports');
	define('LANG_GENRE_SPORTS_2', 'sports magazine');
	define('LANG_GENRE_SPORTS_3', 'soccer');
	define('LANG_GENRE_SPORTS_4', 'tennis');
	define('LANG_GENRE_SPORTS_5', 'team sports');
	define('LANG_GENRE_SPORTS_6', 'athletics');
	define('LANG_GENRE_SPORTS_7', 'motor sports');
	define('LANG_GENRE_SPORTS_8', 'water sports');
	define('LANG_GENRE_SPORTS_9', 'winter sports');
	define('LANG_GENRE_SPORTS_10', 'equestrian');
	define('LANG_GENRE_SPORTS_11', 'martial sports');
 	
	define('LANG_GENRE_CHILDREN', 'children');
	define('LANG_GENRE_CHILDREN_0', 'children');
	define('LANG_GENRE_CHILDREN_1', 'pre-school');
	define('LANG_GENRE_CHILDREN_2', 'entertainment 6 to 14');
	define('LANG_GENRE_CHILDREN_3', 'entertainment 10 to 16');
	define('LANG_GENRE_CHILDREN_4', 'educational');
	define('LANG_GENRE_CHILDREN_5', 'cartoon');

	
	define('LANG_GENRE_MUSIC', 'music');
	define('LANG_GENRE_MUSIC_0', 'music');
	define('LANG_GENRE_MUSIC_1', 'rock/pop');
	define('LANG_GENRE_MUSIC_2', 'classical');
	define('LANG_GENRE_MUSIC_3', 'folk');
	define('LANG_GENRE_MUSIC_4', 'jazz');
	define('LANG_GENRE_MUSIC_5', 'opera');
	define('LANG_GENRE_MUSIC_6', 'ballet');

	
	define('LANG_GENRE_CULTURE', 'culture');
	define('LANG_GENRE_CULTURE_0', 'culture');
	define('LANG_GENRE_CULTURE_1', 'performing art');
	define('LANG_GENRE_CULTURE_2', 'fine art');
	define('LANG_GENRE_CULTURE_3', 'religion');
	define('LANG_GENRE_CULTURE_4', 'popular art');
	define('LANG_GENRE_CULTURE_5', 'literature');
	define('LANG_GENRE_CULTURE_6', 'film/cinema');
	define('LANG_GENRE_CULTURE_7', 'experimental video');
	define('LANG_GENRE_CULTURE_8', 'press');
	define('LANG_GENRE_CULTURE_9', 'new media');
	define('LANG_GENRE_CULTURE_10', 'art magazine');
	define('LANG_GENRE_CULTURE_11', 'fashion');

	
	define('LANG_GENRE_POLITICAL', 'political');
	define('LANG_GENRE_POLITICAL_0', 'political');
	define('LANG_GENRE_POLITICAL_1', 'documentary');
	define('LANG_GENRE_POLITICAL_2', 'advisory');
	define('LANG_GENRE_POLITICAL_3', 'remarkable people');

	define('LANG_GENRE_SCIENCE', 'science');
	define('LANG_GENRE_SCIENCE_0', 'science');
	define('LANG_GENRE_SCIENCE_1', 'nature');
	define('LANG_GENRE_SCIENCE_2', 'technology');
	define('LANG_GENRE_SCIENCE_3', 'medicine');
	define('LANG_GENRE_SCIENCE_4', 'expedition');
	define('LANG_GENRE_SCIENCE_5', 'social sciences');
	define('LANG_GENRE_SCIENCE_6', 'further education');
	define('LANG_GENRE_SCIENCE_7', 'languages');
	
	define('LANG_GENRE_LEISURE', 'leisure');
	define('LANG_GENRE_LEISURE_0', 'leisure');
	define('LANG_GENRE_LEISURE_1', 'tourism');
	define('LANG_GENRE_LEISURE_2', 'handicraft');
	define('LANG_GENRE_LEISURE_3', 'motoring');
	define('LANG_GENRE_LEISURE_4', 'fitness');
	define('LANG_GENRE_LEISURE_5', 'cooking');
	define('LANG_GENRE_LEISURE_6', 'shopping');
	define('LANG_GENRE_LEISURE_7', 'gardening');

	
	define('LANG_GENRE_SPECIAL', 'special');
	define('LANG_GENRE_SPECIAL_0', 'original language');
	define('LANG_GENRE_SPECIAL_1', 'black and white');
	define('LANG_GENRE_SPECIAL_2', 'unpublished');
	define('LANG_GENRE_SPECIAL_3', 'live');
	define('LANG_GENRE_SPECIAL_4', 'plano-stereoscopic');
	
?>
