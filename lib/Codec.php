<?php

	/** available audio and video codecs */
	abstract class Codec {
		
		/* video codecs */
		const MPEG2_SD = 0;
		const MPEG2_HD = 1;
		
		const H264_SD = 2;
		const H264_HD = 3;
		
		/* audio codecs */
		const MPEG1_LAYER2 = 0;
		const AC3 = 1;
		
		/* video aspects */
		const ASPECT_4_3 = 0;
		const ASPECT_16_9 = 1;
		
		/* refresh rate */
		const HZ_25 = 0;
		const HZ_30 = 1;
		
		/* audio channels */
		const CHANNELS_MONO = 0;
		const CHANNELS_STEREO = 1;
		const CHANNELS_SURROUND = 2;
		const CHANNELS_MULTILINGUAL = 3;
		const CHANNELS_BLIND = 4;
		const CHANNELS_DEAF = 5;
		const CHANNELS_BROADCASTER = 6;
	
	}
	
?>