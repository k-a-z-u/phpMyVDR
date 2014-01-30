
/** start EPG search */
function epgSearch(event) {

	// only react on ENTER
	if (event.keyCode != 13) {return;}
	
	document.location.href="?page=EPG&search=" + event.target.value;
	
}


/** some helper functions */
var Helper = new function() {

	/** get the element either by it's ID or directly */
	this.getElement = function(objOrId) {
		var elem = (objOrId instanceof Object) ? (objOrId) : (document.getElementById(objOrId));
		if (elem == null) {alert("the template does not contain the needed element '"+element+"'! please fix this");}
		return elem;
	}

	/** set the element with the provided ID enabled/disabled */
	this.setVisible = function(elem, visible) {
		var obj = Helper.getElement(elem);
		obj.style.display = (visible) ? ('block') : ('none');
	}

	/** set the element with the provided ID enabled/disabled */
	this.setEnabled = function(elem, enabled) {
		var obj = Helper.getElement(elem);
		obj.disabled = (enabled) ? (false) : (true);
	}
	
	/** toggle the visibility of the provided element */
	this.toggleVisibility = function(elem, visible) {
		var obj = Helper.getElement(elem);
		var isVisible = obj.style.display != 'none';
		obj.style.display = (isVisible) ? ('none') : ('');
	}
	
	/** query the given URL */
	this.query = function(url) {
		xmlHttp = new XMLHttpRequest();
		xmlHttp.open('GET', url, false);
		xmlHttp.send(null);
		return xmlHttp.responseText;
	}
	
	/** query the given URL */
	this.queryAsync = function(url) {
		xmlHttp = new XMLHttpRequest();
		xmlHttp.open('GET', url, true);
		xmlHttp.send(null);
	}
	
	/** update a progress-bar */
	this.setProgress = function(elemID, value) {
		if (value < 0 || value > 100) {return;}
		var main = document.getElementById(elemID);
		var bar = document.getElementById(elemID+"_bar");
		if (value > 10) {bar.innerHTML = value + "%"} else {bar.innerHTML = "";}
		bar.style.width = value+"%";
	}
	
	/** confirm like "delete channel" and "channel-name" */
	this.confirm = function(question, element) {
		return confirm(question + "\n" + '"' + element + '"');
	}
	
	
}

/** class for timer operations */
var Timer = new function() {

	/** attributes */
	this.elems = ['timer_day_monday', 'timer_day_tuesday', 'timer_day_wednesday', 'timer_day_thursday', 'timer_day_friday', 'timer_day_saturday', 'timer_day_sunday'];

	/** intitialize all JS function = register events, etc.. */
	this.init = function() {
		for (var i = 0; i < this.elems.length; ++i) {
			Helper.getElement(Timer.elems[i]).setAttribute('onclick', 'Timer.daySelectionChanged()');
		}
		Timer.dateToCheckboxes();
	}

	/** change the timer's date if multiple days have been selected for schedule */
	this.daySelectionChanged = function() {
		var str = '';
		str += (Helper.getElement('timer_day_monday').checked)		? ('M') : ('-');
		str += (Helper.getElement('timer_day_tuesday').checked)		? ('T') : ('-');
		str += (Helper.getElement('timer_day_wednesday').checked)	? ('W') : ('-');
		str += (Helper.getElement('timer_day_thursday').checked)	? ('T') : ('-');
		str += (Helper.getElement('timer_day_friday').checked)		? ('F') : ('-');
		str += (Helper.getElement('timer_day_saturday').checked)	? ('S') : ('-');
		str += (Helper.getElement('timer_day_sunday').checked)		? ('S') : ('-');
		if (str == '-------') {
			var now = new Date();
			var year = now.getFullYear();
			var month = (now.getMonth()+1 < 10) ? ("0" + (now.getMonth()+1)) : (now.getMonth()+1);
			var day = (now.getDate() < 10) ? ("0" + now.getDate()) : (now.getDate());
			str = year + "-" + month + "-" + day;
		}
		Helper.getElement('timer_date').value = str;
	}
	
	/** adjust date-selections to the provided day-checkboxes */
	this.dateToCheckboxes = function() {
		var date = Helper.getElement('timer_date').value;
		for (var i = 0; i < 7; ++i) {
			if (date.charCodeAt(i) > 57) {Helper.getElement(this.elems[i]).checked = true;}
		}
	}

}


/** the EPGupdate class */
var EPGupdate = new function() {

	/** show the current progress */
	this.showProgress = function () {
		var val = Helper.query('ext/epgToSqlite.php?action=progress');		// query the curren status
		Helper.setVisible('epg_update_status_not_running', val==-1);
		Helper.setVisible('epg_update_bar', val!=-1);
		Helper.setEnabled('epg_update_btn_start', val==-1);
		Helper.setProgress('epg_update_bar', val);
	}
	
	/** start the update */
	this.start = function() {
		Helper.setEnabled('epg_update_btn_start', false);					// disable the start-button
		Helper.queryAsync('ext/epgToSqlite.php?action=update');				// start update
	}
		
	/** this will start updating the progress and configure the buttons */
	this.initialize = function() {
		this.showProgress();
		window.setInterval(this.showProgress, 750);
		var btn = document.getElementById('epg_update_btn_start');
		btn.setAttribute('onclick', 'EPGupdate.start()');
	}

    this.type = "macintosh";
    this.color = "red";
    this.getInfo = function () {
        return this.color + ' ' + this.type + ' apple';
    };
}


/** helps with RSS links */
var RSS = new function() {

	/** get rss feed that searches for the given string */
	this.getLinkSearch = function($txt) {
		return 'ext/rss.php?search=' + $txt;
	}

}


/** perform IMDb lookups */
var IMDB = new function() {

	/** get IMDB entry by title */
	this.getForTitle = function(title) {
		var ret = Helper.query("http://www.imdbapi.com/?t=" + title);
		var obj = eval("(" + ret + ")");
		var str = '';
		str += '<b>Title:</b> '		+ obj.Title + ' (' + obj.Year + ')\n';
		str += '<b>Rating:</b> '	+ obj.imdbRating + '/10 (' + obj.Rated + ')\n';
		str += '<b>Genre:</b> '		+ obj.Genre + '\n';
		str += '<b>Actors:</b> '	+ obj.Actors + '\n';
		str += '<b>Plot:</b> '		+ obj.Plot + '\n';
		return str.replace(/\n/g, '<br/>');
	}
	
	/** query IMDB data into the given element's innerHTML */
	this.queryTo = function(title, elem) {
		var obj = Helper.getElement(elem);
		obj.innerHTML = IMDB.getForTitle(title);
	}
}
