#!/usr/bin/env python3
"""Draw the leaflet's QR code: plain square modules in the brand night
colour, no border (the white panel around it in leaflet.html is the quiet
zone). Run from the repository root:  python3 leaflet/make-qr.py
Needs:  pip install segno
"""
import segno

URL = 'https://lilypaw.digital'
OUT = 'leaflet/qr-lilypaw-digital.svg'

qr = segno.make(URL, error='m')
qr.save(OUT, kind='svg', scale=10, border=0, dark='#1d0e38', light=None, xmldecl=False)
print(f'wrote {OUT}: version {qr.version}, {qr.symbol_size(border=0)[0]} modules, error level {qr.error}')
