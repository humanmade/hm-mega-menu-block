import { getContext, getElement, store } from '@wordpress/interactivity';

import './view';

jest.mock( '@wordpress/interactivity', () => ( {
	getContext: jest.fn(),
	getElement: jest.fn(),
	store: jest.fn( ( namespace, definition ) => definition ),
} ) );

const { actions } = store.mock.calls[ 0 ][ 1 ];

describe( 'mega menu focus handling', () => {
	let context;
	let item;

	beforeEach( () => {
		document.body.innerHTML = `
			<li id="menu">
				<button id="toggle">Our Company</button>
				<div id="panel"><a id="inside" href="/inside">Inside</a></div>
			</li>
			<a id="outside" href="/outside">Outside</a>
		`;
		item = document.getElementById( 'menu' );
		context = {
			menuOpenedBy: { click: true, focus: false },
			megaMenu: item,
			previousFocus: document.getElementById( 'toggle' ),
		};
		getContext.mockReturnValue( context );
		getElement.mockReturnValue( { ref: item } );
	} );

	it( 'keeps the menu open while focus remains inside', () => {
		actions.handleOutsideFocus( {
			target: document.getElementById( 'inside' ),
		} );

		expect( context.menuOpenedBy.click ).toBe( true );
	} );

	it( 'closes the menu when focus moves outside', () => {
		const outside = document.getElementById( 'outside' );
		outside.focus();

		actions.handleOutsideFocus( {
			target: outside,
		} );

		expect( context.menuOpenedBy.click ).toBe( false );
		expect( context.menuOpenedBy.focus ).toBe( false );
		expect( document.activeElement ).toBe( outside );
		expect( context.previousFocus ).toBeNull();
		expect( context.megaMenu ).toBeNull();
	} );

	it( 'returns focus to the toggle when Escape closes the menu', () => {
		const toggle = document.getElementById( 'toggle' );
		const inside = document.getElementById( 'inside' );
		inside.focus();

		actions.handleMenuKeydown( { key: 'Escape' } );

		expect( document.activeElement ).toBe( toggle );
		expect( context.menuOpenedBy.click ).toBe( false );
		expect( context.previousFocus ).toBeNull();
		expect( context.megaMenu ).toBeNull();
	} );

	it( 'ignores focus changes when no menu is open', () => {
		context.megaMenu = null;
		actions.handleOutsideFocus( {
			target: document.getElementById( 'outside' ),
		} );

		expect( context.menuOpenedBy.click ).toBe( true );
	} );
} );
