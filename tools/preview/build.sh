#!/bin/bash
# Renders the theme's six pages to static HTML in .preview/site/ so they
# can be opened in a browser and screenshotted without WordPress.
#   bash tools/preview/build.sh          (run from the repository root)
#   cd .preview && python3 -m http.server 8765 --bind 127.0.0.1
#   open http://127.0.0.1:8765/site/home.html
# This is NOT real WordPress - see "Preview is not WordPress" in DESIGN.md.
set -e
ROOT=$(cd "$(dirname "$0")/../.." && pwd)
THEME=$ROOT/lilypawdigital
OUT=$ROOT/.preview
mkdir -p "$OUT/wpcss"
if [ ! -f "$OUT/wpcss/package/build-style/style.css" ]; then
  (cd "$OUT/wpcss" && npm pack --silent @wordpress/block-library@9.8.0 >/dev/null && tar xzf wordpress-block-library-9.8.0.tgz package/build-style/style.css)
fi
rm -rf "$OUT/site" && mkdir -p "$OUT/site" && cp -r "$THEME" "$OUT/site/theme"
python3 - "$THEME" > "$OUT/content.html" <<'PY'
import sys, html
names = {'home': 'home', 'webdesign': 'web-design', 'colocation': 'colocation', 'about': 'about-us', 'work': 'our-work', 'contact': 'contact-us', 'getstarted': 'get-started'}
for key, slug in names.items():
    content = open(f'{sys.argv[1]}/inc/pages/{slug}.html').read().replace('{{theme_uri}}', '/wp-content/themes/lilypawdigital')
    print(f'<textarea id="ta-{key}">' + html.escape(content, quote=False) + '</textarea>')
PY
for page in home webdesign colocation about work contact getstarted 404; do
  php "$ROOT/tools/preview/render.php" "$OUT/site/theme" "$OUT/content.html" $page theme > "$OUT/site/$page.html"
done
echo "built $OUT/site"
