<?php
$GLOBALS['THEME']=$argv[1]; require __DIR__.'/wpmock.php'; require $THEME.'/inc/page-setup.php';
$fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
function reset_db(){ $GLOBALS['db']=array_merge($GLOBALS['db'],['posts'=>[],'next'=>10,'revisions'=>[],'options'=>['show_on_front'=>'posts','page_on_front'=>0],'mods'=>[],'menus'=>[],'items'=>[],'transients'=>[]]); }
$slugs=array_keys(lpd_setup_pages());

echo "== 1. Fresh site: activate\n"; reset_db();
do_action_hook('after_switch_theme');
ok(count($db['posts'])===16, '16 pages created');
foreach($slugs as $s){ $p=lpd_setup_find_page($s); ok($p && $p->post_content===lpd_setup_page_content($s) && strlen($p->post_content)>100, "$s content stored intact"); }
$home=lpd_setup_find_page('home');
ok(strpos($home->post_content,'{{theme_uri}}')===false && strpos($home->post_content,'https://example.test/wp-content/themes/lilypawdigital/assets/images/')!==false, 'theme asset paths filled in');
ok(strpos(lpd_setup_find_page('colocation')->post_content,'{"className":"lpd-colocation-pricing"}')!==false, 'block JSON quotes survive wp_slash/unslash');
ok(get_option('show_on_front')==='page' && get_option('page_on_front')===$home->ID, 'Home set as front page');
$loc=get_nav_menu_locations(); $items=wp_get_nav_menu_items($loc['primary']);
ok(count($items)===8 && implode(',',array_map(fn($i)=>$i->title,$items))==='Home,Web Design,Colocation,Blog,Our Work,About Us,Contact Us,Get Started', 'menu built in order: '.implode(',',array_map(fn($i)=>$i->title,$items)));
ob_start(); lpd_setup_activation_notice(); $n=ob_get_clean();
ok(strpos($n,'created 16 pages, set Home as the front page, built the main menu')!==false, 'activation notice: '.strip_tags($n));
ob_start(); lpd_setup_activation_notice(); ok(ob_get_clean()==='', 'notice shows once');

echo "== 2. Existing live site: activate leaves everything alone\n"; reset_db();
$custom='<!-- wp:shortcode -->[price_calculator id="7"]<!-- /wp:shortcode -->';
$gs=wp_insert_post(wp_slash(['post_type'=>'page','post_name'=>'get-started','post_title'=>'Get Started','post_content'=>$custom]));
$h=wp_insert_post(wp_slash(['post_type'=>'page','post_name'=>'home','post_title'=>'Home','post_content'=>'old home']));
$landing=wp_insert_post(wp_slash(['post_type'=>'page','post_name'=>'landing','post_title'=>'Landing','post_content'=>'x']));
update_option('show_on_front','page'); update_option('page_on_front',$landing);
$m=wp_create_nav_menu('My Header'); wp_update_nav_menu_item($m,0,['menu-item-object'=>'page','menu-item-object-id'=>$h,'menu-item-title'=>'Home']); set_theme_mod('nav_menu_locations',['primary'=>$m]);
do_action_hook('after_switch_theme');
ok(lpd_setup_find_page('get-started')->post_content===$custom, 'existing Get Started (calculator) untouched');
ok(lpd_setup_find_page('home')->post_content==='old home', 'existing Home untouched');
ok(count($db['posts'])===17, 'only the 14 missing pages created (3 existing + 14)');
ok(get_option('page_on_front')===$landing, 'existing front page kept');
ok((int)get_option('page_for_posts')===(int)lpd_setup_find_page('blog')->ID, 'Blog page set as the posts page');
ok(count(wp_get_nav_menu_items($m))===1 && count($db['menus'])===1, 'existing header menu untouched');
ob_start(); lpd_setup_activation_notice(); ok(strpos(strip_tags(ob_get_clean()),'created 14 pages')!==false, 'notice reports 14 created');

echo "== 3. Replace + menu top-up via the admin screen\n";
function post_action($do,$slug=''){ $_POST=['_wpnonce'=>'ok','lpd_do'=>$do,'lpd_slug'=>$slug,'action'=>'lpd_page_setup']; try{ lpd_setup_handle_action(); }catch(Redirect $r){ return $r->getMessage(); } return 'no redirect'; }
$u=post_action('replace','get-started');
ok(lpd_setup_find_page('get-started')->post_content===lpd_setup_page_content('get-started'), 'replace writes theme version');
ok(($db['revisions'][$gs][0]??'')===$custom, 'old content kept as a revision');
ok(strpos($u,'lpd_done=replace')!==false, 'redirects with success flag');
post_action('menu'); post_action('menu');
$items=wp_get_nav_menu_items($m);
ok(count($items)===8 && count($db['menus'])===1 && !in_array('Hosted PBX',array_map(fn($i)=>$i->title,$items),true), 'service pages kept out of the menu; menu topped up to 8 in the existing header menu, no duplicates, no new menu');
post_action('front_page'); ok(get_option('page_on_front')===lpd_setup_find_page('home')->ID, 'front page button sets Home');
ok(strpos(post_action('create','not-a-page'),'lpd_done=&')!==false, 'unknown slug does nothing');
$_POST=['_wpnonce'=>'bad','lpd_do'=>'create_missing']; try{ lpd_setup_handle_action(); ok(false,'nonce'); }catch(Exception $e){ ok($e->getMessage()==='bad nonce','bad nonce rejected'); }
$db['caps']=false; try{ lpd_setup_handle_action(); ok(false,'caps'); }catch(Exception $e){ ok(str_starts_with($e->getMessage(),'wp_die'),'non-admin rejected'); } $db['caps']=true;

echo "== 4. Admin page renders\n";
set_error_handler(function($no,$str,$f,$l){ global $fail; echo "PHP WARNING: $str @ $l\n"; $fail++; return true; });
$_GET=['lpd_done'=>'replace']; ob_start(); lpd_setup_render_admin_page(); $html=ob_get_clean();
ok(substr_count($html,'Up to date')>=1 && strpos($html,'Edited by you')!==false, 'statuses shown');
ok(strpos($html,'Replaced')===false && strpos($html,'Page replaced with the theme version')!==false, 'success message shown');
ok(strpos($html,'such as a different form')!==false, 'Get Started warning shown');
file_put_contents(__DIR__.'/admin.html','<link rel="stylesheet" href="https://example.invalid/none.css"><body style="font:13px sans-serif;padding:20px;background:#f0f0f1">'.$html);
echo $fail? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
