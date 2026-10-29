( function () {
	'use strict';

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ---------------------------------------------------------------
	 * Hero background video - injected via JS so it doesn't require
	 * touching the pasted page content. Skipped entirely (falls back
	 * to the static poster image) for reduced-motion or data-saver
	 * users, since an autoplaying video is exactly the kind of motion
	 * those settings exist to avoid.
	 * ------------------------------------------------------------- */
	(function () {
		var hero = document.querySelector( '.lpd-hero' );
		if ( ! hero ) return;

		var themeUrl = ( typeof lpdTheme !== 'undefined' && lpdTheme.url ) ? lpdTheme.url : '';
		if ( ! themeUrl ) return; // localized data missing - skip rather than guess wrong URLs

		var posterUrl = themeUrl + '/assets/images/hero-poster.jpg';

		var saveData = navigator.connection && navigator.connection.saveData;
		var isMobile = window.matchMedia( '(max-width: 780px)' ).matches;
		if ( prefersReducedMotion || saveData || isMobile ) return;

		var video = document.createElement( 'video' );
		video.className = 'lpd-hero-video';
		video.setAttribute( 'aria-hidden', 'true' );
		video.setAttribute( 'tabindex', '-1' );
		video.autoplay = true;
		video.muted = true;
		video.loop = true;
		video.playsInline = true;
		video.setAttribute( 'poster', posterUrl );

		var webm = document.createElement( 'source' );
		webm.src = themeUrl + '/assets/video/hero-bg.webm';
		webm.type = 'video/webm';

		var mp4 = document.createElement( 'source' );
		mp4.src = themeUrl + '/assets/video/hero-bg.mp4';
		mp4.type = 'video/mp4';

		video.appendChild( webm );
		video.appendChild( mp4 );
		hero.insertBefore( video, hero.firstChild );
	})();

	/* ---------------------------------------------------------------
	 * Header: subtle shadow once scrolled past the top
	 * ------------------------------------------------------------- */
	var header = document.querySelector( '.site-header' );

	if ( header ) {
		var ticking = false;
		var updateHeader = function () {
			if ( window.scrollY > 8 ) {
				header.classList.add( 'is-scrolled' );
			} else {
				header.classList.remove( 'is-scrolled' );
			}
			ticking = false;
		};
		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( updateHeader );
				ticking = true;
			}
		}, { passive: true } );
		updateHeader();
	}

	/* ---------------------------------------------------------------
	 * FAQ accordion: click a question to expand/collapse its answer.
	 * Animates to the answer's real measured height rather than a
	 * fixed guess, so the reveal is smooth regardless of answer length.
	 * ------------------------------------------------------------- */
	document.querySelectorAll( '.lpd-faq-item' ).forEach( function ( item, index ) {
		var heading = item.querySelector( 'h3' );
		var answer = item.querySelector( 'p' );
		if ( ! heading || ! answer ) return;

		// A real button inside the heading keeps the question in the
		// heading outline for screen readers (role="button" on the h3
		// itself would replace its heading role).
		var question = document.createElement( 'button' );
		question.type = 'button';
		question.className = 'lpd-faq-trigger';
		while ( heading.firstChild ) question.appendChild( heading.firstChild );
		heading.appendChild( question );

		question.id = 'lpd-faq-q-' + index;
		answer.id = answer.id || 'lpd-faq-a-' + index;
		answer.setAttribute( 'role', 'region' );
		answer.setAttribute( 'aria-labelledby', question.id );
		question.setAttribute( 'aria-controls', answer.id );
		question.setAttribute( 'aria-expanded', 'false' );

		var toggle = function () {
			var isOpen = item.classList.contains( 'is-open' );

			if ( isOpen ) {
				// Closing: set an explicit starting height first so the
				// transition has a real value to animate from, then
				// collapse to 0 on the next frame.
				answer.style.maxHeight = answer.scrollHeight + 'px';
				window.requestAnimationFrame( function () {
					window.requestAnimationFrame( function () {
						answer.style.maxHeight = '0px';
					} );
				} );
				item.classList.remove( 'is-open' );
			} else {
				answer.style.maxHeight = answer.scrollHeight + 'px';
				item.classList.add( 'is-open' );
			}

			question.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
		};

		// Once the open transition finishes, switch to 'none' so the
		// answer can reflow naturally (e.g. on window resize) without
		// being pinned to a stale pixel height.
		answer.addEventListener( 'transitionend', function ( e ) {
			if ( e.propertyName === 'max-height' && item.classList.contains( 'is-open' ) ) {
				answer.style.maxHeight = 'none';
			}
		} );

		question.addEventListener( 'click', toggle );
	} );

	// Move a leading "+" to trailing instead (e.g. "+15" -> "15+").
	// This is a static text fix, not animation, so it runs regardless
	// of the reduced-motion preference checked just below.
	document.querySelectorAll( '.lpd-stat-number' ).forEach( function ( el ) {
		var raw = el.textContent.trim();
		if ( raw.charAt( 0 ) === '+' ) {
			el.textContent = raw.slice( 1 ) + '+';
		}
	} );

	if ( prefersReducedMotion ) {
		// SMIL animations (animateTransform/animate inside the footer cat SVGs)
		// aren't controllable via CSS, so pause them here explicitly.
		document.querySelectorAll( '.lpd-footer-cat' ).forEach( function ( svg ) {
			if ( typeof svg.pauseAnimations === 'function' ) {
				svg.pauseAnimations();
			}
		} );
		return; // Skip reveal/counter motion entirely - respect the user's setting.
	}

	/* ---------------------------------------------------------------
	 * Scroll reveal: fade + rise, once, staggered within a row
	 * ------------------------------------------------------------- */
	// Only rows of cards reveal: headings, prose and FAQ rows stay put, so
	// the motion marks "here is a set to compare" instead of decorating
	// every block on the page the same way.
	var revealSelectors = [
		'.lpd-features .lpd-card',
		'.lpd-pricing-card',
		'.lpd-step',
		'.lpd-why-item',
		'.lpd-service-card',
		'.post-card'
	];

	var revealTargets = document.querySelectorAll( revealSelectors.join( ',' ) );

	if ( 'IntersectionObserver' in window && revealTargets.length ) {
		// Stagger siblings that share a parent row (columns/grid) instead of
		// every element on the page firing on its own timer. Content inside
		// .lpd-page-content is a tall single-column page, not a row - each
		// paragraph/heading scrolls into view at its own natural time as
		// the user scrolls, so it gets no artificial delay at all (a fixed
		// per-sibling delay would otherwise keep growing the further down
		// the page you go, making later content feel laggy to reveal).
		var viewportH = window.innerHeight || document.documentElement.clientHeight;
		var rowState = new Map();
		var toAnimate = [];

		// Read every position first, then write: interleaving
		// getBoundingClientRect() with classList.add() forces a reflow per card.
		var tops = Array.prototype.map.call( revealTargets, function ( el ) {
			return el.getBoundingClientRect().top;
		} );
		var scrollY = window.pageYOffset;

		revealTargets.forEach( function ( el, i ) {
			// Anything already on screen (or scrolled past) when this script
			// runs is left alone. Hiding it now would make content the visitor
			// is already looking at dim and flicker while the page loads.
			if ( tops[ i ] < viewportH - 40 ) return;

			toAnimate.push( el );

			// Only items that sit side by side in the same visual row are
			// staggered. On a phone the columns stack, so every card is its
			// own row and gets no delay.
			var parent = el.closest( '.wp-block-columns' ) || el.parentElement;
			var top = tops[ i ] + scrollY;
			var state = rowState.get( parent );
			if ( ! state ) {
				state = { top: top, count: 0 };
				rowState.set( parent, state );
			} else if ( Math.abs( top - state.top ) < 12 ) {
				state.count++;
			} else {
				state.top = top;
				state.count = 0;
			}
			el.style.transitionDelay = ( Math.min( state.count, 3 ) * 60 ) + 'ms';
		} );

		toAnimate.forEach( function ( el ) {
			el.classList.add( 'lpd-reveal' );
		} );

		var observer = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) return;
				var el = entry.target;
				el.classList.add( 'is-visible' );
				obs.unobserve( el );
				// Once the fade-in has finished, give the element back to its
				// normal styles so hover effects keep their own timing instead
				// of inheriting the reveal's slower, staggered one.
				window.setTimeout( function () {
					el.classList.remove( 'lpd-reveal', 'is-visible' );
					el.style.transitionDelay = '';
				}, 800 );
			} );
		}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' } );

		toAnimate.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* ---------------------------------------------------------------
	 * Stat counter: only where it means something (your uptime/scale
	 * numbers) - counts up once when scrolled into view.
	 * ------------------------------------------------------------- */
	var statNodes = document.querySelectorAll( '.lpd-stat-number' );

	if ( 'IntersectionObserver' in window && statNodes.length ) {
		var easeOutCubic = function ( t ) {
			return 1 - Math.pow( 1 - t, 3 );
		};

		var animateStat = function ( el ) {
			var raw = ( el.getAttribute( 'data-final' ) || el.textContent ).trim();
			var match = raw.match( /^([^\d]*)([\d.]+)(.*)$/ );
			if ( ! match ) return;

			var prefix = match[1];
			var target = parseFloat( match[2] );
			var suffix = match[3];
			var decimals = ( match[2].split( '.' )[1] || '' ).length;
			var duration = 1700;
			var start = null;

			var step = function ( timestamp ) {
				if ( ! start ) start = timestamp;
				var progress = Math.min( ( timestamp - start ) / duration, 1 );
				var eased = easeOutCubic( progress );
				var current = ( target * eased ).toFixed( decimals );
				el.textContent = prefix + current + suffix;
				if ( progress < 1 ) {
					window.requestAnimationFrame( step );
				} else {
					el.textContent = raw; // land exactly on the original text
				}
			};

			window.requestAnimationFrame( step );
		};

		var statObserver = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					animateStat( entry.target );
					obs.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.5 } );

		var statViewportH = window.innerHeight || document.documentElement.clientHeight;
		statNodes.forEach( function ( el ) {
			// Already on screen when the page loads: just leave the number as is.
			if ( el.getBoundingClientRect().top < statViewportH ) return;
			// Otherwise show zero now, while it is off screen, so it counts up
			// from zero as it arrives instead of flashing the real number first.
			var raw = el.textContent.trim();
			var m = raw.match( /^([^\d]*)([\d.]+)(.*)$/ );
			if ( ! m ) return;
			var decimals = ( m[2].split( '.' )[1] || '' ).length;
			el.setAttribute( 'data-final', raw );
			el.textContent = m[1] + ( 0 ).toFixed( decimals ) + m[3];
			statObserver.observe( el );
		} );
	}
} )();
