/** class for tooltips */
var Tooltip = new function() {

	this.box = document.createElement('div');
	this.boxContent = document.createElement('div');
	
	this.create = function() {
		
		this.box.appendChild(this.boxContent);
		this.box.style.position = 'absolute';
		this.box.className = 'tooltip';
		this.boxContent.className = 'tooltip_content';
		this.box.style.visibility = 'hidden';
		
		// the tooltip will register itself to all elements when the pages has been loaded
		window.addEventListener("load", this.register, false);
		
	}
	
	
	/** the tooltip will register itself to every element that has a title attribute */
	this.registerAt = function(obj) {
		
		// we found an object with a title -> display tooltip for it
		if (obj.title) {
		
			// register in/out listeners
			obj.addEventListener("mouseover", this.prepare, true);
			obj.addEventListener("mouseout", this.hide, false);
			
			
			// remove original title, else the browser will show it's default tool-tip
			var title = obj.title;
			obj.setAttribute('data-title', title, 0);
			obj.removeAttribute('title', 0)
			
		}
		
		var subs = obj.childNodes;
		for(var i = 0; i < subs.length; ++i) {
			this.registerAt(subs[i]);
		}
		
	}

	/** register the tooltip to all elements */
	this.register = function() {
		document.body.appendChild(Tooltip.box);
		Tooltip.registerAt(document.body);
	}
	
	
	/**
	 * show a new tooltip at the position of the provided object
	 * using the object's title-attribute as content
	 */
	this.show = function(posX, posY, content) {
		 
		Tooltip.box.innerHTML = content;
		Tooltip.box.style.left = posX + 'px';
		Tooltip.box.style.top = posY + 'px';
		Tooltip.box.style.visibility = 'visible';
	}
	
	/**
	 * show a new tooltip at the position of the provided object
	 * using the object's title-attribute as content
	 */
	this.prepare = function(event) {
		
		event = event ? event : window.event;
		//if (event.stopPropagation)    event.stopPropagation();
		//if (event.cancelBubble!=null) event.cancelBubble = true;
		
		var obj = event.srcElement ? event.srcElement : event.originalTarget;
		while (!obj.getAttribute('data-title', 0)) {obj = obj.parentElement;}

		var content = obj.getAttribute('data-title', 0);
		content = content.replace(/\r/g, '<br/>');
	
		var pos = Tooltip.getPos(obj);
		var myHeight = Tooltip.box.clientHeight + 5;
		var myWidth = Tooltip.box.clientWidth + 5;
		var posX = pos[0];
		var posY = pos[1] - myHeight;
		
		if (posX + myWidth > document.body.clientWidth) {posX = document.body.clientWidth - myWidth;}
		
		// show the tooltip
		Tooltip.show(posX, posY, content);
	
	}
	
	/** hide the box */
	this.hide = function(event) {
		Tooltip.box.style.visibility = 'hidden';
	}
	
	
	/** get the position of an object */
	this.getPos = function(obj) {
		var curleft = curtop = 0;
		if (obj.offsetParent) {
			do {
				curleft += obj.offsetLeft - obj.scrollLeft;
				curtop += obj.offsetTop - obj.scrollTop;
			} while (obj = obj.offsetParent);
			return [curleft,curtop];
		}
	}

}


/** create the tooltip once */
Tooltip.create();
