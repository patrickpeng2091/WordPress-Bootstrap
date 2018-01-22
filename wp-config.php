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
define('DB_NAME', 'wp3');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'ZF& aedz{zqKYUUSo50Ax z|&F0OX =6w1j;dcx$IH*&I1;{<jHQR <m^oL`|^Xd');
define('SECURE_AUTH_KEY',  'tciaSGTU:Ff&70YBi,gLz2^nTQF.qS^[C;Fi|xtv`S7E[_{Psmq5czbne@40>OZg');
define('LOGGED_IN_KEY',    'M_=Y&`]h`P`)GL>R`]-N F}I;$a?<t3`Z6fLH.tN{D7F-)&daN:>mxK&zzeu5yAC');
define('NONCE_KEY',        'r yyK<>N^gJ[Q(a$p4oRNRd`n*JpK]I`!3sEfp0tm^oXg12fh^.r&0ftfFHYHxq4');
define('AUTH_SALT',        '7#%4OZFgM`MKrJO}mt1E.~&3MKf.)p^O!aIKd:j: C41KAbs?MJk<]!RV#/x3c/W');
define('SECURE_AUTH_SALT', 'OrUaq sy+s$v@/eNf29z>,fckHW*<[zu1oemtKG^y:=#$*9sZ_iC{8M?`?$<.n1e');
define('LOGGED_IN_SALT',   '3z,iD|O(uN(fyO$OxgpA(=N>q#i8NDE>fQXx.U>KhPO2`7TveGKK/ebSa!ID(E|`');
define('NONCE_SALT',       'Vp%x^kkU$~U3b,;h%RrJ~Om$WreLWT~`bzM2lD67kq<,.ZMPENK$37/`T0G}M<t0');

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
