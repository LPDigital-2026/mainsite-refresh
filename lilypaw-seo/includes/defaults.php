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
		'colocation'          => array( 'Colocation in Newcastle | Lily Paw Digital', 'Rack space at Reliance House, Newcastle, from £65.99 a month. N+1 power and cooling, 24/7 access, remote hands and carrier-neutral connectivity.' ),
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
		'hosted-pbx'          => array( 'Hosted PBX Business Phones | Lily Paw Digital', 'Cloud business phone systems on FreePBX, FusionPBX and more, from £16.99 a month. Security, DDoS protection, daily backups and numbers on request.' ),
	);
}
