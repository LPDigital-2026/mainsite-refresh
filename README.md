# Lily Paw Digital website

WordPress theme for lilypawdigital.xyz, plus the page content it ships with.

| Path | What it is |
|------|------------|
| `lilypawdigital/` | The theme. Zip this folder to upload it. |
| `lilypawdigital/inc/pages/` | Block content for Home, Colocation, Our Work, About Us, Contact Us and Get Started. |
| `lilypawdigital/inc/page-setup.php` | Creates those pages, sets the homepage and builds the menu. |
| `page-content/copy-all-page-content.html` | The same content as copy buttons, for pasting by hand. |
| `docs/impeccable-audit.md` | Design and accessibility audit, before and after v3.1. |

## Design (v4.2)

- **Spacing:** one scale, set as variables at the top of `style.css`. Every section is separated by the same distance, every heading sits the same distance above its content, and every grid and panel uses the same gap and padding.
- **Surfaces:** the brand purple is the page colour; pricing, FAQ, About and Contact sit on a pale lilac.
- **Colour:** paw pink for buttons; the logo's pink-to-orange gradient for icons, progress lines and the one offer panel.
- **Type:** Sora throughout, the brand typeface, from one self-hosted variable font file in `lilypawdigital/assets/fonts/`.
- **Pictures:** the service panels and testimonial show the real Ryton CC site and the real data hall.
- **Motion** (driven by scroll position in `js/interactions.js`, so it works in Safari, Firefox, Chrome and on phones):
  - the hero headline rises in word by word, once, on load;
  - the founding statement lights up word by word as it travels up the screen;
  - the "How It Works" track fills and the step numbers come up to strength;
  - the colocation section pins to the screen while its photographs wipe from one to the next and the matching fact comes forward;
  - photographs on inner pages unveil once; pages cross-fade; the footer cats' eyes follow the pointer.
- **Reduced motion:** all of it is switched off for visitors who ask for reduced motion, and the page reads as ordinary sections.

## Install

1. In WP Admin: **Appearance → Themes → Add New → Upload Theme**, choose `lilypawdigital.zip`, then **Activate** (or **Replace** when updating).
2. The theme then sets the pages up by itself. It does this on activation and again whenever its version changes:
   - it creates any of the six pages that don't exist yet;
   - it updates any page nobody has edited by hand to the current content;
   - it makes Home the front page if no static front page is set;
   - it builds a main menu if the header has none.
3. A notice says what it did. **Appearance → Page Setup** shows each page's status and can replace a page on request.

A page counts as "not edited" when its content matches what the theme last wrote, or any version of the content the theme has ever shipped. Edited pages are left alone. That matters for **Get Started** if it holds a price calculator. Replacing always keeps the old content as a revision.

After changing anything in `lilypawdigital/inc/pages/`, run this from the repository root and commit the result, so the new content is recognised later:

```bash
python3 tools/update-page-fingerprints.py
```

## Note on the rename

The theme used to live in a folder called `lilypaw-digital-editable`. WordPress treats `lilypawdigital` as a separate theme, so:

- the old theme stays installed until you delete it;
- menu and widget locations carry over automatically;
- Customizer settings (such as a custom logo) need setting again on the new theme.
