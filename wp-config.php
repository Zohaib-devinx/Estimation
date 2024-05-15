<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Estimation' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '] *f1i|F3LIC]H3])n=zjRR|M{G+chb(Up&r<OPm.AKgo^7![cIQ~mY{WW,f/ua3' );
define( 'SECURE_AUTH_KEY',  '|Qok3ZxP/WU;/B3Q;u5=Q-X1TVTWI/1`ztka>ezb&u~abq>^7YjyR6d4wu:`Q83)' );
define( 'LOGGED_IN_KEY',    'LH2&XtUcT|MvQ$au]65L<__cZzg.*l`<XP(VpiCZpn:)[8bb:!I*QmN$OI;R@T%]' );
define( 'NONCE_KEY',        '-1u+D9z>*RkJZ=]N?CKT>!b^PROGa]T?p-(R^^RiTsN.6^~%t[p=|srb[T)@[n=u' );
define( 'AUTH_SALT',        'Gq%C4oIO99fwu$OGHq,[|L:XLT%c :W#2E!Ggp[=nb@vvPvhUW>di?*:,w>lXp,V' );
define( 'SECURE_AUTH_SALT', 'W^`{[Yg5g6;V+M1M%Jk-yVVQtinM/n&P_tT*s4&5o]F%Hg@{<EjHRmVa#e6a,,E,' );
define( 'LOGGED_IN_SALT',   'CBR_|a>_4UXS4Sy}F%c!DW!it+6jy:QA1}s~Dv8rL4PoKT8<aM[&B XXEG}z,KDL' );
define( 'NONCE_SALT',       'kAq,;bN@ABB;yAU^=ih;>JK~Lay[?CXHfj61$s8WEDr}=#aw}j^z8BCt *`E!EiS' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
