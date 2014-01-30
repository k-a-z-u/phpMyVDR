function Timeline() {

	/* attributes */
	this.width = 800;
	this.height = 60;
	this.displayInterval = 1 * 24 * 60 * 60;
	this.totalInterval = 7 * 24 * 60 * 60;
	this.divFinal = document.createElement('div');
	this.divEntries = document.createElement('div');
	this.divChannels = document.createElement('div');
	this.entries = new Array();
	
	this.channelPaddingTop = 5;
	this.channelPaddingBottom = 7;
	this.channelWidth = 22;
	
	this.divEntries.className='epg_schedule_entries';
	this.divChannels.className='epg_schedule_channels';
	this.divFinal.className = 'epg_schedule';
	
	/** set the width */
	this.setSize = function(width, height) {
		this.width = width;
		this.height = height;
		this.divEntries.style.width = this.width + 'px';
		if (this.height != 0) {this.divEntries.style.height = this.height + 'px';}
	}
	
	/** set the time (in days) to be displayed WITHOUT scrolling */
	this.setDisplayTime = function(days) {
		this.displayInterval = days * 24 * 60 * 60;
	}
	
	/** set the time (in days) to be displayed with scrolling * */
	this.setTotalTime = function(days) {
		this.totalInterval = days * 24 * 60 * 60;
	}
	
	/** add a new entry to display */
	this.addEntry = function(entry) {
		this.entries.push(entry);
	}

	/** display the graph */
	this.displayTo = function(elem) {
		this.render();
		this.divFinal.innerHTML = "";
		this.divFinal.appendChild(this.divChannels);
		this.divFinal.appendChild(this.divEntries);
		var dst = document.getElementById(elem);
		dst.innerHTML = "";
		dst.appendChild(this.divFinal);
	}
	
	/** render */
	this.render = function() {
		this.divEntries.innerHTML = '';
		this.sortEntries();
		var curTS = new Date().getTime() / 1000 - 3*60*60;
		this.renderTimeSlots(curTS);
		this.renderEntries(curTS);
	}
	
	/** render the timeslots (day and hours) */
	this.renderTimeSlots = function(curTS) {
			
		var slice = 24*60*60;
		var width = slice * this.width / this.displayInterval;
		var prevFullDay = new Date(); prevFullDay.setHours(0); prevFullDay.setMinutes(0); prevFullDay.setSeconds(0);	// round to the last full day
		var start = prevFullDay.getTime() / 1000 - curTS;
		

		for (var step = start; step < this.totalInterval; step += slice) {
		
			var date = new Date( (curTS + step) * 1000 );
			var width = slice;
			var offset = step;
			if (offset < 0) {width += offset; offset = 0;}
			
			var slot = document.createElement('div');
			var left = (offset) * this.width / this.displayInterval;
			slot.className = 'epg_schedule_slot_day';
			slot.style.left = left + 'px';
			slot.style.width = width * this.width / this.displayInterval + 'px';
			//slot.style.top = '0px';
			slot.innerHTML = date;
			this.divEntries.appendChild(slot);
		}
		
		var slice = 60*60;
		var width = slice * this.width / this.displayInterval;											// the width of one "hour"-element
		var prevFullHour = new Date();	prevFullHour.setMinutes(0); prevFullHour.setSeconds(0);			// round to the last full hour
		var start = prevFullHour.getTime() / 1000 - curTS;
		
		for (var offset = start; offset < this.totalInterval; offset += slice) {
			var date = new Date( (curTS + offset) * 1000 );
			var slot = document.createElement('div');
			var left = (offset) * this.width / this.displayInterval;
			slot.className = 'epg_schedule_slot_hour';
			slot.style.left = Math.floor(left) + 'px';
			slot.style.width = width +2+ 'px';
			//slot.style.top = '15px';
			slot.innerHTML = date.getHours();
			this.divEntries.appendChild(slot);
		}
		
		
		
	}
	
	/** comparator */
	this.compareByChannel = function(a, b) {
		return a.channel.localeCompare(b.channel);
	}
	
	/** sort the entries */
	this.sortEntries = function() {
		this.entries.sort(this.compareByChannel);
	}
	
	/** render the entries */
	this.renderEntries = function(curTS) {
	
		var lastChannelStr = null;
		var lastChannelDiv = null;
		var lastEntryDiv = null;
		var curChanPadding = 0;
		var top = 32 - this.channelWidth;
		
		// add each entry
		for (var i = 0; i < this.entries.length; ++i) {
		
			var entry = this.entries[i];
			if (entry.startTS > curTS + this.totalInterval) {continue;}							// is this element after the to-be-displayed time window?
			
			var offsetTS = entry.startTS - curTS;
			var duration = entry.duration;
			if (offsetTS < 0) { duration += offsetTS; offsetTS = 0;}							// is the element currently active? (started before "now")			
			
			if (lastChannelStr != entry.channel) {
				top += this.channelWidth;
				curChanPadding = this.channelPaddingBottom;
				lastChannelDiv = this.renderChannel(entry.channel, top);
				lastChannelStr = entry.channel;
				top += this.channelPaddingTop;
			} else {
				top += 8;
				curChanPadding += 8;
				lastChannelDiv.style.paddingBottom = curChanPadding + 'px';
			}
			
			var left = offsetTS * this.width / this.displayInterval;
			var width = duration * this.width / this.displayInterval;
			lastEntryDiv = this.renderEntry(entry.title, top, left, width, entry.clazz);		
			
		}
		
		// set the final height
		top += this.channelWidth + 18;
		this.divEntries.style.height = top + 'px';
		
	}
	
	/** add a new entry at the given position */
	this.renderEntry = function(title, top, left, width, clazz) {
		var entryDiv = document.createElement('div');
		entryDiv.style.left = left + 'px';
		entryDiv.style.width = width + 'px';
		entryDiv.style.top = top + 'px';
		entryDiv.title = title;
		entryDiv.className = 'epg_schedule_entry ' + clazz;
		entryDiv.innerHTML = title;
		this.divEntries.appendChild(entryDiv);
		return entryDiv;
	}
	
	/** add a new channel (channel) at the given position (top) */
	this.renderChannel = function(channel, top) {
		var chan = document.createElement('div');
		chan.innerHTML = channel;
		chan.style.top = top + 'px';
		chan.className = 'epg_schedule_channel';
		this.divChannels.appendChild(chan);
		return chan;
	}
	
	/** parse the given schedule string */
	this.parse = function(str) {
		var entries = str.split("\n");
		for (var i = 0; i < entries.length - 1; ++i) {
			var entry = entries[i];
			var attrs = entry.split(':');
			this.addEntry(new TimelineEntry(attrs[0], attrs[1], attrs[2], attrs[3], attrs[4]));
		}
	}
	
}

/** a timeline entry */
function TimelineEntry(channel, title, startTS, duration, clazz) {
	this.channel = channel;
	this.title = title;
	this.startTS = parseInt(startTS);
	this.duration = parseInt(duration);
	this.clazz = clazz;
}