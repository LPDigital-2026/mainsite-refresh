#!/usr/bin/env python3
"""Join page pictures into an A4 PDF at 300 dpi. Called by build.js.
Usage: images-to-pdf.py out.pdf page1.png page2.png ...
"""
import sys
from PIL import Image

out, files = sys.argv[1], sys.argv[2:]
pages = [Image.open(f).convert('RGB').resize((2480, 3508), Image.LANCZOS) for f in files]
pages[0].save(out, save_all=True, append_images=pages[1:], resolution=300, quality=92)
