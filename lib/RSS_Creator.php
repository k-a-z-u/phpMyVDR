<?php


class RSS_Creator {
	
	/* attributes */
	protected $items;
	private $namespaces;
	private $channelParams;
	private $myURL;


	/** create */
	public function __construct($title = 'RSS', $link = '#', $description = 'RSS Feed') {
		$this->myUrl = $link;
		$this->namespaces = array();
		$this->channelParams = array();
		$this->items = array();
		$this->channelParams['title'] = $title;
		$this->channelParams['link'] = $link;
		$this->channelParams['description'] = $description;
	}
	
	/** add a namespace-definition to the header */
	public function addNamespace($short, $url) {
		$this->namespaces[$short] = $url;
	}
	
	/** add Element to the ChannelTags */
	public function addChannelParameter($key, $value) {
		$this->channelParams[$key] = $value;
	}
			
	/* add SubItem to the ChannelElements */
	public function addItem($title = 'RSS Item', $link = '#', $description = 'RSS Item') {
		$item = new Item($title, $link, $description);
		$this->items[] = $item;
		return $item;
	}
	
	/** get the header for output */
	private function getHeader() {
		$out  = "<?xml version='1.0' encoding='UTF-8' ?>\n<rss version='2.0' ";
		foreach ($this->namespaces as $key => $value) {
			$out .= "xmlns:{$key}='{$value}' ";
		}
		$out .= ">\n\n\t<channel>\n";
		foreach ($this->channelParams as $key => $value) {
			$out .= "\t\t<{$key}>{$value}</{$key}>\n";
		}
		return $out;
	}
	
	/** get the footer for output */
	private function getFooter() {
		return "\t</channel>\n</rss>\n";
	}
	
	//Echo all
	public function EchoRSS() {
		
		ob_start();
	
		echo $this->getHeader();
		
		foreach ($this->items as $item) {
			echo $item->ToString() . "\n";
		}
		
		echo $this->getFooter();
		
		ob_end_flush();
		
	}
	
}


class Item {

	/* attributes */
	protected $ItemString = 'test';
	
	public function __construct($title, $link, $description) {
		$this->ItemString  = "\t\t\t<title><![CDATA[{$title}]]></title>\n";
		$this->ItemString .= "\t\t\t<link>{$link}</link>\n";
		$this->ItemString .= "\t\t\t<description><![CDATA[{$description}]]></description>\n";
	}
	
	public function addElement($name, $value) {
		$this->ItemString .= "\t\t\t<{$name}>{$value}</{$name}>\n";
	}
	
	public function addLine($line) {
		$this->ItemString = $this->ItemString.$line.$this::newline;
	}

	public function ToString() {
		return "\t\t<item>\n{$this->ItemString}\t\t</item>";
	}
		
}

?>
