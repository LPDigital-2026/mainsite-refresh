<?php
/**
 * Footer template
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
</div><!-- #content -->

<footer id="colophon" class="site-footer">
	<div class="container">

		<div class="footer-grid">

			<div class="footer-brand">
				<img
					class="lpd-logo-img"
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-stacked.png' ); ?>"
					alt="<?php bloginfo( 'name' ); ?>"
					width="175"
					height="64"
				>
				<p><?php esc_html_e( 'Web design, hosting and colocation from the North East of England. Clear pricing, real people, and no jargon.', 'lilypaw-digital' ); ?></p>
				<p class="lpd-footer-phone"><a href="tel:+443331127191">0333 112 7191</a></p>
			</div>

			<div>
				<h4><?php esc_html_e( 'Explore', 'lilypaw-digital' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'lilypaw-digital' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'lilypaw-digital' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/get-started/' ) ); ?>"><?php esc_html_e( 'Get Started', 'lilypaw-digital' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'lilypaw-digital' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'lilypaw-digital' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Company', 'lilypaw-digital' ); ?></h4>
				<ul>
					<li><a href="https://status.lilypaw.digital" target="_blank" rel="noopener"><?php esc_html_e( 'Status', 'lilypaw-digital' ); ?></a></li>
					<li><a href="https://billing.lilypawdigital.com/login" target="_blank" rel="noopener"><?php esc_html_e( 'Client Login', 'lilypaw-digital' ); ?></a></li>
					<li><a href="https://uk.trustpilot.com/review/lilypawdigital.xyz" target="_blank" rel="noopener"><?php esc_html_e( 'Reviews', 'lilypaw-digital' ); ?></a></li>
					<li><a href="https://links.lilypawdigital.xyz" target="_blank" rel="noopener"><?php esc_html_e( 'Links', 'lilypaw-digital' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Legal', 'lilypaw-digital' ); ?></h4>
				<ul>
					<li><a href="https://legal.lilypaw.digital/privacy-policy" target="_blank" rel="noopener"><?php esc_html_e( 'Privacy Policy', 'lilypaw-digital' ); ?></a></li>
					<li><a href="https://legal.lilypaw.digital/terms-of-service" target="_blank" rel="noopener"><?php esc_html_e( 'Terms of Service', 'lilypaw-digital' ); ?></a></li>
					<li><a href="https://legal.lilypaw.digital/cookie-policy" target="_blank" rel="noopener"><?php esc_html_e( 'Cookie Policy', 'lilypaw-digital' ); ?></a></li>
				</ul>
			</div>

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div><?php dynamic_sidebar( 'footer-1' ); ?></div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div><?php dynamic_sidebar( 'footer-2' ); ?></div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div><?php dynamic_sidebar( 'footer-3' ); ?></div>
			<?php endif; ?>

		</div>


		<div class="lpd-status-badge">
			<iframe src="https://status.lilypaw.digital/badge?theme=dark" title="<?php esc_attr_e( 'Lily Paw Digital service status', 'lilypaw-digital' ); ?>" width="183" height="30" frameborder="0" scrolling="no" loading="lazy" style="color-scheme: normal"></iframe>
		</div>

		<div class="lpd-footer-cats">
			<div class="lpd-footer-cat-wrap">
			<svg class="lpd-footer-cat lpd-footer-cat-dark" aria-hidden="true" focusable="false" viewBox="25 70 110 140" xmlns="http://www.w3.org/2000/svg">
			  <defs>
			    <clipPath id="leye-d"><ellipse cx="86" cy="109" rx="7" ry="5.5"/></clipPath>
			    <clipPath id="reye-d"><ellipse cx="106" cy="109" rx="7" ry="5.5"/></clipPath>
			  </defs>
			        <!-- Tail (separate so it swishes freely) -->
			        <g>
			          <animateTransform attributeName="transform" type="rotate"
			            values="-12,96,193;9,96,193;-12,96,193"
			            dur="2.6s" repeatCount="indefinite" calcMode="spline"
			            keySplines="0.5 0 0.5 1;0.5 0 0.5 1"/>
			          <path d="M96 193 Q66 172 50 148 Q37 126 55 112"
			                stroke="#444" stroke-width="9" stroke-linecap="round" fill="none"/>
			          <ellipse cx="55" cy="112" rx="6.5" ry="6.5" fill="#555"/>
			        </g>

			        <!-- Body (static) -->
			        <g>
			          <ellipse cx="96" cy="162" rx="28" ry="33" fill="#444"/>
			          <ellipse cx="96" cy="170" rx="17" ry="21" fill="#555"/>
			        </g>

			        <!-- Head tilts curiously, same pattern as the light cat -->
			        <g>
			          <animateTransform attributeName="transform" type="rotate"
			            values="-5,96,112;5,96,112;-5,96,112"
			            dur="3.3s" repeatCount="indefinite" calcMode="spline"
			            keySplines="0.5 0 0.5 1;0.5 0 0.5 1"/>

			          <circle cx="96" cy="112" r="26" fill="#444"/>
			          <polygon points="77,100 70,76 91,94"   fill="#444"/>
			          <polygon points="115,100 122,76 101,94" fill="#444"/>
			          <polygon points="79,98 73,79 89,93"    fill="#7a4550" opacity="0.65"/>
			          <polygon points="113,98 119,79 103,93"  fill="#7a4550" opacity="0.65"/>

			          <!-- Left eye -->
			          <ellipse cx="86" cy="109" rx="7" ry="5.5" fill="#1a1a1a"/>
			          <ellipse cx="86" cy="109" rx="3.8" ry="3.8" fill="#060606"/>
			          <circle  cx="88" cy="106.5" r="1.8" fill="white" opacity="0.9"/>
			          <!-- Eyelid slides down from top using clipPath -->
			          <rect x="79" y="103.5" width="14" height="0" fill="#444" clip-path="url(#leye-d)">
			            <animate attributeName="height" values="0;0;0;0;0;0;11;0;0;0;0;0;0;11;0" dur="8s" repeatCount="indefinite"/>
			          </rect>

			          <!-- Right eye -->
			          <ellipse cx="106" cy="109" rx="7" ry="5.5" fill="#1a1a1a"/>
			          <ellipse cx="106" cy="109" rx="3.8" ry="3.8" fill="#060606"/>
			          <circle  cx="108" cy="106.5" r="1.8" fill="white" opacity="0.9"/>
			          <rect x="99" y="103.5" width="14" height="0" fill="#444" clip-path="url(#reye-d)">
			            <animate attributeName="height" values="0;0;0;0;0;0;11;0;0;0;0;0;0;11;0" dur="8s" repeatCount="indefinite"/>
			          </rect>

			          <polygon points="96,118 92,113 100,113" fill="#c07888"/>
			          <path d="M96,118 Q91,123 88,121" stroke="#555" stroke-width="1.5" fill="none" stroke-linecap="round"/>
			          <path d="M96,118 Q101,123 104,121" stroke="#555" stroke-width="1.5" fill="none" stroke-linecap="round"/>
			          <line x1="70"  y1="116" x2="87"  y2="118" stroke="#666" stroke-width="1" opacity="0.6"/>
			          <line x1="69"  y1="122" x2="86"  y2="120" stroke="#666" stroke-width="1" opacity="0.6"/>
			          <line x1="122" y1="116" x2="105" y2="118" stroke="#666" stroke-width="1" opacity="0.6"/>
			          <line x1="123" y1="122" x2="106" y2="120" stroke="#666" stroke-width="1" opacity="0.6"/>
			        </g>

			        <!-- Paws (static) -->
			        <g>
			          <ellipse cx="82"  cy="197" rx="12" ry="6" fill="#3a3a3a"/>
			          <ellipse cx="108" cy="197" rx="12" ry="6" fill="#3a3a3a"/>
			          <line x1="78"  y1="194" x2="78"  y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			          <line x1="82"  y1="193" x2="82"  y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			          <line x1="86"  y1="194" x2="86"  y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			          <line x1="104" y1="194" x2="104" y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			          <line x1="108" y1="193" x2="108" y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			          <line x1="112" y1="194" x2="112" y2="199" stroke="#303030" stroke-width="1" opacity="0.5"/>
			        </g>
			</svg>
			</div>
			<div class="lpd-footer-cat-wrap">
			<svg class="lpd-footer-cat lpd-footer-cat-light" aria-hidden="true" focusable="false" viewBox="388 70 110 140" xmlns="http://www.w3.org/2000/svg">
			  <defs>
			    <clipPath id="leye-l"><ellipse cx="414" cy="109" rx="7" ry="8"/></clipPath>
			    <clipPath id="reye-l"><ellipse cx="434" cy="109" rx="7" ry="8"/></clipPath>
			  </defs>
			        <!-- Tail -->
			        <g>
			          <animateTransform attributeName="transform" type="rotate"
			            values="12,424,193;-10,424,193;12,424,193"
			            dur="3.1s" repeatCount="indefinite" calcMode="spline"
			            keySplines="0.5 0 0.5 1;0.5 0 0.5 1"/>
			          <path d="M424 193 Q454 172 470 148 Q483 126 465 112"
			                stroke="#aaa" stroke-width="9" stroke-linecap="round" fill="none"/>
			          <ellipse cx="465" cy="112" rx="6.5" ry="6.5" fill="#c0c0c0"/>
			        </g>

			        <!-- Body (no bob — this cat is just watching) -->
			        <ellipse cx="424" cy="162" rx="28" ry="33" fill="#aaa"/>
			        <ellipse cx="424" cy="170" rx="17" ry="21" fill="#bebebe"/>

			        <!-- Head tilts curiously -->
			        <g>
			          <animateTransform attributeName="transform" type="rotate"
			            values="-5,424,138;5,424,138;-5,424,138"
			            dur="3.5s" repeatCount="indefinite" calcMode="spline"
			            keySplines="0.5 0 0.5 1;0.5 0 0.5 1"/>

			          <circle cx="424" cy="112" r="26" fill="#aaa"/>
			          <polygon points="405,100 398,76 419,94"  fill="#aaa"/>
			          <polygon points="443,100 450,76 429,94"  fill="#aaa"/>
			          <polygon points="407,98 401,79 417,93"  fill="#7a4550" opacity="0.55"/>
			          <polygon points="441,98 447,79 431,93"  fill="#7a4550" opacity="0.55"/>

			          <!-- Left eye -->
			          <ellipse cx="414" cy="109" rx="7" ry="8" fill="#1a1a1a"/>
			          <ellipse cx="414" cy="109" rx="3.2" ry="6" fill="#060606"/>
			          <circle  cx="416" cy="105.5" r="2" fill="white" opacity="0.9"/>
			          <rect x="407" y="101" width="14" height="0" fill="#aaa" clip-path="url(#leye-l)">
			            <animate attributeName="height" values="0;0;0;16;0;0;0;0;0;0;0;16;0;0" dur="11s" repeatCount="indefinite"/>
			          </rect>

			          <!-- Right eye -->
			          <ellipse cx="434" cy="109" rx="7" ry="8" fill="#1a1a1a"/>
			          <ellipse cx="434" cy="109" rx="3.2" ry="6" fill="#060606"/>
			          <circle  cx="436" cy="105.5" r="2" fill="white" opacity="0.9"/>
			          <rect x="427" y="101" width="14" height="0" fill="#aaa" clip-path="url(#reye-l)">
			            <animate attributeName="height" values="0;0;0;16;0;0;0;0;0;0;0;16;0;0" dur="11s" repeatCount="indefinite"/>
			          </rect>

			          <polygon points="424,118 420,113 428,113" fill="#c07888"/>
			          <path d="M424,118 Q419,123 416,121" stroke="#909090" stroke-width="1.5" fill="none" stroke-linecap="round"/>
			          <path d="M424,118 Q429,123 432,121" stroke="#909090" stroke-width="1.5" fill="none" stroke-linecap="round"/>
			          <line x1="398" y1="116" x2="415" y2="118" stroke="#c0c0c0" stroke-width="1" opacity="0.6"/>
			          <line x1="397" y1="122" x2="414" y2="120" stroke="#c0c0c0" stroke-width="1" opacity="0.6"/>
			          <line x1="450" y1="116" x2="433" y2="118" stroke="#c0c0c0" stroke-width="1" opacity="0.6"/>
			          <line x1="451" y1="122" x2="434" y2="120" stroke="#c0c0c0" stroke-width="1" opacity="0.6"/>
			        </g>

			        <!-- Light cat paws -->
			        <ellipse cx="410" cy="197" rx="12" ry="6" fill="#909090"/>
			        <ellipse cx="436" cy="197" rx="12" ry="6" fill="#909090"/>
			        <line x1="406" y1="194" x2="406" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			        <line x1="410" y1="193" x2="410" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			        <line x1="414" y1="194" x2="414" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			        <line x1="432" y1="194" x2="432" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			        <line x1="436" y1="193" x2="436" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			        <line x1="440" y1="194" x2="440" y2="199" stroke="#808080" stroke-width="1" opacity="0.5"/>
			</svg>
			</div>
		</div>


		<div class="footer-bottom">

			<p>
				<?php
				/* translators: %s: site link */
				printf(
					esc_html__( 'Website Design By %s', 'lilypaw-digital' ),
					'<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>'
				);
				?>
			</p>
			<p>
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
