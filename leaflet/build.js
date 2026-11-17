// Builds the leaflet PDFs and preview PNGs from leaflet.html.
// Run from the repository root:  node leaflet/build.js
const { chromium } = require('playwright');
const path = require('path');
const dir = __dirname;
const http = require('http');
const fs = require('fs');
// Served over http, not file://: Chromium refuses SVG masks (the icons and
// ticks) loaded from file:// pages.
const root = path.join(dir, '..');
const types = { '.html': 'text/html', '.svg': 'image/svg+xml', '.png': 'image/png', '.jpg': 'image/jpeg', '.woff2': 'font/woff2' };
const server = http.createServer((req, res) => {
  const file = path.join(root, decodeURIComponent(new URL(req.url, 'http://x').pathname));
  if (!file.startsWith(root) || !fs.existsSync(file)) { res.writeHead(404); return res.end(); }
  res.writeHead(200, { 'Content-Type': types[path.extname(file)] || 'application/octet-stream' });
  fs.createReadStream(file).pipe(res);
});
let url;

async function open(page, trim) {
  await page.goto(url + (trim ? '?trim=1' : ''));
  await page.waitForLoadState('networkidle');
  await page.evaluate(() => document.fonts.ready);
}

(async () => {
  await new Promise((r) => server.listen(0, '127.0.0.1', r));
  url = 'http://127.0.0.1:' + server.address().port + '/leaflet/leaflet.html';
  const browser = await chromium.launch();
  const page = await browser.newPage();
  const outputs = [
    ['leaflet-print-3mm-bleed.pdf', false, '216mm', '303mm'],
    ['leaflet-a4.pdf', true, '210mm', '297mm'],
  ];
  for (const [file, trim, width, height] of outputs) {
    await open(page, trim);
    await page.pdf({ path: path.join(dir, file), width, height, printBackground: true,
      margin: { top: 0, right: 0, bottom: 0, left: 0 } });
    console.log('wrote', file);
  }

  // PNG previews of each side (trimmed A4)
  const shot = await browser.newPage({ viewport: { width: 794, height: 1123 }, deviceScaleFactor: 2 });
  await open(shot, true);
  const sheets = await shot.$$('.sheet');
  for (let i = 0; i < sheets.length; i++) {
    await sheets[i].screenshot({ path: path.join(dir, `preview-${i ? 'back' : 'front'}.png`) });
  }

  // Check nothing spills outside the safe area, and report the slack left
  const report = await shot.evaluate(() => [...document.querySelectorAll('.safe')].map((s, i) => {
    const b = s.getBoundingClientRect();
    const over = [...s.querySelectorAll('*')].filter((e) => {
      const r = e.getBoundingClientRect();
      return r.bottom > b.bottom + 1 || r.right > b.right + 1 || r.top < b.top - 1;
    }).map((e) => e.className || e.tagName);
    return { side: i ? 'back' : 'front', spills: over.slice(0, 6) };
  }));
  console.log(JSON.stringify(report));
  await browser.close();
  server.close();
})();
