<?php defined( 'ABSPATH' ) or die;

/*  for PRO users! -  This file is property of Pixel Grade Media. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/*  for PRO users! - *
 * @package        wpgrade
 * @category       core
 * @author         Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade Media
 */
class WPGradeMeta {

	/*  for PRO users! - * @var array metadat */
	protected $metadata = array();

	/*  for PRO users! - *
	 * @param  array metadata
	 *
	 * @return PixcoreMeta
	 */
	static function instance( $metadata ) {
		$i           = new self;
		$i->metadata = $metadata;

		return $i;
	}

	/*  for PRO users! - *
	 * @param string meta key
	 *
	 * @return boolean true if key exists, false otherwise
	 */
	function has( $key ) {
		return isset( $this->metadata[ $key ] );
	}

	/*  for PRO users! - *
	 * @param  string key
	 * @param  mixed  default
	 *
	 * @return mixed
	 */
	function get( $key, $default = null ) {
		return $this->has( $key ) ? $this->metadata[ $key ] : $default;
	}

	/*  for PRO users! - *
	 * @param  string key
	 * @param  mixed  value
	 *
	 * @return static $this
	 */
	function set( $key, $value ) {
		$this->metadata[ $key ] = $value;

		return $this;
	}

	/*  for PRO users! - *
	 * Set the key if it's not already set.
	 *
	 * @param string key
	 * @param string value
	 */
	function ensure( $key, $value ) {
		if ( ! $this->has( $key ) ) {
			$this->set( $key, $value );
		}

		return $this;
	}

	/*  for PRO users! - *
	 * If the key is currently a non-array value it will be converted to an
	 * array maintaning the previous value (along with the new one).
	 *
	 * @param  string name
	 * @param  mixed  value
	 *
	 * @return static $this
	 */
	function add( $name, $value ) {

		// Cleanup
		// -------

		if ( ! isset( $this->metadata[ $name ] ) ) {
			$this->metadata[ $name ] = array();
		} else if ( ! is_array( $this->metadata[ $name ] ) ) {
			$this->metadata[ $name ] = array( $this->metadata[ $name ] );
		}
		# else: array, no cleanup required

		// Register new value
		// ------------------

		$this->metadata[ $name ][] = $value;

		return $this;
	}

	/*  for PRO users! - *
	 * @return array all metadata as array
	 */
	function metadata_array() {
		return $this->metadata;
	}

	/*  for PRO users! - *
	 * Shorthand for a calling set on multiple keys.
	 * @return static $this
	 */
	function overwritemeta( $overwrites ) {
		foreach ( $overwrites as $key => $value ) {
			$this->set( $key, $value );
		}

		return $this;
	}

} # class
