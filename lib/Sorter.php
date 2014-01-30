<?php
	
	abstract class Sorter {
	
		/** sort the given array (by reference) using the provided comparator */
		public static function sort(array &$arr, Comparator $comp) {
			
			usort($arr, array($comp, 'compareTo'));
			
			/*
			for ($n = count($arr); $n > 1; --$n) {
				for ($i = 0; $i < ($n-1); ++$i) {
					
					// compare and check if swapping needed
					if ($comp->compareTo($arr[$i], $arr[$i+1]) > 0) {
						$old = $arr[$i];
						$arr[$i] = $arr[$i+1];
						$arr[$i+1] = $old;
					}
					
				}
			}
			*/
			
		
		}
	
	}

?>