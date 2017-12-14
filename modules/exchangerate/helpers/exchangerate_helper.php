<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Rename the file to exchangerate_helper.php
 * and Define Module Helper Function (if any)
 */


if ( ! function_exists('round_up'))
{
	function round_up ($value, $places=0) {
		if ($places < 0) { $places = 0; }
		$mult = pow(10, $places);
		return ceil($value * $mult) / $mult;
	}
}

/* End of file exchangerate_helper.php */
/* Location: ./modules/exchangerate/helpers/exchangerate_helper.php */

