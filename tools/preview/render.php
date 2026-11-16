<?php
// Minimal WordPress stub: renders the theme templates to static HTML for screenshots.
// usage: php render.php <themeDir> <contentHtml> <page> <themeUrl>
[$_, $THEME, $CONTENT, $PAGE, $THEME_URL] = $argv;
define('ABSPATH', '/');
$GLOBALS['lpd_page'] = $PAGE;

$html = file_get_contents($CONTENT);
preg_match_all('#<textarea id="ta-([a-z]+)"[^>]*>(.*?)</textarea>#s', $html, $m, PREG_SET_ORDER);
$PAGES = [];
foreach ($m as $x) $PAGES[$x[1]] = html_entity_decode($x[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
$TITLES = ['home'=>'Home','colocation'=>'Colocation','about'=>'About Us','work'=>'Our Work','contact'=>'Contact Us','getstarted'=>'Get Started','404'=>'Page Not Found'];

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
function is_singular(){return true;} function comments_open(){return false;} function get_comments_number(){return 0;}
function have_posts(){ static $n=0; return $n++===0; } function the_post(){}
function the_title(){ echo $GLOBALS['TITLES'][$GLOBALS['lpd_page']]; }
function the_ID(){echo 1;} function post_class($c=''){echo 'class="'.$c.' page type-page"';}
function wp_link_pages($a){}
function get_queried_object_id(){return 1;}
function get_post_field($f,$id){ return $GLOBALS['PAGES'][$GLOBALS['lpd_page']]; }
function get_header(){ include $GLOBALS['THEME'].'/header.php'; }
function get_footer(){ include $GLOBALS['THEME'].'/footer.php'; }
function wp_nav_menu($a){
  $items=['Home'=>'/','Colocation'=>'/colocation/','Our Work'=>'/our-work/','About Us'=>'/about-us/','Contact Us'=>'/contact-us/','Get Started'=>'/get-started/'];
  echo '<ul id="primary-menu" class="menu">';
  foreach($items as $k=>$v){ $cur = ($GLOBALS['lpd_page']==='home'&&$k==='Home')||($GLOBALS['lpd_page']==='colocation'&&$k==='Colocation'); echo '<li class="menu-item'.($cur?' current-menu-item':'').'"><a href="'.$v.'"'.($cur?' aria-current="page"':'').'>'.$k.'</a></li>'; }
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
  $c = $GLOBALS['PAGES'][$GLOBALS['lpd_page']];
  $c = str_replace('/wp-content/themes/lilypawdigital', $GLOBALS['THEME_URL'], $c);
  $c = preg_replace('#class="wp-block-(buttons|columns)( |")#', 'class="wp-block-$1 is-layout-flex$2', $c);
  $c = preg_replace('#class="wp-block-group( |")#', 'class="wp-block-group is-layout-flow$1', $c);
  $form = '<div class="lpd-form"><form><label>Name</label><input type="text"><label>Email</label><input type="email"><label>Message</label><textarea rows="5"></textarea><button class="wpforms-submit btn btn-primary" type="button">Send Message</button></form></div>';
  $c = preg_replace('#\[(lpd_form|wpforms)[^\]]*\]#', $form, $c);
  echo $c;
}
$GLOBALS['THEME']=$THEME; $GLOBALS['THEME_URL']=$THEME_URL; $GLOBALS['PAGES']=$PAGES; $GLOBALS['TITLES']=$TITLES;
require $THEME.'/inc/template-tags.php'; require $THEME.'/inc/customizer-defaults.php';
include $THEME . ($PAGE==='home' ? '/front-page.php' : ($PAGE==='404' ? '/404.php' : '/page.php'));
