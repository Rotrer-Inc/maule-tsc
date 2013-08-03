<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'maule_tsc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '=mBwRm]c*%Yrp9bq-c3|088u(m79KRccC-Ro9}9po-RoKHHn4<<$byHps(aMxM(t');
define('SECURE_AUTH_KEY',  'kL4yt$t@I.ZlWZODUnLi$v[0%!:F_b@zHNalTL<fM~VMw_JGyPJIaSS66B/}L=kO');
define('LOGGED_IN_KEY',    'PTeIM Ap[S~]=_s`3@3]t|;IAe^#V/vNxtc-7?s<KWipb^`!jKavq]FB48u{XU=9');
define('NONCE_KEY',        'XFrT2@nn6yqz$Wy,;g9;o+N#4c|e#VAPmthA~79=`ss[6^+ynx?pW|M%$%^>a~%Y');
define('AUTH_SALT',        'c3g:(-V828!{1~O5_0^BT%g;Re/?4W>!}/2Kcj^M9bm36[Y,wJzBb.? -{~oTk|F');
define('SECURE_AUTH_SALT', 'MeIU`5|S&gAHLDKlIJPQ)K*S43Pj:D^P +FW=J+SYXGe.oedn/+.LL+|%H[i6%S^');
define('LOGGED_IN_SALT',   '+hxp=^n mQ|l_8K)NgyRDMC}aO9yGry>]D$--H5qiH+f$sA9M(5OXxueg^(2a0Yq');
define('NONCE_SALT',       '@=+dqm~|3DN&U.0TpuD+?S@.)L}Qs+9,W+f7}o[W3=4lE|2:~i}0to[2^cwI(XD>');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
