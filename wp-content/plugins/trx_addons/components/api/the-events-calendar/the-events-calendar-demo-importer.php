<?php
/**
 * Plugin support: The Events Calendar (Importer support)
 *
 * @package ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Check plugin in the required plugins
if ( ! function_exists( 'trx_addons_tribe_events_importer_required_plugins' ) ) {
	add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_tribe_events_importer_required_plugins', 10, 2 );
	function trx_addons_tribe_events_importer_required_plugins( $not_installed = '', $list = '' ) {
		if ( strpos( $list, 'the-events-calendar' ) !== false && ! trx_addons_exists_tribe_events() ) {
			$not_installed .= '<br>' . esc_html__( 'Tribe Events Calendar', 'trx_addons' );
		}
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( ! function_exists( 'trx_addons_tribe_events_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'trx_addons_tribe_events_importer_set_options' );
	function trx_addons_tribe_events_importer_set_options( $options = array() ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $options['required_plugins'] ) ) {
			$options['additional_options'][] = 'tribe_events_calendar_options';
		}
		if ( is_array( $options['files'] ) && count( $options['files'] ) > 0 ) {
			foreach ( $options['files'] as $k => $v ) {
				$options['files'][$k]['file_with_the-events-calendar'] = str_replace( 'name.ext', 'the-events-calendar.txt', $v['file_with_'] );
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( ! function_exists( 'trx_addons_tribe_events_importer_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'trx_addons_tribe_events_importer_check_options', 10, 4 );
	function trx_addons_tribe_events_importer_check_options( $allow, $k, $v, $options ) {
		if ( $allow && $k == 'tribe_events_calendar_options' ) {
			$allow = trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $options['required_plugins'] );
		}
		return $allow;
	}
}

// Check if the row will be imported
if ( ! function_exists( 'trx_addons_tribe_events_importer_check_row' ) ) {
	add_filter( 'trx_addons_filter_importer_import_row', 'trx_addons_tribe_events_importer_check_row', 9, 4 );
	function trx_addons_tribe_events_importer_check_row( $flag, $table, $row, $list ) {
		if ( $flag || strpos( $list, 'the-events-calendar' ) === false ) {
			return $flag;
		}
		if ( trx_addons_exists_tribe_events() ) {
			if ( $table == 'posts' ) {
				$flag = in_array( $row['post_type'], array( Tribe__Events__Main::POSTTYPE, Tribe__Events__Main::VENUE_POST_TYPE, Tribe__Events__Main::ORGANIZER_POST_TYPE ) );
			}
		}
		return $flag;
	}
}

// Add checkbox to the one-click importer
if ( ! function_exists( 'trx_addons_tribe_events_importer_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'trx_addons_tribe_events_importer_show_params', 10, 1 );
	function trx_addons_tribe_events_importer_show_params( $importer ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $importer->options['required_plugins'] ) ) {
			$importer->show_importer_params( array(
				'slug' => 'the-events-calendar',
				'title' => esc_html__('Import Tribe Events Calendar', 'trx_addons'),
				'part' => 0
			) );
		}
	}
}

// Import posts
if ( ! function_exists( 'trx_addons_tribe_events_importer_import' ) ) {
	add_action( 'trx_addons_action_importer_import',	'trx_addons_tribe_events_importer_import', 10, 2 );
	function trx_addons_tribe_events_importer_import( $importer, $action ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $importer->options['required_plugins'] ) ) {
			if ( $action == 'import_the-events-calendar' ) {
				$importer->response['start_from_id'] = 0;
				// A third parameter 'false' to avoid an error message when an old demo data is imported
				// (without additional tables 'tec_events' and 'tec_occurrences')
				$importer->import_dump( 'the-events-calendar', esc_html__( 'The Events Calendar data', 'trx_addons' ), false );
			}
		}
	}
}

// Display import progress
if ( ! function_exists( 'trx_addons_tribe_events_importer_import_fields' ) ) {
	add_action( 'trx_addons_action_importer_import_fields',	'trx_addons_tribe_events_importer_import_fields', 10, 1 );
	function trx_addons_tribe_events_importer_import_fields( $importer ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $importer->options['required_plugins'] ) ) {
			$importer->show_importer_fields( array(
				'slug'=>'the-events-calendar', 
				'title' => esc_html__('The Events Calendar data', 'trx_addons')
			) );
		}
	}
}

// Export posts
if ( ! function_exists( 'trx_addons_tribe_events_importer_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'trx_addons_tribe_events_importer_export', 10, 1 );
	function trx_addons_tribe_events_importer_export( $importer ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $importer->options['required_plugins'] ) ) {
			trx_addons_fpc( $importer->export_file_dir( 'the-events-calendar.txt' ),
							serialize( apply_filters( 'trx_addons_filter_importer_export_tables', array(
											'tec_events'     => $importer->export_dump("tec_events"),
											'tec_occurrences' => $importer->export_dump("tec_occurrences"),
											),
											'tribe_events'
										)
							)
			);
		}
	}
}

// Display exported data in the fields
if ( ! function_exists( 'trx_addons_tribe_events_importer_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'trx_addons_tribe_events_importer_export_fields', 10, 1 );
	function trx_addons_tribe_events_importer_export_fields( $importer ) {
		if ( trx_addons_exists_tribe_events() && in_array( 'the-events-calendar', $importer->options['required_plugins'] ) ) {
			$importer->show_exporter_fields( array(
				'slug'	=> 'the-events-calendar',
				'title' => esc_html__( 'The Events Calendar', 'trx_addons' )
			) );
		}
	}
}

// Fix for the version 6.0+ to enable posts are created in the previous version
if ( ! function_exists( 'trx_addons_tribe_events_importer_import_end' ) ) {
	add_action( 'trx_addons_action_importer_import_end', 'trx_addons_tribe_events_importer_import_end', 10, 1 );
	function trx_addons_tribe_events_importer_import_end( $importer = null ) {

		if ( trx_addons_exists_tribe_events() && class_exists( 'TEC\Events\Custom_Tables\V1\Repository\Events' ) ) {

			$tec = new TEC\Events\Custom_Tables\V1\Repository\Events();

			if ( is_object( $tec ) && method_exists( $tec, 'update' ) ) {

				// get_posts() is not work with old events,
				// because a plugin inject own conditions (its ignore an argument 'suppress_filters')
				// and a next call return an empty result
				/*
				$events = get_posts( array(
					'post_type' => Tribe__Events__Main::POSTTYPE,
					'posts_per_page' => -1
				) );
				*/

				// A direct query is used
				global $wpdb;
				$events = $wpdb->get_results( "SELECT ID FROM " . esc_sql( $wpdb->prefix . 'posts' ) . " WHERE post_type='" . esc_sql( Tribe__Events__Main::POSTTYPE ) . "'", OBJECT );

				if ( is_array( $events ) ) {
					foreach( $events as $event ) {
						$tec->update( $event->ID, array() );
					}
				}
			}
		}
	}
}
