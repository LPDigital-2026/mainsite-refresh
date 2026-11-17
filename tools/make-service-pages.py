#!/usr/bin/env python3
"""Generate lilypawdigital/inc/pages/<slug>.html for each service in SERVICES.

Run from the repository root after editing a service below, then run
python3 tools/update-page-fingerprints.py. Each page: an introduction
beside a "What's Included" tile, three reasons to choose Lily Paw Digital,
and a call to action.
"""
SERVICES = [
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
         items=[]),
    dict(slug='hosted-pbx', cls='lpd-service-pbx', short='Hosted PBX',
         lead='A business phone system hosted in the cloud, so your number goes wherever you do.',
         body='Talk to us about what your business needs from its phones and we will explain the options in plain English.',
         items=[]),
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
    tile = group(f'lpd-service-tile {s["cls"]}', h(3, "What's Included") + '\n' + included)
    prose = group('lpd-prose', p(s['lead'], 'lpd-lead') + '\n' + p(s['body']) + '\n<!-- wp:buttons -->\n<div class="wp-block-buttons">\n'
                  + button('lpd-btn-primary', '/contact-us/', 'Talk to Us') + '\n' + button('lpd-btn-secondary', '/#services', 'All Services') + '\n</div>\n<!-- /wp:buttons -->')
    intro = band('lpd-band', '\n' + columns('lpd-split lpd-split-top', [column(prose), column(tile)]) + '\n')
    cards = [column(group(f'lpd-card lpd-why-item {c}', h(3, t) + '\n' + p(x))) for c, t, x in WHY]
    why = band('lpd-band lpd-band-alt', '\n' + group('lpd-section-heading lpd-left', h(2, 'Why Lily Paw Digital')) + '\n\n' + columns('lpd-grid-3', cards) + '\n')
    cta = band('lpd-band lpd-cta-section', group('lpd-cta-banner', h(2, f'Talk to Us About {s["short"]}') + '\n'
               + p('Tell us what you need and we will get back to you with clear advice and a clear price. No pressure, no jargon.')
               + '\n<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + button('lpd-btn-primary', '/contact-us/', 'Talk to Us') + '\n</div>\n<!-- /wp:buttons -->'))
    return '\n\n'.join([intro, why, cta]) + '\n'


if __name__ == '__main__':
    for s in SERVICES:
        with open(f'lilypawdigital/inc/pages/{s["slug"]}.html', 'w') as f:
            f.write(page(s))
        print('wrote', s['slug'])
