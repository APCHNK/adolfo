<?php
/**
 * "Tour Dates" link in the header menu.
 *
 * The schedule block on the front page carries id="tour-dates". While that
 * block exists and the Schedule options hold at least one upcoming date, a
 * plain <a href="/#tour-dates"> item (RU: "Афиша") is added to the header
 * menu right before its last top-level item, so crawlers and visitors reach
 * the dates through a normal link. Without dates the item disappears.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** True when the Schedule options page has at least one date today or later. */
function satellite_has_upcoming_dates() {
	if ( ! function_exists( 'get_field' ) ) return false;
	$items = get_field( 'schedule_items', 'option' );
	if ( ! $items || ! is_array( $items ) ) return false;
	$today = strtotime( 'today' );
	foreach ( $items as $item ) {
		$parts = explode( '/', $item['date'] ?? '' );
		if ( count( $parts ) !== 3 ) continue;
		if ( strtotime( "{$parts[2]}-{$parts[1]}-{$parts[0]}" ) >= $today ) return true;
	}
	return false;
}

/** True when the front page (in the current language) contains a schedule block. */
function satellite_front_has_schedule() {
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) return false;
	if ( function_exists( 'pll_get_post' ) && ( $tr = pll_get_post( $front_id ) ) ) {
		$front_id = (int) $tr;
	}
	global $wpdb;
	return (bool) $wpdb->get_var( $wpdb->prepare(
		"SELECT 1 FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value LIKE %s AND ( meta_key LIKE %s OR meta_key = %s ) LIMIT 1",
		$front_id,
		'%"schedule"%',
		$wpdb->esc_like( 'flex-content_' ) . '%',
		'page_sections'
	) );
}

add_filter( 'wp_nav_menu_objects', function ( $items, $args ) {
	static $show = null;
	if ( null === $show ) {
		$show = satellite_has_upcoming_dates() && satellite_front_has_schedule();
	}
	if ( ! $show || empty( $items ) ) return $items;

	$location = $args->theme_location ?? '';
	if ( $location && ! in_array( $location, [ 'primary', 'header' ], true ) ) return $items;

	$is_ru = function_exists( 'pll_current_language' ) && pll_current_language() === 'ru';

	$link                   = new stdClass();
	$link->ID               = -1001;
	$link->db_id            = -1001;
	$link->menu_item_parent = 0;
	$link->object_id        = -1001;
	$link->object           = 'custom';
	$link->type             = 'custom';
	$link->title            = $is_ru ? 'Афиша' : 'Tour Dates';
	$home                   = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	$link->url              = trailingslashit( $home ) . '#tour-dates';
	$link->target           = '';
	$link->attr_title       = '';
	$link->description      = '';
	$link->xfn              = '';
	$link->classes          = [ 'menu-item', 'menu-item-tour-dates' ];
	$link->current          = false;
	$link->menu_order       = 0;

	// Insert before the last top-level item (Contact / Booking & Contact).
	$last_top = null;
	foreach ( $items as $i => $item ) {
		if ( (int) $item->menu_item_parent === 0 ) $last_top = $i;
	}
	$pos = null === $last_top ? count( $items ) : array_search( $last_top, array_keys( $items ), true );
	array_splice( $items, $pos, 0, [ $link ] );

	foreach ( $items as $n => $item ) $item->menu_order = $n + 1;
	return $items;
}, 20, 2 );
