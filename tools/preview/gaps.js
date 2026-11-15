const { chromium } = require('playwright');
const [site, w, h] = process.argv.slice(2);
(async () => {
  const b = await chromium.launch(); const pg = await (await b.newContext({ viewport: { width: +w, height: +h }, reducedMotion: 'reduce' })).newPage();
  for (const p of ['home','colocation','about','work','contact','getstarted']) {
    await pg.goto(`http://127.0.0.1:8765/${site}/${p}.html`); await pg.waitForTimeout(700);
    const rows = await pg.evaluate(() => {
      const out = []; const vis = e => e.getBoundingClientRect().height > 0;
      const inners = [...document.querySelectorAll('main .lpd-inner, main .lpd-scene-stage, .page-hero .container, .lpd-rail')];
      let prevBottom = null, prevBg = null;
      inners.forEach(inner => {
        const sec = inner.parentElement; const kids = [...inner.children].filter(vis); if (!kids.length) return;
        const top = Math.min(...kids.map(k => k.getBoundingClientRect().top)) + scrollY, bottom = Math.max(...kids.map(k => k.getBoundingClientRect().bottom)) + scrollY;
        const bg = getComputedStyle(sec).backgroundColor;
        const inside = kids.slice(1).map((k, i) => Math.round(k.getBoundingClientRect().top - kids[i].getBoundingClientRect().bottom));
        out.push(`${(sec.className||'').replace(/wp-block-group|alignfull/g,'').trim().slice(0,38).padEnd(38)} gapAbove=${prevBottom===null?'-':Math.round(top-prevBottom)}${prevBg&&prevBg!==bg?' (surface change)':''} inside=[${inside.join(',')}]`);
        prevBottom = bottom; prevBg = bg;
      });
      const f = document.querySelector('.site-footer'); out.push('footer gapAbove=' + Math.round(f.getBoundingClientRect().top + scrollY - prevBottom));
      return out;
    });
    console.log('\n## ' + p + ' @' + w); rows.forEach(r => console.log(r));
  }
  await b.close();
})();
