( function () {
	'use strict';

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var finePointer = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

	/* ---------------------------------------------------------------
	 * Hero background video - injected here so it never needs to be in
	 * the page content. Skipped (the poster image stays) for reduced
	 * motion, data saver and small screens.
	 * ------------------------------------------------------------- */
	( function () {
		var hero = document.querySelector( '.lpd-hero' );
		var themeUrl = ( typeof lpdTheme !== 'undefined' && lpdTheme.url ) ? lpdTheme.url : '';
		if ( ! hero || ! themeUrl ) return;

		var saveData = navigator.connection && navigator.connection.saveData;
		var isMobile = window.matchMedia( '(max-width: 780px)' ).matches;
		if ( reduceMotion || saveData || isMobile ) return;

		var video = document.createElement( 'video' );
		video.className = 'lpd-hero-video';
		video.setAttribute( 'aria-hidden', 'true' );
		video.setAttribute( 'tabindex', '-1' );
		video.autoplay = true;
		video.muted = true;
		video.loop = true;
		video.playsInline = true;
		video.setAttribute( 'poster', themeUrl + '/assets/images/hero-poster.jpg' );

		[ [ 'webm', 'video/webm' ], [ 'mp4', 'video/mp4' ] ].forEach( function ( type ) {
			var source = document.createElement( 'source' );
			source.src = themeUrl + '/assets/video/hero-bg.' + type[0];
			source.type = type[1];
			video.appendChild( source );
		} );
		hero.insertBefore( video, hero.firstChild );
	} )();

	/* ---------------------------------------------------------------
	 * Headline entrance: wrap each word so it can rise out of its own
	 * line box (CSS does the motion). Inline elements inside the heading
	 * are kept; only text nodes are split.
	 * ------------------------------------------------------------- */
	document.querySelectorAll( '.lpd-hero-heading, .page-hero .entry-title' ).forEach( function ( heading ) {
		if ( reduceMotion ) {
			heading.classList.add( 'is-split' );
			return;
		}
		var label = heading.textContent.replace( /\s+/g, ' ' ).trim();
		var index = 0;

		var split = function ( node ) {
			Array.prototype.slice.call( node.childNodes ).forEach( function ( child ) {
				if ( child.nodeType === 1 ) {
					split( child );
					return;
				}
				if ( child.nodeType !== 3 || ! child.textContent.trim() ) return;

				var frag = document.createDocumentFragment();
				child.textContent.split( /(\s+)/ ).forEach( function ( part ) {
					if ( ! part ) return;
					if ( /^\s+$/.test( part ) ) {
						frag.appendChild( document.createTextNode( ' ' ) );
						return;
					}
					var outer = document.createElement( 'span' );
					outer.className = 'lpd-w';
					var inner = document.createElement( 'span' );
					inner.style.setProperty( '--i', index++ );
					inner.textContent = part;
					outer.appendChild( inner );
					frag.appendChild( outer );
				} );
				node.replaceChild( frag, child );
			} );
		};

		split( heading );
		// Screen readers get the sentence once, not a word at a time.
		heading.setAttribute( 'aria-label', label );
		heading.classList.add( 'is-split' );
	} );

	/* ---------------------------------------------------------------
	 * Header: solid once the page has scrolled
	 * ------------------------------------------------------------- */
	var header = document.querySelector( '.site-header' );
	if ( header ) {
		var ticking = false;
		var heroVideo = document.querySelector( '.lpd-hero-video' );
		var updateHeader = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 24 );
			// The hero stays pinned under the page (see "Hero curtain" in
			// style.css), so stop decoding its film once it is covered.
			if ( heroVideo ) {
				var covered = window.scrollY > window.innerHeight * 1.05;
				if ( covered && ! heroVideo.paused ) heroVideo.pause();
				if ( ! covered && heroVideo.paused ) heroVideo.play().catch( function () {} );
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
	 * FAQ accordion. A real button inside the heading keeps the question
	 * in the heading outline for screen readers. Animates to the answer's
	 * measured height so the reveal is smooth whatever its length.
	 * ------------------------------------------------------------- */
	document.querySelectorAll( '.lpd-faq-item' ).forEach( function ( item, index ) {
		var heading = item.querySelector( 'h3' );
		var answer = item.querySelector( 'p' );
		if ( ! heading || ! answer ) return;

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

		question.addEventListener( 'click', function () {
			var isOpen = item.classList.contains( 'is-open' );
			answer.style.maxHeight = answer.scrollHeight + 'px';
			if ( isOpen ) {
				// Closing: start from the real height, collapse next frame.
				window.requestAnimationFrame( function () {
					window.requestAnimationFrame( function () {
						answer.style.maxHeight = '0px';
					} );
				} );
			}
			item.classList.toggle( 'is-open', ! isOpen );
			question.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
		} );

		// Once open, let the answer reflow naturally (e.g. on resize).
		answer.addEventListener( 'transitionend', function ( e ) {
			if ( e.propertyName === 'max-height' && item.classList.contains( 'is-open' ) ) {
				answer.style.maxHeight = 'none';
			}
		} );
	} );

	/* ---------------------------------------------------------------
	 * Stats: show a leading "+" as trailing ("+15" -> "15+"), and count
	 * up once when the figure scrolls into view.
	 * ------------------------------------------------------------- */
	var statNodes = document.querySelectorAll( '.lpd-stat-number' );
	statNodes.forEach( function ( el ) {
		var raw = el.textContent.trim();
		if ( raw.charAt( 0 ) === '+' ) el.textContent = raw.slice( 1 ) + '+';
	} );

	if ( ! reduceMotion && 'IntersectionObserver' in window && statNodes.length ) {
		var countUp = function ( el ) {
			var raw = el.getAttribute( 'data-final' );
			var match = raw.match( /^([^\d]*)([\d.]+)(.*)$/ );
			if ( ! match ) return;
			var target = parseFloat( match[2] );
			var decimals = ( match[2].split( '.' )[1] || '' ).length;
			var start = null;
			var step = function ( now ) {
				if ( ! start ) start = now;
				var t = Math.min( ( now - start ) / 1400, 1 );
				var eased = 1 - Math.pow( 1 - t, 4 );
				el.textContent = t < 1 ? match[1] + ( target * eased ).toFixed( decimals ) + match[3] : raw;
				if ( t < 1 ) window.requestAnimationFrame( step );
			};
			window.requestAnimationFrame( step );
		};
		var statObserver = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) return;
				countUp( entry.target );
				obs.unobserve( entry.target );
			} );
		}, { threshold: 0.6 } );

		var viewportH = window.innerHeight;
		var tops = Array.prototype.map.call( statNodes, function ( el ) { return el.getBoundingClientRect().top; } );
		statNodes.forEach( function ( el, i ) {
			if ( tops[ i ] < viewportH ) return; // already on screen: leave it be
			var raw = el.textContent.trim();
			var m = raw.match( /^([^\d]*)([\d.]+)(.*)$/ );
			if ( ! m ) return;
			el.setAttribute( 'data-final', raw );
			el.textContent = m[1] + ( 0 ).toFixed( ( m[2].split( '.' )[1] || '' ).length ) + m[3];
			statObserver.observe( el );
		} );
	}

	/* ---------------------------------------------------------------
	 * Photographs unveil once as they arrive. Anything already on screen
	 * at load is left alone, so nothing the visitor is looking at blinks.
	 * ------------------------------------------------------------- */
	if ( ! reduceMotion && 'IntersectionObserver' in window ) {
		var frames = document.querySelectorAll( '.lpd-colo-teaser-photo, .lpd-bands .lpd-photo, .lpd-colo-gallery .wp-block-image' );
		var frameTops = Array.prototype.map.call( frames, function ( el ) { return el.getBoundingClientRect().top; } );
		var imgObserver = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) return;
				entry.target.classList.add( 'is-in' );
				obs.unobserve( entry.target );
			} );
		}, { threshold: 0.2, rootMargin: '0px 0px -8% 0px' } );

		frames.forEach( function ( el, i ) {
			if ( frameTops[ i ] < window.innerHeight * 0.9 ) return;
			el.classList.add( 'lpd-img-reveal' );
			imgObserver.observe( el );
		} );
	}

	/* ---------------------------------------------------------------
	 * The footer cats watch the pointer. Each pupil shifts a little
	 * toward it, in SVG units, so it works at any rendered size.
	 * ------------------------------------------------------------- */
	( function () {
		var cats = document.querySelectorAll( '.lpd-footer-cat' );

		if ( reduceMotion ) {
			// SMIL (tail, head tilt, blink) can't be stopped from CSS.
			cats.forEach( function ( svg ) {
				if ( typeof svg.pauseAnimations === 'function' ) svg.pauseAnimations();
			} );
			return;
		}
		if ( ! finePointer || ! cats.length ) return;

		var pointer = null;
		var queued = false;
		var look = function () {
			queued = false;
			cats.forEach( function ( svg ) {
				var box = svg.getBoundingClientRect();
				if ( box.bottom < -200 || box.top > window.innerHeight + 200 ) return;
				var dx = pointer.x - ( box.left + box.width / 2 );
				var dy = pointer.y - ( box.top + box.height * 0.3 );
				var dist = Math.sqrt( dx * dx + dy * dy ) || 1;
				var reach = Math.min( dist / 160, 1 ) * 2.4;
				var x = ( dx / dist ) * reach;
				var y = ( dy / dist ) * reach * 0.8;
				svg.querySelectorAll( '.lpd-cat-eye' ).forEach( function ( eye ) {
					eye.style.transform = 'translate(' + x.toFixed( 2 ) + 'px,' + y.toFixed( 2 ) + 'px)';
				} );
			} );
		};
		window.addEventListener( 'pointermove', function ( e ) {
			pointer = { x: e.clientX, y: e.clientY };
			if ( ! queued ) {
				queued = true;
				window.requestAnimationFrame( look );
			}
		}, { passive: true } );
	} )();
} )();
