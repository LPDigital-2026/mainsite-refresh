<?php
/**
 * Starter blog articles. Appearance → Page Setup → "Add draft articles"
 * saves them as drafts (never published) for the owner to read, edit,
 * give a featured image and publish. An article is skipped if a post
 * with the same slug already exists, so the button is safe to press twice.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function lpd_draft_articles() {
	return array(
		'spf-dkim-dmarc-explained'          => array(
			'title'    => 'SPF, DKIM and DMARC Explained: Stop Your Emails Going to Spam',
			'file'     => 'spf-dkim-dmarc.html',
			'category' => 'Email',
			'tags'     => array( 'Email', 'DNS', 'Security' ),
			'excerpt'  => 'Three DNS records decide whether your business emails reach the inbox or the spam folder. Here is what SPF, DKIM and DMARC do and how to set them up.',
		),
		'microsoft-365-business-plans-compared' => array(
			'title'    => 'Microsoft 365 Business Basic vs Standard vs Premium: Which Do You Need?',
			'file'     => 'microsoft-365-plans.html',
			'category' => 'Microsoft 365',
			'tags'     => array( 'Microsoft 365', 'Email', 'Security' ),
			'excerpt'  => 'The three Microsoft 365 business plans compared in plain English, so you can pick the right one for your team without paying for features you will not use.',
		),
		'does-my-small-business-need-a-website' => array(
			'title'    => 'Does Your Small Business Still Need a Website in 2026?',
			'file'     => 'small-business-website.html',
			'category' => 'Web Design',
			'tags'     => array( 'Web Design', 'Small Business', 'Local SEO' ),
			'excerpt'  => 'A social media page is not enough on its own. What a simple website does for a local business, and what it needs to include.',
		),
		'what-is-colocation'                => array(
			'title'    => 'What Is Colocation, and Is It Right for Your Business?',
			'file'     => 'what-is-colocation.html',
			'category' => 'Colocation',
			'tags'     => array( 'Colocation', 'Data Centre', 'Newcastle' ),
			'excerpt'  => 'Colocation moves your own servers into a professional data centre. How it works, how it compares with the cloud and the office, and who it suits.',
		),
		'signs-your-wordpress-site-needs-maintenance' => array(
			'title'    => 'Five Signs Your WordPress Website Needs Maintenance',
			'file'     => 'wordpress-maintenance.html',
			'category' => 'Website Maintenance',
			'tags'     => array( 'WordPress', 'Security', 'Backups' ),
			'excerpt'  => 'Waiting updates, missing backups and slow pages all add up. Five warning signs your WordPress site needs attention, and what good maintenance looks like.',
		),
		'hosted-pbx-explained'              => array(
			'title'    => 'Hosted PBX Explained: Business Phones Ready for the Digital Switchover',
			'file'     => 'hosted-pbx.html',
			'category' => 'Hosted PBX',
			'tags'     => array( 'Hosted PBX', 'Phones', 'Digital Switchover' ),
			'excerpt'  => 'The UK copper phone network is being switched off. What a hosted PBX is, how FreePBX and FusionPBX compare, and whether you can keep your number.',
		),
		'domain-and-ssl-expiry'             => array(
			'title'    => 'Domain and SSL Expiry: How to Stop Your Website Going Offline',
			'file'     => 'domain-and-ssl-expiry.html',
			'category' => 'Domains and DNS',
			'tags'     => array( 'Domains', 'SSL', 'Security' ),
			'excerpt'  => 'A missed domain or SSL renewal can take your website and email offline overnight. How to make sure it never happens to your business.',
		),
		'how-to-spot-a-phishing-email'      => array(
			'title'    => 'How to Spot a Phishing Email: A Guide for Small Business Teams',
			'file'     => 'phishing.html',
			'category' => 'Email',
			'tags'     => array( 'Email', 'Phishing', 'Security' ),
			'excerpt'  => 'Phishing is the most common cyber attack on UK businesses. Eight warning signs to share with your team, and what to do if someone has already clicked.',
		),
		'protect-your-small-business-website' => array(
			'title'    => 'How to Protect Your Small Business Website from Hackers',
			'file'     => 'website-security.html',
			'category' => 'Website Security',
			'tags'     => array( 'WordPress', 'Security', 'Backups' ),
			'excerpt'  => 'Most hacked websites are found by automated tools, not chosen. Six practical steps to keep your WordPress site safe, and the signs it may already be hacked.',
		),
		'why-is-my-website-slow'            => array(
			'title'    => 'Why Is My Website Slow? Seven Common Causes and How to Fix Them',
			'file'     => 'slow-website.html',
			'category' => 'Website Maintenance',
			'tags'     => array( 'WordPress', 'Speed', 'Hosting' ),
			'excerpt'  => 'A slow website loses customers and search rankings. The seven most common reasons small business sites are slow, how to measure it, and the fixes that work.',
		),
		'local-seo-guide'                   => array(
			'title'    => 'Local SEO: How to Get Your Business Found on Google Nearby',
			'file'     => 'local-seo.html',
			'category' => 'Web Design',
			'tags'     => array( 'Local SEO', 'Small Business', 'Google' ),
			'excerpt'  => 'How Google decides which local businesses to show, and six practical steps to appear in the map results, from your Business Profile to reviews.',
		),
		'cyber-essentials-explained'        => array(
			'title'    => 'Cyber Essentials Explained: What It Is and How to Get Ready',
			'file'     => 'cyber-essentials.html',
			'category' => 'Website Security',
			'tags'     => array( 'Cyber Essentials', 'Security', 'Microsoft 365' ),
			'excerpt'  => 'Cyber Essentials is the UK government-backed scheme for basic cyber security. The five controls in plain English, the two levels, and why it is worth doing.',
		),
		'how-to-choose-a-colocation-provider' => array(
			'title'    => 'How to Choose a Colocation Provider in Newcastle: 10 Questions to Ask',
			'file'     => 'colocation-provider.html',
			'category' => 'Colocation',
			'tags'     => array( 'Colocation', 'Data Centre', 'Newcastle' ),
			'excerpt'  => 'Ten questions to ask before choosing colocation in Newcastle upon Tyne, from power and cooling to access and security, and what good answers sound like.',
		),
		'how-much-does-a-website-cost'      => array(
			'title'    => 'How Much Does a Small Business Website Cost in the UK?',
			'file'     => 'website-cost.html',
			'category' => 'Web Design',
			'tags'     => array( 'Web Design', 'Small Business', 'Pricing' ),
			'excerpt'  => 'What drives the cost of a small business website in the UK, the running costs after launch, the questions to ask any web designer, and what we charge.',
		),
		'what-is-web-hosting'               => array(
			'title'    => 'What Is Web Hosting? A Plain-English Guide for Small Businesses',
			'file'     => 'what-is-web-hosting.html',
			'category' => 'Web Hosting',
			'tags'     => array( 'Web Hosting', 'Domains', 'Small Business' ),
			'excerpt'  => 'What web hosting is, how it differs from your domain, what storage, bandwidth and databases mean on a hosting plan, and how to choose the right one.',
		),
		'move-website-to-a-new-host'        => array(
			'title'    => 'How to Move Your Website to a New Host Without Downtime',
			'file'     => 'move-host.html',
			'category' => 'Web Hosting',
			'tags'     => array( 'Web Hosting', 'DNS', 'Email' ),
			'excerpt'  => 'A step-by-step guide to moving your website and email to a new host without breaking anything, from backups and testing to DNS and switching over.',
		),
		'microsoft-365-vs-google-workspace' => array(
			'title'    => 'Microsoft 365 vs Google Workspace: Which Is Right for Your Business?',
			'file'     => 'm365-vs-workspace.html',
			'category' => 'Microsoft 365',
			'tags'     => array( 'Microsoft 365', 'Google Workspace', 'Email' ),
			'excerpt'  => 'Microsoft 365 and Google Workspace compared in plain English: what they share, where each stands out, and simple questions to help your business choose.',
		),
	);
}

/**
 * Saves the articles as drafts. Returns the number added.
 */
function lpd_add_draft_articles() {
	$added = 0;
	foreach ( lpd_draft_articles() as $slug => $article ) {
		if ( get_page_by_path( $slug, OBJECT, 'post' ) ) {
			continue;
		}
		$path = get_template_directory() . '/inc/drafts/' . $article['file'];
		if ( ! is_readable( $path ) ) {
			continue;
		}
		$cat_id = 0;
		$term   = term_exists( $article['category'], 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $article['category'], 'category' );
		}
		if ( ! is_wp_error( $term ) && $term ) {
			$cat_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );
		}
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'     => 'post',
					'post_status'   => 'draft',
					'post_name'     => $slug,
					'post_title'    => $article['title'],
					'post_content'  => file_get_contents( $path ),
					'post_excerpt'  => $article['excerpt'],
					'post_category' => $cat_id ? array( $cat_id ) : array(),
					'tags_input'    => $article['tags'],
				)
			),
			true
		);
		if ( ! is_wp_error( $id ) && $id ) {
			$added++;
		}
	}
	return $added;
}
