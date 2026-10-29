( function () {
	var toggle = document.querySelector( '.menu-toggle' );
	var nav = document.querySelector( '.main-navigation' );

	if ( ! toggle || ! nav ) return;

	var CLOSE_ANIMATION_MS = 320; // must be at least the CSS lpd-menu-out duration (0.3s)
	var closeTimer = null;

	function openMenu() {
		clearTimeout( closeTimer );
		nav.classList.remove( 'is-closing' );
		nav.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.classList.add( 'lpd-menu-open' );
	}

	function closeMenu() {
		if ( ! nav.classList.contains( 'is-open' ) ) return;
		clearTimeout( closeTimer );
		nav.classList.remove( 'is-open' );
		nav.classList.add( 'is-closing' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'lpd-menu-open' );
		closeTimer = setTimeout( function () {
			nav.classList.remove( 'is-closing' );
		}, CLOSE_ANIMATION_MS );
	}

	toggle.addEventListener( 'click', function () {
		if ( nav.classList.contains( 'is-open' ) ) {
			closeMenu();
		} else {
			openMenu();
		}
	} );

	// Close the mobile menu if a nav link is tapped
	nav.addEventListener( 'click', function ( e ) {
		if ( e.target.tagName === 'A' ) {
			closeMenu();
		}
	} );

	// Close on Escape for keyboard users
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
			closeMenu();
			toggle.focus();
		}
	} );
} )();
