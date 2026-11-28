<?php
/**
 * Suggested titles and descriptions for the site's pages, used until a
 * different one is typed into the SEO box on the page. Titles stay under
 * 60 characters and descriptions between 120 and 160, the lengths Google
 * shows in full.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function lpd_seo_defaults() {
	return array(
		'home'                => array( 'Lily Paw Digital | Web Design, Hosting & IT, North East', 'Websites, hosting, business email, Microsoft 365, security and colocation for North East businesses. Clear pricing, real people, no jargon.' ),
		'web-design'          => array( 'Small Business Web Design, North East | Lily Paw Digital', 'Websites designed and built for North East businesses and hosted on UK hardware, from £99. See yours live on a demo address before you pay.' ),
		'colocation'          => array( 'Colocation in Newcastle upon Tyne | Lily Paw Digital', 'Colocation in Newcastle upon Tyne from £65.99 a month. Rack space at Reliance House with N+1 power and cooling, 24/7 access and remote hands.' ),
		'blog'                => array( 'Blog: Plain-English Tech Guides | Lily Paw Digital', 'Plain-English guides to websites, hosting, email, Microsoft 365 and security for small businesses, from our team in the North East of England.' ),
		'our-work'            => array( 'Our Work: Websites We Have Built | Lily Paw Digital', 'Websites we have designed and built for North East organisations, including Ryton Singers and Ryton Cricket Club, and what their owners said.' ),
		'about-us'            => array( 'About Us | Lily Paw Digital, North East England', 'Lily Paw Digital is a North East technology business founded in 2026 and named after Lily the cat. Websites, hosting, email, security and more.' ),
		'contact-us'          => array( 'Contact Us | Lily Paw Digital', 'Get in touch with Lily Paw Digital. Call 0333 112 7191 or send us a message, and a real person in the North East will get back to you.' ),
		'get-started'         => array( 'Get Started | Lily Paw Digital', 'Tell us what you need and get a clear price. Start your website, hosting, email or colocation with Lily Paw Digital in a few simple steps.' ),
		'website-maintenance' => array( 'WordPress Website Maintenance | Lily Paw Digital', 'WordPress maintenance for small businesses: plugin and theme updates, security checks, backup monitoring and troubleshooting by a local team.' ),
		'domains-and-dns'     => array( 'Domain and DNS Management | Lily Paw Digital', 'We manage your domain and DNS records, set up SPF, DKIM and DMARC, and monitor domain and SSL expiry so your website and email keep working.' ),
		'business-email'      => array( 'Business Email on Your Own Domain | Lily Paw Digital', 'Professional email on your own domain, set up properly. Mailbox migration, email authentication and delivery troubleshooting from a local team.' ),
		'website-security'    => array( 'Website Security Monitoring | Lily Paw Digital', 'Website security monitoring for small businesses: configuration reviews, malware scan monitoring, SSL checks, login protection and clear advice.' ),
		'microsoft-365'       => array( 'Microsoft 365 for Business from £7.49 | Lily Paw Digital', 'Microsoft 365 Business Basic, Standard and Premium with set-up and local support. From £7.49 per user per month on a 1-year commitment.' ),
		'areas-we-cover'      => array( 'Areas We Cover: the North East and Beyond | Lily Paw Digital', 'Web design, hosting, email and security across the North East, from Newcastle and Sunderland to Durham and Teesside, and anywhere else in the UK.' ),
		'web-hosting'         => array( 'UK cPanel Web Hosting from £5.99 | Lily Paw Digital', 'cPanel web hosting on UK hardware from £5.99 a month, with Imunify360 security, LiteSpeed, free SSL, daily offsite backups and free migration.' ),
		'hosted-pbx'          => array( 'Hosted PBX Business Phones | Lily Paw Digital', 'Cloud business phone systems on FreePBX, FusionPBX and more, from £16.99 a month. Security, DDoS protection, daily backups and numbers on request.' ),
		// Starter articles: shorter titles than their headlines, so Google shows them in full.
		'spf-dkim-dmarc-explained'                    => array( 'SPF, DKIM and DMARC Explained | Lily Paw Digital', 'Three DNS records decide whether your business emails reach the inbox or the spam folder. Here is what SPF, DKIM and DMARC do and how to set them up.' ),
		'microsoft-365-business-plans-compared'       => array( 'Microsoft 365 Basic vs Standard vs Premium', 'The three Microsoft 365 business plans compared in plain English, so you can pick the right one for your team without paying for features you will not use.' ),
		'does-my-small-business-need-a-website'       => array( 'Does Your Small Business Need a Website in 2026?', 'A social media page is not enough on its own. What a simple website does for a local business, what it needs to include and how to get one built.' ),
		'what-is-colocation'                          => array( 'What Is Colocation? A Plain-English Guide', 'Colocation moves your own servers into a professional data centre. How it works, how it compares with the cloud and the office, and who it suits.' ),
		'signs-your-wordpress-site-needs-maintenance' => array( '5 Signs Your WordPress Site Needs Maintenance', 'Waiting updates, missing backups and slow pages all add up. Five warning signs your WordPress site needs attention, and what good maintenance looks like.' ),
		'hosted-pbx-explained'                        => array( 'Hosted PBX Explained: Business Phones in the Cloud', 'The UK copper phone network is being switched off. What a hosted PBX is, how FreePBX and FusionPBX compare, and whether you can keep your number.' ),
		'domain-and-ssl-expiry'                       => array( 'Domain and SSL Expiry: Keep Your Site Online', 'A missed domain or SSL renewal can take your website and email offline overnight. How to make sure it never happens to your business.' ),
		'how-to-spot-a-phishing-email'                => array( 'How to Spot a Phishing Email | Lily Paw Digital', 'Phishing is the most common cyber attack on UK businesses. Eight warning signs to share with your team, and what to do if someone has already clicked.' ),
		'protect-your-small-business-website'         => array( 'Protect Your Small Business Website from Hackers', 'Most hacked websites are found by automated tools, not chosen. Six practical steps to keep your WordPress site safe, and the signs it may already be hacked.' ),
		'why-is-my-website-slow'                      => array( 'Why Is My Website Slow? 7 Causes and Fixes', 'A slow website loses customers and search rankings. The seven most common reasons small business sites are slow, how to measure it, and the fixes that work.' ),
		'local-seo-guide'                             => array( 'Local SEO: Get Your Business Found on Google', 'How Google decides which local businesses to show, and six practical steps to appear in the map results, from your Business Profile to your reviews.' ),
		'cyber-essentials-explained'                  => array( 'Cyber Essentials Explained | Lily Paw Digital', 'Cyber Essentials is the UK government-backed scheme for basic cyber security. The five controls in plain English, the two levels, and why it is worth doing.' ),
		'how-to-choose-a-colocation-provider'         => array( 'Choosing a Colocation Provider in Newcastle', 'Ten questions to ask before choosing colocation in Newcastle upon Tyne, from power and cooling to access and security, and what good answers sound like.' ),
		'how-much-does-a-website-cost'                => array( 'How Much Does a Website Cost in the UK? | Lily Paw', 'What drives the cost of a small business website in the UK, the running costs after launch, the questions to ask any web designer, and what we charge.' ),
		'what-is-web-hosting'                         => array( 'What Is Web Hosting? A Plain-English Guide', 'What web hosting is, how it differs from your domain, what storage, bandwidth and databases mean on a hosting plan, and how to choose the right one.' ),
		'move-website-to-a-new-host'                  => array( 'Move Your Website to a New Host Without Downtime', 'A step-by-step guide to moving your website and email to a new host without breaking anything, from backups and testing to DNS and switching over.' ),
		'microsoft-365-vs-google-workspace'           => array( 'Microsoft 365 vs Google Workspace for Business', 'Microsoft 365 and Google Workspace compared in plain English: what they share, where each stands out, and simple questions to help your business choose.' ),
	);
}
