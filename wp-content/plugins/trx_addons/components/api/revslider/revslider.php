<?php
/**
 * Plugin support: Revolution Slider
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

define( 'REVSLIDER_GO_PRO_LINKS', array(
	// Dashboard
	'//account.sliderrevolution.com/portal' => 'https://themepunch.pxf.io/4ekEVG',
	// Go Premium
	'//account.sliderrevolution.com/portal/pricing' => 'https://themepunch.pxf.io/KeRz5z',
	// Prmium Features
	'sliderrevolution.com/premium-slider-revolution' => 'https://themepunch.pxf.io/9W1nyy',
	// Support
	'//support.sliderrevolution.com' => 'https://themepunch.pxf.io/P0LbGq',
	// Help center
	'sliderrevolution.com/help-center' => 'https://themepunch.pxf.io/doXGdy',
	// Geting Started
	'sliderrevolution.com/manual' => 'https://themepunch.pxf.io/ZdkK3q',
	// Get on board
	'sliderrevolution.com/get-on-board-the-slider-revolution-dashboard' => 'https://themepunch.pxf.io/QOqb1z',
	// Addons
	'sliderrevolution.com/expand-possibilities-with-addons' => 'https://themepunch.pxf.io/6baEN3',
	// Templates
	'sliderrevolution.com/examples' => 'https://themepunch.pxf.io/rnvXdB',
	// Pro level design
	'sliderrevolution.com/pro-level-design-with-slider-revolution' => 'https://themepunch.pxf.io/jWEmda',
	// Privacy Policy
	'sliderrevolution.com/plugin-privacy-policy' => 'https://themepunch.pxf.io/gbzGE0',
	// FAQ: Licence deactivated
	'sliderrevolution.com/faq/why-was-my-slider-revolution-license-deactivated' => 'https://themepunch.pxf.io/RyxbVy',
	// FAQ: Clear caches
	'sliderrevolution.com/faq/updating-make-sure-clear-caches' => 'https://themepunch.pxf.io/Yg5Nzq',
	// FAQ: Where to find purchase code
	'sliderrevolution.com/faq/where-to-find-purchase-code' => 'https://themepunch.pxf.io/x9xZdO',
	// Documentation: Changelog
	'sliderrevolution.com/documentation/changelog' => 'https://themepunch.pxf.io/EanyNn',
	// Documentation: System requirements
	'sliderrevolution.com/documentation/system-requirements/' => 'https://themepunch.pxf.io/LPv2kO',
	// Site
	'sliderrevolution.com' => 'https://themepunch.pxf.io/DVEORn',
) );

// Check if RevSlider installed and activated
// Attention! This function is used in many files and was moved to the api.php
/*
if ( !function_exists( 'trx_addons_exists_revslider' ) ) {
	function trx_addons_exists_revslider() {
		return function_exists('rev_slider_shortcode') || class_exists( 'RevSliderData' );
	}
}
*/

// Return RevSliders list, prepended inherit (if need)
if ( !function_exists( 'trx_addons_get_list_revsliders' ) ) {
	function trx_addons_get_list_revsliders($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			if (trx_addons_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
		}
		return $prepend_inherit ? array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) : $list;
	}
}


// Add RevSlider to the slider engines
if ( !function_exists( 'trx_addons_add_revslider_to_engines' ) ) {
	add_filter('trx_addons_filter_get_list_sc_slider_engines', 'trx_addons_add_revslider_to_engines');
	function trx_addons_add_revslider_to_engines($list) {
		if (trx_addons_exists_revslider()) {
			$list["revo"] = esc_html__("Layer slider (Revolution)", 'trx_addons');
		}
		return $list;
	}
}


// Allow loading RevSlider scripts and styles
// if it present in the content of the current page
if (!function_exists('trx_addons_check_revslider_in_content')) {
	add_filter( 'revslider_include_libraries', 'trx_addons_check_revslider_in_content', 20 );
	function trx_addons_check_revslider_in_content( $load, $post_id = -1 ) {
		if ( ! $load ) {
			$load = trx_addons_is_preview()					// Load if current page is builder preview
					|| trx_addons_sc_check_in_content(		// or if a shortcode is present in the current page
							array(
								'sc' => 'revslider',
								'entries' => array(
									array( 'type' => 'sc',  'sc' => 'rev_slider' ),
									array( 'type' => 'sc',  'sc' => 'trx_widget_slider',                'param' => 'engine="revo"' ),
									array( 'type' => 'gb',  'sc' => 'wp:trx-addons/slider',             'param' => '"engine":"revo"' ),
									array( 'type' => 'elm', 'sc' => '"widgetType":"trx_widget_slider',  'param' => '"engine":"revo"' ),
									array( 'type' => 'elm', 'sc' => '"widgetType":"wp-widget-rev-slider' ),
									array( 'type' => 'elm', 'sc' => '"shortcode":"[rev_slider' ),
									array( 'type' => 'elm', 'sc' => '"shortcode":"[trx_widget_slider',  'param' => 'engine="revo"' ),
								)
							),
							$post_id
						);
		}
		return $load;
	}
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_revslider_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'trx_addons_revslider_load_scripts_front', TRX_ADDONS_ENQUEUE_SCRIPTS_PRIORITY );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'trx_addons_revslider_load_scripts_front', 10, 1 );
	function trx_addons_revslider_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! trx_addons_exists_revslider() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'revslider',
									'entries' => array(
										array( 'type' => 'sc',  'sc' => 'rev_slider' ),
										array( 'type' => 'sc',  'sc' => 'trx_widget_slider',                'param' => 'engine="revo"' ),
										array( 'type' => 'gb',  'sc' => 'wp:trx-addons/slider',             'param' => '"engine":"revo"' ),
										array( 'type' => 'elm', 'sc' => '"widgetType":"trx_widget_slider',  'param' => '"engine":"revo"' ),
										array( 'type' => 'elm', 'sc' => '"widgetType":"wp-widget-rev-slider' ),
										array( 'type' => 'elm', 'sc' => '"shortcode":"[rev_slider' ),
										array( 'type' => 'elm', 'sc' => '"shortcode":"[trx_widget_slider',  'param' => 'engine="revo"' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'revslider' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'revslider', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'trx_addons_revslider_check_in_html_output' ) ) {
//	add_filter( 'trx_addons_filter_get_menu_cache_html', 'trx_addons_revslider_check_in_html_output', 10, 1 );
//	add_action( 'trx_addons_action_show_layout_from_cache', 'trx_addons_revslider_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_check_page_content', 'trx_addons_revslider_check_in_html_output', 10, 1 );
	function trx_addons_revslider_check_in_html_output( $content = '' ) {
		if ( trx_addons_exists_revslider()
			&& ! trx_addons_need_frontend_scripts( 'revslider' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'id=[\'"][^\'"]*rev_slider_',
							'<rs-module ',
							'<rs-slide '
							),
							'revslider'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					trx_addons_revslider_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'trx_addons_revslider_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'trx_addons_revslider_filter_head_output', 10, 1 );
	function trx_addons_revslider_filter_head_output( $content = '' ) {
		if ( trx_addons_exists_revslider()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'revslider' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'revslider' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/revslider/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'trx_addons_revslider_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'trx_addons_revslider_filter_body_output', 10, 1 );
	function trx_addons_revslider_filter_body_output( $content = '' ) {
		if ( trx_addons_exists_revslider()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'revslider' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'revslider' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/revslider/[^>]*>#', '', $content );
			// Essential Grid may use some scripts from EevSlider (tools.js)
			if ( ! trx_addons_need_frontend_scripts( 'essential_grid' ) ) {
				$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/revslider/[^>]*>[\\s\\S]*</script>#U', '', $content );
			}
		}
		return $content;
	}
}

// Disable a welcome screen after a plugin activated
if ( ! function_exists( 'trx_addons_revslider_disable_welcome_screen' ) ) {
	add_action( 'admin_init', 'trx_addons_revslider_disable_welcome_screen', 0 );
	function trx_addons_revslider_disable_welcome_screen() {
		if ( trx_addons_exists_revslider() && class_exists( 'RevSliderAdmin' )
			&& (
				trx_addons_check_url( 'admin.php' ) && trx_addons_get_value_gp( 'page' ) == 'trx_addons_theme_panel'
				||
				(int) trx_addons_get_value_gp( 'admin-multi' ) == 1
				||
				trx_addons_get_value_gp( 'page' ) == 'tgmpa-install-plugins'
			) 
		) {
			//remove_action( 'admin_init', array( 'RevSliderAdmin', 'open_welcome_page' ) );
			trx_addons_remove_filter( 'admin_init', 'open_welcome_page' );
		}
	}
}


// Change "Go Premium" links
//----------------------------------------------
if ( ! function_exists( 'trx_addons_revslider_change_gopro_plugins' ) && defined('RS_PLUGIN_SLUG_PATH') ) {
	add_filter( 'plugin_action_links_' . RS_PLUGIN_SLUG_PATH, 'trx_addons_revslider_change_gopro_plugins', 11 );
	function trx_addons_revslider_change_gopro_plugins( $links ) {
		if ( ! empty( $links['go_premium'] ) && preg_match( '/href="([^"]*)"/', $links['go_premium'], $matches ) && ! empty( $matches[1] ) ) {
			$links['go_premium'] = str_replace( $matches[1], trx_addons_get_url_by_mask( $matches[1], REVSLIDER_GO_PRO_LINKS ), $links['go_premium'] );
		}
		return $links;
	}
}
if ( ! function_exists( 'trx_addons_revslider_change_gopro_menu' ) ) {
	add_filter( 'wp_redirect', 'trx_addons_revslider_change_gopro_menu', 11, 2 );
	function trx_addons_revslider_change_gopro_menu( $link, $status = 0 ) {
		return trx_addons_get_url_by_mask( $link, REVSLIDER_GO_PRO_LINKS );
	}
}
if ( ! function_exists( 'trx_addons_revslider_change_gopro_url_in_js' ) ) {
	add_filter( 'trx_addons_filter_localize_script', 'trx_addons_revslider_change_gopro_url_in_js' );
	add_filter( 'trx_addons_filter_localize_script_admin', 'trx_addons_revslider_change_gopro_url_in_js' );
	function trx_addons_revslider_change_gopro_url_in_js( $vars ) {
		if ( ! isset( $vars['add_to_links_url'] ) ) {
			$vars['add_to_links_url'] = array();
		}
		if ( is_array( REVSLIDER_GO_PRO_LINKS ) ) {
			foreach( REVSLIDER_GO_PRO_LINKS as $mask => $url ) {
				$vars['add_to_links_url'][] = array(
					'page' => array( 'admin.php?page=revslider', 'plugins.php' ),
					'mask' => $mask,
					'link' => $url
				);
			}
		}
		return $vars;
	}
}


// Add shortcodes
//----------------------------------------------------------------------------

// Add shortcodes to Gutenberg
if ( trx_addons_exists_revslider() && trx_addons_exists_gutenberg() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'revslider/revslider-sc-gutenberg.php';
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'revslider/revslider-demo-importer.php';
}

// OCDI support
if ( is_admin() && trx_addons_exists_revslider() && function_exists( 'trx_addons_exists_ocdi' ) && trx_addons_exists_ocdi() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'revslider/revslider-demo-ocdi.php';
}
