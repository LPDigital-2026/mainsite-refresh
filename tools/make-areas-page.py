#!/usr/bin/env python3
"""Writes lilypawdigital/inc/pages/areas-we-cover.html from the county and
town list in lilypawdigital/inc/areas.php (the same list the structured data
uses). Run from the repository root, then python3 tools/update-page-fingerprints.py."""
import importlib.util, json, subprocess

spec = importlib.util.spec_from_file_location('svc', 'tools/make-service-pages.py')
svc = importlib.util.module_from_spec(spec); spec.loader.exec_module(svc)
p, group, column, columns, h, button, band = svc.p, svc.group, svc.column, svc.columns, svc.h, svc.button, svc.band

areas = json.loads(subprocess.check_output(['php', '-r', 'define("ABSPATH",1); require "lilypawdigital/inc/areas.php"; echo json_encode(lpd_service_areas());']))

def town_list(towns):
    return ', '.join(towns[:-1]) + ' and ' + towns[-1] + '.'

def buttons(*b):
    return '<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + '\n'.join(button(*x) for x in b) + '\n</div>\n<!-- /wp:buttons -->'

services = [('/web-design/', 'Web design'), ('/web-hosting/', 'Web hosting'), ('/business-email/', 'Business email'), ('/microsoft-365/', 'Microsoft 365'),
            ('/website-maintenance/', 'Website maintenance'), ('/website-security/', 'Website security'), ('/domains-and-dns/', 'Domains and DNS'),
            ('/hosted-pbx/', 'Hosted PBX phone systems'), ('/colocation/', 'Colocation in Newcastle')]
lis = '\n'.join(f'<!-- wp:list-item --><li><a href="{u}">{t}</a></li><!-- /wp:list-item -->' for u, t in services)
tile = group('lpd-service-tile lpd-service-areas', h(2, 'What We Offer') + '\n<!-- wp:list {"className":"lpd-service-list"} -->\n<ul class="wp-block-list lpd-service-list">\n' + lis + '\n</ul>\n<!-- /wp:list -->')
prose = group('lpd-prose',
    p('We cover everywhere. We are based in the North East and work right across it, from the Scottish border to the Tees, and with businesses anywhere else in the UK and beyond.', 'lpd-lead') + '\n'
    + p('Most of what we do, from building websites to looking after hosting, email and Microsoft 365, happens online. So wherever you are, you get the same service and the same local team on the end of the phone. Our colocation space is at Reliance House in Newcastle, within easy reach of the whole North East.') + '\n'
    + p('Our clients so far include <a href="/our-work/">Ryton Singers and Ryton Cricket Club</a> in Gateshead.') + '\n'
    + buttons(('lpd-btn-primary', '/contact-us/', 'Talk to Us'), ('lpd-btn-secondary', '/#services', 'All Services')))
intro = band('lpd-band', '\n' + columns('lpd-split lpd-split-top', [column(prose), column(tile)]) + '\n')

cards = [column(group('lpd-card', h(3, county) + '\n' + p(town_list(towns)))) for county, towns in areas.items()]
where = band('lpd-band lpd-band-alt', '\n' + group('lpd-section-heading lpd-left', h(2, 'Towns and Cities We Cover') + '\n' + p('Across Tyne and Wear, Northumberland, County Durham and the Tees Valley.')) + '\n\n'
             + columns('lpd-grid-2', cards) + '\n\n' + p('Do not see your town? We cover it, in the North East or anywhere else. <a href="/contact-us/">Get in touch</a>.', 'lpd-pricing-note') + '\n')

cta = band('lpd-band lpd-cta-section', group('lpd-cta-banner', h(2, 'Wherever You Are, We Cover It') + '\n'
           + p('Tell us what you need and we will get back to you with clear advice and a clear price. No pressure, no jargon.') + '\n'
           + buttons(('lpd-btn-primary', '/contact-us/', 'Talk to Us'))))

open('lilypawdigital/inc/pages/areas-we-cover.html', 'w').write('\n\n'.join([intro, where, cta]) + '\n')
print('wrote areas-we-cover:', sum(len(t) for t in areas.values()), 'towns in', len(areas), 'areas')
