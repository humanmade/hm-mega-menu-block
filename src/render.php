<?php
$label = $attributes['label'] ?? '';
$label_color = $attributes['labelColor'] ?? '';
$menu_slug = $attributes['menuSlug'] ?? '';
$justify_menu = $attributes['justifyMenu'] ?? '';
$menu_width = $attributes['width'] ?? 'content';

// Accept a single color value (hex, name, rgb(), var(), etc.); anything else,
// such as ";" or an entity, could inject further declarations into the style.
if ( ! preg_match( '/^[#a-zA-Z0-9(),.%\/\s-]+$/', $label_color ) ) {
	$label_color = '#000';
}

$menu_classes  = 'hm-mega-menu wp-block-hm-mega-menu__menu-container';
$menu_classes .= ' menu-width-' . $menu_width;
$menu_classes .= $justify_menu ? ' menu-justified-' . $justify_menu : '';
$menu_id       = wp_unique_id( 'hm-mega-menu-panel-' );

wp_interactivity_state(
	'hm-blocks/hm-mega-menu-block',
	[
		'isMenuOpen' => static function (): bool {
			$context        = wp_interactivity_get_context();
			$menu_opened_by = $context['menuOpenedBy'] ?? [];

			return is_array( $menu_opened_by ) && (bool) array_filter( $menu_opened_by );
		},
	]
);
?>

<li <?php echo get_block_wrapper_attributes(); ?> data-wp-interactive='{"namespace": "hm-blocks/hm-mega-menu-block" }' data-wp-context='{ "menuOpenedBy": {} }' data-wp-on-document--keydown="actions.handleMenuKeydown" data-wp-on-document--click="actions.handleOutsideClick" data-wp-on-document--focusin="actions.handleOutsideFocus" data-wp-watch="callbacks.initMenu">

	<button aria-controls="<?php echo esc_attr( $menu_id ); ?>" aria-expanded="false" class="wp-block-hm-mega-menu__toggle" data-wp-on--click="actions.toggleMenuOnClick" data-wp-bind--aria-expanded="state.isMenuOpen" style="color:<?php echo esc_attr( $label_color ); ?>">
		<?php echo esc_html( $label ); ?><span class="wp-block-hm-mega-menu__toggle-icon">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true" focusable="false" fill="none"><path d="M1.50002 4L6.00002 8L10.5 4" stroke-width="1.5"></path></svg>
		</span>
	</button>

	<div class="<?php echo esc_attr( $menu_classes ); ?>" id="<?php echo esc_attr( $menu_id ); ?>">
		<?php block_template_part( $menu_slug ); ?>

		<button aria-label="<?php esc_attr_e( 'Close menu', 'hm-mega-menu-block' ); ?>" class="menu-container__close-button" data-wp-on--click="actions.closeMenuOnClick" type="button">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
		</button>
	</div>

</li>
