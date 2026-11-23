<?php
// php tools/tests/test-seo-plugin.php <pluginDir> : Lily Paw SEO checks.
define('ABSPATH','/'); $fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
$H=[]; function add_filter($h,$f,$p=10,$a=1){ $GLOBALS['H'][$h][]=$f; } function add_action($h,$f,$p=10,$a=1){ $GLOBALS['H'][$h][]=$f; }
function apply_filters_x($h,...$a){ $v=$a[0]; foreach($GLOBALS['H'][$h]??[] as $f) { $a[0]=$v; $v=$f(...$a); } return $v; }
function __return_false(){return false;} function register_activation_hook(){} function register_deactivation_hook(){} function add_rewrite_rule(){} function __($s){return $s;} function esc_html__($s){return $s;}
function esc_url($u){return htmlspecialchars($u,ENT_QUOTES);} function wp_strip_all_tags($s){return trim(strip_tags($s));} function strip_shortcodes($s){return preg_replace('/\[[^\]]*\]/','',$s);}
function wp_list_pluck($l,$k){ return array_map(fn($x)=>$x[$k]??'', $l); } function is_wp_error($x){return false;}
$O=['page_on_front'=>1,'show_on_front'=>'page','permalink_structure'=>'/%postname%/','page_for_posts'=>7,'blog_public'=>1];
function get_option($k,$d=false){ return $GLOBALS['O'][$k]??$d; } function home_url($p='/'){ return 'https://lilypaw.digital'.$p; } function add_query_arg($k,$v,$u){ return $u.'?'.$k.'='.$v; }
$M=[]; function get_post_meta($id,$k,$s=false){ return $GLOBALS['M'][$id][$k]??''; }
function P($id,$slug,$type='page',$extra=[]){ return (object)array_merge(['ID'=>$id,'post_name'=>$slug,'post_type'=>$type,'post_status'=>'publish','post_password'=>'','post_title'=>ucwords(str_replace('-',' ',$slug)),'post_excerpt'=>'','post_content'=>'Some words here.','post_modified_gmt'=>'2026-11-20 10:00:00'],$extra); }
$PAGES=[P(1,'home'),P(2,'colocation'),P(3,'secret-page'),P(4,'draft-page','page',['post_status'=>'draft']),P(7,'blog')];
$POSTS=[P(20,'spf-dkim-dmarc-explained','post',['post_excerpt'=>'Three DNS records decide whether your business emails reach the inbox.']), P(21,'hidden-post','post')];
$M[3]['_lpd_seo_noindex']=1; $M[21]['_lpd_seo_noindex']=1;
function get_posts($a){ return ($a['post_type']==='page') ? $GLOBALS['PAGES'] : $GLOBALS['POSTS']; }
function get_permalink($p){ $p=is_object($p)?$p:null; return 'https://lilypaw.digital/'.$p->post_name.'/'; }
function get_the_title($p){ return $p->post_title; } function get_bloginfo($k){ return 'Lily Paw Digital'; } function get_lastpostmodified(){ return '2026-11-20 10:00:00'; }
function get_terms($a){ return [(object)['slug'=>'email','term_id'=>3],(object)['slug'=>'uncategorized','term_id'=>1]]; } function get_term_link($t){ return 'https://lilypaw.digital/category/'.$t->slug.'/'; }
function has_excerpt($p){ return $p->post_excerpt!==''; } function has_post_thumbnail($p){ return false; }
function get_template_directory(){ return '/nonexistent'; } function get_template_directory_uri(){ return 'https://lilypaw.digital/wp-content/themes/lilypawdigital'; }
function lpd_post_image_url($p){ return 'https://lilypaw.digital/wp-content/themes/lilypawdigital/assets/images/blog/'.$p->post_name.'.jpg'; }
foreach (['defaults','meta','sitemap','indexing'] as $f) require $argv[1].'/includes/'.$f.'.php';
// suggested titles and descriptions fit Google's limits
$bad=[]; foreach (lpd_seo_defaults() as $k=>$v){ $t=mb_strlen($v[0]); $d=mb_strlen($v[1]); if($t>60||$d<120||$d>160) $bad[]="$k ($t/$d)"; }
ok(!$bad, 'every suggested title is 60 characters or fewer and description 120-160'.($bad?': '.implode(', ',$bad):''));
$descs=array_map(fn($v)=>$v[1], lpd_seo_defaults()); ok(count($descs)===count(array_unique($descs)), 'no two pages share a description');
ok(lpd_seo_title_for($PAGES[0])===lpd_seo_defaults()['home'][0], 'the front page uses the home title');
$M[2]['_lpd_seo_title']='My own title'; ok(lpd_seo_title_for($PAGES[1])==='My own title', 'a title typed into the SEO box wins'); unset($M[2]['_lpd_seo_title']);
ok(lpd_seo_description_for($POSTS[0])==='Three DNS records decide whether your business emails reach the inbox.', 'an article uses its excerpt');
$long=str_repeat('word ',80); $t=lpd_seo_trim($long); ok(mb_strlen($t)<=158 && substr($t,-3)==='…', 'long text is trimmed to fit, on a whole word');
// sitemaps
$idx=lpd_seo_sitemap_xml('index'); $x=simplexml_load_string($idx); ok($x && count($x->sitemap)===3, 'sitemap index lists pages, articles and categories');
$pg=lpd_seo_sitemap_xml('pages'); $x=simplexml_load_string($pg); $locs=array_map('strval', iterator_to_array($x->url ? (function($x){ foreach($x->url as $u) yield (string)$u->loc; })($x) : [] , false));
ok($locs && $locs[0]==='https://lilypaw.digital/', 'home page comes first, at the site address');
ok(!in_array('https://lilypaw.digital/secret-page/',$locs) && !in_array('https://lilypaw.digital/draft-page/',$locs), 'hidden and draft pages are left out');
ok(in_array('https://lilypaw.digital/colocation/',$locs) && strpos($pg,'<lastmod>2026-11-20T10:00:00+00:00</lastmod>')!==false, 'pages listed with their last-changed date');
$ps=lpd_seo_sitemap_xml('posts'); ok(simplexml_load_string($ps)!==false && strpos($ps,'spf-dkim-dmarc-explained.jpg')!==false && strpos($ps,'hidden-post')===false, 'articles listed with their picture; hidden article left out');
$cs=lpd_seo_sitemap_xml('categories'); ok(strpos($cs,'/category/email/')!==false && strpos($cs,'uncategorized')===false, 'categories listed, "Uncategorized" left out');
// robots.txt
$r=apply_filters_x('robots_txt',"User-agent: *\nDisallow: /wp-admin/\n\nSitemap: https://lilypaw.digital/wp-sitemap.xml\n",true);
ok(strpos($r,'Sitemap: https://lilypaw.digital/sitemap.xml')!==false && strpos($r,'wp-sitemap')===false, 'robots.txt points to the new sitemap only');
ok(apply_filters_x('wp_sitemaps_enabled',true)===false, "WordPress's own sitemap is switched off");
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
