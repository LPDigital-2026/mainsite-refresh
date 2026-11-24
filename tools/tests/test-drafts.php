<?php
// php tools/tests/test-drafts.php <themeDir> : the "Add draft articles" button.
$GLOBALS['THEME']=$argv[1]; require __DIR__.'/wpmock.php'; require $THEME.'/inc/page-setup.php'; require $THEME.'/inc/drafts.php';
$fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
if (!function_exists('term_exists')) { function term_exists($n,$t){ foreach($GLOBALS['db']['terms']??[] as $id=>$x) if($x===$n) return ['term_id'=>$id]; return null; } }
if (!function_exists('wp_insert_term')) { function wp_insert_term($n,$t){ $id=count($GLOBALS['db']['terms']??[])+1; $GLOBALS['db']['terms'][$id]=$n; return ['term_id'=>$id]; } }
if (!function_exists('get_page_by_path')) { function get_page_by_path($slug,$o=null,$type='page'){ foreach($GLOBALS['db']['posts'] as $p) if($p->post_name===$slug && $p->post_type===$type) return $p; return null; } }
$GLOBALS['db']['posts']=[]; $GLOBALS['db']['terms']=[];
$N=count(lpd_draft_articles()); $C=count(array_unique(array_column(lpd_draft_articles(),'category')));
$n=lpd_add_draft_articles();
$posts=array_values(array_filter($GLOBALS['db']['posts'],fn($p)=>$p->post_type==='post'));
ok($n===$N && count($posts)===$N && $N>=12, "$N articles added");
ok(count(array_filter($posts,fn($p)=>$p->post_status==='draft'))===$N, 'all saved as drafts, none published');
ok(count(array_filter($posts,fn($p)=>strlen($p->post_excerpt)>40 && strlen($p->post_content)>2000))===$N, 'each has an excerpt and full content');
ok(count($GLOBALS['db']['terms'])===$C, "$C categories created");
ok(lpd_add_draft_articles()===0 && count(array_filter($GLOBALS['db']['posts'],fn($p)=>$p->post_type==='post'))===$N, 'pressing again adds nothing');
$bad=[]; foreach (glob($THEME.'/inc/drafts/*.html') as $f) { $s=file_get_contents($f); preg_match_all('#href="(/[a-z0-9-]+/)"#',$s,$m); foreach($m[1] as $u){ $slug=trim($u,'/'); if(!isset(lpd_setup_pages()[$slug])) $bad[]=basename($f).' -> '.$u; } }
ok(!$bad, 'every link in the articles goes to a page the theme creates'.($bad?': '.implode(', ',$bad):''));
ok(count(glob($THEME.'/inc/drafts/*.html'))===$N, 'every article file is listed');
$miss=[]; foreach(array_keys(lpd_draft_articles()) as $s) if(!is_file($THEME."/assets/images/blog/$s.jpg")) $miss[]=$s;
ok(!$miss, 'every article has a cover'.($miss?': '.implode(', ',$miss):''));
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
