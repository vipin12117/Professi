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
define('DB_NAME', 'professi');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '+6vz+Tvt/9wu([(s8*?ysu|J~VOpaK2v|Ea(n~VOrIt1HCSYQP|avU=4+;IQlr`$');
define('SECURE_AUTH_KEY',  'KE$G|gbw++&uiUKWHTi<^8DR-lnv-#v?WHF^0o+s8S[ILM?JihZtnUF(YSm.8D9(');
define('LOGGED_IN_KEY',    ';S4%*w4 <fOhBb-pFn6C?d88,lTTfY3[]KP*Vv:+9.8?I.+$QCi[VAcW)?sOOMEU');
define('NONCE_KEY',        'vcykA3yZocfJh<f$&p H-[|&#%p%ONSWAI|U.j#^)w:RH7zr@>agJ_ |1jat=(C?');
define('AUTH_SALT',        '<}P=9.g8It+WQ(:GstE+4mW*hqsh+q,GvXp?)Qs^mchgKD?~eE%U2tX3fC;gyXS;');
define('SECURE_AUTH_SALT', '<RQ]M=HVcijqfw@-G~9pDfRPkBbR_qaw|$%^E|5epi9`xTdTgbhal*Pww@QV9wWD');
define('LOGGED_IN_SALT',   'hdwj<;S@>-AlP=t3t2 86!--2B7|[AV([6F` Ak56~1m0o&5|o_+S4Cq>+ct$+!+');
define('NONCE_SALT',       '}^v6}+z-4n|o!#RE3Cr+Cr{1z#L< H`{9X5ZRV+uxA$$(-l i&Xmcm!kd[ztn:wN');

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
