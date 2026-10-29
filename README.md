# Lily Paw Digital website

WordPress theme for lilypawdigital.xyz, plus the page content it ships with.

| Path | What it is |
|------|------------|
| `lilypawdigital/` | The theme. Zip this folder to upload it. |
| `lilypawdigital/inc/pages/` | Block content for Home, Colocation, Our Work, About Us, Contact Us and Get Started. |
| `lilypawdigital/inc/page-setup.php` | Creates those pages, sets the homepage and builds the menu. |
| `page-content/copy-all-page-content.html` | The same content as copy buttons, for pasting by hand. |
| `docs/impeccable-audit.md` | Design and accessibility audit, before and after v3.1. |

## Install

1. In WP Admin: **Appearance → Themes → Add New → Upload Theme**, choose `lilypawdigital.zip`, then **Activate**.
2. On activation the theme fills any gaps, and only gaps:
   - creates any of the six pages that don't exist yet;
   - makes Home the front page if no static front page is set;
   - builds a main menu if the header has none.
3. **Appearance → Page Setup** shows each page's status. From there you can create a missing page, or replace a page with the theme's version. Replacing keeps the old content as a revision, which you can restore from the page editor.

Existing pages are never overwritten automatically. That matters for **Get Started** if it now holds a price calculator: don't press Replace on it unless you mean to.

## Note on the rename

The theme used to live in a folder called `lilypaw-digital-editable`. WordPress treats `lilypawdigital` as a separate theme, so:

- the old theme stays installed until you delete it;
- menu and widget locations carry over automatically;
- Customizer settings (such as a custom logo) need setting again on the new theme.
