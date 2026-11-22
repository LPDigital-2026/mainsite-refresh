<?php
// php tools/tests/test-blog.php <themeDir> : checks the blog helpers and structured data.
define('ABSPATH','/'); define('DATE_W3C_', 1);
$fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
$GLOBALS['ctx']=['front'=>false,'single'=>true,'thumb'=>false,'blog'=>7];
$post=(object)['ID'=>42,'post_title'=>'How to Set Up SPF','post_content'=>str_repeat('word ',660).'[shortcode]','post_excerpt'=>'Three DNS records.'];
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
require $argv[1].'/inc/blog.php';
ok(lpd_reading_time()===3, 'reading time: 660 words is 3 minutes (shortcodes ignored)');
ob_start(); lpd_structured_data(); $out=ob_get_clean();
preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',$out,$m);
ok(count($m[1])===2, 'article page prints two JSON-LD blocks');
$a=json_decode($m[1][0],true); $b=json_decode($m[1][1],true);
ok($a && $a['@type']==='BlogPosting' && $a['headline']==='How to Set Up SPF' && $a['datePublished'] && $a['publisher']['logo']['url'], 'BlogPosting has headline, date and publisher logo');
ok($b && $b['@type']==='BreadcrumbList' && count($b['itemListElement'])===3 && $b['itemListElement'][1]['name']==='Blog', 'breadcrumbs: Home > Blog > article');
$GLOBALS['ctx']=['front'=>true,'single'=>false,'thumb'=>false,'blog'=>7];
ob_start(); lpd_structured_data(); preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s',ob_get_clean(),$m);
$c=json_decode($m[1][0]??'',true);
ok($c && $c['@type']==='ProfessionalService' && $c['telephone']==='+443331127191', 'home page describes the business');
define('WPSEO_VERSION','1'); ob_start(); lpd_structured_data(); ok(ob_get_clean()==='', 'nothing printed when an SEO plugin is active');
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
