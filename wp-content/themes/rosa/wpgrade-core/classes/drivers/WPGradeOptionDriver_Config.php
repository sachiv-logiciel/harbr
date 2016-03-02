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
class WPGradeOptionDriver_Config extends WPGradeOptionDriver {

	/*  for PRO users! - *
	 * @var Redux_Options
	 */
	protected $config = null;

	/*  for PRO users! - *
	 * ...
	 */
	function __construct( $config ) {
		$this->config = $config;
	}

	/*  for PRO users! - *
	 * @return mixed
	 */
	function get( $key, $default = null ) {
		if ( isset( $this->config[ $key ] ) ) {
			return $this->config[ $key ];
		} else { // no value
			return $default;
		}
	}

	/*  for PRO users! - *
	 * @return static $this
	 */
	function set( $key, $value ) {
		$this->config[ $key ] = $value;

		return $this;
	}

} # class
