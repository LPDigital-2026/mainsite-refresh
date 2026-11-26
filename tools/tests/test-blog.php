<?php
// php tools/tests/test-blog.php <themeDir> : checks the blog helpers and structured data.
define('ABSPATH','/'); define('DATE_W3C_', 1);
$fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
$GLOBALS['ctx']=['front'=>false,'single'=>true,'thumb'=>false,'blog'=>7];
$post=(object)['ID'=>42,'post_name'=>'spf-dkim-dmarc-explained','post_title'=>'How to Set Up SPF','post_content'=>str_repeat('word ',660).'[shortcode]','post_excerpt'=>'Three DNS records.'];
function add_action(){} function add_shortcode(){} function add_filter(){} function __($s){return $s;} function esc_html__($s){return $s;}
function get_post_field($f,$p=null){ return $GLOBALS['post']->post_content; } function strip_shortcodes($s){ return preg_replace('/\[[^\]]+\]/','',$s); } function wp_strip_all_tags($s){ return trim(strip_tags($s)); }
function get_bloginfo($k){ return 'Lily Paw Digital'; } function home_url($p='/'){ return 'https://lilypaw.digital'.$p; } function get_template_directory_uri(){ return 'https://lilypaw.digital/wp-content/themes/lilypawdigital'; }
function is_front_page(){ return $GLOBALS['ctx']['front']; } function is_singular($t=null){ return $GLOBALS['ctx']['single']; } function get_queried_object(){ return $GLOBALS['post']; }
function has_post_thumbnail($p=null){ return $GLOBALS['ctx']['thumb']; } function get_post_thumbnail_id($p){ return 5; } function wp_get_attachment_image_url($id,$s){ return 'https://lilypaw.digital/img.jpg'; }
function get_the_title($p=null){ return is_int($p) && $p===7 ? 'Blog' : $GLOBALS['post']->post_title; } function get_the_excerpt($p=null){ return $GLOBALS['post']->post_excerpt; }
function get_the_date($f='',$p=null){ return '2026-11-20T10:00:00+00:00'; } function get_the_modified_date($f='',$p=null){ return '2026-11-21T10:00:00+00:00'; }
function get_permalink($p=null){ return is_int($p) && $p===7 ? 'https://lilypaw.digital/blog/' : 'https://lilypaw.digital/blog/spf/'; }
function get_option($k,$d=false){ return $k==='page_for_posts' ? $GLOBALS['ctx']['blog'] : $d; }
function wp_json_encode($d,$f=0){ return json_encode($d,$f); }
function get_post($p=null){ return is_object($p) ? $p : $GLOBALS['post']; } function get_template_directory(){ return $GLOBALS['argv'][1]; }
function is_page($slug=null){ $c=$GLOBALS['ctx']['page']??false; return $slug===null ? (bool)$c : $c===$slug; } function get_queried_object_id(){ return 42; }
require $argv[1].'/inc/areas.php';
require $argv[1].'/inc/blog.php';
ok(lpd_reading_time()===3, 'reading time: 660 words is 3 minutes (shortcodes ignored)');
ob_start(); lpd_structured_data(); $out=ob_get_clean();
preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',$out,$m);
ok(count($m[1])===2, 'article page prints two JSON-LD blocks');
ok(lpd_post_image_url()==='https://lilypaw.digital/wp-content/themes/lilypawdigital/assets/images/blog/spf-dkim-dmarc-explained.jpg', 'article without a featured image uses its theme cover');
$GLOBALS['post']->post_name='something-new'; ok(substr(lpd_post_image_url(),-11)==='default.jpg', 'an article with no cover of its own uses the general cover'); $GLOBALS['post']->post_name='spf-dkim-dmarc-explained';
$GLOBALS['ctx']['thumb']=true; ok(lpd_post_image_url()==='https://lilypaw.digital/img.jpg', 'a featured image set in WordPress takes over'); $GLOBALS['ctx']['thumb']=false;
$a=json_decode($m[1][0],true); $b=json_decode($m[1][1],true);
ok($a && $a['@type']==='BlogPosting' && $a['headline']==='How to Set Up SPF' && $a['datePublished'] && $a['publisher']['logo']['url'] && strpos($a['image'],'/blog/spf-dkim-dmarc-explained.jpg')!==false, 'BlogPosting has headline, date, cover image and publisher logo');
ok($b && $b['@type']==='BreadcrumbList' && count($b['itemListElement'])===3 && $b['itemListElement'][1]['name']==='Blog', 'breadcrumbs: Home > Blog > article');
$GLOBALS['ctx']=['front'=>true,'single'=>false,'thumb'=>false,'blog'=>7];
ob_start(); lpd_structured_data(); preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',ob_get_clean(),$m);
$w=json_decode($m[1][0]??'',true); $c=json_decode($m[1][1]??'',true);
ok($w && $w['@type']==='WebSite' && $w['name']==='Lily Paw Digital' && in_array('Lily Paw',$w['alternateName']), 'home page names the site, with the names people search for');
ok($c && $c['@type']==='ProfessionalService' && $c['telephone']==='+443331127191', 'home page describes the business');
ok(count($c['areaServed'])>40 && in_array('Gateshead',array_column($c['areaServed'],'name')) && in_array('Northumberland',array_column($c['areaServed'],'name')), 'the business covers every North East county and its towns');
ok(in_array('United Kingdom',array_column($c['areaServed'],'name')), 'the business also covers the rest of the UK');
ok(($c['sameAs'][0]??'')!=='' , 'the business links to its review profile');
$page=file_get_contents($argv[1].'/inc/pages/areas-we-cover.html'); $missing=[];
foreach(lpd_service_areas() as $county=>$towns) foreach(array_merge([$county],$towns) as $t) if(strpos($page,$t)===false) $missing[]=$t;
ok(!$missing, 'the Areas We Cover page names every area in the list'.($missing?': '.implode(', ',$missing):''));
$GLOBALS['ctx']=['front'=>false,'single'=>false,'thumb'=>false,'blog'=>7,'page'=>'areas-we-cover']; $GLOBALS['post']->post_content=$page;
ob_start(); lpd_structured_data(); preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',ob_get_clean(),$m);
ok(count($m[1])===1 && json_decode($m[1][0],true)['@type']==='ProfessionalService', 'the Areas We Cover page describes the business too');
foreach(['colocation','web-design'] as $slug){ $html=file_get_contents($argv[1]."/inc/pages/$slug.html"); $f=lpd_faq_schema($html);
  ok($f && $f['@type']==='FAQPage' && count($f['mainEntity'])===substr_count($html,'class="wp-block-group lpd-faq-item"') && strlen($f['mainEntity'][0]['acceptedAnswer']['text'])>20 && strpos(json_encode($f),'<')===false, "$slug: FAQ markup has every question with its answer, as plain text"); }
ok(lpd_faq_schema(file_get_contents($argv[1].'/inc/pages/about-us.html'))===null, 'no FAQ markup on a page without questions');
$GLOBALS['ctx']['page']='colocation'; $GLOBALS['post']->post_content=file_get_contents($argv[1].'/inc/pages/colocation.html');
ob_start(); lpd_structured_data(); preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',ob_get_clean(),$m);
ok(count($m[1])===1 && json_decode($m[1][0],true)['@type']==='FAQPage', 'the Colocation page prints its FAQ markup');
define('WPSEO_VERSION','1'); ob_start(); lpd_structured_data(); ok(ob_get_clean()==='', 'nothing printed when an SEO plugin is active');
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
