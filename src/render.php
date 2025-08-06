<?php
$label = esc_html($attributes['label'] ?? '');
$color_label = esc_attr($attributes['labelColor'] ?? '#000');
$menu_slug = esc_attr($attributes['menuSlug'] ?? '');
$justify_menu = esc_attr($attributes['justifyMenu'] ?? '');
$menu_width = esc_attr($attributes['width'] ?? 'content');

$menu_classes  = 'hm-mega-menu wp-block-hm-mega-menu__menu-container';
$menu_classes .= ' menu-width-' . $menu_width;
$menu_classes .= $justify_menu ? ' menu-justified-' . $justify_menu : '';

$wrapper_attributes = get_block_wrapper_attributes();

$close_icon  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>';
$toggle_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true" focusable="false" fill="none"><path d="M1.50002 4L6.00002 8L10.5 4" stroke-width="1.5"></path></svg>';
?>

<li <?php echo $wrapper_attributes; ?> data-wp-interactive='{"namespace": "hm-blocks/hm-mega-menu-block" }' data-wp-context='{ "menuOpenedBy": {} }' data-wp-on-document--keydown="actions.handleMenuKeydown" data-wp-on-document--click="actions.handleOutsideClick" data-wp-watch="callbacks.initMenu">

	<button class="wp-block-hm-mega-menu__toggle" data-wp-on--click="actions.toggleMenuOnClick" data-wp-bind--aria-expanded="state.isMenuOpen" style="color:<?php echo $color_label; ?>">
		<?php echo $label; ?><span class="wp-block-hm-mega-menu__toggle-icon"><?php echo $toggle_icon; ?></span>
	</button>

	<div class="<?php echo $menu_classes; ?>">
		<?php echo block_template_part($menu_slug); ?>

		<button aria-label="<?php echo __('Close menu', 'mega-menu'); ?>" class="menu-container__close-button" data-wp-on--click="actions.closeMenuOnClick" type="button">
			<?php echo $close_icon; ?>
		</button>
	</div>

</li>