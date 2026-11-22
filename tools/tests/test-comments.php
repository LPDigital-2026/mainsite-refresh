<?php
// php tools/tests/test-comments.php <themeDir> : comments are switched off.
define('ABSPATH','/'); $fail=0; function ok($c,$m){ global $fail; echo ($c?'PASS ':'FAIL ').$m."\n"; if(!$c)$fail++; }
$H=[]; function add_filter($h,$f,$p=10){ $GLOBALS['H'][$h][]=$f; } function add_action($h,$f,$p=10){ $GLOBALS['H'][$h][]=$f; }
function apply_filters($h,$v,...$a){ foreach($GLOBALS['H'][$h]??[] as $f) $v=is_string($f)?$f($v,...$a):$f($v,...$a); return $v; }
function __return_false(){return false;} function __return_zero(){return 0;} function __return_empty_array(){return [];}
function esc_html__($s){return $s;} class Died extends Exception{} function wp_die($m){ throw new Died($m); }
$supports=['post'=>['comments'=>1,'trackbacks'=>1],'page'=>['comments'=>1]];
function get_post_types(){ return array_keys($GLOBALS['supports']); } function post_type_supports($t,$f){ return !empty($GLOBALS['supports'][$t][$f]); } function remove_post_type_support($t,$f){ unset($GLOBALS['supports'][$t][$f]); }
require $argv[1].'/inc/no-comments.php';
ok(apply_filters('comments_open', true, 1)===false, 'comments closed even where a post allows them');
ok(apply_filters('pings_open', true, 1)===false, 'pingbacks and trackbacks closed');
ok(apply_filters('comments_array', [['c'=>1]], 1)===[], 'existing comments are hidden');
ok(apply_filters('get_comments_number', 5, 1)===0, 'comment count shows zero');
ok(apply_filters('feed_links_show_comments_feed', true)===false, 'no comments feed');
$m=apply_filters('xmlrpc_methods',['pingback.ping'=>1,'other'=>1]); ok(!isset($m['pingback.ping']) && isset($m['other']), 'XML-RPC pingbacks removed, other methods kept');
$died=false; try { foreach($GLOBALS['H']['pre_comment_on_post'] as $f) $f(1); } catch(Died $e){ $died=true; } ok($died, 'a comment posted directly is refused');
foreach($GLOBALS['H']['init'] as $f) $f(); ok(empty($supports['post']['comments']) && empty($supports['page']['comments']), 'comment support removed from posts and pages');
echo $fail ? "\n$fail FAILED\n" : "\nALL PASSED\n"; exit($fail?1:0);
