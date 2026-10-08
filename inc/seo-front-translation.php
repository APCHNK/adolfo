<?php
/**
 * Front page translations live at /{lang}/, never at their own slug.
 *
 * Polylang builds a translated front page's permalink from its slug
 * (e.g. /ru/home/), so hreflang, the language switcher and menus point to a
 * duplicate of /ru/ whose canonical is /ru/. Rewrite those links to /{lang}/
 * and 301 the slug URL there.
 *
 * Polylang's own fix is the "front page URL contains the language code"
 * setting (redirect_lang): it is switched on once here, after which the
 * admin may change it back if really needed.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Language slug when $post_id is a non-default-language translation of the
 * static front page, empty string otherwise.
 */
function satellite_front_translation_lang( $post_id ) {
	if ( ! function_exists( 'pll_get_post_translations' ) || ! function_exists( 'pll_default_language' ) ) return '';
	if ( 'page' !== get_option( 'show_on_front' ) ) return '';
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) return '';

	static $translations = [];
	if ( ! $translations ) {
		$translations = array_map( 'intval', (array) pll_get_post_translations( $front_id ) );
	}
	$lang = array_search( (int) $post_id, $translations, true );
	return ( $lang && $lang !== pll_default_language() ) ? $lang : '';
}

add_action( 'init', function () {
	if ( get_option( 'satellite_redirect_lang_done' ) || ! function_exists( 'pll_default_language' ) ) return;
	$options = get_option( 'polylang' );
	if ( is_array( $options ) && empty( $options['redirect_lang'] ) ) {
		$options['redirect_lang'] = 1;
		update_option( 'polylang', $options );
		delete_transient( 'pll_languages_list' );
	}
	update_option( 'satellite_redirect_lang_done', 1 );
}, 20 );

function satellite_front_translation_url( $lang ) {
	return untrailingslashit( get_option( 'home' ) ) . '/' . $lang . '/';
}

add_filter( 'page_link', function ( $link, $post_id ) {
	$lang = satellite_front_translation_lang( $post_id );
	return $lang ? satellite_front_translation_url( $lang ) : $link;
}, 99, 2 );

add_action( 'template_redirect', function () {
	if ( ! is_page() || is_preview() || empty( $_SERVER['REQUEST_URI'] ) ) return;
	$lang = satellite_front_translation_lang( get_queried_object_id() );
	if ( ! $lang ) return;

	$target = satellite_front_translation_url( $lang );
	$path   = (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	if ( trailingslashit( $path ) === wp_parse_url( $target, PHP_URL_PATH ) ) return;

	wp_safe_redirect( $target, 301 );
	exit;
} );

/**
 * Polylang prints x-default only when browser language detection is on.
 * Point x-default at the default-language (English) version of every page
 * that has translations, so the en/ru pair carries en + ru + x-default.
 */
add_filter( 'pll_rel_hreflang_attributes', function ( $hreflangs ) {
	if ( isset( $hreflangs['x-default'] ) || ! function_exists( 'pll_default_language' ) ) {
		return $hreflangs;
	}
	$default = pll_default_language( 'slug' );
	foreach ( $hreflangs as $code => $url ) {
		if ( $code === $default || strpos( $code, $default . '-' ) === 0 ) {
			$hreflangs['x-default'] = $url;
			break;
		}
	}
	return $hreflangs;
} );

/**
 * The static front page answers /page/N/ with a copy of itself (canonical /),
 * which only produces duplicate URLs: send those back to the front page.
 */
add_action( 'template_redirect', function () {
	if ( ! is_front_page() || is_preview() ) return;
	$paged = max( (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
	if ( $paged < 2 ) return;
	$home = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	wp_safe_redirect( $home, 301 );
	exit;
}, 5 );
