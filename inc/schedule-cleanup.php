<?php
/**
 * Past concerts leave the Schedule options list by themselves.
 *
 * Once a day (WP-Cron) and whenever the Schedule settings page is opened,
 * every Tour dates row whose date is before today is removed from
 * Theme Options → Schedule. The concert day itself stays until midnight.
 * Removed rows are kept in the `satellite_schedule_archive` option (raw ACF
 * values, newest last) so they can be restored or used for a past-shows list.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function satellite_schedule_prune() {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) return 0;
	$rows = get_field( 'field_schedule_items', 'option', false ); // raw: date as Ymd
	if ( ! $rows || ! is_array( $rows ) ) return 0;

	$today = (int) wp_date( 'Ymd' );
	$keep  = [];
	$gone  = [];
	foreach ( $rows as $row ) {
		$date = preg_replace( '/\D/', '', (string) ( $row['field_sched_date'] ?? '' ) );
		if ( strlen( $date ) === 8 && (int) $date < $today ) $gone[] = $row;
		else $keep[] = $row;
	}
	if ( ! $gone ) return 0;

	update_field( 'field_schedule_items', $keep, 'option' );
	$archive = get_option( 'satellite_schedule_archive', [] );
	update_option( 'satellite_schedule_archive', array_merge( is_array( $archive ) ? $archive : [], $gone ), false );
	return count( $gone );
}

add_action( 'satellite_schedule_prune', 'satellite_schedule_prune' );

add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'satellite_schedule_prune' ) ) {
		wp_schedule_event( strtotime( 'tomorrow 00:05' ), 'daily', 'satellite_schedule_prune' );
	}
} );

// opening Theme Options → Schedule shows an already cleaned list
add_action( 'admin_init', function () {
	if ( ( $_GET['page'] ?? '' ) === 'schedule-settings' && current_user_can( 'edit_posts' ) ) {
		satellite_schedule_prune();
	}
} );
