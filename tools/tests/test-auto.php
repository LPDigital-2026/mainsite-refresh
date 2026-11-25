<?php
$GLOBALS['THEME']=$argv[1]; require __DIR__.'/wpmock.php'; require $THEME.'/inc/page-setup.php';
$fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
function reset_db(){ $GLOBALS['db']=array_merge($GLOBALS['db'],['posts'=>[],'meta'=>[],'next'=>10,'revisions'=>[],'options'=>['show_on_front'=>'posts','page_on_front'=>0],'mods'=>[],'menus'=>[],'items'=>[],'transients'=>[]]); }
function mk($slug,$content){ return wp_insert_post(wp_slash(['post_type'=>'page','post_name'=>$slug,'post_title'=>$slug,'post_content'=>$content])); }
function notice(){ ob_start(); lpd_setup_activation_notice(); return strip_tags(ob_get_clean()); }

echo "== fingerprint matches the generator\n";
ok(lpd_setup_fingerprint(file_get_contents($THEME.'/inc/pages/home.html'))===$argv[2], 'PHP fingerprint == Python fingerprint for home.html');
ok(lpd_setup_fingerprint('<a href="x" rel="noopener">a</a>  <img src="https://s.test/wp-content/themes/old-name/a.jpg">')===lpd_setup_fingerprint("<a href=\"x\">a</a>\n<img src=\"/wp-content/themes/lilypawdigital/a.jpg\">"), 'ignores rel, whitespace and theme location');
ok(lpd_setup_fingerprint('<p>Hello</p>')!==lpd_setup_fingerprint('<p>Hello!</p>'), 'a real edit changes it');

echo "== your live site: pages pasted from the ORIGINAL copy file, plus an edited Get Started\n"; reset_db();
preg_match_all('#<textarea id="ta-([a-z]+)"[^>]*>(.*?)</textarea>#s', file_get_contents(__DIR__.'/original-copy.html'), $m, PREG_SET_ORDER);
$orig=[]; foreach($m as $x) $orig[$x[1]]=html_entity_decode($x[2], ENT_QUOTES|ENT_HTML5,'UTF-8');
$home=mk('home',$orig['home']); $colo=mk('colocation',$orig['colocation']); $about=mk('about-us',$orig['about']);
$calc='<!-- wp:shortcode -->[price_calculator id="7"]<!-- /wp:shortcode -->'; $gs=mk('get-started',$calc);
ok(lpd_setup_page_state('home')==='outdated' && lpd_setup_page_state('colocation')==='outdated' && lpd_setup_page_state('about-us')==='outdated', 'old pasted pages recognised as untouched older versions');
ok(lpd_setup_page_state('get-started')==='edited', 'Get Started with a calculator recognised as edited');
ok(lpd_setup_page_state('our-work')==='missing', 'missing page recognised');

do_action_hook('admin_init');   // what happens after uploading a new zip
ok(get_post($home)->post_content===lpd_setup_page_content('home'), 'Home updated automatically');
ok(get_post($colo)->post_content===lpd_setup_page_content('colocation') && get_post($about)->post_content===lpd_setup_page_content('about-us'), 'Colocation and About updated automatically');
ok(($db['revisions'][$home][0]??'')===$orig['home'], 'old Home kept as a revision');
ok(get_post($gs)->post_content===$calc && empty($db['revisions'][$gs]), 'edited Get Started left alone');
ok(lpd_setup_find_page('our-work') && lpd_setup_find_page('contact-us'), 'missing pages created');
$n=notice(); ok(strpos($n,'created 11 pages, updated 3 pages')!==false && strpos($n,'Left alone because you have edited them: Get Started')!==false, 'notice: '.$n);
ok(get_option('lpd_setup_version')===LPD_VERSION, 'version recorded');

echo "== nothing happens again until the version changes\n";
$before=serialize($db['posts']); do_action_hook('admin_init'); ok($before===serialize($db['posts']) && notice()==='', 'second load is a no-op');

echo "== a later theme version\n";
get_post($about)->post_content .= "\n<!-- wp:paragraph --><p>We also do cake.</p><!-- /wp:paragraph -->";   // user edits About
get_post($colo)->post_content = '<p>pretend this is what theme v9.9 wrote</p>'; update_post_meta($colo,'_lpd_fingerprint',lpd_setup_fingerprint(get_post($colo)->post_content)); // theme-written, untouched
update_option('lpd_setup_version','9.8');
do_action_hook('admin_init');
ok(strpos(get_post($about)->post_content,'cake')!==false, 'page edited after the theme wrote it is left alone');
ok(get_post($colo)->post_content===lpd_setup_page_content('colocation'), 'page the theme wrote and nobody touched is updated');
$n=notice(); ok(strpos($n,'updated 1 page.')!==false && strpos($n,'About Us')!==false && strpos($n,'Get Started')!==false, 'notice: '.$n);

echo "== not for visitors or non-admins\n";
update_option('lpd_setup_version','9.7'); $db['caps']=false; $before=serialize($db['posts']); do_action_hook('admin_init');
ok($before===serialize($db['posts']) && get_option('lpd_setup_version')==='9.7', 'does nothing without permission, and tries again later');
$db['caps']=true;

echo "== admin page\n"; set_error_handler(function($no,$str,$f,$l){ global $fail; echo "PHP WARNING: $str @ $l\n"; $fail++; return true; });
ob_start(); lpd_setup_render_admin_page(); $html=ob_get_clean();
ok(strpos($html,'Up to date')!==false && strpos($html,'Edited by you (left alone)')!==false, 'statuses shown');
echo $fail? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
