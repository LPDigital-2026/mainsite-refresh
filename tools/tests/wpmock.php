<?php
// In-memory stand-ins for the WordPress APIs inc/page-setup.php uses.
define('ABSPATH','/'); define('LPD_VERSION','9.9'); define('HOUR_IN_SECONDS',3600); define('OBJECT','OBJECT');
class WP_Error { public $m; function __construct($c='',$m=''){ $this->m=$m; } }
class Redirect extends Exception {}
$GLOBALS['db']=['posts'=>[],'next'=>10,'revisions'=>[],'options'=>['show_on_front'=>'posts','page_on_front'=>0],'mods'=>[],'menus'=>[],'items'=>[],'transients'=>[],'actions'=>[],'caps'=>true];
function is_wp_error($x){return $x instanceof WP_Error;}
function __($s,$d=null){return $s;} function esc_html__($s,$d=null){return htmlspecialchars($s);} function esc_html_e($s,$d=null){echo htmlspecialchars($s);}
function _n($a,$b,$n,$d=null){return $n==1?$a:$b;}
function esc_html($s){return htmlspecialchars((string)$s);} function esc_attr($s){return htmlspecialchars((string)$s,ENT_QUOTES);} function esc_url($s){return $s;}
function wp_json_encode($v){return json_encode($v);} 
function wp_slash($v){ return is_array($v)?array_map('wp_slash',$v):(is_string($v)?addslashes($v):$v);} 
function wp_unslash($v){ return is_array($v)?array_map('wp_unslash',$v):(is_string($v)?stripslashes($v):$v);} 
function sanitize_key($s){return preg_replace('/[^a-z0-9_\-]/','',strtolower($s));} function sanitize_title($s){return sanitize_key($s);}
function get_template_directory(){return $GLOBALS['THEME'];} function get_template_directory_uri(){return 'https://example.test/wp-content/themes/lilypawdigital';}
function add_action($h,$f,$p=10){$GLOBALS['db']['actions'][$h][]=$f;}
function current_user_can($c){return $GLOBALS['db']['caps'];}
function get_option($k,$d=false){return $GLOBALS['db']['options'][$k]??$d;} function update_option($k,$v){$GLOBALS['db']['options'][$k]=$v;return true;}
function get_theme_mod($k,$d=false){return $GLOBALS['db']['mods'][$k]??$d;} function set_theme_mod($k,$v){$GLOBALS['db']['mods'][$k]=$v;}
function get_nav_menu_locations(){return get_theme_mod('nav_menu_locations',[]);}
function set_transient($k,$v,$t){$GLOBALS['db']['transients'][$k]=$v;} function get_transient($k){return $GLOBALS['db']['transients'][$k]??false;} function delete_transient($k){unset($GLOBALS['db']['transients'][$k]);}
function get_post($id){ return $GLOBALS['db']['posts'][$id]??null; }
function get_post_meta($id,$k,$single=false){ return $GLOBALS['db']['meta'][$id][$k]??''; }
function update_post_meta($id,$k,$v){ $GLOBALS['db']['meta'][$id][$k]=$v; return true; }
function get_page_by_path($slug,$o=null,$t='page'){ foreach($GLOBALS['db']['posts'] as $p) if($p->post_name===$slug&&$p->post_type===$t) return $p; return null; }
function wp_insert_post($a,$e=false){ $a=wp_unslash($a); $id=$GLOBALS['db']['next']++; $p=(object)array_merge(['ID'=>$id,'post_status'=>'publish','post_type'=>'post','post_content'=>'','post_name'=>'','post_title'=>''],$a); $p->ID=$id; $GLOBALS['db']['posts'][$id]=$p; return $id; }
function wp_update_post($a,$e=false){ $a=wp_unslash($a); $p=$GLOBALS['db']['posts'][$a['ID']]??null; if(!$p) return new WP_Error('x'); $GLOBALS['db']['revisions'][$p->ID][]=$p->post_content; foreach($a as $k=>$v) $p->$k=$v; return $p->ID; }
function wp_get_nav_menu_object($m){ foreach($GLOBALS['db']['menus'] as $id=>$name) if($id===$m||$name===$m||(is_numeric($m)&&(int)$m===$id)) return (object)['term_id'=>$id,'name'=>$name]; return false; }
function wp_create_nav_menu($n){ $id=$GLOBALS['db']['next']++; $GLOBALS['db']['menus'][$id]=$n; $GLOBALS['db']['items'][$id]=[]; return $id; }
function wp_get_nav_menu_items($m){ return $GLOBALS['db']['items'][$m]??[]; }
function wp_update_nav_menu_item($m,$i,$a){ $GLOBALS['db']['items'][$m][]=(object)['object'=>$a['menu-item-object'],'object_id'=>$a['menu-item-object-id'],'title'=>$a['menu-item-title']]; return 1; }
function admin_url($p=''){return 'https://example.test/wp-admin/'.$p;} function add_query_arg($a,$u){return $u.'?'.http_build_query($a);} 
function wp_safe_redirect($u){ throw new Redirect($u); }
function check_admin_referer($a){ if(($_POST['_wpnonce']??'')!=='ok') throw new Exception('bad nonce'); return true; }
function wp_nonce_field($a){ echo '<input type="hidden" name="_wpnonce" value="ok">'; }
function wp_die($m,$c=null){ throw new Exception('wp_die: '.$m); }
function get_permalink($p){return 'https://example.test/'.$p->post_name.'/';} function get_edit_post_link($id){return admin_url('post.php?post='.$id.'&action=edit');}
function add_theme_page(...$a){}
function do_action_hook($h){ foreach($GLOBALS['db']['actions'][$h]??[] as $f) $f(); }
