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
class WPGradeOptionDriver_Redux extends WPGradeOptionDriver {

	/*  for PRO users! - *
	 * @var Redux_Options
	 */
	protected $redux = null;

	/*  for PRO users! - *
	 * ...
	 */
	function __construct( $redux_options_instance ) {
		$this->redux = $redux_options_instance;
	}

	/*  for PRO users! - *
	 * @return mixed
	 */
	function get( $key, $default = null ) {
		$value = $this->redux->get( $key, null );
		if ( $value !== null ) {
			return $value;
		} else { // null, assuming key is not set
			return $default;
		}
	}

	/*  for PRO users! - *
	 * @return static $this
	 */
	function set( $key, $value ) {
		$this->redux->set( $key, $value );

		return $this;
	}

	function get_args() {
		return $this->redux->args;
	}

	function get_sections() {
		return $this->redux->sections;
	}

	function _default_values() {
		return $this->redux->_default_values();
	}

} # class
