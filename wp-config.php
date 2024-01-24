<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'student_wp_live' );

/** Database username */
define( 'DB_USER', 'student_wp_live' );

/** Database password */
define( 'DB_PASSWORD', '@F(p9S14cF' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lqavnpyjhutdcxoybkkgpnjskz3nhtam9jxkfu7l7x6gom7aimykc4srxndewfg0' );
define( 'SECURE_AUTH_KEY',  'jy019wtnzddpyoztup56spjhprwbd3bzillwmtkayihgojyuysjcfshbvi0ak3xa' );
define( 'LOGGED_IN_KEY',    'dwnujzalm6nwc93qaizqh3dzwzohlddb3gtm6wbtusejusnkzijgqw6ig9b1ripv' );
define( 'NONCE_KEY',        'yhpdbnzjqfbczbh4sjrpl3qnh4tjsn50mbdewepmnivdhnpshptaadkrtaesmk5n' );
define( 'AUTH_SALT',        'rcxowr4l4xljkpyvlrqt77kqen8njitliwjco3k2yedttnnauuvjny4vfpjaywuy' );
define( 'SECURE_AUTH_SALT', 'kyfgrswnicfwrsazbrxrp7pyxdxrxmldqhxlrjv3q4drm53eqzw1wc4hahckxfrj' );
define( 'LOGGED_IN_SALT',   'a0izhdiqznr4drmhrc2drgeuzrlgkou1d4kuix9dkkchq8fdktpafteraiwmdrb5' );
define( 'NONCE_SALT',       'vwvhqtk3oq5k8btq9arvsj5joxtk1da2d83dfpstbd3myxqtoeildnnyjzyxsvts' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpop_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
