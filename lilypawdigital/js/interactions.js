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
	 * Hero line above the headline, and the headline's closing full
	 * stop: the full stops are coloured, which needs them in their own
	 * element. Runs for everyone (it is colour, not motion).
	 * ------------------------------------------------------------- */
	var dotSpan = function () {
		var dot = document.createElement( 'span' );
		dot.className = 'lpd-dot';
		dot.textContent = '.';
		return dot;
	};
	document.querySelectorAll( '.lpd-hero-kicker' ).forEach( function ( line ) {
		var words = line.textContent.replace( /\s+/g, ' ' ).trim().split( ' ' );
		line.setAttribute( 'aria-label', words.join( ' ' ) );
		line.textContent = '';
		words.forEach( function ( word, i ) {
			var span = document.createElement( 'span' );
			span.className = 'lpd-kw';
			span.setAttribute( 'aria-hidden', 'true' );
			span.style.setProperty( '--i', i );
			if ( /\.$/.test( word ) ) {
				span.textContent = word.slice( 0, -1 );
				span.appendChild( dotSpan() );
			} else {
				span.textContent = word;
			}
			line.appendChild( span );
			if ( i < words.length - 1 ) line.appendChild( document.createTextNode( ' ' ) );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Headline entrance: wrap each word so it can rise out of its own
	 * line box (CSS does the motion). Inline elements inside the heading
	 * are kept; only text nodes are split.
	 * ------------------------------------------------------------- */
	document.querySelectorAll( '.lpd-hero-heading, .page-hero .entry-title' ).forEach( function ( heading ) {
		// The hero headline is split even with reduced motion: the words
		// then simply appear (the CSS animates them only when motion is
		// allowed), and splitting is what colours the full stops pink.
		if ( reduceMotion && ! heading.classList.contains( 'lpd-hero-heading' ) ) {
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
		if ( heading.classList.contains( 'lpd-hero-heading' ) ) {
			heading.querySelectorAll( '.lpd-w > span' ).forEach( function ( word ) {
				if ( /\.$/.test( word.textContent ) ) {
					word.textContent = word.textContent.slice( 0, -1 );
					word.appendChild( dotSpan() );
				}
			} );
		}
		// Screen readers get the sentence once, not a word at a time.
		heading.setAttribute( 'aria-label', label );
		heading.classList.add( 'is-split' );
	} );

	/* ---------------------------------------------------------------
	 * Header: solid once the page has scrolled
	 * ------------------------------------------------------------- */
	var header = document.querySelector( '.site-header' );
	var heroVideo = document.querySelector( '.lpd-hero-video' );

	/* ---------------------------------------------------------------
	 * The statement: wrap each word so it can light up in turn.
	 * ------------------------------------------------------------- */
	var statements = document.querySelectorAll( '.lpd-statement-text' );
	if ( ! reduceMotion ) {
		statements.forEach( function ( el ) {
			var words = el.textContent.replace( /\s+/g, ' ' ).trim().split( ' ' );
			el.textContent = '';
			words.forEach( function ( word, i ) {
				var span = document.createElement( 'span' );
				span.className = 'lpd-sw';
				span.style.setProperty( '--i', i );
				span.textContent = word;
				el.appendChild( span );
				if ( i < words.length - 1 ) el.appendChild( document.createTextNode( ' ' ) );
			} );
			el.style.setProperty( '--n', words.length );
		} );
	}

	/* ---------------------------------------------------------------
	 * Scroll scenes. One loop measures how far each scene has travelled
	 * and writes it to --p (0 to 1) on the element; the stylesheet does
	 * the rest (see ".lpd-scrub" rules). Plain scroll position, so it
	 * behaves the same in Safari, Firefox, Chrome and on phones.
	 *   pin     - the tall colocation scene: 0 when its stage pins,
	 *             1 when it lets go
	 *   through - 0 as the element enters low on screen, 1 once it has
	 *             risen to the upper part
	 *   leave   - 0 at the top of the page, 1 when the hero has gone
	 * ------------------------------------------------------------- */
	var scenes = [];
	var add = function ( selector, mode ) {
		document.querySelectorAll( selector ).forEach( function ( el ) {
			scenes.push( { el: el, mode: mode, last: -1 } );
		} );
	};
	// The colocation scene still pins and changes photograph for visitors
	// who ask for reduced motion; it cross-fades instead of wiping.
	add( '.lpd-scene', 'pin' );
	if ( ! reduceMotion ) {
		add( '.lpd-statement-text', 'through' );
		add( '.lpd-process .wp-block-columns', 'through' );
		add( '.lpd-hero', 'leave' );
	}
	if ( scenes.length ) document.documentElement.classList.add( 'lpd-scrub' );

	var ramp = function ( p, from, to ) {
		var t = Math.min( Math.max( ( p - from ) / ( to - from ), 0 ), 1 );
		return t * t * ( 3 - 2 * t );
	};
	// Photograph 2 arrives around a third of the way, photograph 3 around
	// two thirds; each fact is at full strength while its photograph shows.
	var paintScene = function ( el, p ) {
		var short = window.innerHeight <= 560; // stage is not pinned: leave it alone
		var stacked = window.innerWidth <= 900;
		var off = stacked ? 0 : 0.62;
		var marks = [ 0.26, 0.6 ];
		el.querySelectorAll( '.lpd-scene-photo' ).forEach( function ( photo, i ) {
			var img = photo.querySelector( 'img' );
			if ( i === 0 || short ) {
				photo.style.clipPath = photo.style.webkitClipPath = photo.style.opacity = '';
				if ( img ) img.style.transform = '';
				return;
			}
			var m = marks[ i - 1 ];
			if ( m === undefined ) return;
			var t = ramp( p, m, m + 0.14 );
			if ( reduceMotion ) {
				photo.style.opacity = t.toFixed( 3 );
				return;
			}
			var clip = 'inset(' + ( ( 1 - t ) * 100 ).toFixed( 2 ) + '% 0 0 0)';
			photo.style.webkitClipPath = clip;
			photo.style.clipPath = clip;
			if ( img ) img.style.transform = 'scale(' + ( 1.14 - 0.14 * ramp( p, m, m + 0.2 ) ).toFixed( 4 ) + ')';
		} );
		el.querySelectorAll( '.lpd-scene-cap' ).forEach( function ( cap, i ) {
			if ( short ) { cap.style.opacity = ''; return; }
			var up = i === 0 ? 1 : ramp( p, marks[ i - 1 ] + 0.06, marks[ i - 1 ] + 0.12 );
			var down = marks[ i ] === undefined ? 0 : ramp( p, marks[ i ], marks[ i ] + 0.06 );
			cap.style.opacity = ( off + ( 1 - off ) * Math.max( up - down, 0 ) ).toFixed( 3 );
		} );
	};

	var measure = function () {
		var vh = window.innerHeight;
		scenes.forEach( function ( scene ) {
			var box = scene.el.getBoundingClientRect();
			var p;
			if ( scene.mode === 'pin' ) {
				p = -box.top / Math.max( box.height - vh, 1 );
			} else if ( scene.mode === 'leave' ) {
				p = -box.top / Math.max( box.height, 1 );
			} else {
				p = ( vh * 0.88 - box.top ) / ( box.height + vh * 0.4 );
			}
			p = Math.round( Math.min( Math.max( p, 0 ), 1 ) * 1000 ) / 1000;
			var key = p + '/' + window.innerWidth + '/' + vh;
			if ( key !== scene.last ) {
				scene.last = key;
				scene.el.style.setProperty( '--p', p );
				if ( scene.mode === 'pin' ) paintScene( scene.el, p );
			}
		} );
	};

	// A step track that is already on screen when the page opens (Get
	// Started) would sit half-filled, and on a short page could never be
	// scrolled to the end. Fill it on a timer instead, once.
	scenes = scenes.filter( function ( scene ) {
		if ( scene.mode !== 'through' || ! scene.el.matches( '.lpd-process .wp-block-columns' ) ) return true;
		if ( window.scrollY > 8 || scene.el.getBoundingClientRect().top > window.innerHeight * 0.8 ) return true;
		var began = null;
		var fill = function ( now ) {
			if ( began === null ) began = now + 500;
			var t = Math.min( Math.max( ( now - began ) / 1800, 0 ), 1 );
			scene.el.style.setProperty( '--p', ( 1 - Math.pow( 1 - t, 3 ) ).toFixed( 3 ) );
			if ( t < 1 ) window.requestAnimationFrame( fill );
		};
		scene.el.style.setProperty( '--p', 0 );
		window.requestAnimationFrame( fill );
		return false;
	} );

	var ticking = false;
	var onFrame = function () {
		ticking = false;
		if ( header ) header.classList.toggle( 'is-scrolled', window.scrollY > 24 );
		// No point decoding the hero film once it is off screen.
		if ( heroVideo ) {
			var gone = window.scrollY > window.innerHeight;
			if ( gone && ! heroVideo.paused ) heroVideo.pause();
			if ( ! gone && heroVideo.paused ) heroVideo.play().catch( function () {} );
		}
		measure();
	};
	var queue = function () {
		if ( ticking ) return;
		ticking = true;
		window.requestAnimationFrame( onFrame );
	};
	window.addEventListener( 'scroll', queue, { passive: true } );
	window.addEventListener( 'resize', queue, { passive: true } );
	window.addEventListener( 'load', queue );
	onFrame();

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
	 * Reviews: when there is more than one, show one at a time with
	 * previous / next arrows (and a swipe on touch screens). The arrows
	 * are added here, so the page content stays plain blocks.
	 * ------------------------------------------------------------- */
	document.querySelectorAll( '.lpd-reviews' ).forEach( function ( wrap, wrapIndex ) {
		var slides = Array.prototype.filter.call( wrap.children, function ( el ) {
			return el.classList.contains( 'lpd-proof-grid' );
		} );
		if ( slides.length < 2 ) return;

		var current = 0;
		var nav = document.createElement( 'div' );
		nav.className = 'lpd-reviews-nav';
		var count = document.createElement( 'p' );
		count.className = 'lpd-reviews-count';
		count.setAttribute( 'aria-live', 'polite' );

		var show = function ( next, dir ) {
			current = ( next + slides.length ) % slides.length;
			wrap.style.setProperty( '--dir', dir );
			slides.forEach( function ( slide, i ) {
				var on = i === current;
				slide.classList.toggle( 'is-active', on );
				slide.setAttribute( 'aria-hidden', on ? 'false' : 'true' );
				if ( 'inert' in slide ) slide.inert = ! on;
			} );
			count.textContent = 'Review ' + ( current + 1 ) + ' of ' + slides.length;
		};

		var arrow = function ( label, dir, path ) {
			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'lpd-reviews-btn';
			btn.setAttribute( 'aria-label', label );
			btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' + path + '"/></svg>';
			btn.addEventListener( 'click', function () { show( current + dir, dir ); } );
			nav.appendChild( btn );
		};
		arrow( 'Previous review', -1, 'M19 12H5M11 6l-6 6 6 6' );
		arrow( 'Next review', 1, 'M5 12h14M13 6l6 6-6 6' );
		nav.appendChild( count );

		slides.forEach( function ( slide, i ) {
			slide.setAttribute( 'role', 'group' );
			slide.setAttribute( 'aria-roledescription', 'review' );
			slide.setAttribute( 'aria-label', ( i + 1 ) + ' of ' + slides.length );
		} );
		wrap.setAttribute( 'role', 'region' );
		wrap.setAttribute( 'aria-roledescription', 'carousel' );
		wrap.setAttribute( 'aria-label', 'Client reviews' );
		wrap.classList.add( 'is-carousel' );
		wrap.parentNode.insertBefore( nav, wrap.nextSibling );
		show( 0, 1 );

		// Swipe sideways to change review; vertical scrolling is untouched.
		var startX = null, startY = null;
		wrap.addEventListener( 'touchstart', function ( e ) {
			startX = e.touches[0].clientX;
			startY = e.touches[0].clientY;
		}, { passive: true } );
		wrap.addEventListener( 'touchend', function ( e ) {
			if ( startX === null ) return;
			var dx = e.changedTouches[0].clientX - startX;
			var dy = e.changedTouches[0].clientY - startY;
			startX = null;
			if ( Math.abs( dx ) < 48 || Math.abs( dx ) < Math.abs( dy ) * 1.5 ) return;
			show( current + ( dx < 0 ? 1 : -1 ), dx < 0 ? 1 : -1 );
		}, { passive: true } );
	} );

	/* ---------------------------------------------------------------
	 * Plan helper: two plain questions above the web design plans, then
	 * it points at one. Everything it says is read from the plan cards
	 * themselves (name, price, page limit, shop features), so editing a
	 * plan in WordPress keeps the helper right. It only appears when the
	 * cards carry page limits, so not on the colocation plans.
	 * ------------------------------------------------------------- */
	( function () {
		var section = document.querySelector( '.lpd-front-page .lpd-pricing' );
		var firstRow = section && section.querySelector( '.wp-block-columns' );
		if ( ! firstRow ) return;

		var text = function ( card, selector ) {
			var el = card.querySelector( selector );
			return el ? el.textContent.replace( /\s+/g, ' ' ).trim() : '';
		};
		var sized = [], shop = null, custom = null;
		section.querySelectorAll( '.lpd-pricing-card' ).forEach( function ( card ) {
			var features = Array.prototype.map.call( card.querySelectorAll( '.lpd-pricing-feature' ), function ( f ) { return f.textContent; } ).join( ' | ' );
			var plan = { card: card, name: text( card, '.lpd-pricing-name' ), price: text( card, '.lpd-pricing-price' ), sub: text( card, '.lpd-pricing-subprice' ) };
			if ( ! features ) { custom = custom || plan; return; }
			if ( /shop|checkout/i.test( features ) ) { shop = shop || plan; return; }
			var pages = features.match( /(\d+)\s+pages?\b/i );
			if ( pages && plan.name ) { plan.pages = parseInt( pages[1], 10 ); sized.push( plan ); }
		} );
		if ( sized.length < 2 ) return;
		sized.sort( function ( a, b ) { return a.pages - b.pages; } );

		var make = function ( tag, className, content ) {
			var el = document.createElement( tag );
			if ( className ) el.className = className;
			if ( content ) el.textContent = content;
			return el;
		};
		var helper = make( 'div', 'lpd-plan-helper' );
		var intro = make( 'div', 'lpd-plan-helper-intro' );
		intro.appendChild( make( 'h3', '', 'Not Sure Which Plan?' ) );
		intro.appendChild( make( 'p', '', 'Two quick questions and we will point you to one.' ) );
		var stage = make( 'div', 'lpd-plan-helper-stage' );
		stage.setAttribute( 'aria-live', 'polite' );
		helper.appendChild( intro );
		helper.appendChild( stage );

		var clearMark = function () {
			section.querySelectorAll( '.lpd-pricing-card.is-suggested' ).forEach( function ( c ) { c.classList.remove( 'is-suggested' ); } );
		};
		var ask = function ( question, options ) {
			stage.textContent = '';
			stage.appendChild( make( 'p', 'lpd-plan-helper-q', question ) );
			var row = make( 'div', 'lpd-plan-helper-options' );
			options.forEach( function ( option ) {
				var btn = make( 'button', 'lpd-plan-helper-option', option.label );
				btn.type = 'button';
				btn.addEventListener( 'click', option.go );
				row.appendChild( btn );
			} );
			stage.appendChild( row );
		};
		var suggest = function ( plan ) {
			clearMark();
			stage.textContent = '';
			var line = make( 'p', 'lpd-plan-helper-q' );
			line.appendChild( make( 'strong', '', plan.name || plan.price ) );
			line.appendChild( document.createTextNode( plan.pages || plan === shop ? ' looks like the right fit.' : ': tell us what you need and we will put something together.' ) );
			stage.appendChild( line );
			if ( plan !== custom && plan.price ) {
				stage.appendChild( make( 'p', 'lpd-plan-helper-price', plan.price + ( plan.sub ? ', ' + plan.sub.charAt( 0 ).toLowerCase() + plan.sub.slice( 1 ) : '' ) ) );
			}
			var row = make( 'div', 'lpd-plan-helper-options' );
			var show = make( 'button', 'lpd-plan-helper-option is-primary', 'Show Me' );
			show.type = 'button';
			show.addEventListener( 'click', function () {
				plan.card.classList.add( 'is-suggested' );
				plan.card.scrollIntoView( { behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' } );
			} );
			var again = make( 'button', 'lpd-plan-helper-option', 'Start Again' );
			again.type = 'button';
			again.addEventListener( 'click', function () { clearMark(); start(); } );
			row.appendChild( show );
			row.appendChild( again );
			stage.appendChild( row );
			plan.card.classList.add( 'is-suggested' );
		};
		var askPages = function () {
			var options = sized.map( function ( plan ) {
				return { label: plan.pages === 1 ? 'Just one' : 'Up to ' + plan.pages, go: function () { suggest( plan ); } };
			} );
			options.push( { label: custom ? 'More, or not sure' : 'More', go: function () { suggest( custom || sized[ sized.length - 1 ] ); } } );
			ask( 'How many pages do you need?', options );
		};
		var start = function () {
			if ( ! shop ) { askPages(); return; }
			ask( 'Do you want to sell products online?', [
				{ label: 'Yes', go: function () { suggest( shop ); } },
				{ label: 'No', go: askPages }
			] );
		};
		start();
		firstRow.parentNode.insertBefore( helper, firstRow );
	} )();

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
