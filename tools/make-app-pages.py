#!/usr/bin/env python3
"""Writes one hosting page per popular Softaculous app (lilypawdigital/inc/pages/<slug>.html)
from APPS below. Each page has its own text: what the app is, why our hosting suits it,
which plan to pick, and its own questions (which become FAQ structured data). Shared
facts (the plans and what every plan includes) come from the Web Hosting definition in
make-service-pages.py, so a price change there reaches every app page.
Run from the repository root, then python3 tools/update-page-fingerprints.py.
Keep each page genuinely different: Google treats near-identical pages as doorway pages."""
import importlib.util

spec = importlib.util.spec_from_file_location('svc', 'tools/make-service-pages.py')
svc = importlib.util.module_from_spec(spec); spec.loader.exec_module(svc)
p, group, column, columns, h, button, band = svc.p, svc.group, svc.column, svc.columns, svc.h, svc.button, svc.band
HOSTING = next(s for s in svc.SERVICES if s['slug'] == 'web-hosting')
PLANS = {pl['name']: pl for pl in HOSTING['plans']}

APPS = [
    dict(slug='wordpress-hosting', name='WordPress', title='WordPress Hosting',
         lead='Fast, secure WordPress hosting on UK servers, with LiteSpeed caching and one-click installs.',
         body='WordPress powers over 40% of all websites, from personal blogs to busy business sites. Install it in one click with Softaculous, and our LiteSpeed servers with LSCache keep it quick, while Imunify360 keeps it safe from the attacks WordPress sites see every day.',
         why=[('Built for Speed', 'LiteSpeed and the free LSCache plugin cache your pages so they load quickly, which helps both visitors and your Google rankings.'),
              ('Protected From Attacks', 'Imunify360 blocks the password guessing and plugin exploits aimed at WordPress sites, and scans for malware.'),
              ('Easy to Look After', 'Softaculous can keep WordPress updated for you, and daily backups mean a bad update is never a disaster.')],
         plans=[('Singapura', 'one or a few WordPress sites, such as a business site and a blog'), ('Siamese', 'growing sites with more content, images or visitors'), ('Maine Coon', 'agencies and anyone running lots of WordPress sites')],
         faq=[('Can you move my existing WordPress site?', 'Yes, for free. We move your site, database and email, and check everything works before switching over.'),
              ('Do I need a caching plugin?', 'Install the free LiteSpeed Cache plugin. It works directly with our LiteSpeed servers and is usually faster than other caching plugins.'),
              ('Will WordPress keep itself updated?', 'WordPress can update itself, and Softaculous can also handle updates for you. We still recommend keeping an eye on plugins, or our website maintenance service can do it for you.')]),
    dict(slug='woocommerce-hosting', name='WooCommerce', title='WooCommerce Hosting',
         lead='Hosting for WooCommerce shops, with the speed, security and backups an online store needs.',
         body='WooCommerce is the free plugin that turns WordPress into a full online shop. Install WordPress in one click with Softaculous, add WooCommerce from the plugins screen, and start selling. Our LiteSpeed servers keep product pages fast, and Imunify360 and free SSL keep checkout secure.',
         why=[('Fast Product Pages', 'LiteSpeed caching speeds up your shop pages while leaving the basket and checkout working as they should.'),
              ('Secure Checkout', 'Free SSL on every domain, with Imunify360 watching for malware and attacks, protects your customers and their orders.'),
              ('Daily Backups', 'Orders and products change every day, so daily remote backups with one-click restores give you a safety net.')],
         plans=[('Singapura', 'a small shop with a modest number of products'), ('Siamese', 'most small shops, with room for more products and images'), ('Maine Coon', 'larger catalogues, or a shop alongside other sites')],
         faq=[('Can I take card payments?', 'Yes. WooCommerce works with payment providers such as Stripe and PayPal through free plugins, and our free SSL keeps checkout secure.'),
              ('Can you move my existing shop?', 'Yes, for free. We move your site, products, orders and email, and check the shop works before switching over.'),
              ('Would you rather we built the shop?', 'Our E-commerce web design package builds a complete WooCommerce shop for you, with hosting included.')]),
    dict(slug='joomla-hosting', name='Joomla', title='Joomla Hosting',
         lead='Joomla hosting on UK servers, installed in one click and backed up every day.',
         body='Joomla is a free, open-source content management system known for its flexibility, with multilingual support built in. It suits organisations, clubs and businesses that want more structure than a simple blog. Install it in seconds with Softaculous and manage everything from cPanel.',
         why=[('One-Click Install', 'Softaculous installs Joomla in a few clicks and can keep it updated for you.'),
              ('Multilingual Ready', 'Joomla handles content in several languages without extra plugins, and our servers run the PHP versions it needs.'),
              ('Safe and Backed Up', 'Imunify360 protection, free SSL and daily remote backups come with every plan.')],
         plans=[('Singapura', 'a single Joomla site or two'), ('Siamese', 'larger sites, or several Joomla sites'), ('Maine Coon', 'busy sites or lots of installations')],
         faq=[('Which PHP version will Joomla use?', 'You choose. cPanel lets you switch PHP versions, so you can match what your version of Joomla and its extensions need.'),
              ('Can you move my existing Joomla site?', 'Yes, for free. We move your files, database and email for you.'),
              ('Can I run Joomla alongside other sites?', 'Yes. Every plan hosts several websites, and Maine Coon hosts unlimited sites.')]),
    dict(slug='drupal-hosting', name='Drupal', title='Drupal Hosting',
         lead='Drupal hosting on UK servers, for structured, content-rich websites.',
         body='Drupal is a powerful open-source content management system used by universities, charities and public bodies. It is at its best with lots of structured content, detailed user permissions and complex sites. Install it with Softaculous and get the developer tools Drupal people expect, including free SSH access and cron jobs.',
         why=[('Developer Friendly', 'Free SSH access, cron jobs and your choice of PHP version from cPanel, so you can work the way Drupal developers do.'),
              ('Room to Grow', 'Up to unlimited databases and websites, with 2 CPU cores and 2GB RAM on every plan.'),
              ('Secure by Default', 'Imunify360, CageFS isolation and free SSL protect your site and the data it holds.')],
         plans=[('Singapura', 'a small Drupal site'), ('Siamese', 'larger sites with more content and modules'), ('Maine Coon', 'several Drupal sites or development and live copies side by side')],
         faq=[('Can I use Composer and Drush?', 'Free SSH access is included on every plan, so you can work from the command line.'),
              ('Can you move my existing Drupal site?', 'Yes, for free. We move your files and database and check the site works before switching over.'),
              ('Does Drupal need cron?', 'Drupal uses scheduled tasks for things like search indexing, and you can set up cron jobs in cPanel in a few clicks.')]),
    dict(slug='prestashop-hosting', name='PrestaShop', title='PrestaShop Hosting',
         lead='PrestaShop hosting on UK servers, for online shops that need to be fast and secure.',
         body='PrestaShop is a free, open-source shop platform, especially popular across Europe, with thousands of modules and themes. It handles multiple languages and currencies out of the box, which makes it a strong choice if you sell abroad. Install it with Softaculous, and our LiteSpeed servers, Imunify360 security and free SSL give your shop a solid home.',
         why=[('Quick to Launch', 'Softaculous installs PrestaShop in a few clicks, ready for your products and theme.'),
              ('Secure Checkout', 'Free SSL on every domain, with Imunify360 and ModSecurity protecting your shop from attacks.'),
              ('Every Order Backed Up', 'Daily remote backups with one-click restores protect your products, customers and orders.')],
         plans=[('Singapura', 'a small shop just starting out'), ('Siamese', 'most small shops, with more room for products and images'), ('Maine Coon', 'larger catalogues or several shops')],
         faq=[('Is PrestaShop free?', 'Yes, PrestaShop itself is free and open source. Some modules and themes are paid extras.'),
              ('Can you move my existing shop?', 'Yes, for free. We move your files and database and check everything works before switching over.'),
              ('Which plan should I start on?', 'Most small shops are comfortable on Siamese. You can move up at any time if you need more room.')]),
    dict(slug='opencart-hosting', name='OpenCart', title='OpenCart Hosting',
         lead='OpenCart hosting on UK servers, for simple, lightweight online shops.',
         body='OpenCart is a free, open-source shop platform known for being lightweight and easy to use. It is a good choice if you want a straightforward online shop without the complexity of larger platforms, and it can even run several stores from one admin area. Install it in one click with Softaculous.',
         why=[('Lightweight and Quick', 'OpenCart is light on resources, and LiteSpeed servers keep it responsive.'),
              ('Secure Payments', 'Free SSL certificates and Imunify360 protection keep checkout and customer details safe.'),
              ('Backed Up Daily', 'Daily remote backups with one-click restores, so your shop can always be put right.')],
         plans=[('Singapura', 'a small OpenCart shop'), ('Siamese', 'a growing shop with more products and images'), ('Maine Coon', 'several shops or a large catalogue')],
         faq=[('Is OpenCart free?', 'Yes. OpenCart is free and open source, with optional paid extensions and themes.'),
              ('Can you move my existing OpenCart shop?', 'Yes, for free. We move your files and database for you.'),
              ('Can I use my own domain?', 'Yes. Every plan hosts websites on your own domain, with free SSL and email addresses included.')]),
    dict(slug='moodle-hosting', name='Moodle', title='Moodle Hosting',
         lead='Moodle hosting on UK servers, for online courses, training and learning.',
         body='Moodle is the open-source learning platform used by schools, universities and training providers around the world. Use it to run courses, share materials, set quizzes and track progress. Install it with Softaculous, and we will help you choose a plan with the room your courses need.',
         why=[('UK Hosting for Learners', 'Your courses and learner data stay on UK servers, with free SSL on every page.'),
              ('Cron Built In', 'Moodle relies on regular scheduled tasks, and cron jobs are easy to set up in cPanel.'),
              ('Backed Up Every Day', 'Daily remote backups protect course content, grades and submissions.')],
         plans=[('Siamese', 'a small number of courses and learners'), ('Maine Coon', 'more courses, more learners and more uploaded files, with the most storage')],
         faq=[('How many learners can Moodle handle on shared hosting?', 'It depends on how many people use it at once and how much they upload. Shared hosting suits small to medium course sites. Tell us what you are planning and we will give you an honest answer.'),
              ('Can you move my existing Moodle site?', 'Yes, for free. We move your files, database and course data for you.'),
              ('Does Moodle need cron?', 'Yes. Moodle needs its scheduled task to run regularly, and you can set this up in cPanel in a couple of clicks.')]),
    dict(slug='phpbb-hosting', name='phpBB', title='phpBB Forum Hosting',
         lead='phpBB forum hosting on UK servers, for communities, clubs and support forums.',
         body='phpBB is free, open-source forum software that has powered online communities for over twenty years. Install it with Softaculous and have a forum ready for your members in minutes, protected by Imunify360 and free SSL.',
         why=[('Forum Ready in Minutes', 'Softaculous installs phpBB in a few clicks, so you can start setting up boards straight away.'),
              ('Spam and Attack Protection', 'Imunify360, a comprehensive firewall and ModSecurity help keep bots and attacks away from your forum.'),
              ('Your Community, Backed Up', 'Daily remote backups protect every post and member account.')],
         plans=[('Singapura', 'a club or community forum'), ('Siamese', 'busier forums with more members and attachments'), ('Maine Coon', 'large communities or several forums')],
         faq=[('Is phpBB free?', 'Yes. phpBB is free and open source, with many free extensions and styles.'),
              ('Can you move my existing forum?', 'Yes, for free. We move your files and database, including every post and member.'),
              ('Can the forum sit alongside my website?', 'Yes. Install phpBB in a folder or on a subdomain alongside your main site on the same plan.')]),
    dict(slug='mediawiki-hosting', name='MediaWiki', title='MediaWiki Hosting',
         lead='MediaWiki hosting on UK servers, for wikis, documentation and knowledge bases.',
         body='MediaWiki is the free, open-source software behind Wikipedia. Use it for a company knowledge base, product documentation, a club wiki or a fan site. Install it with Softaculous and control who can read and edit it.',
         why=[('The Software Behind Wikipedia', 'A proven, well-documented platform for collaborative writing, installed in one click.'),
              ('Control Who Has Access', 'Keep your wiki private, open it to the world, or restrict access by IP address for internal use.'),
              ('Every Edit Backed Up', 'MediaWiki keeps the history of every page, and daily remote backups protect the whole wiki.')],
         plans=[('Singapura', 'a team or club wiki'), ('Siamese', 'larger wikis with lots of images and files'), ('Maine Coon', 'several wikis or very large knowledge bases')],
         faq=[('Can I make my wiki private?', 'Yes. MediaWiki can require a login to read or edit, and you can also restrict access by IP address from cPanel.'),
              ('Can you move my existing wiki?', 'Yes, for free. We move your files and database, including page history.'),
              ('Is MediaWiki free?', 'Yes. MediaWiki is free and open source.')]),
    dict(slug='matomo-hosting', name='Matomo', title='Matomo Analytics Hosting',
         lead='Host Matomo analytics on UK servers and keep your visitor data in your own hands.',
         body='Matomo is free, open-source website analytics, a popular alternative to Google Analytics. When you host it yourself, your visitor data stays on your own hosting rather than with a third party. Install it with Softaculous and add the tracking code to your sites.',
         why=[('Your Data, Your Hosting', 'Visitor statistics are stored on your own UK hosting, not shared with an advertising company.'),
              ('Track All Your Sites', 'One Matomo installation can track several websites, ideal alongside the sites on your plan.'),
              ('Reports Kept Up to Date', 'Set up a cron job in cPanel so Matomo processes your reports automatically.')],
         plans=[('Singapura', 'tracking a few small websites'), ('Siamese', 'busier sites with more visits to record'), ('Maine Coon', 'lots of sites or high traffic, with unlimited databases')],
         faq=[('Is Matomo free?', 'Yes. Matomo is free and open source when you host it yourself.'),
              ('Can Matomo track sites hosted elsewhere?', 'Yes. Add the tracking code to any website, wherever it is hosted.'),
              ('Does Matomo help with privacy rules?', 'Matomo has privacy features, such as anonymising visitor IP addresses, which can help. How you set it up and use it is still up to you.')]),
]


def plan_line(name, use):
    pl = PLANS[name]
    return f'<strong>{name}, {pl["price"]} a month:</strong> {use}.'


def page(a):
    included = ['One-click install with Softaculous', 'LiteSpeed web server and LSCache', 'Imunify360 security', 'Free SSL certificates',
                'Daily remote backups', '2 CPU cores and 2GB RAM', 'Free migration from your current host']
    lis = '\n'.join(f'<!-- wp:list-item --><li>{i}</li><!-- /wp:list-item -->' for i in included)
    tile = group('lpd-service-tile lpd-service-hosting', h(2, "What's Included") + '\n<!-- wp:list {"className":"lpd-service-list"} -->\n<ul class="wp-block-list lpd-service-list">\n' + lis + '\n</ul>\n<!-- /wp:list -->\n' + p('From ' + HOSTING['plans'][0]['price'] + ' a month', 'lpd-service-price'))
    btns = '<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + button('lpd-btn-primary', '/web-hosting/', 'See Hosting Plans') + '\n' + button('lpd-btn-secondary', '/contact-us/', 'Talk to Us') + '\n</div>\n<!-- /wp:buttons -->'
    intro = band('lpd-band', '\n' + columns('lpd-split lpd-split-top', [column(group('lpd-prose', p(a['lead'], 'lpd-lead') + '\n' + p(a['body']) + '\n' + btns)), column(tile)]) + '\n')
    cards = [column(group('lpd-card', h(3, t) + '\n' + p(x))) for t, x in a['why']]
    why = band('lpd-band lpd-band-alt', '\n' + group('lpd-section-heading lpd-left', h(2, f'Why Host {a["name"]} With Us')) + '\n\n' + columns('lpd-grid-3', cards) + '\n')
    pl = '\n'.join(f'<!-- wp:list-item --><li>{plan_line(n, u)}</li><!-- /wp:list-item -->' for n, u in a['plans'])
    plans = band('lpd-band', '\n' + columns('lpd-split lpd-split-top lpd-split-narrow', [
        column(group('lpd-prose', h(2, f'Which Plan Is Right for {a["name"]}?') + '\n' + p('Every plan includes cPanel, Imunify360, free SSL and daily backups. You can move up at any time.'))),
        column(group('lpd-prose', '<!-- wp:list {"className":"lpd-checklist lpd-checklist-single"} -->\n<ul class="wp-block-list lpd-checklist lpd-checklist-single">\n' + pl + '\n</ul>\n<!-- /wp:list -->\n' + btns))]) + '\n')
    items = '\n\n'.join(group('lpd-faq-item', h(3, q) + '\n' + p(x)) for q, x in a['faq'])
    faq = band('lpd-band lpd-band-alt', '\n' + columns('lpd-split lpd-split-top lpd-split-narrow', [
        column(group('lpd-prose', h(2, 'Frequently Asked Questions') + '\n' + p('Something else you need to know? <a href="/contact-us/">Get in touch</a> and we will answer it.'))),
        column(group('lpd-faq', group('lpd-faq-list', '\n' + items + '\n')))]) + '\n')
    cta = band('lpd-band lpd-cta-section', group('lpd-cta-banner', h(2, f'Get Started With {a["name"]}') + '\n'
               + p('Pick a plan and install ' + a['name'] + ' in one click, or talk to us first. Moving from another host? We will move it for you, free.') + '\n'
               + '<!-- wp:buttons -->\n<div class="wp-block-buttons">\n' + button('lpd-btn-primary', '/web-hosting/', 'See Hosting Plans') + '\n</div>\n<!-- /wp:buttons -->'))
    return '\n\n'.join([intro, why, plans, faq, cta]) + '\n'


if __name__ == '__main__':
    for a in APPS:
        open(f'lilypawdigital/inc/pages/{a["slug"]}.html', 'w').write(page(a))
    print('wrote', len(APPS), 'app pages:', ', '.join(a['slug'] for a in APPS))
