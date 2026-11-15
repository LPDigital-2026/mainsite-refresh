# Lily Paw Digital website

WordPress theme (`lilypawdigital/`) for lilypaw.digital. Classic PHP theme,
no build step, no `theme.json`.

**Before any visual change, read `DESIGN.md` in full.** It is the design
system, the list of things the owner has already rejected, and the WordPress
traps that caused earlier failures. Follow it over your own taste.

The rules that matter most:

- Use the tokens at the top of `lilypawdigital/style.css`. No new colours,
  no one-off spacing values. One spacing scale: `--section`, `--head`,
  `--gap`, `--pad`.
- Never rename or remove an `lpd-*` class: page content in the database
  depends on them.
- Sora only. Real photos and real client screenshots only. No gradient
  text, no eyebrow labels, no fade-in-on-everything ("the AI look").
- Scroll effects are driven from `js/interactions.js` by scroll position.
  Do not use CSS `animation-timeline` or paused-keyframe scrubbing.
- WordPress wraps Group block contents in an extra div on this kind of
  theme; `functions.php` removes it. Do not undo that, and do not add a
  `theme.json` without understanding DESIGN.md §14.
- The local preview (`tools/preview`) is not WordPress. Never tell the owner
  something works on their site because it works in the preview.

Every change:

1. Bump `Version:` in `style.css` and `LPD_VERSION` in `functions.php`.
2. If `lilypawdigital/inc/pages/` changed, run
   `python3 tools/update-page-fingerprints.py` and regenerate
   `page-content/copy-all-page-content.html`.
3. Run `bash tools/check.sh`, build the preview, measure gaps with
   `tools/preview/gaps.js`, and look at screenshots at 1440 and 390 wide.
4. Report to the owner in plain English: what changed, what was tested,
   what was not, and that their cache must be cleared after uploading.

The owner is not a developer, views the site on a phone and a computer, and
has asked three times for spacing to be fixed. Treat any spacing
inconsistency as a bug.
