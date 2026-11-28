#!/usr/bin/env python3
"""Generate lilypawdigital/inc/pages/<slug>.html for each service in SERVICES.

Run from the repository root after editing a service below, then run
python3 tools/update-page-fingerprints.py. Each page: an introduction
beside a "What's Included" tile, three reasons to choose Lily Paw Digital,
and a call to action.
"""
SERVICES = [
    dict(slug='web-hosting', cls='lpd-service-hosting', short='Web Hosting',
         lead='Fast, secure cPanel hosting on UK hardware, for websites you already have or want to build yourself.',
         body='Every plan runs on cPanel, the most widely used hosting control panel, on LiteSpeed servers with Imunify360 security, free SSL and daily backups. You also get business email addresses on your own domain, free migration from your current host, and a local team to call when you need a hand. Would you rather we built the site for you? Our <a href="/web-design/">web design</a> packages include hosting.',
         items=['Hosting on UK hardware', 'cPanel control panel', 'Imunify360 security', 'LiteSpeed and CloudLinux', 'Free SSL certificates', 'Daily offsite backups', 'Free migration from your current host', 'Support from our local team'],
         plans_title='Web Hosting Plans',
         plans_intro='Simple monthly prices. Every plan includes cPanel and Imunify360 security. Pick the plan that fits, and move up whenever you need more room.',
         plans_subprice='Per month',
         plan_button=('Order Now', '/get-started/'),
         plans=[
             dict(name='Singapura', desc='A great start for a handful of small sites.', price='£5.99',
                  features=['6 hosted websites', '10GB SSD storage', '10 MySQL databases', '40GB bandwidth', '10 email addresses', 'cPanel with Imunify360', 'Free SSL and daily backups']),
             dict(name='Siamese', desc='More room for growing sites and databases.', price='£7.99',
                  features=['9 hosted websites', '15GB SSD storage', '20 MySQL databases', '60GB bandwidth', '25 email addresses', 'cPanel with Imunify360', 'Free SSL and daily backups']),
             dict(name='Maine Coon', desc='Unlimited websites, databases and bandwidth.', price='£9.99',
                  features=['Unlimited hosted websites', '20GB SSD storage', 'Unlimited MySQL databases', 'Unlimited bandwidth', '40 email addresses', 'cPanel with Imunify360', 'Free SSL and daily backups']),
         ],
         plans_note='Not sure which plan you need? <a href="/contact-us/">Get in touch</a> and we will tell you honestly.',
         features_title='Everything Included',
         features_intro='No upsells for the basics. Every plan comes with all of this as standard.',
         features=[
             ('cPanel Control Panel', 'The most widely used hosting control panel in the world. Manage domains, DNS, email, databases and files from one friendly dashboard.'),
             ('Imunify360 Security', 'Firewall, malware scanning and attack protection watching over every account, so threats are stopped before they reach your site.'),
             ('LiteSpeed Web Server', 'A fast web server with LSCache built in, so WordPress and WooCommerce sites load quickly.'),
             ('CloudLinux Isolation', 'Each account gets its own resources, so a busy neighbour on the server never slows your site down.'),
             ('Free SSL Certificates', 'Automatic SSL on every domain you host, for the padlock, secure forms and better search rankings.'),
             ('Daily Offsite Backups', 'Daily backups stored away from the server, with one-click restores from cPanel when you need them.'),
             ('One-Click Installs', 'Softaculous installs WordPress, Joomla and hundreds of other apps in a few clicks.'),
             ('PHP, Node.js and Python', 'Choose your PHP version from cPanel, and run Node.js and Python apps too. Free SSH access is included for developers.'),
             ('Free Migration', 'Moving from another host? We move your websites, email and databases for you, free of charge.'),
         ],
         faq=[
             ('What is cPanel?', 'cPanel is the most widely used web hosting control panel in the world. It gives you a simple dashboard to manage your websites, email, databases, files and one-click app installs.'),
             ('What does Imunify360 do?', 'Imunify360 is a security suite that protects every account on the server. It combines a firewall, malware scanning and protection against common attacks, so problems are caught and blocked automatically.'),
             ('Can you move my website from my current host?', 'Yes, and it is free. We move your websites, email and databases for you, with as little downtime as possible.'),
             ('Is WordPress supported?', 'Absolutely. Install WordPress in one click with Softaculous, and enjoy LiteSpeed caching tuned for WordPress, with the PHP version you choose.'),
             ('Are there any setup fees?', 'No. There are no setup fees and no hidden extras. You pay the monthly price shown for your plan.'),
         ]),
    dict(slug='website-maintenance', cls='lpd-service-maintenance', short='Website Maintenance',
         lead='We keep your WordPress website updated, backed up and running smoothly, so you can get on with running your business.',
         body='Updates, checks and fixes are handled for you by the same local team, and you can ask us whenever something does not look right.',
         items=['Plugin and theme updates', 'Security checks', 'Backup monitoring', 'Basic troubleshooting']),
    dict(slug='domains-and-dns', cls='lpd-service-dns', short='Domains and DNS',
         lead='We look after your domain names and DNS records, so your website and email keep working and nothing expires by accident.',
         body='From the records your email needs to be trusted, to renewal dates and certificates, we handle the technical side and explain it in plain English.',
         items=['DNS record management', 'SPF, DKIM and DMARC configuration', 'Domain expiry monitoring', 'SSL certificate monitoring']),
    dict(slug='business-email', cls='lpd-service-email', short='Business Email',
         lead='Professional email on your own domain, set up properly so your messages arrive where they should.',
         body='We configure your mailboxes, help move your existing email across, and sort out delivery problems when they happen.',
         items=['Custom domain email configuration', 'Mailbox migration assistance', 'Email authentication', 'Troubleshooting delivery problems']),
    dict(slug='website-security', cls='lpd-service-security', short='Website Security',
         lead='We keep an eye on the security of your website and tell you, in plain English, what needs doing.',
         body='Regular checks on scans, certificates and logins, with clear recommendations rather than jargon.',
         items=['Security configuration reviews', 'Malware scan monitoring', 'SSL checks', 'Login protection', 'Security recommendations']),
    dict(slug='microsoft-365', cls='lpd-service-m365', short='Microsoft 365',
         lead='Set-up and support for Microsoft 365, so your email, files and Teams work together.',
         body='Tell us how your business works and we will help you get Microsoft 365 set up to suit it, with a local team to call when you need help.',
         items=['Microsoft 365 licences on a 1-year commitment, billed monthly', 'Set-up on your own domain', 'Support from our local team'],
         # Selling prices: Microsoft's UK list price (annual commitment, ex VAT,
         # from 1 July 2026: Basic 5.75, Standard 11.55, Premium 18.10) plus a
         # 20% markup, plus Stripe's UK card fee (1.5% + 20p per user, the
         # cautious case), rounded up to .49/.99. Re-check when Microsoft
         # changes its prices.
         plans_title='Microsoft 365 Plans',
         plans_intro='Prices are per user, per month. Every plan has a 1-year commitment, billed monthly.',
         plans_subprice='Per user, per month, 1-year commitment',
         plans=[
             dict(name='Business Basic', desc='Email, Teams and storage, with the apps in your browser.', price='£7.49',
                  features=['Business email on your domain (50 GB mailbox)', 'Teams for chat, calls and meetings', '1 TB of OneDrive storage per user', 'Web and mobile Word, Excel and PowerPoint']),
             dict(name='Business Standard', desc='Everything in Basic, plus the full desktop apps.', price='£14.49', featured=True,
                  features=['Everything in Business Basic', 'Desktop Word, Excel, PowerPoint and Outlook', 'Install on up to 5 PCs or Macs per user', 'Webinars and appointment booking']),
             dict(name='Business Premium', desc='Everything in Standard, plus advanced security.', price='£22.49',
                  features=['Everything in Business Standard', 'Advanced threat protection', 'Device management with Intune', 'Conditional access for sign-ins']),
         ],
         plans_note='Need a different Microsoft 365 plan, or have licences already? <a href="/contact-us/">Get in touch</a> and we will sort it.'),
    dict(slug='hosted-pbx', cls='lpd-service-pbx', short='Hosted PBX',
         lead='A business phone system hosted in the cloud, so your number goes wherever you do.',
         body='Choose from many PBX solutions, like FreePBX, FusionPBX and more. Talk to us about what your business needs from its phones and we will explain the options in plain English.',
         items=['Your choice of PBX: FreePBX, FusionPBX and more', 'Powerful security and DDoS protection', '2 vCores at 4.0GHz', '4GB ECC RAM', '48GB SSD storage', 'Daily backups', 'Phone numbers available on request'],
         price='From £16.99 a month'),
]

WHY = [
    ('lpd-why-pricing', 'Fair, Transparent Pricing', 'You will always know exactly what you are paying and why. No hidden fees, no surprise charges, and no contracts designed to catch you out. Just a clear price for a service that works.'),
    ('lpd-why-care', 'We Actually Care', 'Everything we build and run gets real attention from a real person, not a template filled in and forgotten. We are a small North East England business, and everything we do has our name on it, so we make sure it is done properly.'),
    ('lpd-why-location', 'One Local Team', 'Websites, hosting, email, domains and security from one North East team, so there is always someone who knows your setup and picks up the phone.'),
]


def p(text, cls=None):
    if cls:
        return f'<!-- wp:paragraph {{"className":"{cls}"}} --><p class="{cls}">{text}</p><!-- /wp:paragraph -->'
    return f'<!-- wp:paragraph --><p>{text}</p><!-- /wp:paragraph -->'


def group(cls, inner):
    return f'<!-- wp:group {{"className":"{cls}"}} -->\n<div class="wp-block-group {cls}">\n{inner}\n</div>\n<!-- /wp:group -->'


def column(inner):
    return f'<!-- wp:column -->\n<div class="wp-block-column">\n{inner}\n</div>\n<!-- /wp:column -->'


def columns(cls, cols):
    return f'<!-- wp:columns {{"className":"{cls}"}} -->\n<div class="wp-block-columns {cls}">\n\n' + '\n\n'.join(cols) + '\n\n</div>\n<!-- /wp:columns -->'


def h(level, text):
    if level == 2:
        return f'<!-- wp:heading --><h2 class="wp-block-heading">{text}</h2><!-- /wp:heading -->'
    return f'<!-- wp:heading {{"level":{level}}} --><h{level} class="wp-block-heading">{text}</h{level}><!-- /wp:heading -->'


def button(cls, href, text):
    return f'<!-- wp:button {{"className":"{cls}"}} -->\n<div class="wp-block-button {cls}"><a class="wp-block-button__link wp-element-button" href="{href}">{text}</a></div>\n<!-- /wp:button -->'


def band(cls, inner):
    return group(cls, group('lpd-inner', inner))


def page(s):
    if s['items']:
        lis = '\n'.join(f'<!-- wp:list-item --><li>{i}</li><!-- /wp:list-item -->' for i in s['items'])
        included = f'<!-- wp:list {{"className":"lpd-service-list"}} -->\n<ul class="wp-block-list lpd-service-list">\n{lis}\n</ul>\n<!-- /wp:list -->'
    else:
        included = p('We are finalising the details of this service. Get in touch and we will talk you through what it can do for your business.', 'lpd-service-sub')
    if s.get('price'):
        included += '\n' + p(s['price'], 'lpd-service-price')
    tile = group(f'lpd-service-tile {s["cls"]}', h(2, "What's Included") + '\n' + included)
    prose = group('lpd-prose', p(s['lead'], 'lpd-lead') + '\n' + p(s['body']) + '\n<!-- wp:buttons -->\n<div class="wp-block-buttons">\n'
                  + button('lpd-btn-primary', '/contact-us/', 'Talk to Us') + '\n' + button('lpd-btn-secondary', '/#services', 'All Services') + '\n</div>\n<!-- /wp:buttons -->')
    intro = band('lpd-band', '\n' + columns('lpd-split lpd-split-top', [column(prose), column(tile)]) + '\n')
    cards = [column(group(f'lpd-card lpd-why-item {c}', h(3, t) + '\n' + p(x))) for c, t, x in WHY]
    why = band('lpd-band lpd-band-alt', '\n' + group('lpd-section-heading lpd-left', h(2, 'Why Lily Paw Digital')) + '\n\n' + columns('lpd-grid-3', cards) + '\n')
    cta = band('lpd-band lpd-cta-section', group('lpd-cta-banner', h(2, f'Talk to Us About {s["short"]}') + '\n'
               + p('Tell us what you need and we will get back to you with clear advice and a clear price. No pressure, no jargon.')
               + '\n<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + button('lpd-btn-primary', '/contact-us/', 'Talk to Us') + '\n</div>\n<!-- /wp:buttons -->'))
    sections = [intro]
    if s.get('plans'):
        cards = []
        for plan in s['plans']:
            inner = ''
            if plan.get('featured'):
                inner += p('Most Popular', 'lpd-pricing-badge') + '\n'
            inner += p(plan['name'], 'lpd-pricing-name') + '\n' + p(plan['desc'], 'lpd-pricing-desc') + '\n'
            inner += p(plan['price'], 'lpd-pricing-price') + '\n' + p(s.get('plans_subprice', 'Per user, per month'), 'lpd-pricing-subprice') + '\n'
            inner += '\n'.join(p(f, 'lpd-pricing-feature') for f in plan['features'])
            inner += '\n<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + button('lpd-btn-primary' if plan.get('featured') else 'lpd-btn-secondary', s.get('plan_button', ('Get Started', '/contact-us/'))[1], s.get('plan_button', ('Get Started', '/contact-us/'))[0]) + '\n</div>\n<!-- /wp:buttons -->'
            cls = 'lpd-pricing-card lpd-featured' if plan.get('featured') else 'lpd-pricing-card'
            cards.append(column(group(cls, inner)))
        heading = group('lpd-section-heading lpd-left', h(2, s['plans_title']) + '\n' + p(s['plans_intro']))
        sections.append(band('lpd-band lpd-pricing', '\n' + heading + '\n\n' + columns('lpd-colocation-pricing', cards) + '\n\n' + p(s['plans_note'], 'lpd-pricing-note') + '\n'))
    if s.get('features'):
        fcards = [column(group('lpd-card', h(3, t) + '\n' + p(x))) for t, x in s['features']]
        sections.append(band('lpd-band lpd-band-alt', '\n' + group('lpd-section-heading lpd-left', h(2, s['features_title']) + '\n' + p(s['features_intro'])) + '\n\n' + columns('lpd-grid-3', fcards) + '\n'))
    sections.append(why)
    if s.get('faq'):
        items = '\n\n'.join(group('lpd-faq-item', h(3, q) + '\n' + p(a)) for q, a in s['faq'])
        left = column(group('lpd-prose', h(2, 'Frequently Asked Questions') + '\n' + p('Something else you need to know? <a href="/contact-us/">Get in touch</a> and we will answer it.')))
        right = column(group('lpd-faq', group('lpd-faq-list', '\n' + items + '\n')))
        sections.append(band('lpd-band', '\n' + columns('lpd-split lpd-split-top lpd-split-narrow', [left, right]) + '\n'))
    sections.append(cta)
    return '\n\n'.join(sections) + '\n'


if __name__ == '__main__':
    for s in SERVICES:
        with open(f'lilypawdigital/inc/pages/{s["slug"]}.html', 'w') as f:
            f.write(page(s))
        print('wrote', s['slug'])
