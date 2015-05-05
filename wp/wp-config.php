<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'turbulence');

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
define('AUTH_KEY',         'J-/x0-1O$AbRTLMrZQD{5-2hm_HwAGb[G+O4>(B4SzOs7:DFjgx4df;<?GeXJ&+O');
define('SECURE_AUTH_KEY',  '!+h<|/VtmjJLnLtCv`,KcSW+Qd%%q1E2KNKte -+[EIpJz*80h!$!v-;.Q;@@a@@');
define('LOGGED_IN_KEY',    'uf9yZ!L]OJ??Ej^@k+V3 n91#lLT|W:8rb>Sd+h<iQ-5]d<I{XK$<X&LDWj7!m6Z');
define('NONCE_KEY',        'X[YeKx%/Y~3VvsboAUJxk`-=E;#d~J?jaH+Zo&Ii-`.NIpN3]`VkT3]<vGO0>HB.');
define('AUTH_SALT',        'iJkdV~1|:sEa?UH2pHNGM]YG+JC>T|N!^FXlLVvbIDP-kyiBQ[*Nf0h{Kf%MSub^');
define('SECURE_AUTH_SALT', 'FEwD<4HT>;*0}%JX: <UblH~0@hxuMW)$lTIsQ//c*u+t}+Lzi@G8ka`j&[jIa+-');
define('LOGGED_IN_SALT',   '+eT!w*B]7P+CZf0Ms|!p@]>%!{~i__HL)S{f^rbN+Y,|MKtyYmPxAJn]2qb>${rs');
define('NONCE_SALT',       '9~%+~^ow>gh;+%1fUzZAK|?}Y8%#!:WKEkL%g1p8Mj1<KkX=bql_N1|4f-zF07m.');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
