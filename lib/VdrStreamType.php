<?php

	class VdrStreamType {
				
		/* static access */
		private static $arr = null;
	
		/** get stream type from array */
		public static function get($major, $minor) {
			return @VdrStreamType::$arr[hexdec($major)][hexdec($minor)];
		}
		
		/** create stream array (once) */
		public static function init() {
			
			$arr = array();
			$arr[1] = array();
			
			$arr[1][0x01] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_4_3, CODEC::HZ_25);
			$arr[1][0x02] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x03] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x04] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x05] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_4_3, CODEC::HZ_30);
			$arr[1][0x06] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[1][0x07] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[1][0x08] = new VdrStreamVideo(Codec::MPEG2_SD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			
			$arr[1][0x09] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_4_3, CODEC::HZ_25);
			$arr[1][0x0A] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x0B] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x0C] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[1][0x0D] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_4_3, CODEC::HZ_30);
			$arr[1][0x0E] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[1][0x0F] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[1][0x10] = new VdrStreamVideo(Codec::MPEG2_HD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			
			$arr[2][0x01] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_MONO);
			$arr[2][0x02] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_MONO);
			$arr[2][0x03] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_STEREO);
			$arr[2][0x04] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_MULTILINGUAL);
			$arr[2][0x05] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_SURROUND);

			$arr[2][0x40] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_BLIND);
			$arr[2][0x41] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_DEAF);
			$arr[2][0x48] = new VdrStreamAudio(Codec::MPEG1_LAYER2, Codec::CHANNELS_BROADCASTER);
			
			// AC3
			for ($i = 0; $i <= 0xFF; ++$i) {
				$arr[4][$i] = new VdrStreamAudio(Codec::AC3, Codec::CHANNELS_STEREO);
			}
			
			// H.264
			$arr[5][0x01] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_4_3, CODEC::HZ_25);
			
			$arr[5][0x03] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[5][0x04] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[5][0x05] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_4_3, CODEC::HZ_30);
			
			$arr[5][0x07] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[5][0x08] = new VdrStreamVideo(Codec::H264_SD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			
			$arr[5][0x0B] = new VdrStreamVideo(Codec::H264_HD, CODEC::ASPECT_16_9, CODEC::HZ_25);
			$arr[5][0x0C] = new VdrStreamVideo(Codec::H264_HD, CODEC::ASPECT_16_9, CODEC::HZ_25);
		
			$arr[5][0x0F] = new VdrStreamVideo(Codec::H264_HD, CODEC::ASPECT_16_9, CODEC::HZ_30);
			$arr[5][0x10] = new VdrStreamVideo(Codec::H264_HD, CODEC::ASPECT_16_9, CODEC::HZ_30);
		
			VdrStreamType::$arr = $arr;
			
		}
			
			
			
/*	





0x03  0x01  EBU Teletext subtitles 
0x03  0x02  associated EBU Teletext 
0x03  0x03  VBI data 
0x03  0x04 to 0x0F  reserved for future use 
0x03  0x10  DVB subtitles (normal) with no monitor aspect ratio criticality 
0x03  0x11  DVB subtitles (normal) for display on 4:3 aspect ratio monitor 
0x03  0x12  DVB subtitles (normal) for display on 16:9 aspect ratio monitor 
0x03  0x13  DVB subtitles (normal) for display on 2.21:1 aspect ratio monitor 
0x03  0x14  DVB subtitles (normal) for display on a high definition monitor 
0x03  0x15 to 0x1F  reserved for future use 
0x03  0x20  DVB subtitles (for the hard of hearing) with no monitor aspect ratio criticality 
0x03  0x21  DVB subtitles (for the hard of hearing) for display on 4:3 aspect ratio monitor 
0x03  0x22  DVB subtitles (for the hard of hearing) for display on 16:9 aspect ratio monitor 
0x03  0x23  DVB subtitles (for the hard of hearing) for display on 2.21:1 aspect ratio monitor 
0x03  0x24  DVB subtitles (for the hard of hearing) for display on a high definition monitor 
0x03  0x25 to 0x2F  reserved for future use 
0x03  0x30  Open (in-vision) sign language interpretation for the deaf 
0x03  0x31  Closed sign language interpretation for the deaf 
0x03  0x32 to 0x3F  reserved for future use 
0x03  0x40  video up-sampled from standard definition source material 
0x03  0x41 to 0xAF  reserved for future use 
0x03  0xB0 to 0xFE  user defined 
0x03  0xFF  reserved for future use 

			
		}
	
	}
*/
	
	}
	
	/** static constructor */
	VdrStreamType::init();
	
?>