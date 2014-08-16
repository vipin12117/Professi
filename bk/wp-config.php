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
define('DB_NAME', 'imnxlsoj_wp860');

/** MySQL database username */
define('DB_USER', 'imnxlsoj_wp860');

/** MySQL database password */
define('DB_PASSWORD', 'E0S6o9--3P');

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
define('AUTH_KEY',         'qb9vlhimvzrkmp9n8pnmlrr8ezpgpsfzg5mfvxtjrppgipfv5dkicigkbnlj7eqk');
define('SECURE_AUTH_KEY',  'z9oiea8kfdjuy8eojgwsosobvqxaw9gwliboedr8qvjgbzfnwkpjl9qkbsicqc3c');
define('LOGGED_IN_KEY',    'u3e6fhftyilsfihtlf6oshluhmul45x9e3yad4f2gbkobwou2wqoympg68opb5li');
define('NONCE_KEY',        'zaahbxxzsxed2g8rhoidipob10rmvm7kc85fwsuhujnpzx9r59dhfowiqqzipy7c');
define('AUTH_SALT',        'aeyshpxyvrgssu0w4tpxl3ewtq9b2l8jyzsuvvycqtcnid2wtz9dqo083jmxj8bx');
define('SECURE_AUTH_SALT', '8svk03h6d06xxrmordzx9voz3vcvukwbrbbiavalm6ggyrwq37v1mr7woetfmyii');
define('LOGGED_IN_SALT',   'papyjwbq2hgsihwlihgjbkfzzwkr2bon3hqvza7rh9ui9eiriwnpysgrkrsggc0i');
define('NONCE_SALT',       'lrm0tibcl4uxyr6ykyskzxv4agnhdmhqlswuiuramippyj3vnveshqh5mqql9ew6');

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

define('WP_SITEURL','http://techgroupjaipur.com/wordpress');
define('WP_HOME','http://techgroupjaipur.com/wordpress');
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
