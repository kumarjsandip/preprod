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
define('DB_NAME', 'nextproject');

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
define('AUTH_KEY',         'lzhdmsurkdwafshbzytlshnalefipclfj2otadymzxoeyc4bzso3dwvnokxanxl3');
define('SECURE_AUTH_KEY',  'iccyydgfujiozxb5uskwxkjjruvhx8nhvrglyhxrsa4vx0aioiggrakbttfmidya');
define('LOGGED_IN_KEY',    '61hmxxd4w8harkwxuwooy06133icjpib3uwaznpvjsgr2lqwd4jwrayxybco1o8q');
define('NONCE_KEY',        'awcjoyfmhtte9zaqzfq0ddnmolg5kjeagz3gbs9j4xuhsb4w1patiaz2mltmcqs7');
define('AUTH_SALT',        'y0dqyiaumd6brtbdz2xqteyrk7kx5gyfeo63vr5sikifibj3qicmwszn2x7vyyzm');
define('SECURE_AUTH_SALT', 'sj4yniitvst1u5loriwsfywm7cypqfoelbm5nece2tgji3vqamrbjsacyzmqjxcw');
define('LOGGED_IN_SALT',   '1v1xsoqpxoin7acc7z9yu7ooll8va2v9f7opfcjzgtkwzips8kgqaba0cl352nqh');
define('NONCE_SALT',       'mq3yl5umn7nghy4gu0bbpaie6djqjuxwenarngi3ghzc1juhwk3cw4xvvkhbvzgv');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpum_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );

#@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

