#!/bin/bash
# Everything that can be checked without WordPress. Run from the repository
# root before every commit:  bash tools/check.sh
set -e
cd "$(dirname "$0")/.."
T=$PWD/lilypawdigital
for f in $(find lilypawdigital -name '*.php'); do php -l "$f" >/dev/null; done; echo "php syntax OK"
node -e "for (const f of ['navigation','interactions']) new (require('vm').Script)(require('fs').readFileSync('lilypawdigital/js/'+f+'.js','utf8'))"; echo "js syntax OK"
python3 tools/blockcheck.py lilypawdigital/inc/pages/*.html
python3 tools/update-page-fingerprints.py >/dev/null
git diff --quiet -- lilypawdigital/inc/pages/known-fingerprints.php || echo "NOTE: known-fingerprints.php changed - commit it"
HASH=$(python3 - <<'PY'
import re, hashlib
c = open('lilypawdigital/inc/pages/home.html').read().replace('{{theme_uri}}', '{{T}}')
c = re.sub(r'(?:https?://[^\s"\'<>]+?)?/wp-content/themes/[A-Za-z0-9_-]+', '{{T}}', c)
c = re.sub(r'\s+rel="[^"]*"', '', c); c = re.sub(r'\s+', ' ', c).strip()
print(hashlib.md5(c.encode()).hexdigest())
PY
)
for t in "test.php $T" "test-auto.php $T $HASH"; do
  out=$(php tools/tests/$t 2>&1); echo "$(echo "$out" | grep -c '^PASS') passed, $(echo "$out" | grep -cE '^FAIL|Fatal' || true) failed  (tools/tests/${t%% *})"
  if echo "$out" | grep -qE '^FAIL|Fatal'; then echo "$out" | grep -E '^FAIL|Fatal'; exit 1; fi
done
php tools/tests/test-blog.php "$T" | tail -1
php tools/tests/test-drafts.php "$T" | tail -1
php tools/tests/test-comments.php "$T" | tail -1
