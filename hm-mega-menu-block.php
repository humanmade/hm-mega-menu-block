<?php
/**
 * Plugin Name:       HM Mega Menu Block
 * Description:       A megamenu block.
 * Version:           1.0
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hm-mega-menu-block
 *
 * @package           hm-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_hm_mega_menu_block_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_hm_mega_menu_block_block_init' );

/**
 * Registers menu template part location.
 */
function hm_mega_menu_template_part_areas( array $areas ) {
		$areas[] = [
			'area' => 'menu',
			'area_tag' => 'div',
			'description' => __('Menu templates are used to create sections for a mega menu', 'hm-mega-menu-block'),
			'icon' => 'layout',
			'label' => __('Megamenu', 'hm-mega-menu-block')
		];

		return $areas;
}

add_filter('default_wp_template_part_areas', 'hm_mega_menu_template_part_areas');
