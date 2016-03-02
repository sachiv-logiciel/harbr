<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'harbr');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'x~/s1j}takLcrEar_NuNn,.Oz{zj9]&TTc+}R-9/O8y^@0[Sh&I+A@%>?QB*K^ g');
define('SECURE_AUTH_KEY',  '&}QOU0-eXdqzebk%DXpE2UfFbPh QYP$`,Z8i6)~Rr/)?u< /&p!6ES{`Be/iPq2');
define('LOGGED_IN_KEY',    ' bP/n&t>Koj?-I>Iv=>1N|G7+Ea$^$!$-R{9_v|nEV-?>.+$uZ|Eqo+&XBLl}Ct(');
define('NONCE_KEY',        '?gkzW{1@FXbe]&.vP^3B_xai?8c_DBtf>,<RyBNTqowtP)loNtoI@S#|MzY,jC(k');
define('AUTH_SALT',        'NgA8Gm6ABWZfm*t-u(Dxk-*~],[15g.:Q.3J#[d!CiOd)s7IB4B7$kU$)u#s<urv');
define('SECURE_AUTH_SALT', '}Se]~&:P9eA)_(gY-@qhWqwBS~r*V!xiH-vFB~z>7tc4*P-2+$}hs9 Ctj.8a13]');
define('LOGGED_IN_SALT',   'Y1HPcc&-?PD0s2g-8Nl$oZB|-#.`Pe1|>+FX~2R{dgr.G-mC>~DUHf08{x1EdxNe');
define('NONCE_SALT',       'ov;9dnjnP[=Yy[o`C~y!jM%Uqd(BlNcZ =I8u3heA#?Xq3NBMw-$-[,I6!Cz3USY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
