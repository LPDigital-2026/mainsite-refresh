# Lily Paw Digital — design guide

This is the full brief for anyone (person or AI assistant) who works on this
theme after the sessions that built it. Read it before changing anything
visual. It records what the design is, why each decision was made, what the
owner has already rejected, and how to check work before showing it.

If a rule here conflicts with something that "looks nicer", the rule wins
unless the owner asks otherwise: most of these rules exist because the
alternative was tried and sent back.

- [1. Who this is for and what they want](#1-who-this-is-for-and-what-they-want)
- [2. The design in one paragraph](#2-the-design-in-one-paragraph)
- [3. Things the owner has rejected](#3-things-the-owner-has-rejected)
- [4. Colour](#4-colour)
- [5. Type](#5-type)
- [6. Spacing](#6-spacing)
- [7. Layout](#7-layout)
- [8. Components](#8-components)
- [9. Imagery](#9-imagery)
- [10. Motion](#10-motion)
- [11. Copy](#11-copy)
- [12. Accessibility and phones](#12-accessibility-and-phones)
- [13. How the theme is put together](#13-how-the-theme-is-put-together)
- [14. WordPress traps that have already bitten](#14-wordpress-traps-that-have-already-bitten)
- [15. How to make a change](#15-how-to-make-a-change)
- [16. Recipes](#16-recipes)
- [17. Open questions](#17-open-questions)

---

## 1. Who this is for and what they want

Lily Paw Digital is a small North East England business: web design and
hosting for local businesses and clubs, and colocation in a Newcastle data
centre (Reliance House). The site is `lilypaw.digital` (older address
`lilypawdigital.xyz` redirects to it). Customers are small-business owners
and club volunteers, not technical buyers, plus a smaller technical audience
for colocation.

What the owner has asked for, in their own words across the project:

- "sexy, professional, and premium **without the AI look**"
- "good animations", specifically Apple-style full-page scroll scenes
- Sora as the typeface: "it's my brand"
- full-width pages, "not just centred"
- everything should work "on phone and computer"
- spacing that is correct everywhere (raised three separate times — treat
  any spacing inconsistency as a bug, not a nit)

Reference the owner likes: <https://stacklayer.host> (dark, confident,
large type, real product imagery, scroll-driven sections).

The owner looks at the result on a real phone and a real computer and judges
it by feel. They do not read code. Report in plain English, say exactly what
was and was not tested, and never claim something works on their site unless
it was verified on their site.

## 2. The design in one paragraph

A dark brand-purple page with large, tightly-set Sora headings, where
sections alternate between "night" (purple) and "light" (pale lilac)
surfaces. Colour is restrained: solid paw pink for buttons, and the logo's
pink-to-orange gradient reserved for icons, progress lines and one loud
offer panel. Content sits in rounded panels on a single spacing scale.
Pictures are always real: the client sites the business built and the actual
data hall. Motion answers the scroll rather than decorating it: one entrance
on the hero, then a sentence that lights up word by word, a process track
that fills, and one pinned colocation scene. The footer has two cats whose
eyes follow the pointer, named for Lily, the cat the business is named after.

## 3. Things the owner has rejected

These were all tried. Do not bring them back.

| Rejected | What happened |
|---|---|
| Centred, narrow inner pages | "make the pages full ones not just centred" |
| Gradient headline text, glowing blobs, glassmorphism cards, mono-spaced "eyebrow" labels above headings, emoji icons, identical card grids with a fade-in on every card | "make it look less AI" |
| A hero that stays pinned and dims while the page slides over it ("curtain") | "the new animation from hero to the rest of the page is horrible" |
| Beige/paper light sections, near-black navy | "colours or fonts feel wrong" — now lilac and brand purple |
| Text-on-hairlines layouts with a sticky heading in a half-empty left column | "too plain and text-heavy", "layout feels unbalanced" |
| Scroll effects built on CSS `animation-timeline` | did not run in their browsers; "the whole page animation doesn't work" |
| Screenshots with a cookie banner in them, or cropped through a button | "weird" |

General "AI look" tells to keep out: purple-to-blue gradients on everything,
three equal feature cards with icon-title-text, centred hero with a pill
badge above it, every element fading up on scroll, decorative sparkles,
stock illustrations, vague superlatives in copy.

One exception, asked for by the owner: the services line "Web Design •
Hosting • Cybersecurity • Communications" above the hero headline. It is
set in Sora at reading size (about 1.35rem, weight 500), in normal case
with pink separators, never as a small uppercase or monospaced label.
Keep it that way, and don't add similar lines above other headings.

## 4. Colour

All colours are variables at the top of `lilypawdigital/style.css`. Never
write a hex value anywhere else in the stylesheet.

| Token | Value | Use |
|---|---|---|
| `--night` | `#1d0e38` | Page colour, dark sections, text on pink |
| `--night-deep` | `#140928` | Footer, image placeholders |
| `--navy` | `#2a1650` | Panels on night; the Process and Stats sections |
| `--navy-hi` | `#351d63` | Panels on navy; hover on a night panel that is a link |
| `--light` | `#f5f1fb` | Light sections (lilac white) |
| `--white` | `#ffffff` | Panels on light |
| `--ink` | `#1d0e38` | Text on light |
| `--cream` | `#fbf7f2` | Text on night (warm, not pure white) |
| `--pink` | `#f0529c` | Primary buttons, focus ring, selection |
| `--pink-hi` | `#ff76b4` | Hover on pink, focus outline |
| `--pink-ink` | `#b3276b` | Pink text and links on light (passes contrast) |
| `--orange` | `#fb7a3c` | Only inside the paw gradient |
| `--paw` | `linear-gradient(115deg, pink, #f6677a, orange)` | Icons, progress lines, "Most Popular" badge, the offer panel |

Rules:

- **The gradient is a surface, never text.** No gradient headings.
- **One loud panel per page.** `.lpd-cta-banner` is the only large area in
  the full gradient. Text on it is `--night`, and its button is night-coloured.
- **Buttons are solid pink with night text**, not gradient. Secondary buttons
  are outlined in the current text colour.
- Pink text on night uses `--accent-text` (`#ff8fc2`); on light it uses
  `--pink-ink`. Don't use `--pink` itself for body-size text on light: it
  fails contrast.

### Surfaces

Components never name a colour directly. They read contextual variables:
`--bg`, `--panel`, `--fg`, `--fg-dim`, `--rule`, `--rule-strong`,
`--accent-text`, `--field-bg`, `--field-border`. A section switches surface
by redeclaring those once (see the "Surfaces" block in `style.css`). This is
why the same pricing card works on lilac and, when `.lpd-featured`, flips to
night inside it.

Current assignment:

- **Night** (default): hero, statement, services, features, colocation
  scene, "How We're Different", testimonial, inner-page bands (`.lpd-band`).
- **Navy** (a step lighter): How It Works, stats.
- **Light**: pricing, the offer, FAQ on the home page, About, Contact,
  `.lpd-band-alt`, `.lpd-form-card`.

To add a section, give it one of these by adding its class to the right
selector list in "Surfaces". Do not invent a fourth surface.

## 5. Type

Sora everywhere, one self-hosted variable file
(`assets/fonts/sora-latin-wght-normal.woff2`, weights 100–800), preloaded in
`functions.php`. No second typeface, no mono labels.

| Role | Size | Weight | Tracking | Line height |
|---|---|---|---|---|
| Hero services line | `clamp(1.05rem, 1.55vw, 1.35rem)` | 500 | -0.02em | 1.3 |
| Hero h1 | `clamp(2.6rem, 6.3vw, 5.75rem)` | 600 | -0.045em | 0.98 |
| Page title (inner) | `clamp(2.6rem, 7vw, 5.75rem)` | 600 | -0.05em | 0.98 |
| Section h2 | `clamp(2rem, 4.4vw, 3.5rem)` | 600 | -0.045em | 1.02 |
| Statement | `clamp(1.7rem, 4.2vw, 3.6rem)` | 500 | -0.04em | 1.12 |
| Card h3 | `1.2rem`–`2.4rem` by component | 600 | -0.035em | 1.0–1.15 |
| Big numerals (stats, steps, prices) | up to `6rem` | 600 | -0.06em | 0.9–1.0, tabular figures |
| Lead paragraph | `clamp(1.02rem, 1.3vw, 1.15rem)` | 400 | body | 1.55 |
| Body | `1.0625rem` | 400 | -0.011em | 1.65 |
| Small print | `0.86`–`0.92rem` | 400 | body | 1.45 |

Rules:

- Headings are large and tight; body is comfortable and plain. The contrast
  between the two is most of the "premium" feel. Don't shrink headings to fit
  more in — cut words instead.
- Never go tighter than **-0.06em** (numerals only) or **-0.05em** (words).
- Maximum display size is about **6rem**. Bigger reads as a template.
- Headings use `text-wrap: balance`; paragraphs `text-wrap: pretty`.
- Cap paragraph width (`max-width: 44ch`–`60ch`). Full-width paragraphs on
  a 1240px column are unreadable.
- Title Case for headings is the owner's existing style ("Web Design &
  Hosting", "How It Works"). Keep it.
- Form inputs are **16px** minimum or iPhones zoom the page on focus.

## 6. Spacing

This is the area the owner has complained about most. There is **one
scale**, and nothing sets a one-off distance.

| Token | Value | Meaning |
|---|---|---|
| `--section` | `clamp(80px, 10vw, 152px)` | Padding above and below every section |
| `--head` | `clamp(32px, 4.4vw, 60px)` | Section heading to its content |
| `--gap` | `clamp(12px, 1.4vw, 20px)` | Between cards in any grid |
| `--pad` | `clamp(22px, 2.6vw, 36px)` | Inside any panel or card |
| `--gutter` | `clamp(20px, 4.4vw, 64px)` | Page side margin |
| `--wrap` | `1240px` | Maximum content width |
| `--bar` | `64px` | Header height |
| `--radius` / `--radius-sm` | `24px` / `10px` | Panels / inputs and small frames |

How it works:

- Every section is a full-width element containing `.lpd-inner`, which
  provides the gutters and `--section` top and bottom padding.
- **Two neighbouring sections on the same surface share one gap.** The
  second one's top padding is removed (see "Section rhythm" in `style.css`
  and the matching rule for `.lpd-band`). So the distance between any two
  sections' content is always exactly `--section` when the background does
  not change, and `2 × --section` (one each side of the colour edge) when
  it does.
- The hero's fact rail already leaves space below itself, so the statement
  after it subtracts that amount. Likewise the first band after an inner
  page title uses a smaller top padding.
- Larger internal gaps (split layouts, two-column About) use
  `clamp(28px, 5vw, 80px)`. That is the only other distance in use.
- WordPress gives `.wp-block-columns`, `.wp-block-image`,
  `.wp-block-buttons` and `.wp-block-gallery` margins of their own. These
  are zeroed inside `.lpd-front-page` and `.lpd-bands`; every gap is set by
  our layout rules.

**When adding a section:** add its class to the correct surface list *and*
to the matching group in the "Section rhythm" rule, or its gap will be
double.

**How to check:** `node tools/preview/gaps.js site 1440 900` prints the
measured gap above every section on every page. At 1440px every same-surface
gap should read **144** and every surface change **288**; at 390px, **80**
and **160**. Expected exceptions at 1440px: **56** (hero text to its fact
rail), **116** (inner page title to first band), **242** (into the pinned
colocation stage, which has its own header clearance) and a figure in the
thousands straight after the colocation scene (its pinned scroll length).
Any other number is a bug.

## 7. Layout

- Content column is 1240px with fluid gutters. Sections are full-bleed;
  only their contents are constrained.
- Section heading: `h2` with an optional one-line intro beneath, left
  aligned, sitting `--head` above a **full-width** grid. Do not put the
  heading in its own column beside the content, except in the three places
  where the content is genuinely a narrow list or a form: FAQ, About,
  Contact (5fr / 7fr).
- Grids are CSS grid on `.wp-block-columns`, with explicit column counts
  per component and `minmax(0, 1fr)` tracks so long words can't blow them
  out.
- Don't make every grid the same. Services is 2 large media panels plus a
  full-width line; features is 4 tiles; differences is 2×2; steps is 4
  columns on a track; stats is 3 numeral tiles; pricing is 4 + 2. That
  variety is deliberate.
- Breakpoints in use: **1100 / 1080** (4-up grids go 2-up, nav tightens),
  **900** (two-column layouts stack, mobile menu, scene switches to phone
  layout), **760 / 640** (grids go single column), **400** (logo shrinks).
- Left-align by default, with one exception: on the home page, headings of
  full-width sections (What We Do, How We're Different, Testimonials, How
  It Works, Pricing, website features) are centred, with a larger subhead
  (up to 1.35rem), in the manner of Apple's product pages. Two-column
  sections (FAQ, About, Contact) and inner pages keep left-aligned
  headings.

## 8. Components

Class names are a contract with the page content stored in the WordPress
database. **Never rename or remove an `lpd-*` class**: pages that were
edited by hand keep the old markup forever.

| Component | Class | Notes |
|---|---|---|
| Header | `.site-header` | 64px, fixed, transparent over the hero; after 24px of scrolling it becomes a translucent purple material (72% opacity, 20px blur, no dividing line). Blur only ≥901px (see §14). Links are 0.88rem regular weight; the current page is brighter, never underlined. Client Login is a small filled pill. |
| Buttons | `.wp-block-button.lpd-btn-primary` / `.lpd-btn-secondary`, `.btn` | Pill, 50px tall, press scales to 0.97. |
| Hero | `.lpd-hero`, `.lpd-hero-heading`, `.lpd-hero-text`, `.lpd-hero-actions`, `.lpd-hero-note`, `.lpd-rail` | Bottom-aligned. Left: the services line "Web Design • Hosting • Cybersecurity • Communications" (`.lpd-hero-kicker`; JS keeps each item on one line, colours the separators pink and keeps each separator with the item before it), then the headline "Capable. Connected. Considered.", one word to a line, each full stop pink (`.lpd-dot`, added by JS, which also splits it under reduced motion). Right: text and buttons, with the buttons' bottom edge level with the headline's. Three facts on a rail below (on phones, just their titles as a short list). The price line under the buttons (`.lpd-hero-note`) was removed at the owner's request; its styles remain only for older content. Entrance: the services rise in one by one, then the headline words (140ms apart). On screens under 900px the headline is set larger, up to 4.5rem. |
| Statement | `.lpd-statement`, `.lpd-statement-text` | One sentence, set large. |
| Section heading | `.lpd-section-heading` | h2 + optional p. |
| Service panel | `.lpd-service-card` + `.lpd-service-web` / `.lpd-service-colo` / `.lpd-service-more` | Picture on top (CSS background), title, text, price line, outline link. |
| Service tile | `.lpd-service-grid` > `.lpd-service-tile` (+ `.lpd-service-<name>` for its icon), `.lpd-service-sub`, `.lpd-service-list`, `.lpd-service-link`; `.lpd-service-tile-more` for the dashed "Something Else?" tile, always last | Further services under the two picture panels in "What We Do". The grid takes any number, about three to a row. Each tile: gradient icon, title, the owner's one-line summary, ticked list, link to Contact. No prices on any service panel or tile. A service that isn't live yet adds `.lpd-service-soon` and a `.lpd-service-badge` paragraph ("Coming soon", top right), has no list until the owner supplies one, and links "Register your interest" to Contact. When it launches, remove the badge and class and add its list. |
| Tile | `.lpd-card`; icon via `.lpd-why-*` or position in `.lpd-features` | Gradient icon top-left, generous space, then title and text. |
| Steps | `.lpd-process`, `.lpd-step`, `.lpd-step-number`; `.lpd-steps-3` for three | Track fills with scroll; vertical on phones. |
| Stats | `.lpd-stat`, `.lpd-stat-number`, `.lpd-stat-label` | Count up once. Content writes "+15"; JS shows "15+". |
| Pricing | `.lpd-pricing-card` (+ `.lpd-featured`), `-badge`, `-name`, `-domain`, `-price`, `-subprice`, `-desc`, `-feature`; `.lpd-colocation-pricing` | Buttons pinned to the bottom so they line up. A card with no features is the dashed "Custom" card. |
| Plan helper | `.lpd-plan-helper` (built by JS), `.lpd-pricing-card.is-suggested` | Dashed panel above the home page plans: "sell online?" then "how many pages?", then it names a plan and outlines its card. All names, prices and page limits are read from the cards, so plan edits in WordPress flow through. It needs each plan to keep a feature like "Up to 8 pages" and the shop plan to mention "shop" or "checkout". Appears only when at least two cards carry page limits. |
| Offer | `.lpd-cta-section` > `.lpd-cta-banner` | The one gradient panel. |
| FAQ | `.lpd-faq`, `.lpd-faq-list`, `.lpd-faq-item` (h3 + p) | JS turns each h3 into a real button and animates to measured height. |
| Colocation scene | `.lpd-scene`, `.lpd-scene-stage`, `.lpd-scene-photos`, `.lpd-scene-photo`, `.lpd-scene-copy`, `.lpd-scene-intro`, `.lpd-scene-caps`, `.lpd-scene-cap`, `.lpd-colo-price` | See §10. |
| Testimonial + work | `.lpd-proof`, `.lpd-reviews`, `.lpd-proof-grid`, `.lpd-testimonial*`, `.lpd-shots`, `.lpd-shot-desktop`, `.lpd-shot-phone` | Each `.lpd-proof-grid` inside `.lpd-reviews` is one review: quote beside the site it is about. With two or more, JS shows one at a time and adds previous/next arrows, a counter and swipe (`.is-carousel`, `.lpd-reviews-nav`, `.lpd-reviews-btn`). The first one in the content is the main review. All share one grid cell so the section height never changes. |
| Inner-page band | `.lpd-band`, `.lpd-band-alt`, `.lpd-split` (`-top`, `-narrow`), `.lpd-grid-2`, `.lpd-grid-3`, `.lpd-lead`, `.lpd-prose`, `.lpd-checklist`, `.lpd-form-card`, `.lpd-pointer`, `.lpd-photo`, `.lpd-alt-contact`, `.lpd-colo-gallery` | A page whose content contains `lpd-band` renders full width (`page.php`); otherwise it falls back to a single reading column. |
| Specification | `.lpd-figures` > `.lpd-figure` (`-number`, `-label`); `.lpd-spec` > `.lpd-spec-row` (h3 + list) | Colocation page. Four headline figures, then labelled rows of ticked facts. Figures do not count up. |
| Page not found | `404.php`, `.lpd-404-links` | Same title header as any page, the owner's sentence as a lead, then four link panels. |
| Legacy | `.lpd-colo-teaser`, `.lpd-page-content` | Styled so old content still looks right. Don't use in new content. |
| Footer | `.site-footer`, `.lpd-footer-cats`, `.lpd-cat-eye` | Cats stay. |

Component rules:

- Panels are a flat fill (`--panel`) with `--radius`. **No borders plus
  shadows plus gradients on one card.** Shadows are used once: under the
  work screenshots.
- A thin outline instead of a fill (`box-shadow: inset 0 0 0 1px
  var(--rule-strong)`, transparent background) means "this one is
  different / not a product": the Custom plan, the plan helper, the
  pricing pointer, "Something Else?". No dashed borders anywhere.
- No hairlines inside cards; separate content with space. Links inside
  service panels and tiles are accent-coloured text with a chevron
  ("See web design ›"), not outlined buttons.
- Icons are the SVGs in `assets/icons/`, drawn as CSS masks filled with the
  paw gradient. 34px on home tiles, 30px on inner pages. Add a new icon by
  adding the SVG and one `--icon` line; don't use an icon font or emoji.
- Hover effects are wrapped in `@media (hover: hover) and (pointer: fine)`
  so phones never get stuck hover states.

## 9. Imagery

**Real pictures only.** No stock photos, no illustrations, no AI images.

| File | What |
|---|---|
| `assets/images/colocation/colocation-1/2/3.jpg` | The actual data hall, cable trays, fire suppression |
| `assets/images/work/ryton-singers-desktop.jpg`, `ryton-singers-phone.jpg` | rytonsingers.org.uk — Web Design service panel, and beside Joanna T's review (the main one) |
| `assets/images/work/ryton-cc-desktop.jpg`, `ryton-cc-phone.jpg` | rytoncc.org.uk — beside Amanda's testimonial and on Our Work |
| `assets/images/share.jpg` | 1200×630 link-preview image (logo, the three-word headline, services). Source page: `.preview/share/index.html` is not committed; rebuild it from this description if the headline changes. `lpd_share_meta()` in `functions.php` prints it with a title and description unless an SEO plugin is active. Regenerate it if the headline or logo changes. |
| `assets/images/hero-tyne.jpg`, `assets/video/hero-tyne.mp4` | Hero film: an aerial pull-back over the Tyne bridges (Pexels video 31183278, free licence, no credit needed), first 11 seconds played forward then back (22-second loop) so it loops without a jump, 1280×720, 24fps, no sound, H.264 MP4 only (about 1.8 MB; every current browser plays it, and a WebM came out larger). Skipped on phones, data saver and reduced motion; the still is shown instead. Give replacement media a new file name so caches can't serve the old one. |

Rules for client-site screenshots:

- Dismiss the cookie banner before capturing, and hide any floating
  "manage consent" tab.
- The top of the page must be complete: logo, navigation, headline and its
  buttons all inside the frame. If the buttons fall off the bottom, capture
  with a taller window (Ryton CC needed 1440×1000).
- Desktop at 1440 wide; phone at 390×844. Save as JPEG, quality ~82.
- The frame's `aspect-ratio` in CSS must equal the image's, so nothing is
  cropped (`.lpd-shot-desktop` takes its image's own height, so give the `<img>`
  `width` and `height` attributes; `.lpd-shot-phone` is 390/844).
- **Keep a testimonial beside the site it is about.** Joanna T's review is
  of Ryton Singers, Amanda's is of the cricket club; never cross them.
- The Ryton Singers project description on Our Work was read off the site's
  navigation and then confirmed by the owner.
- Reviews are quoted word for word. The one agreed exception: Joanna T's
  review said "Lou Paw Digital" and the owner chose to correct it to
  "Lily Paw Digital".

## 10. Motion

Principle: **motion answers the visitor**. One authored entrance, then
things respond to scrolling. No element fades in just because it arrived.

What exists:

1. **Hero entrance** (once, on load): headline rises word by word out of
   its own line box (55ms stagger, 0.9s), then text, buttons, note and the
   rail follow.
2. **Hero film** drifts slightly slower than the page.
3. **Statement**: words go from 30% to full opacity in order as the
   sentence travels up the screen.
4. **How It Works**: the gradient track fills left to right (top to bottom
   on phones) and each numeral comes up to strength as the track reaches it.
   When the steps are already on screen as the page opens (Get Started),
   the track fills on a timer instead, once, so it never sits half-lit.
   Inner page titles sit on the same track, drawn once on load (hidden on
   a page that opens straight onto the steps).
5. **Colocation scene**: the section is 320vh tall and its stage pins to
   the screen. Photo 2 wipes up over photo 1 around a third of the way,
   photo 3 around two thirds, each settling from a slight zoom; the matching
   fact comes to full strength and a gradient bar tracks progress. On phones
   the photo sits on top and one fact shows at a time.
6. Stats count up once. Inner-page photographs unveil once. FAQ answers
   open to their measured height. Pages cross-fade (`@view-transition`).
   The cats' pupils follow the pointer.

How it is built (important):

- `js/interactions.js` runs one `requestAnimationFrame` loop on scroll and
  resize. For each scene it measures progress from `getBoundingClientRect()`
  and writes it to `--p` (0–1) on the element. CSS uses `var(--p)` directly.
- The colocation scene's photos and captions are styled **directly by the
  script** (`clip-path`, `transform`, `opacity` as inline styles).
- The class `lpd-scrub` on `<html>` means the script is running; every
  pinned or scrubbed style is scoped under it, so without JavaScript the
  page is ordinary sections.
- **Do not use CSS `animation-timeline` / `view()` / `scroll()`**, and do
  not use the "paused keyframes scrubbed by a negative `animation-delay`"
  trick. The first is unsupported in Safari and Firefox; the second was
  replaced after the owner reported the scene dead. Plain scroll position in
  JS is the only approach that has been accepted.

Values:

| | |
|---|---|
| Standard ease-out | `cubic-bezier(0.23, 1, 0.32, 1)` |
| In-out (wipes) | `cubic-bezier(0.77, 0, 0.175, 1)` |
| Drawer / mobile menu | `cubic-bezier(0.32, 0.72, 0, 1)` |
| Press | `scale(0.97)`, 160ms |
| Hover colour | 150ms ease |
| Stagger | 45–80ms per item |
| Entrances | 0.8–0.9s; nothing over ~1.1s except the image settle |

Rules:

- Animate `transform`, `opacity` and `clip-path` only. Never animate
  layout properties (height, margin, top) except the FAQ's `max-height`.
- **Reduced motion:** everything is off except the colocation scene, which
  still pins and cross-fades (no wipe, no zoom). This is deliberate: with
  "reduce motion" on, a static section looked exactly like a broken one.
- On a window shorter than 560px the scene un-pins and becomes a normal
  section.
- Before adding motion, ask what it tells the visitor. If the answer is
  "nothing, it looks nice", leave it out. The owner asked for *bolder*
  motion in a few places, not *more* motion everywhere.

## 11. Copy

- Plain, friendly, concrete. "Clear pricing, real people, no jargon." is
  the brand in five words.
- The home page is about the company, not one service (owner's request).
  Order: hero, statement, About, What We Do, How We're Different, stats,
  testimonials, colocation scene, contact. No web design prices, plans,
  process or FAQ on it: those live on the Web Design page (/web-design/):
  intro with the Ryton Singers screenshots, What You Get, How It Works,
  pricing (with the plan helper), questions, and the free website design
  offer. Colocation detail lives on the Colocation page. Copy on the home
  page speaks for all services ("the technology small businesses depend
  on"), not just websites.
- Sentences, not slogans. No "unleash", "seamless", "elevate", "next-level".
- British English. "We will", not "we'll", in the body copy the owner wrote.
- **Don't rewrite the owner's copy** to suit a layout. Restructure, split or
  move it; if it must change, ask.
- Colocation facility facts come from the building operator's own page,
  <https://wildcard.net.uk/colocation-newcastle/> (the owner pointed to it).
  Use facts about the building, in our own words. Do not name the operator,
  and do not carry over its commercial terms: its network SLA, customer
  count, AS number, memberships, other sites, phone number or hours.
- The owner removed "Free for 30 days" from the hero facts. The trial is
  still described in How It Works, the offer panel and the FAQ; don't
  put it back in the hero.
- Facts in use (don't alter without the owner): founded 2026; four years'
  web design experience; websites from £99 then £5.75/month; colocation from
  £65.99/month; 30-day free trial on a demo URL; phone 0333 112 7191;
  Reliance House, Newcastle; N+1 UPS, diesel generator, Inergen fire
  suppression; 24/7 access.

## 12. Accessibility and phones

- Text contrast meets WCAG AA on both surfaces. The only intentional
  exceptions are transient scroll states (unlit statement words at 30%,
  inactive scene facts at 62%), which reach full contrast as you scroll.
- Focus ring: 2px `--pink-hi`, 3px offset, on everything focusable.
- FAQ questions are real `<button>`s inside the `<h3>`, with
  `aria-expanded` / `aria-controls`.
- The split hero headline carries an `aria-label` with the whole sentence.
- Touch targets are at least 44px. Tap highlight is removed and replaced by
  the press state.
- Use `100svh` with a `100vh` fallback for full-height things; `vh` alone
  jumps when the mobile address bar hides.
- `body` uses `overflow-x: clip`, never `hidden` (see §14).
- The mobile menu is a full-screen sheet with a clip-path reveal; closing
  waits 320ms (`CLOSE_ANIMATION_MS` in `navigation.js`) for the exit.

## 13. How the theme is put together

```
lilypawdigital/            the theme (zip this folder to install)
  style.css                the whole design; tokens at the top
  functions.php            setup, assets, LPD_VERSION, wrapper removal
  header.php footer.php    chrome (footer holds the cats and status badge)
  front-page.php           home: outputs page content inside .lpd-front-page
  page.php                 inner pages: full width if content has lpd-band
  js/navigation.js         mobile menu
  js/interactions.js       hero film, headline split, scroll scenes, FAQ,
                           stats, image reveal, cats
  inc/pages/*.html         block content for the seven pages
  inc/pages/known-fingerprints.php   generated; see below
  inc/page-setup.php       creates/updates the pages automatically
  assets/                  fonts, icons, images, video
page-content/copy-all-page-content.html   same content, for pasting by hand
tools/                     preview, checks, tests (see §15)
docs/impeccable-audit.md   earlier accessibility/design audit
.claude/skills/            design skills used on this project
```

Content lives in the database, not the theme. `inc/pages/*.html` is what the
theme installs:

- On activation, and whenever `LPD_VERSION` changes, the theme creates any
  missing page and updates any page **nobody has edited by hand**.
- "Not edited" means its content fingerprint matches one in
  `known-fingerprints.php` (every version ever shipped). Edited pages are
  never touched; **Appearance → Page Setup** can replace one on request and
  keeps the old content as a revision.
- So: after changing anything in `inc/pages/`, run
  `python3 tools/update-page-fingerprints.py`, commit the result, and bump
  `LPD_VERSION` (and `Version:` in `style.css`), or sites will not pick the
  change up.
- Content must be valid block markup (`<!-- wp:group -->` etc.), or the
  editor shows "this block contains unexpected content".
  `python3 tools/blockcheck.py lilypawdigital/inc/pages/*.html` checks it.
- Use `{{theme_uri}}` for theme asset URLs inside page content.

The theme folder was renamed from `lilypaw-digital-editable` to
`lilypawdigital`. WordPress treats them as two different themes.

## 14. WordPress traps that have already bitten

Each of these cost at least one round of "it still doesn't work".

1. **The group wrapper.** On themes without a `theme.json`, WordPress wraps
   the contents of every Group block in
   `<div class="wp-block-group__inner-container">`. That extra element sits
   between every grid/flex container and its children: layouts collapse to
   one column and `position: sticky` has nothing to stick within. This was
   the real cause of "spacing is off on nearly everything" *and* "the
   colocation animation doesn't work". `functions.php` removes the filter
   (`wp_restore_group_inner_container`), strips the wrapper again on
   `render_block_core/group` at priority 99, and CSS sets it to
   `display: contents` as a last resort. **Do not add a `theme.json`
   casually** — it changes many other defaults.
2. **Caching plugin lazy rendering.** The host runs AccelerateWP / WP
   Rocket, which adds `data-wpr-lazyrender` (→ `content-visibility: auto`)
   to sections below the fold. A skipped section has no height, so the page
   changes length while scrolling. The stylesheet forces those sections to
   render. The same plugin **caches pages and minifies JS**: after any
   upload the cache must be cleared, or the old version is what's seen.
3. **Core block CSS with `!important`.** `.wp-block-columns` has
   `align-items: normal !important` and `flex-wrap` rules. You cannot
   vertically centre columns with `align-items`; use `align-self` on the
   column or restructure.
4. **`overflow-x: hidden` on both `html` and `body`** turns `body` into a
   scroll container, which breaks sticky and scroll measurement. `body` is
   `overflow-x: clip`.
5. **`backdrop-filter` on the header** makes it the containing block for
   the fixed full-screen mobile menu and clips it. Blur is desktop only.
6. **An element fully clipped by its own `clip-path` never intersects**, so
   an IntersectionObserver on it never fires. Clip the inner `<img>`.
7. **`.entry-content p` colours** leak into band components. Generic
   content styles are scoped to `.entry-content:not(.lpd-bands)`.
8. **The preview is not WordPress.** `tools/preview` renders the templates
   with a stub. It does not add the group wrapper, run plugins, or cache.
   Trap 1 was invisible in it for several rounds. Treat a clean preview as
   "the CSS is right", not "the site is right".
9. **The live site may not be running this theme.** When last checked,
   `lilypaw.digital` was still serving the old `lilypaw-digital-editable`
   folder with old content. Always confirm where the owner is looking
   before debugging what they report.

## 15. How to make a change

1. Read the relevant part of this guide and the section of `style.css`.
2. Make the change using the tokens. No new colours, no one-off distances.
3. If page content changed: `python3 tools/update-page-fingerprints.py`,
   regenerate `page-content/copy-all-page-content.html` (replace each
   `<textarea id="ta-…">` body with the matching `inc/pages` file, with
   `{{theme_uri}}` → `/wp-content/themes/lilypawdigital`).
4. Bump the version in **both** `style.css` (`Version:`) and
   `functions.php` (`LPD_VERSION`). They are also the cache-busting query
   string for CSS and JS.
5. Check:

   ```bash
   bash tools/check.sh
   ```

   ```bash
   bash tools/preview/build.sh
   ```

   ```bash
   cd .preview && python3 -m http.server 8765 --bind 127.0.0.1
   ```

   Then, from the repository root in another shell:

   ```bash
   node tools/preview/gaps.js site 1440 900
   ```

   ```bash
   node tools/preview/gaps.js site 390 800
   ```

   ```bash
   mkdir -p .preview/shots && node tools/preview/walk.js site home .preview/shots/d 1440 900
   ```

   ```bash
   node tools/preview/walk.js site home .preview/shots/m 390 760 chromium mobile
   ```

   `walk.js` takes a screenshot per screenful (full-page screenshots lie
   about pinned sections); `sheet.py` tiles them into a contact sheet.
   **Look at the screenshots.** Check 1440, 1024, 768 and 390 wide, and
   every page, not only the one you touched.
6. For anything involving sticky or scroll, also test with the group
   wrapper put back (wrap each `.wp-block-group`'s children in a
   `wp-block-group__inner-container` div before scripts run) and with
   `reducedMotion: 'reduce'`.
7. Zip the `lilypawdigital` folder for the owner, commit, push.
8. Tell the owner, in plain words: what changed, what was tested and where,
   what was **not** tested (real WordPress, Safari, a real phone), and that
   the cache needs clearing after upload.

## 16. Recipes

**A new home section.** Group with a class → `.lpd-inner` inside → optional
`.lpd-section-heading` → content. Add the class to a surface list and to the
"Section rhythm" group for that surface. Use `--head`, `--gap`, `--pad`.

**A new inner page.** Bands: `.lpd-band` (night) and `.lpd-band-alt`
(light), each with `.lpd-inner`. Start with a `.lpd-lead` paragraph. Add the
slug to `lpd_setup_pages()` in `inc/page-setup.php`, the `SLUGS` map in
`tools/update-page-fingerprints.py`, and the maps in `tools/preview`.

**A new review on the home page.** Copy a whole `.lpd-proof-grid` columns
block inside `.lpd-reviews` (quote, name, site link on the left; that
site's two screenshots on the right). Put it first to make it the main
review. The arrows and counter appear by themselves.

**A new portfolio project.** Capture desktop and phone screenshots per §9
into `assets/images/work/`. On Our Work, copy a project band (add
`lpd-split-flip` to every other one so the picture alternates sides): `.lpd-split`
with prose and checklist on the left, `.lpd-shots` and (if there is one) the
testimonial on the right. Only attach a testimonial that is about that site.

**A new service.** From the repository root:
`python3 .preview/addtile.py lpd-service-<name> <icon>.svg "Title" "One-line summary" "Ask about …" "Item" "Item" …`
is not committed (it lives in the ignored `.preview` folder); without it,
copy an existing `.lpd-service-tile` column inside `.lpd-service-grid`,
keep "Something Else?" last, and add a `--icon` rule beside
`.lpd-service-maintenance` in `style.css`.

**A new icon tile.** Add the SVG to `assets/icons/`, add
`.lpd-why-yourname { --icon: url('assets/icons/yourname.svg'); }`, and use
`lpd-card lpd-why-item lpd-why-yourname` on the group.

**A new scroll effect.** Register the element in the `scenes` list in
`interactions.js` with mode `through` (0→1 as it crosses the screen), `pin`
(tall section with a sticky stage) or `leave`. Style from `var(--p)` under
`.lpd-scrub`. Give it a sensible look when `--p` is absent (the fallback in
`var(--p, 1)`), because that is what no-JS and reduced-motion visitors get.

**Changing a price or fact.** It appears in several places: pricing cards
(which also feed the plan helper), the offer panel, How It Works step 4
and its disclaimer, FAQ answers, the colocation scene and the colocation
page. Search for the number.

## 17. Open questions

Unresolved when this guide was written:

- **Spacing on the owner's real site.** The owner still reported spacing
  as "a bit off" after v4.6. v4.7 fixed everything measurable in the
  preview, but it has not been confirmed on a real install. The next step
  is to get the address where they are viewing the new theme (or a
  screenshot) and inspect the real output.
- **Nothing has been verified in real WordPress, Safari, Firefox or on a
  physical phone** — only in Chromium against the preview.
- The contact form (`[lpd_form id="lpd_form_9d620c8f7c"]`), the photo of
  Lily on About, and the status badge iframe come from outside the theme
  and have only been seen as stand-ins.
