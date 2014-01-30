<?php

	class VdrFemonResult {
	
		/* attributes */
		private $card;
		private $sigStrength;
		private $sigNoiseRatio;
		
		/** create */
		public function __construct($card, $sigStrength, $sigNoiseRatio) {
			$this->card = $card;
			$this->sigStrength = $sigStrength;
			$this->sigNoiseRatio = $sigNoiseRatio;
		}
		
		/** get the card name */
		public function getCard() {return $this->card;}
		
		/** get the signal strength */
		public function getSignalStrength() {return $this->sigStrength;}
		
		/** get the signal/noise ratio */
		public function getSignalNoiseRatio() {return $this->sigNoiseRatio;}
	
	}
	
?>