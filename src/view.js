/**
 * WordPress dependencies
 */
import { store, getContext, getElement } from '@wordpress/interactivity';

/**
 * Open menu items, kept outside the reactive context.
 *
 * The document-level handlers run for every focus change on the page,
 * including one made synchronously inside another store's watch callback
 * (core's Navigation overlay focuses its first element from one). A reactive
 * read in the handler would subscribe that watch to this menu's state, so
 * opening a menu would re-run it and pull focus out of the new panel.
 */
const openMenus = new WeakSet();

const { state, actions } = store('hm-blocks/hm-mega-menu-block', {
	state: {
		get isMenuOpen() {
			return Object.values(state.menuOpenedBy).filter(Boolean).length > 0;
		},

		get menuOpenedBy() {
			const context = getContext();

			return context.menuOpenedBy;
		},
	},

	actions: {
		toggleMenuOnClick() {
			const context = getContext();
			const { ref } = getElement();

			if (state.menuOpenedBy.click || state.menuOpenedBy.focus) {
				actions.closeMenuOnClick();
			} else {
				context.previousFocus = ref;
				actions.openMenu('click');
			}
		},

		closeMenuOnClick() {
			actions.closeMenu('click');
			actions.closeMenu('focus');
		},

		handleMenuKeydown(event) {
			if (state.menuOpenedBy.click) {
				// If Escape close the menu.
				if (event?.key === 'Escape') {
					actions.closeMenuOnClick();
				}
			}
		},

		handleOutsideClick(event) {
			const { ref } = getElement();

			if (!openMenus.has(ref) || ref.contains(event.target)) {
				return;
			}

			actions.closeMenuOnClick();
		},

		handleOutsideFocus( event ) {
			const { ref } = getElement();

			if ( ! openMenus.has( ref ) || ref.contains( event.target ) ) {
				return;
			}

			actions.closeMenuOnClick();
		},

		openMenu(menuOpenedOn = 'click') {
			state.menuOpenedBy[menuOpenedOn] = true;
		},

		closeMenu(menuClosedOn = 'click') {
			const context = getContext();
			state.menuOpenedBy[menuClosedOn] = false;

			// Reset the menu reference and button focus when closed.
			if (!state.isMenuOpen) {
				if (context.megaMenu?.contains(window.document.activeElement)) {
					context.previousFocus?.focus();
				}
				openMenus.delete(context.megaMenu);
				context.previousFocus = null;
				context.megaMenu = null;
			}
		},
	},

	callbacks: {
		initMenu() {
			const context = getContext();
			const { ref } = getElement();

			// Set the menu reference when initialized.
			if (state.isMenuOpen) {
				context.megaMenu = ref;
				openMenus.add(ref);
			}
		},
	},
});
