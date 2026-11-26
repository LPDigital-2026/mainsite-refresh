"""Builds the Instagram/Facebook adverts as HTML. Run render.js afterwards."""
import re, pathlib
A = '../lilypawdigital/assets'
PAW = re.search(r'd="([^"]+)"', pathlib.Path(A + '/icons/paw.svg').read_text()).group(1)
ADS = {
  'free-trial': dict(
    big='30', unit='days<br>free.', small='No obligation',
    h1='See your new website<br><em>before you pay a penny.</em>',
    bullets=['We design and build it for you', 'Try it live on a demo address', 'Not for you? No cost, no obligation'],
    price='Love it? Keep it from <strong>£99</strong>, then hosting from <strong>£5.75 a month</strong>.',
    terms='Terms and conditions apply. Prices shown are for the Essentials plan.'),
  'colocation': dict(
    big='24/7', unit='access.', small='In Newcastle',
    h1='Your server deserves<br><em>better than a cupboard.</em>',
    bullets=['Backup power, cooling and fast fibre', 'Remote hands and free setup included', 'Visit your kit whenever you need to'],
    price='Colocation from <strong>£65.99 a month</strong>.',
    terms='Terms and conditions apply. Price shown is for the Lynx plan.'),
  'real-people': dict(
    big='Hi.', unit="we're<br>real people.", small='Based in the North East',
    h1='No support queues.<br><em>Just us.</em>',
    bullets=['You talk to someone who knows your project', 'Honest pricing, no hidden fees', "We don't disappear after launch"],
    price='Websites from <strong>£99</strong>. Colocation from <strong>£65.99 a month</strong>.',
    terms='Terms and conditions apply.'),
  'hosting-from': dict(
    big='£5.99', unit='per<br>month.', small='Web hosting', scale=.66,
    h1='Hosting for the site<br><em>you already have.</em>',
    bullets=['UK hardware and SSD storage', 'Email addresses on your own domain', 'A local team to call when you need a hand'],
    price='Web hosting plans from <strong>£5.99 a month</strong>.',
    terms='Terms and conditions apply. Price shown is for the Singapura plan.'),
  'hosting-plans': dict(
    big='3', unit='simple<br>plans.', small='All named after cats',
    h1='Pick a plan,<br><em>move up any time.</em>',
    bullets=['<b>Singapura</b> · 6 websites · <b>£5.99</b> a month', '<b>Siamese</b> · 9 websites · <b>£7.99</b> a month', '<b>Maine Coon</b> · unlimited · <b>£9.99</b> a month'],
    price='Every plan includes <strong>email on your own domain</strong>.',
    terms='Terms and conditions apply. Prices are per month.'),
  'hosting-unlimited': dict(
    big='£9.99', unit='per<br>month.', small='Maine Coon plan', scale=.66,
    h1='Unlimited websites.<br><em>Unlimited bandwidth.</em>',
    bullets=['Unlimited MySQL databases', '20GB SSD storage on UK hardware', '40 email addresses on your domain'],
    price='Our biggest web hosting plan, <strong>£9.99 a month</strong>.',
    terms='Terms and conditions apply.'),
}
# Format sizes. Story keeps clear of the top ~250px and bottom ~340px that the apps cover.
FORMATS = {
  'feed':  dict(w=1080, h=1350, pad='84px 80px 64px', logo=80, big=250, unit=56, h1=62, li=29, price=27, terms=17, gap_hero=56, gap_list=36),
  'story': dict(w=1080, h=1920, pad='250px 80px 340px', logo=92, big=300, unit=64, h1=80, li=36, price=32, terms=20, gap_hero=80, gap_list=52),
}
TPL = '''<!doctype html><html><head><meta charset="utf-8"><style>
@font-face{{font-family:Sora;src:url({A}/fonts/sora-latin-wght-normal.woff2) format('woff2');font-weight:100 800}}
*{{margin:0;box-sizing:border-box}}
body{{width:{w}px;height:{h}px;background:#1d0e38;font-family:Sora;color:#fbf7f2;overflow:hidden;position:relative}}
.glow{{position:absolute;width:900px;height:900px;border-radius:50%;background:radial-gradient(circle,rgba(240,82,156,.3),rgba(251,122,60,.08) 50%,transparent 70%)}}
.g1{{right:-380px;top:-380px}} .g2{{left:-420px;bottom:-460px;opacity:.8}}
.wrap{{position:absolute;inset:0;padding:{pad};display:flex;flex-direction:column}}
.logo{{height:{logo}px;width:auto;align-self:flex-start}}
.body{{margin-top:auto}}
.offer{{display:flex;align-items:center;gap:30px}}
.offer b{{font-size:{big}px;line-height:1;margin:-.13em 0 -.1em;font-weight:800;letter-spacing:-.06em;padding-right:.05em;background:linear-gradient(140deg,#f0529c,#f6677a 55%,#fb7a3c);-webkit-background-clip:text;color:transparent}}
.unit{{font-size:{unit}px;line-height:1;font-weight:700;letter-spacing:-.02em}}
.unit small{{display:block;margin-top:16px;font-size:{small}px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:rgba(251,247,242,.6)}}
h1{{margin-top:{gap_hero}px;font-size:{h1}px;line-height:1.06;font-weight:700;letter-spacing:-.03em}}
h1 em{{font-style:normal;white-space:nowrap;background:linear-gradient(115deg,#f0529c,#f6677a 50%,#fb7a3c);-webkit-background-clip:text;color:transparent}}
ul{{list-style:none;padding:0;margin-top:{gap_list}px;display:flex;flex-direction:column;gap:{li_gap}px}}
li{{font-size:{li}px;color:rgba(251,247,242,.85);display:flex;align-items:center;gap:20px}}
li svg{{width:{icon}px;height:{icon}px;flex:none;color:#f0529c}}
.price{{margin-top:{gap_list}px;padding-top:{price_pad}px;border-top:2px solid rgba(251,247,242,.16);font-size:{price}px;color:rgba(251,247,242,.74)}}
li b{{color:#fbf7f2;font-weight:700}}
.price strong{{color:#fbf7f2;font-weight:700;white-space:nowrap}}
.terms{{margin-top:14px;font-size:{terms}px;color:rgba(251,247,242,.5)}}
</style></head><body>
<div class="glow g1"></div><div class="glow g2"></div>
<div class="wrap">
<img class="logo" src="{A}/images/logo-stacked.png">
<div class="body">
<div class="offer"><b>{big_text}</b><div class="unit">{unit_text}<small>{small_text}</small></div></div>
<h1>{h1_text}</h1>
<ul>{lis}</ul>
<div class="price">{price_text}</div>
<div class="terms">{terms_text}</div>
</div></div></body></html>'''
for name, ad in ADS.items():
    for fmt, f in FORMATS.items():
        lis = ''.join(f'<li><svg viewBox="0 0 512 512"><path fill="currentColor" d="{PAW}"/></svg><span>{b}</span></li>' for b in ad['bullets'])
        g = dict(f, big=round(f['big'] * ad.get('scale', 1)))
        html = TPL.format(A=A, **g, small=round(f['unit'] * .38), li_gap=round(f['li'] * .55), icon=round(f['li'] * 1.15),
                          price_pad=round(f['gap_list'] * .8), big_text=ad['big'], unit_text=ad['unit'], small_text=ad['small'],
                          h1_text=ad['h1'], lis=lis, price_text=ad['price'], terms_text=ad['terms'])
        pathlib.Path(f'{name}-{fmt}.html').write_text(html)
