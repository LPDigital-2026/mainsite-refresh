<?php
// Minimal WordPress stub: renders the theme templates to static HTML for screenshots.
// usage: php render.php <themeDir> <contentHtml> <page> <themeUrl>
[$_, $THEME, $CONTENT, $PAGE, $THEME_URL] = $argv;
define('ABSPATH', '/');
$GLOBALS['lpd_page'] = $PAGE;

$html = file_get_contents($CONTENT);
preg_match_all('#<textarea id="ta-([a-z0-9]+)"[^>]*>(.*?)</textarea>#s', $html, $m, PREG_SET_ORDER);
$PAGES = [];
foreach ($m as $x) $PAGES[$x[1]] = html_entity_decode($x[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

// ---- fake posts for previewing the blog -------------------------------
$GLOBALS['POSTS'] = [];
$samples = [
  ['How to Set Up SPF, DKIM and DMARC for Your Business Email', 'Email', 'Three DNS records decide whether your emails land in the inbox or the spam folder. Here is what each one does and how to set them up.', 1400, true],
  ['Do Small Businesses Still Need a Website in 2026?', 'Web Design', 'Social media is not enough on its own. We look at what a simple website does for a local business that a profile page cannot.', 900, false],
  ['What Is Colocation, and Is It Right for You?', 'Colocation', 'Moving your own servers into a data centre can cut risk and cost. A plain-English guide to when it makes sense.', 1100, true],
  ['Five Signs Your WordPress Site Needs Maintenance', 'Maintenance', 'Slow pages, old plugins and missing backups all add up. Here are the warning signs to look out for.', 700, false],
  ['Microsoft 365 Business Basic vs Standard vs Premium', 'Microsoft 365', 'Which plan do you actually need? We compare the three business plans in plain English.', 1300, false],
  ['Hosted PBX Explained: Business Phones in the Cloud', 'Hosted PBX', 'What a hosted phone system is, what it costs and what you need to get started.', 1000, false],
  ['Keeping Your Domain Safe From Expiry', 'Domains', 'Losing your domain can take your website and email down overnight. Here is how to make sure it never happens.', 600, false],
];
foreach ($samples as $i => $x) {
  $slugs=['spf-dkim-dmarc-explained','does-my-small-business-need-a-website','what-is-colocation','signs-your-wordpress-site-needs-maintenance','microsoft-365-business-plans-compared','hosted-pbx-explained','keeping-domain-safe'];
  $GLOBALS['POSTS'][] = (object)['ID'=>100+$i,'post_name'=>$slugs[$i],'post_title'=>$x[0],'cat'=>$x[1],'excerpt'=>$x[2],'post_content'=>str_repeat('word ', $x[3]),'thumb'=>false,'date'=>date('j F Y', strtotime("2026-11-20 -".($i*6)." days"))];
}
$GLOBALS['LOOP'] = null; $GLOBALS['CUR'] = null;
class WP_Query { public $posts; public $post_count; private $i = -1;
  function __construct($a=[]) { $n = $a['posts_per_page'] ?? 3; $ex = $a['post__not_in'] ?? []; $this->posts = array_values(array_filter($GLOBALS['POSTS'], fn($p)=>!in_array($p->ID,$ex))); $this->posts = array_slice($this->posts,0,$n); $this->post_count = count($this->posts); }
  function have_posts() { return $this->i + 1 < count($this->posts); }
  function the_post() { $this->i++; $GLOBALS['CUR'] = $this->posts[$this->i]; } }
function lpd_preview_loop($posts) { $GLOBALS['LOOP'] = new WP_Query(['posts_per_page'=>count($posts)]); $GLOBALS['LOOP']->posts = $posts; $GLOBALS['LOOP']->post_count = count($posts); }
function add_shortcode($t,$f){ $GLOBALS['SHORTCODES'][$t]=$f; } function add_action(){} function add_filter(){}
function shortcode_atts($d,$a){ return array_merge($d,(array)$a); }
function _n($s,$p,$n){ return $n==1?$s:$p; }
function wp_strip_all_tags($s){ return trim(strip_tags($s)); } function strip_shortcodes($s){ return $s; }
function wp_kses_post($s){ return $s; } function apply_filters($h,$v){ return $h==='the_content' ? $v : $v; }
function wp_reset_postdata(){} function is_paged(){ return false; } function is_admin(){ return false; }
function wp_json_encode($d,$f=0){ return json_encode($d,$f); }
function get_post($p=null){ return $GLOBALS['CUR']; }
function get_the_category($id=0){ $c=$GLOBALS['CUR']; return $c ? [(object)['slug'=>strtolower($c->cat),'name'=>$c->cat,'term_id'=>1]] : []; }
function get_category_link($c){ return '/category/'.$c->slug.'/'; }
function get_the_tags(){ return [(object)['name'=>'Email'],(object)['name'=>'DNS'],(object)['name'=>'Security']]; } function get_tag_link($t){ return '/tag/'.strtolower($t->name).'/'; }
function the_permalink(){ echo 'post.html'; }
function has_post_thumbnail($p=null){ return $GLOBALS['CUR'] && $GLOBALS['CUR']->thumb; }
function the_post_thumbnail($size='large',$attr=[]){ $imgs=['colocation/colocation-1.jpg','work/ryton-singers-desktop.jpg','colocation/colocation-3.jpg','work/ryton-cc-desktop.jpg']; $img=$imgs[$GLOBALS['CUR']->ID % 4]; echo '<img src="'.$GLOBALS['THEME_URL'].'/assets/images/'.$img.'" alt="">'; }
function get_the_excerpt($p=null){ return $GLOBALS['CUR']->excerpt; } function has_excerpt(){ return true; }
function get_the_date($f=''){ return $f===DATE_W3C ? '2026-11-20T10:00:00+00:00' : ($f==='Ymd' ? '20261120' : $GLOBALS['CUR']->date); }
function get_the_modified_date($f=''){ return get_the_date($f); }
function get_the_ID(){ return $GLOBALS['CUR']->ID; }
function get_the_title($id=0){ return $id ? 'Blog' : $GLOBALS['CUR']->post_title; }
function get_permalink($id=0){ return $id ? 'blogindex.html' : 'post.html'; }
function the_posts_pagination($a=[]){ echo '<nav class="navigation pagination"><div class="nav-links"><span class="page-numbers current">1</span><a class="page-numbers" href="#">2</a><a class="page-numbers" href="#">3</a><a class="next page-numbers" href="#">Older</a></div></nav>'; }
function get_the_archive_title(){ return 'Category: Email'; } function get_the_archive_description(){ return ''; }
function comments_template(){}
function get_the_content(){ return $GLOBALS['CUR']->post_content; } function sanitize_title($t){ return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($t)),'-'); }
$TITLES = ['home'=>'Home','webdesign'=>'Web Design','colocation'=>'Colocation','about'=>'About Us','work'=>'Our Work','contact'=>'Contact Us','getstarted'=>'Get Started','websitemaintenance'=>'Website Maintenance','domainsanddns'=>'Domain and DNS Management','businessemail'=>'Business Email Setup','websitesecurity'=>'Website Security Monitoring','microsoft365'=>'Microsoft 365','hostedpbx'=>'Hosted PBX','webhosting'=>'Web Hosting','areas'=>'Areas We Cover','404'=>'Page Not Found','blogindex'=>'Blog','post'=>'Article'];

function __($s){return $s;} function esc_html__($s){return htmlspecialchars($s);} function esc_html_e($s){echo htmlspecialchars($s);}
function esc_attr_e($s){echo htmlspecialchars($s);} function esc_attr__($s){return htmlspecialchars($s);}
function esc_html($s){return htmlspecialchars($s);} function esc_attr($s){return htmlspecialchars($s);} function esc_url($s){return $s;}
function home_url($p='/'){return '/'.ltrim($p,'/');}
function get_template_directory_uri(){return $GLOBALS['THEME_URL'];}
function get_template_directory(){return $GLOBALS['THEME'];}
function language_attributes(){echo 'lang="en-GB"';}
function bloginfo($k){ if($k==='charset'){echo 'UTF-8';} else echo 'Lily Paw Digital';}
function get_bloginfo($k){return 'Lily Paw Digital';}
function body_class(){ echo 'class="'.($GLOBALS['lpd_page']==='home'?'home page':'page page-'.$GLOBALS['lpd_page']).'"';}
function wp_body_open(){} function has_custom_logo(){return false;}
function date_i18n($f){return date($f);}
function is_active_sidebar($s){return false;}
function is_page($slug=null){ if($slug===null) return true; return $GLOBALS['lpd_page']===$slug; }
function is_singular($t=null){return true;} function get_option($k,$d=false){ return $k==='page_for_posts' ? 99 : $d; } function comments_open(){return false;} function get_comments_number(){return 0;}
function have_posts(){ if ($GLOBALS['LOOP']) return $GLOBALS['LOOP']->have_posts(); static $n=0; return $n++===0; } function the_post(){ if ($GLOBALS['LOOP']) $GLOBALS['LOOP']->the_post(); }
function the_title(){ echo $GLOBALS['CUR'] ? $GLOBALS['CUR']->post_title : $GLOBALS['TITLES'][$GLOBALS['lpd_page']]; }
function the_ID(){echo $GLOBALS['CUR'] ? $GLOBALS['CUR']->ID : 1;} function post_class($c=''){echo 'class="'.$c.' page type-page"';}
function wp_link_pages($a){}
function get_queried_object_id(){return 1;}
function get_post_field($f,$id=0){ if ($GLOBALS['CUR'] && $GLOBALS['lpd_page']!=='blogindex') return $GLOBALS['CUR']->post_content; if ($GLOBALS['lpd_page']==='blogindex') return '<p>Plain-English guides to websites, hosting, email, security and the rest of the technology small businesses rely on, from our team in the North East.</p>'; return $GLOBALS['PAGES'][$GLOBALS['lpd_page']]; }
function get_header(){ include $GLOBALS['THEME'].'/header.php'; }
function get_footer(){ include $GLOBALS['THEME'].'/footer.php'; }
function wp_nav_menu($a){
  $items=['Home'=>'/','Web Design'=>'/web-design/','Colocation'=>'/colocation/','Blog'=>'blogindex.html','Our Work'=>'/our-work/','About Us'=>'/about-us/','Contact Us'=>'/contact-us/','Get Started'=>'/get-started/'];
  echo '<ul id="primary-menu" class="menu">';
  foreach($items as $k=>$v){ $cur = ($GLOBALS['lpd_page']==='home'&&$k==='Home')||($GLOBALS['lpd_page']==='colocation'&&$k==='Colocation')||($GLOBALS['lpd_page']==='webdesign'&&$k==='Web Design'); echo '<li class="menu-item'.($cur?' current-menu-item':'').'"><a href="'.$v.'"'.($cur?' aria-current="page"':'').'>'.$k.'</a></li>'; }
  echo '</ul>';
}
function wp_head(){
  $u=$GLOBALS['THEME_URL'];
  echo "<script>document.documentElement.classList.add('lpd-js');</script>";
  echo '<title>'.$GLOBALS['TITLES'][$GLOBALS['lpd_page']].' – Lily Paw Digital</title>';
  echo '<link rel="stylesheet" href="../wpcss/package/build-style/style.css">';
  echo '<style>body .is-layout-flex{display:flex}.is-layout-flex{flex-wrap:wrap;align-items:center}.is-layout-flex>*{margin:0}:where(.wp-block-columns.is-layout-flex){gap:2em}:where(.wp-block-buttons.is-layout-flex){gap:.5em}:where(.wp-block-group.is-layout-flow)>*{margin-block-start:0}</style>';
  echo '<link rel="stylesheet" href="'.$u.'/style.css">';
}
function wp_footer(){
  $u=$GLOBALS['THEME_URL'];
  echo '<script>var lpdTheme={url:"'.$u.'"};</script>';
  echo '<script src="'.$u.'/js/navigation.js"></script><script src="'.$u.'/js/interactions.js"></script>';
}
function the_content(){
  if ($GLOBALS['lpd_page']==='post') { echo $GLOBALS['CUR']->post_content; return; }
  $c = $GLOBALS['PAGES'][$GLOBALS['lpd_page']];
  $c = str_replace('/wp-content/themes/lilypawdigital', $GLOBALS['THEME_URL'], $c);
  $c = preg_replace('#class="wp-block-(buttons|columns)( |")#', 'class="wp-block-$1 is-layout-flex$2', $c);
  $c = preg_replace('#class="wp-block-group( |")#', 'class="wp-block-group is-layout-flow$1', $c);
  $form = '<div class="lpd-form"><form><label>Name</label><input type="text"><label>Email</label><input type="email"><label>Message</label><textarea rows="5"></textarea><button class="wpforms-submit btn btn-primary" type="button">Send Message</button></form></div>';
  $c = preg_replace('#\[(lpd_form|wpforms)[^\]]*\]#', $form, $c);
  $c = preg_replace_callback('#\[lpd_latest_posts[^\]]*\]#', fn($m)=>lpd_latest_posts_shortcode([]), $c);
  echo $c;
}
$GLOBALS['THEME']=$THEME; $GLOBALS['THEME_URL']=$THEME_URL; $GLOBALS['PAGES']=$PAGES; $GLOBALS['TITLES']=$TITLES;
require $THEME.'/inc/template-tags.php'; require $THEME.'/inc/customizer-defaults.php'; require $THEME.'/inc/blog.php';
if ($PAGE==='blogindex') lpd_preview_loop($GLOBALS['POSTS']);
if ($PAGE==='post') { lpd_preview_loop([$GLOBALS['POSTS'][0]]); $GLOBALS['POSTS'][0]->post_content = file_get_contents(__DIR__.'/sample-article.html');
  // LPD_ARTICLE=<slug> renders one of the starter articles from inc/drafts/ instead of the sample.
  if (($slug = getenv('LPD_ARTICLE')) && ($a = (require_once $THEME.'/inc/drafts.php') ? (lpd_draft_articles()[$slug] ?? null) : null)) { $q = $GLOBALS['POSTS'][0]; $q->post_name = $slug; $q->post_title = $a['title']; $q->post_excerpt = $q->excerpt = $a['excerpt']; $q->cat = $a['category']; $q->post_content = file_get_contents($THEME.'/inc/drafts/'.$a['file']); } }
include $THEME . ($PAGE==='home' ? '/front-page.php' : ($PAGE==='404' ? '/404.php' : ($PAGE==='blogindex' ? '/home.php' : ($PAGE==='post' ? '/single.php' : '/page.php'))));
