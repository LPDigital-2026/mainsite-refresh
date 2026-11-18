// Renders every *.html here to PNG at the exact size Instagram/Facebook want, and reports overflow.
const {chromium} = require('playwright'); const fs = require('fs');
(async () => {
  const b = await chromium.launch();
  for (const f of fs.readdirSync('.').filter(f => f.endsWith('.html'))) {
    const story = f.includes('story'); const w = 1080, h = story ? 1920 : 1350;
    const p = await b.newPage({viewport: {width: w, height: h}});
    await p.goto('file://' + process.cwd() + '/' + f); await p.waitForTimeout(400);
    const r = await p.evaluate(() => { let right = 0, top = 1e9;
      document.querySelectorAll('.body *').forEach(e => { const b = e.getBoundingClientRect(); right = Math.max(right, b.right); top = Math.min(top, b.top); });
      return {right: Math.round(right), bodyTop: Math.round(top), logoBottom: Math.round(document.querySelector('.logo').getBoundingClientRect().bottom)}; });
    console.log(f, JSON.stringify(r));
    await p.screenshot({path: 'lily-paw-digital-' + f.replace('.html', '.png')}); await p.close();
  }
  await b.close();
})();
