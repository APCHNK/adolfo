<?php
/**
 * One entity graph on top of Yoast's schema output.
 *
 * - The artist is a single Person with a stable @id (/#adolfo-sebastiani)
 *   that replaces Yoast's user-based "Person, Organization" node; every
 *   reference to the old node (WebSite publisher, author…) is rewired.
 * - The show is a PerformingGroup (/#show) with the artist as member.
 * - The artist page (and its translations) is a ProfilePage whose
 *   mainEntity is the Person; the front page is "about" the show.
 * - The Tour Dates page template lists upcoming dates from the Schedule
 *   options as MusicEvent nodes (only data that exists: date, place,
 *   ticket link; no invented venues, organizers or prices).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function adolfo_schema_base() {
	return untrailingslashit( get_option( 'home' ) );
}

function adolfo_schema_person_id() {
	return adolfo_schema_base() . '/#adolfo-sebastiani';
}

function adolfo_schema_show_id() {
	return adolfo_schema_base() . '/#show';
}

/** IDs of the artist page in every language (EN slug adolfo-sebastiani). */
function adolfo_artist_page_ids() {
	static $ids = null;
	if ( null !== $ids ) return $ids;
	$ids  = [];
	$page = get_page_by_path( 'adolfo-sebastiani' );
	if ( ! $page ) return $ids;
	$ids = function_exists( 'pll_get_post_translations' )
		? array_map( 'intval', (array) pll_get_post_translations( $page->ID ) )
		: [];
	$ids[] = (int) $page->ID;
	return $ids = array_values( array_unique( $ids ) );
}

/** Artist page URL in the current language. */
function adolfo_artist_page_url() {
	$page = get_page_by_path( 'adolfo-sebastiani' );
	if ( ! $page ) return adolfo_schema_base() . '/';
	$id = function_exists( 'pll_get_post' ) ? ( pll_get_post( $page->ID ) ?: $page->ID ) : $page->ID;
	return get_permalink( $id );
}

/** ISO 3166-1 alpha-2 code for a country named in a schedule item, '' when unknown. */
function adolfo_country_code( $text ) {
	static $map = [
		'austria' => 'AT', 'italy' => 'IT', 'estonia' => 'EE', 'kazakhstan' => 'KZ', 'germany' => 'DE',
		'switzerland' => 'CH', 'france' => 'FR', 'spain' => 'ES', 'portugal' => 'PT', 'poland' => 'PL',
		'latvia' => 'LV', 'lithuania' => 'LT', 'finland' => 'FI', 'sweden' => 'SE', 'norway' => 'NO',
		'denmark' => 'DK', 'netherlands' => 'NL', 'belgium' => 'BE', 'luxembourg' => 'LU', 'monaco' => 'MC',
		'czech republic' => 'CZ', 'czechia' => 'CZ', 'slovakia' => 'SK', 'hungary' => 'HU', 'slovenia' => 'SI',
		'croatia' => 'HR', 'serbia' => 'RS', 'romania' => 'RO', 'bulgaria' => 'BG', 'greece' => 'GR',
		'cyprus' => 'CY', 'malta' => 'MT', 'turkey' => 'TR', 'israel' => 'IL', 'united arab emirates' => 'AE',
		'uae' => 'AE', 'united kingdom' => 'GB', 'uk' => 'GB', 'ireland' => 'IE', 'usa' => 'US',
		'united states' => 'US', 'canada' => 'CA', 'ukraine' => 'UA', 'moldova' => 'MD', 'georgia' => 'GE',
		'armenia' => 'AM', 'azerbaijan' => 'AZ', 'uzbekistan' => 'UZ', 'kyrgyzstan' => 'KG', 'belarus' => 'BY',
		'russia' => 'RU', 'mongolia' => 'MN', 'china' => 'CN', 'japan' => 'JP', 'australia' => 'AU',
	];
	$parts = array_map( 'trim', explode( ',', strtolower( (string) $text ) ) );
	$last  = end( $parts );
	return $map[ $last ] ?? '';
}

/** Upcoming schedule items (options page), sorted, as [ ts, item ] pairs. */
function adolfo_upcoming_schedule() {
	if ( ! function_exists( 'get_field' ) ) return [];
	$items = get_field( 'schedule_items', 'option' );
	if ( ! $items || ! is_array( $items ) ) return [];
	$today = strtotime( 'today' );
	$out   = [];
	foreach ( $items as $item ) {
		$parts = explode( '/', $item['date'] ?? '' );
		if ( count( $parts ) !== 3 ) continue;
		$ts = strtotime( "{$parts[2]}-{$parts[1]}-{$parts[0]}" );
		if ( $ts && $ts >= $today ) $out[] = [ $ts, $item ];
	}
	usort( $out, fn( $a, $b ) => $a[0] - $b[0] );
	return $out;
}

function adolfo_schema_person_image() {
	$page = get_page_by_path( 'adolfo-sebastiani' );
	$img  = $page ? get_the_post_thumbnail_url( $page->ID, 'full' ) : '';
	return $img ?: '';
}

add_filter( 'wpseo_schema_graph', function ( $graph, $context ) {
	if ( ! is_array( $graph ) ) return $graph;

	$person_id = adolfo_schema_person_id();
	$show_id   = adolfo_schema_show_id();
	$base      = adolfo_schema_base();
	$is_ru     = function_exists( 'pll_current_language' ) && pll_current_language() === 'ru';

	// Drop Yoast's user-based person node, remember its @id and image.
	$old_ids   = [];
	$old_image = null;
	foreach ( $graph as $i => $node ) {
		$types = (array) ( $node['@type'] ?? [] );
		if ( in_array( 'Person', $types, true ) ) {
			$old_ids[] = $node['@id'] ?? '';
			$old_image = $node['image'] ?? $old_image;
			unset( $graph[ $i ] );
		}
	}
	$graph = array_values( $graph );

	$person = [
		'@type'    => 'Person',
		'@id'      => $person_id,
		'name'     => 'Adolfo Sebastiani',
		'url'      => adolfo_artist_page_url(),
		'jobTitle' => $is_ru ? 'Исполнитель трибьют-шоу Адриано Челентано' : 'Adriano Celentano tribute artist',
		'memberOf' => [ '@id' => $show_id ],
	];
	if ( $img = adolfo_schema_person_image() ) {
		$person['image'] = [ '@type' => 'ImageObject', 'url' => $img, 'contentUrl' => $img ];
	} elseif ( $old_image ) {
		$person['image'] = $old_image;
	}
	$insta = function_exists( 'get_field' ) ? get_field( 'instagram', 'option' ) : null;
	if ( is_array( $insta ) && ! empty( $insta['url'] ) ) {
		$person['sameAs'] = [ esc_url_raw( $insta['url'] ) ];
	}

	$show = [
		'@type'  => 'PerformingGroup',
		'@id'    => $show_id,
		'name'   => 'Adriano Celentano Tribute Show',
		'url'    => $base . '/',
		'member' => [ '@id' => $person_id ],
	];
	if ( $is_ru ) $show['alternateName'] = 'Трибьют-шоу Адриано Челентано';

	// Rewire references to the removed node (publisher, author, …).
	$old_ids = array_filter( $old_ids );
	if ( $old_ids ) {
		array_walk_recursive( $graph, function ( &$value, $key ) use ( $old_ids, $person_id ) {
			if ( '@id' === $key && in_array( $value, $old_ids, true ) ) $value = $person_id;
		} );
	}

	$queried = (int) get_queried_object_id();
	$url     = is_singular() ? get_permalink( $queried ) : '';

	foreach ( $graph as &$node ) {
		$types = (array) ( $node['@type'] ?? [] );
		if ( in_array( 'WebSite', $types, true ) ) {
			$node['publisher'] = [ '@id' => $person_id ];
			$node['about']     = [ '@id' => $show_id ];
		}
		if ( in_array( 'WebPage', $types, true ) ) {
			if ( is_front_page() ) {
				$node['about'] = [ '@id' => $show_id ];
			}
			if ( is_page() && in_array( $queried, adolfo_artist_page_ids(), true ) ) {
				$node['@type']      = [ 'WebPage', 'ProfilePage' ];
				$node['mainEntity'] = [ '@id' => $person_id ];
				$person['mainEntityOfPage'] = [ '@id' => $node['@id'] ?? $url ];
			}
		}
	}
	unset( $node );

	$graph[] = $person;
	$graph[] = $show;

	// Tour Dates page: one MusicEvent per upcoming date.
	if ( is_page_template( 'template-tour.php' ) ) {
		$n = 0;
		foreach ( adolfo_upcoming_schedule() as [ $ts, $item ] ) {
			$place_name = ( $is_ru && ! empty( $item['text_ru'] ) ) ? $item['text_ru'] : ( $item['text'] ?? '' );
			if ( ! $place_name ) continue;
			$place = [ '@type' => 'Place', 'name' => $place_name ];
			if ( $code = adolfo_country_code( $item['text'] ?? '' ) ) {
				$place['address'] = [ '@type' => 'PostalAddress', 'addressCountry' => $code ];
			}
			$event = [
				'@type'               => 'MusicEvent',
				'@id'                 => $url . '#event-' . gmdate( 'Y-m-d', $ts ) . '-' . ( ++$n ),
				'name'                => 'Adolfo Sebastiani — Adriano Celentano Tribute Show, ' . ( $item['text'] ?? $place_name ),
				'startDate'           => gmdate( 'Y-m-d', $ts ),
				'eventStatus'         => 'https://schema.org/EventScheduled',
				'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
				'location'            => $place,
				'performer'           => [ '@id' => $person_id ],
				'url'                 => $url,
			];
			if ( ! empty( $person['image']['url'] ) ) $event['image'] = [ $person['image']['url'] ];
			if ( ! empty( $item['link'] ) ) {
				$event['offers'] = [ '@type' => 'Offer', 'url' => esc_url_raw( $item['link'] ) ];
			}
			$graph[] = $event;
		}
	}

	return $graph;
}, 20, 2 );

/**
 * A Tour Dates page without upcoming dates is a thin placeholder: keep it
 * reachable (follow) but out of the index until dates are added.
 */
add_filter( 'wpseo_robots', function ( $robots ) {
	if ( is_page_template( 'template-tour.php' ) && ! adolfo_upcoming_schedule() ) return 'noindex, follow';
	return $robots;
} );
add_filter( 'wpseo_sitemap_entry', function ( $url, $type, $post ) {
	if ( 'post' === $type && is_object( $post ) && 'template-tour.php' === get_page_template_slug( $post ) && ! adolfo_upcoming_schedule() ) return false;
	return $url;
}, 10, 3 );
