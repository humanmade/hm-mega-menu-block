<?php
/**
 * Seeds the wp-env site with demo mega menus in the active theme's header.
 *
 * Usage: npm run env:bootstrap
 *
 * Safe to re-run: content is matched by slug and updated in place.
 *
 * @package hm-blocks
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

/**
 * Create or update a post, matched by post type and slug.
 *
 * @param array $postarr Post data, including post_type and post_name.
 * @return int Post ID.
 */
function hm_mega_menu_bootstrap_upsert_post( array $postarr ) : int {
	$existing = get_posts( [
		'post_type' => $postarr['post_type'],
		'name' => $postarr['post_name'],
		'post_status' => 'any',
		'posts_per_page' => 1,
		'fields' => 'ids',
	] );

	if ( $existing ) {
		$postarr['ID'] = $existing[0];
	}

	$post_id = wp_insert_post( wp_slash( $postarr + [ 'post_status' => 'publish' ] ), true );

	if ( is_wp_error( $post_id ) ) {
		WP_CLI::error( $post_id );
	}

	return $post_id;
}

/**
 * Build demo template part content: one column of links per heading.
 *
 * @param array $columns Map of column heading => list of link labels.
 * @return string Block markup.
 */
function hm_mega_menu_bootstrap_menu_content( array $columns ) : string {
	$markup = '';

	foreach ( $columns as $heading => $links ) {
		$items = '';
		foreach ( $links as $link ) {
			$items .= sprintf( '<!-- wp:list-item --><li><a href="#">%s</a></li><!-- /wp:list-item -->', esc_html( $link ) );
		}

		$markup .= sprintf(
			'<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">%s</h3><!-- /wp:heading --><!-- wp:list --><ul class="wp-block-list">%s</ul><!-- /wp:list --></div><!-- /wp:column -->',
			esc_html( $heading ),
			$items
		);
	}

	return '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"backgroundColor":"base","layout":{"type":"constrained"}} --><div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns --><div class="wp-block-columns">'
		. $markup
		. '</div><!-- /wp:columns --></div><!-- /wp:group -->';
}

$theme = get_stylesheet();

$menus = [
	'mega-menu-products' => [
		'title' => 'Mega Menu: Products',
		'block' => [
			'label' => 'Products',
			'width' => 'wide',
		],
		'columns' => [
			'Hardware' => [ 'Laptops', 'Phones', 'Accessories' ],
			'Software' => [ 'Editor', 'Hosting', 'Analytics' ],
			'Services' => [ 'Support', 'Training' ],
		],
	],
	'mega-menu-resources' => [
		'title' => 'Mega Menu: Resources',
		'block' => [
			'label' => 'Resources',
			'labelColor' => '#c0392b',
			'width' => 'content',
			'justifyMenu' => 'right',
		],
		'columns' => [
			'Learn' => [ 'Documentation', 'Tutorials' ],
			'Community' => [ 'Forums', 'Events' ],
		],
	],
];

$nav_items = '<!-- wp:navigation-link {"label":"Home","url":"/","kind":"custom"} /-->';

foreach ( $menus as $slug => $menu ) {
	$part_id = hm_mega_menu_bootstrap_upsert_post( [
		'post_type' => 'wp_template_part',
		'post_name' => $slug,
		'post_title' => $menu['title'],
		'post_content' => hm_mega_menu_bootstrap_menu_content( $menu['columns'] ),
	] );
	wp_set_object_terms( $part_id, $theme, 'wp_theme' );
	wp_set_object_terms( $part_id, 'menu', 'wp_template_part_area' );

	$nav_items .= sprintf(
		'<!-- wp:hm-blocks/hm-mega-menu-block %s /-->',
		wp_json_encode( $menu['block'] + [ 'menuSlug' => $slug ] )
	);

	WP_CLI::log( sprintf( 'Template part "%s": %d', $slug, $part_id ) );
}

$nav_items .= '<!-- wp:navigation-link {"label":"Contact","url":"#contact","kind":"custom"} /-->';

$nav_id = hm_mega_menu_bootstrap_upsert_post( [
	'post_type' => 'wp_navigation',
	'post_name' => 'mega-menu-demo',
	'post_title' => 'Mega Menu Demo',
	'post_content' => $nav_items,
] );
WP_CLI::log( sprintf( 'Navigation "mega-menu-demo": %d', $nav_id ) );

// Override the theme header, pointing its Navigation block at the demo menu.
$header = WP_Block_Patterns_Registry::get_instance()->get_registered( $theme . '/header' );
if ( ! $header ) {
	WP_CLI::error( sprintf( 'No "%s/header" pattern found; the bootstrap targets Twenty Twenty-Five.', $theme ) );
}

$header_content = preg_replace( '/<!-- wp:navigation \{/', '<!-- wp:navigation {"ref":' . $nav_id . ',', $header['content'], 1, $count );
if ( ! $count ) {
	WP_CLI::error( 'No Navigation block found in the header pattern.' );
}

$header_id = hm_mega_menu_bootstrap_upsert_post( [
	'post_type' => 'wp_template_part',
	'post_name' => 'header',
	'post_title' => 'Header',
	'post_content' => $header_content,
] );
wp_set_object_terms( $header_id, $theme, 'wp_theme' );
wp_set_object_terms( $header_id, 'header', 'wp_template_part_area' );

WP_CLI::success( sprintf( 'Header %d now uses the mega menu demo navigation.', $header_id ) );
