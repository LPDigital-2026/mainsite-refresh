#!/usr/bin/env python3
"""Draw the leaflet's QR code as a styled SVG: round dots, rounded finder
squares and the paw in the middle. Uses the highest error correction (H) so
the paw can cover the centre and the code still scans.
Run from the repository root:  python3 leaflet/make-qr.py
Needs:  pip install segno
"""
import re
import segno

URL = 'https://lilypaw.digital'
OUT = 'leaflet/qr-lilypaw-digital.svg'
NIGHT = '#1d0e38'

qr = segno.make(URL, error='h', boost_error=False)
m = [list(row) for row in qr.matrix]
n = len(m)

# centre logo area, in modules (kept odd so it sits centred)
logo = 7 if n < 33 else 9
lo = (n - logo) // 2
hi = lo + logo


def in_finder(r, c):
    return (r < 7 and c < 7) or (r < 7 and c >= n - 7) or (r >= n - 7 and c < 7)


def in_logo(r, c):
    return lo <= r < hi and lo <= c < hi


parts = []
for r in range(n):
    for c in range(n):
        if m[r][c] and not in_finder(r, c) and not in_logo(r, c):
            parts.append(f'<circle cx="{c + 0.5}" cy="{r + 0.5}" r="0.44"/>')

for (fr, fc) in [(0, 0), (0, n - 7), (n - 7, 0)]:
    # outer ring, 7x7 with a 1-module stroke, then a 3x3 centre
    parts.append(f'<rect x="{fc + 0.5}" y="{fr + 0.5}" width="6" height="6" rx="1.7" fill="none" stroke="{NIGHT}" stroke-width="1"/>')
    parts.append(f'<rect x="{fc + 2}" y="{fr + 2}" width="3" height="3" rx="0.9"/>')

paw = open('lilypawdigital/assets/icons/paw.svg').read()
paw_path = re.search(r' d="([^"]+)"', paw).group(1)
size = logo - 1.6
scale = size / 512
off = lo + (logo - size) / 2

svg = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {n} {n}" width="{n * 10}" height="{n * 10}">
<defs><linearGradient id="paw" x1="0" y1="0.3" x2="1" y2="0.7">
<stop offset="0" stop-color="#f0529c"/><stop offset="0.5" stop-color="#f6677a"/><stop offset="1" stop-color="#fb7a3c"/>
</linearGradient></defs>
<g fill="{NIGHT}">
{chr(10).join(parts)}
</g>
<path transform="translate({off:.3f} {off:.3f}) scale({scale:.5f})" fill="url(#paw)" d="{paw_path}"/>
</svg>
'''
open(OUT, 'w').write(svg)
print(f'wrote {OUT}: version {qr.version}, {n}x{n}, error level {qr.error}')
