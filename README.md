# Lily Paw Digital website

WordPress theme for lilypawdigital.xyz, plus the page content it ships with.

| Path | What it is |
|------|------------|
| `lilypawdigital/` | The theme. Zip this folder to upload it. |
| `lilypawdigital/inc/pages/` | Block content for Home, Colocation, Our Work, About Us, Contact Us and Get Started. |
| `lilypawdigital/inc/page-setup.php` | Creates those pages, sets the homepage and builds the menu. |
| `page-content/copy-all-page-content.html` | The same content as copy buttons, for pasting by hand. |
| `docs/impeccable-audit.md` | Design and accessibility audit, before and after v3.1. |

## Design (v4)

- **Surfaces:** the logo's aubergine navy is the page colour; pricing, FAQ, About and Contact sit on warm paper.
- **Colour:** solid paw pink for buttons and for one loud offer panel. No gradients.
- **Type:** Sora throughout, the brand typeface, from one self-hosted variable font file in `lilypawdigital/assets/fonts/`.
- **Motion:**
  - the hero headline rises in word by word, once, on load;
  - the hero holds and dims while the page slides up over it;
  - the colocation section pins to the screen while its photographs wipe from one to the next and the matching fact comes forward;
  - "How It Works" tracks scroll: the heading stays pinned while a pink line and the step numbers follow progress;
  - photographs unveil once as they scroll into view;
  - pages cross-fade into each other;
  - the footer cats' eyes follow the pointer.
- **Reduced motion:** all of it is switched off for visitors who ask for reduced motion.

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
