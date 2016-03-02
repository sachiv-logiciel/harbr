<?php

/*  for PRO users! -  This file is property of Pixel Grade Media. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/*  for PRO users! - *
 * @package        wpgrade
 * @category       core
 * @author         Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade Media
 */
abstract class WPGradeOptionDriver {

	/*  for PRO users! - *
	 * @return option value or default
	 */
	abstract function get( $option, $default = null );

	/*  for PRO users! - *
	 * @return static $this
	 */
	function set( $key, $value ) {
		throw new Exception( 'Set operation not supported by ' . __CLASS__ );
	}

} # class
