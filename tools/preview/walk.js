// node walk.js <site> <page> <outPrefix> <width> <height> [engine] [mobile]
const pw = require('playwright');
const [site, page, out, w, h, engine='chromium', mobile] = process.argv.slice(2);
(async () => {
  const b = await pw[engine].launch();
  const ctx = await b.newContext({ viewport: { width: +w, height: +h }, ...(mobile ? { hasTouch: true } : {}) });
  const pg = await ctx.newPage();
  const errs=[]; pg.on('pageerror', e => errs.push(e.message));
  await pg.goto(`http://127.0.0.1:8765/${site}/${page}.html`);
  await pg.waitForTimeout(2500);
  const total = await pg.evaluate(() => document.documentElement.scrollHeight);
  let i = 0;
  for (let y = 0; y < total - 10; y += Math.round(+h * 0.9)) {
    await pg.evaluate(y => window.scrollTo({top:y,behavior:'instant'}), y);
    await pg.waitForTimeout(700);
    await pg.screenshot({ path: `${out}-${String(i++).padStart(2,'0')}.png` });
  }
  console.log(page, engine, 'frames', i, 'total', total, errs.length ? errs : '');
  await b.close();
})();
