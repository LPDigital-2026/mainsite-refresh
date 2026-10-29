# Impeccable audit: Lily Paw Digital theme v3.0

Audited 29 Oct 2026, before the v3.1 fixes.

- **Target:** `lilypaw-digital-editable/` (style.css, header.php, footer.php, js/), rendered with the real page content from `page-content/copy-all-page-content.html` through a local WordPress stub.
- **Detector:** `impeccable detect` was run on all six pages at 1280×800 and on the homepage at 390×844. It used headless Chromium (Playwright build, run with `--no-sandbox`).
- **Not tested:** real devices, a screen reader, the live form plugin, and the external status-badge iframe.

## Audit Health Score

| # | Dimension | Score | Key finding |
|---|-----------|-------|-------------|
| 1 | Accessibility | 2 | White text on the pink/orange gradient is 2.6–2.9:1 on every primary button, badge, step number and the CTA banner (WCAG AA needs 4.5:1). |
| 2 | Performance | 3 | The scroll-reveal setup alternates layout reads and class writes for every card, which forces a reflow each time; the fonts aren't preloaded. |
| 3 | Theming | 3 | Tokens are defined and mostly used. The drawn service headers and a few rgba whites are hard-coded. There is no dark mode, which is acceptable for this brand. |
| 4 | Responsive | 3 | No overflow at 390px and the breakpoints are sound. Hover styles aren't gated (they stick on touch), there's no tap-highlight reset, and the hero uses `vh`. |
| 5 | Implementation integrity | 2 | 9 kickers above headings, 4 gradient-text uses, coloured glows, 5 radial spotlights, a purple gradient card, a decorative dot grid, and border-plus-shadow "ghost" cards. |
| **Total** | | **13/20** | **Acceptable (significant work needed)** |

## Implementation Integrity Verdict

**Fail, but the problems are concentrated and fixable.** The core system is coherent and specific to the brand: the navy, the paw gradient, self-hosted Sora/Inter/Plex Mono, the drawn service headers and the animated cats. The failures are decoration added in the v3.0 refresh:

- the section kickers;
- gradient numerals;
- pink glow shadows;
- radial "spotlight" hazes on four surfaces;
- the purple gradient behind the featured plan;
- a dot-grid texture on the web-design card.

These read as generic generated-UI habits, not this brand. Removing them makes the site look more like itself.

Two findings are left as they are, as **deliberate exceptions** inherited from the existing brand:

- **The cream page background (`#fbf8f4`):** the incumbent `--paper` token, from before the refresh.
- **Inter as the body face:** the incumbent, self-hosted font.

One finding is a **false positive**: `side-tab` on `.site-footer::before` is a full-width 3px top rule across the footer, not a stripe on one side of a card.

## Executive Summary

- **Score:** 13/20 (Acceptable).
- **Issues:** P0 0 · P1 3 · P2 8 · P3 4.
- **Top issues:**
  1. **[P1]** White-on-gradient contrast fails on every primary action.
  2. **[P1]** CTA banner body text is 2.6:1.
  3. **[P1]** The FAQ puts `role="button"` on the `<h3>` itself, which removes the heading from the outline and leaves no `aria-controls`.
  4. **[P2]** Generated-UI decoration: kickers, glows, spotlights and gradient text.
  5. **[P2]** Touch: hover styles stick after a tap, there's a tap-highlight flash, and there's no `touch-action`.

## Detailed Findings

### P1: Major

**[P1] Primary buttons fail contrast**
- **Location:** `style.css` `.btn-primary`, `.lpd-btn-primary`, `button[type=submit]`, `.wpforms-submit`.
- **Category:** Accessibility.
- **Impact:** White 15px bold text on `#f0529c`→`#fb7a3c` measures 3.3:1 at the pink end and 2.6:1 at the orange end. Every "Get Started" is hard to read for low-vision users and in sunlight.
- **WCAG:** 1.4.3 Contrast (Minimum).
- **Fix:** Use a deeper "ink" version of the brand gradient, `#c8327a`→`#c4461a`, for every surface that carries text (white measures 5.0:1). Keep the bright gradient for icons and rules.
- **Command:** `$impeccable colorize`

**[P1] CTA banner text contrast**
- **Location:** `.lpd-cta-banner`.
- **Category:** Accessibility.
- **Impact:** The 30-day free-trial offer, which is the main pitch, is 2.6:1.
- **WCAG:** 1.4.3.
- **Fix:** Same ink gradient, with solid white text.
- **Command:** `$impeccable colorize`

**[P1] FAQ accordion semantics**
- **Location:** `js/interactions.js` (FAQ block).
- **Category:** Accessibility.
- **Impact:** `role="button"` on the `h3` replaces its heading role, so screen-reader users lose the FAQ questions from the heading list. The toggle isn't linked to the panel it opens.
- **WCAG:** 1.3.1 Info and Relationships, 4.1.2 Name, Role, Value.
- **Fix:** Inject a real `<button aria-expanded aria-controls>` inside each `h3`, and give each answer an `id` plus `role="region"` labelled by the button.
- **Command:** `$impeccable harden`

### P2: Minor

**[P2] Kickers above every heading**
- **Location:** Home content (9 `lpd-eyebrow` paragraphs plus the hero pill).
- **Category:** Implementation integrity.
- **Impact:** Every section ends up with two titles. It's a recognisable generated-UI pattern, and impeccable bans it outright.
- **Fix:** Remove them from the content and drop the dead CSS.
- **Command:** `$impeccable distill`

**[P2] Gradient text on stats, quote mark and mobile-menu current item**
- **Location:** `.lpd-stat-number`, `.lpd-testimonial::before`, the mobile `.current-menu-item`.
- **Category:** Implementation integrity.
- **Impact:** Decorative, and it lowers legibility. The hero "Web Design" accent is incumbent brand and stays.
- **Fix:** Use solid navy or pink.
- **Command:** `$impeccable quieter`

**[P2] Coloured glows and radial spotlights**
- **Location:**
  - pink `box-shadow` on buttons, badge and step numbers;
  - radial hazes on the page hero, colocation band, featured card, footer, mobile menu and CTA.
- **Category:** Implementation integrity.
- **Fix:** Neutral offset shadows, and flat brand surfaces.
- **Command:** `$impeccable quieter`

**[P2] Purple gradient card backgrounds**
- **Location:** `.lpd-featured`, `.lpd-service-colo::before`.
- **Category:** Implementation integrity.
- **Fix:** Flat `--navy`.
- **Command:** `$impeccable quieter`

**[P2] Ghost cards and oversized radius**
- **Location:** `.lpd-card`, `.lpd-pricing-card`, `.lpd-service-card`, FAQ items.
- **Category:** Implementation integrity.
- **Impact:** A 1px border under a soft shadow is a double edge, and the 22px radius reads as bloated.
- **Fix:** Declare elevation once: a border at rest and a shadow only on lift. Radius 16px.
- **Command:** `$impeccable polish`

**[P2] Sticky hover and tap feel on touch**
- **Location:** ungated `:hover` on buttons, nav, FAQ, service links and footer; no `-webkit-tap-highlight-color`; no `touch-action`.
- **Category:** Responsive.
- **Fix:** Gate hover with `(hover: hover) and (pointer: fine)`, set a transparent tap highlight, `touch-action: manipulation`, and `:active` press states everywhere.
- **Command:** `$impeccable adapt`

**[P2] Scroll-reveal layout thrash**
- **Location:** `js/interactions.js` reveal setup.
- **Category:** Performance.
- **Impact:** Each card does `getBoundingClientRect()` then `classList.add()`, forcing about 40 synchronous reflows on load.
- **Fix:** Read all rects first, then write.
- **Command:** `$impeccable optimize`

**[P2] Identical reveal on every section**
- **Location:** `revealSelectors` in `js/interactions.js`.
- **Category:** Implementation integrity / motion.
- **Impact:** Every heading, card, FAQ row and page paragraph fades up the same way, so motion stops meaning anything. The stagger is 90ms; the standard is 30–80ms.
- **Fix:** Reveal only the card rows, with a 60ms stagger, and leave headings and prose still.
- **Command:** `$impeccable animate`

### P3: Polish

**[P3] "Most Popular" badge is 9.9px**
- **Location:** `.lpd-pricing-badge`.
- **Fix:** At least 11px.

**[P3] Our Work skips a heading level**
- **Location:** Our Work content.
- **Detail:** It goes `h1` → `h3`.
- **Fix:** Make the portfolio items `h2`.

**[P3] Status iframe and footer cats**
- **Location:** `footer.php`.
- **Detail:** The iframe has no `title`, and the decorative SVG cats aren't `aria-hidden`.

**[P3] Reading measure and menu motion**
- **Line length:** about 94 characters per line on About and Colocation; the target is 65–75ch.
- **Mobile menu:** opens in 0.6s; the standard for drawers is up to 0.5s.
- **Toggle easing:** the paw swap uses a bounce curve.

## Patterns and Systemic Issues

- **One gradient for two jobs.** The bright brand gradient is used both as decoration and behind text, and it can't carry white text. Splitting it into `--gradient` (decoration) and `--gradient-ink` (text surfaces) fixes contrast everywhere at once.
- **Decoration standing in for hierarchy.** Kickers, glows and spotlights were doing work that type size, weight and spacing already do.
- **Hover-first interaction.** Every interaction state was designed for a mouse. Touch users get sticky hovers and no press feedback.

## Positive Findings

- **Tokens:** a real token layer (`:root`) used across about 1,900 lines; very few raw values outside the drawn illustrations.
- **Reduced motion:** honoured everywhere, including pausing the SMIL cat animations from JS.
- **Focus:** a visible `:focus-visible` ring and a working skip link.
- **Mobile menu:** `aria-expanded` and Escape to close.
- **Fonts and video:** fonts are self-hosted with `font-display: swap`. The hero video is skipped on mobile, data-saver and reduced motion, with a poster fallback.
- **Overflow:** no horizontal overflow at 390px on any page.
- **Forms:** inputs are 16px, so iOS doesn't zoom on focus.

## Recommended Actions

1. **[P1] `$impeccable colorize`:** add an ink gradient for every text-bearing surface.
2. **[P1] `$impeccable harden`:** a real button and region for the FAQ; iframe title; `aria-hidden` cats; heading level on Our Work.
3. **[P2] `$impeccable quieter`:** remove glows, spotlights, gradient text, purple gradient fills and the dot grid.
4. **[P2] `$impeccable distill`:** remove the kickers from content and CSS.
5. **[P2] `$impeccable adapt`:** gate hovers, tap highlight, `touch-action`, `:active` everywhere, `svh` hero.
6. **[P2] `$impeccable optimize` / `animate`:** batch the reveal reads and writes; reveal card rows only, with a 60ms stagger; faster menu.
7. **[P3] `$impeccable polish`:** final pass over radius, elevation, badge size, measure and browser surfaces (caret, scrollbar, `accent-color`, tabular price numerals).

---

# After the fixes: theme v3.1

Re-audited on the same pages, viewports and detector.

| # | Dimension | Before | After | What changed |
|---|-----------|--------|-------|--------------|
| 1 | Accessibility | 2 | 3 | See below. |
| 2 | Performance | 3 | 3 | Batched reveal reads and writes; Sora 800 and Inter 400 preloaded; status iframe lazy-loaded. |
| 3 | Theming | 3 | 3 | Ink-gradient, button-shadow, drawer-easing and live-dot tokens; browser surfaces themed. |
| 4 | Responsive | 3 | 3 | See below. |
| 5 | Implementation integrity | 2 | 4 | See below. |
| **Total** | | **13/20** | **16/20** | **Good** |

**Accessibility**
- An ink gradient, with white text at 5:1 or better, on every button, badge, step number and the CTA.
- The FAQ is now a real `<button aria-expanded aria-controls>` inside each `h3`, with region panels. Checked by script: the heading stays an `h3`, and Enter toggles it.
- The status iframe has a title, the cats are `aria-hidden`, Our Work goes h1 → h2, and the badge and mobile stat labels are at least 11px.

**Responsive**
- Every hover style that moves or recolours is gated behind `(hover: hover) and (pointer: fine)`. The only ungated ones left change a link's colour or underline, which doesn't stick on touch.
- Transparent tap highlight, `touch-action: manipulation`, and `:active` press states on buttons, links, the FAQ and the menu toggle.
- `svh` hero and `theme-color` meta.

**Implementation integrity**
- Kickers, coloured glows, radial spotlights, purple gradient fills, the dot grid and gradient text on stats, the quote glyph and the menu are all gone.
- One elevation per surface, card radius 16px, and the reading measure capped at 640px.
- **Detector:** 36 → 3 findings on the homepage, and 4–10 → 2 on the other pages. The ones left are the documented brand exceptions: Inter, the cream `--paper`, and the hero's "Web Design" gradient accent.

**Motion, following review-animations and apple-design**
- Only rows of cards reveal, over 0.5s, with a 60ms stagger. Headings, prose and the FAQ no longer animate in.
- The mobile menu uses the iOS drawer curve: 0.45s in, 0.3s out, with items staggered from 0.12s.
- The menu-toggle paw no longer bounces.
- Under `prefers-reduced-transparency`, the header and the hero's secondary button drop the blur and go solid.

**Still not verified:** a real phone (sticky hover, tap feel, safe areas), a screen reader, the live form plugin's markup, and Lighthouse field data.
