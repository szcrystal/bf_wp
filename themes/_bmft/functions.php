<?php
/**
 * _s functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

if ( ! function_exists( '_s_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function _s_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_s' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', '_s' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
	$GLOBALS['content_width'] = apply_filters( '_s_content_width', 640 );
}
add_action( 'after_setup_theme', '_s_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _s_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', '_s' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', '_s' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
	wp_enqueue_style( 'awsome', get_template_directory_uri() . '/css/font-awesome.min.css', false, '4.5.0', 'all');
	
    //if(isAgent('all'))
    //	wp_enqueue_style( 'style-sp', get_template_directory_uri() . '/style-sp.css');
    //else
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'jq-script', get_template_directory_uri() . '/js/script.js', array(), '20160102', false );

	wp_enqueue_script( '_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( '_s-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/* For Admin ********** */
include_once('functions-admin.php');
/* ******************** */

function custom_editor_settings( $arr ){
	//https://www.tinymce.com/docs/configure/content-filtering/#valid_elements
    //optionは上記にて　しかしほとんどが効かない
    
    //http://yokotakenji.me/log/cms/wordpress/3139/

    // 空タグや、属性なしのタグとか消そうとしたりするのを停止。
	$arr['verify_html'] = false;

	return $arr;
}
//add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );



/* ************************************************ */
/* $_SESSIONの使用を可能にする */
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}
function myEndSession() {
    session_destroy();
}
add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');


//function generateRandomString($length, $elem=FALSE) { //$length -> 桁数
//    
//    if($length <= 0) return '';
//
//    //使用文字の指定
//    if($elem === FALSE) $elem = 'abcdefghijklmnopqrstuvwxyz1234567890';
//
//    if(! preg_match('/^[\x21-\x7e]+$/', $elem)) return FALSE;    
//    
//    $chars = preg_split('//', $elem, -1, PREG_SPLIT_NO_EMPTY);
//    $chars = array_unique($chars);
//    
//    mt_srand((double) microtime()*10000000);
//    
//    $str = '';
//    $maxIndex = count($chars) -1;
//    
//    for($i=0; $i<$length; $i++) {
//        $str .= $chars[mt_rand(0, $maxIndex)];
//    }
//    
//    return $str;
//}


//ショートコード short code for Include MailForm
function include_func( $atts ) {
	
	extract(shortcode_atts(array(
	    'file' => 'default',
        'type' => '',
	), $atts));
    
	ob_start(); //大量の(html)出力となる場合はこれを付けて、return ob_get_clean()にする
	
    //$slug = $type; //$slug：同じ変数名でincludeするファイル内に渡すことが可能（同じ変数を使用することが可能）
    global $auth, $db, $authId;
    
    $slug = $type;
    
    if($type == 'login')
		include('inc/auth/login.php');
    else if($type == 'register' || $type == 'edit')
    	include_once('inc/auth/register/auth-register.php');
    else if($type == 'userinfo')
    	include_once('inc/auth/register/auth-information.php');
    else if($type == 'reset-passwd')
    	include_once('inc/auth/register/auth-reset.php');
    else if($type == 'contact')
    	include_once('inc/contact/contact.php');
    else
    	echo "Specify the TYPE at ShortCode";
        
    return ob_get_clean();
}
add_shortcode( 'inclauth', 'include_func' );



/* Custom ****************************** */
function thisUrl($arg) {
	echo get_template_directory_uri(). '/' . $arg;
}

function getUrl($arg) {
	echo home_url() . '/' . $arg . '/';
}

/* add class */
function addMainClass() {
	$class = 'class="';
    
	if(is_front_page()) 
    	$class .= 'top';
    elseif(is_page() || is_singular('member')) {
    	$class .= 'fix';
        
        if(get_page_slug(get_the_ID()) != '') {
            $class .= ' ' . get_page_slug(get_the_ID());
        }
    }
    elseif(is_post_type_archive()) {
    	$class .= get_post_type() . 's';
    }
    elseif(is_home() || is_archive() || is_search())
    	$class .= 'allpost';
    else
    	$class .= 'site';
    
    
    echo $class . '"';
}

/* Custom Excerpt */
function new_excerpt_length($length) { 
    return 300;
}
add_filter( 'excerpt_length', 'new_excerpt_length');

function new_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');


function ex_content($char_count) {

    //$more_class = '';
    $texts = get_the_excerpt();
    
    //$continue_format = '<a %shref="%s" title="%sのページへ"> …</a>';
    //$continue_format = sprintf($continue_format, $more_class, esc_url(get_permalink()), get_the_title());
    $continue_format = '...';
    
    //$texts = strip_tags($texts); //html
    //$texts = str_replace("\n", '', $texts); //改行
        
    if(mb_strlen($texts) > $char_count+1) {
    	$texts = mb_substr($texts, 0, $char_count);
	    $texts = $texts . $continue_format;
	}
    
    echo $texts;
}

/* Pagenation */
function set_pagenation($queryArg = '') {
	
    if($queryArg != '') {
		global $wp_query;
		$wp_query->max_num_pages = $queryArg->max_num_pages; //$GLOBALS['wp_query']
    }
                   		
    the_posts_pagination(
    	array(
           'mid_size' => 1,
           'prev_text' => '<i class="fa fa-angle-double-left"></i> Prev',
           'next_text' => 'Next <i class="fa fa-angle-double-right"></i>',
           'screen_reader_text' => __( 'Posts navigation' ),
           'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cm' ) . ' </span>',
    	)
    );
}



function outUrl($arg) {
	$id = get_page_by_path($arg);
    echo get_permalink($id);
}


/* Custom Post */
function create_report() {
	register_post_type( 'report',
        array(
            'labels' => array(
            	'name' => 'レポート',
            	'singular_name' => 'REPORT', //news_project
                'all_items' => 'レポート一覧',
                'add_new_item' => '新規レポート追加',
                'edit_item' => 'レポートを編集',
          	),
            'public' => true,
            'hierarchical' => false,
            'menu_position' => 8,
            'has_archive' => true, 
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            //'taxonomies' => array('category', 'post_tag'),
            //'rewrite' => false,
            'rewrite' => array('slug' => 'report', 'with_front' => true),
            'supports' => array('title','editor','custom-fields', 'thumbnail', 'page-attributes', 'post-formats')
    )
  );
  
}
add_action( 'init', 'create_report' );

function create_topix() {
	register_post_type( 'topix',
        array(
            'labels' => array(
            	'name' => 'トピックス',
            	'singular_name' => 'TOPIX', //news_project
                'all_items' => 'トピックス一覧',
                'add_new_item' => '新規トピックス追加',
                'edit_item' => 'トピックスを編集',
          	),
            'public' => true,
            'hierarchical' => false,
            'menu_position' => 9,
            'has_archive' => true, 
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            //'taxonomies' => array('category', 'post_tag'),
            //'rewrite' => false,
            'rewrite' => array('slug' => 'topix', 'with_front' => false),
            'supports' => array('title','editor','custom-fields', 'thumbnail', 'page-attributes', 'post-formats')
    )
  );
  
}
//add_action( 'init', 'create_topix' );



//Admin file upload 時にzipのみ置き場所を移す -> 現在未使用
function otocon_resize_at_upload( $file ) {
  // $file contains file, url, type
  // array( 'file' => 'path to the image', 'url' => 'url of the image', 'type' => 'mime type' )

  // resize only if the mime type is image
  if ( $file['type'] == 'application/zip') {
  	
    $path = ABSPATH. "/this_zip/";
  	//if(!file_exists($path)) {
    	//wp_mkdir_p($path);
    //}
    
    $files = explode('/', $file['file']);
    $file_name = array_pop($files);
    $file_name = $path.$file_name;
    
    rename($file['file'], $file_name);
    
    //print_r($_FILES);
    
    //move_uploaded_file($file['file'], $path.'aaa.zip');
    
    
  	$file['file'] = $file_name;
    $file['url'] = $file_name;

    

  } // if mime type

  return $file;

}
//add_action( 'wp_handle_upload', 'otocon_resize_at_upload' );




//IDからスラッグをとる
function get_page_slug($page_id) {
    $page = get_page($page_id);
    return $page->post_name;
}

//POSTのセットの確認
function isSetPost($arg) {
	return isset($_POST[$arg]) && $_POST[$arg];
}



function current_nav_class($classes, $item){
	//print_r($item);
    
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $uri = str_replace('/', '', $uri);
    
    $slug = get_page_slug($item->object_id);
    
    if($slug == $uri) {
    	$classes[] = "current". get_the_id();
    }

//     if(is_single() && $item->title == "Blog"){ //Notice you can change the conditional from is_single() and $item->title
//             $classes[] = "special-class";
//     }
     return $classes;
}
//add_filter('nav_menu_css_class' , 'current_nav_class' , 10 , 2);


/* Judge *************************** */
//User Agent Check
function isAgent($agent) {

    $ua_sp = array('iPhone','iPod','Mobile ','Mobile;','Windows Phone','IEMobile');
    $ua_tab = array('iPad','Kindle','Sony Tablet','Nexus 7','Android Tablet');
    $all_agent = array_merge($ua_sp, $ua_tab);
    
    switch($agent) {
        case 'sp':
            $agent = $ua_sp;
            break;
    
        case 'tab':
            $agent = $ua_tab;
            break;
        
        case 'all':
            $agent = $all_agent;
            break;
            
        default:
            //$agent = '';
            break;
    }
       
    if(is_array($agent)) {
        $agent = implode('|', $agent);
    }
    
    return preg_match('/'. $agent .'/', $_SERVER['HTTP_USER_AGENT']);    
}


function isDK() {
	return strpos($_SERVER['SERVER_NAME'], '.cu.cc') !== FALSE;
}

function isLocal() {
    return strpos($_SERVER['SERVER_NAME'], '192.168.10') !== false;
}



//.htmlを付ける
//function my_page_permalink_rule($rules){
//	return(array('(.?.*[a-z].*)\\.html$'=>'index.php?pagename=$matches[1]')+$rules);
//}
//add_filter('rewrite_rules_array', 'my_page_permalink_rule');
//
//function my_page_permalink($link,$pid=0){
//	return($link.'.html');
//}
//add_filter('_get_page_link','my_page_permalink',10,2);



function my_contact_form_filter($tag) {
	
    //if(is_admin()):
    
    //print_r($tag);
    
    $formName = 'menu-991';
    $selectValue = isset($_GET[$formName]) ? $_GET[$formName] : NULL;

    if( !is_array($tag) ) {
    	return $tag;
    }
  
	if( $selectValue && $formName == $tag['name']) {
    	//echo $name."<br>";
      
      	//if( is_array( $tag['values'] ) ) {
        
    	if( $index = array_search($selectValue, $tag['values'])) { // optionsの中から選択する項目を探す
          
        	$index++; //default: オプションの先頭は1なので+1する
          	$defaultOption = 'default:' . $index;
          	// selectタグにoptionsがあるか調べる
//            if( !is_array($tag['options']) ) {
//            	// optionsがなければ作って default:オプションを追加
//                $tag['options'] = array($defaultOption);
//            }
//          	else {
            	// optionsの中に既に default:オプションが有るか調べ、あれば上書きする。他にあるならinclude_brank
                foreach( $tag['options'] as $key => $val ) :
                  //if( substr_compare($value, 'default', 0, 7) === 0 ) {
                	if(strpos($val, 'default') === FALSE)
                		$tag['options'][] = $defaultOption;
                	else
                    	$tag['options'][$key] = $defaultOption;
            
                endforeach;
            //}
        }
      
    //}
	}
    //endif;
	return $tag;
}
add_filter( 'wpcf7_form_tag', 'my_contact_form_filter');










