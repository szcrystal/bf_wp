<?php
//phpinfo();
//print_r($_SERVER);

$arr = array(/*'login', */'register', 'edit', 'reset-passwd', 'contact', 'report');
foreach($arr as $val) {
	if(!is_page($val) && !is_singular($val)) { //is_post_type_archive()
		$_SESSION[$val] = array();
    }
    
//    if(is_singular($val) || is_page($val))
//    	echo get_page_slug(get_the_id());
}
//print_r($_SESSION);

require_once('inc/auth/CustomAuthClass.php');
//require_once('inc/auth/register/AuthRegisterClass.php');

global $db, $auth, $authId, $ca;

$ca = new CustomAuth();
$db = $ca->db;
$auth = $ca->auth;

//if(! is_page('login') || (is_page('login') && $auth->getAuth()))
//	$auth->start(); //start()が何なのかイマイチ分からないが、ログアウト状態ではあまり関係しなさそうでログイン中には関係する。なのでログインページ（ログアウト中）以外ではここで発動させ、ログインページ（ログアウト中）ではページ内で発動させる

$auth->start();

if(isset($_POST['fromHead']) && ! $auth->getAuth()/* && ! is_singular('report')*/) {
//	echo "aaa";
	require_once('inc/auth/register/AuthRegisterClass.php');
    require_once('inc/auth/register/RegisterFormError.php');
    $mf = new AuthRegister('login');
	$errors = array();

    $mf->setDataToSession();
    
    $mf->checkInputAndTicket();
    $mfError = new RegisterFormError($mf);
    
    $errors = $mfError -> checkLogin(true); //true->error checkする false->しない
    
    
//    print_r($_SESSION);
//    print_r($_POST);
//
	
	header('Location: '. home_url() . '/login/');
    exit();
}
if(!is_page('login')) {
	$_SESSION['login'] = array();
}

if(isset($_POST['queryHash']) && is_page('reset-passwd')) {
	$resetHash = $_POST['queryHash'];
	setcookie('resetQueryHash', $resetHash, time()+(60*60*24)); //カレントURLのみ有効
}



//自動ログイン cookieに値があり、ログイン時のsessionが空なら
if(isset($_COOKIE['autoHash']) && count($_SESSION['_authsession']) == 0) {
	//echo deCrypt($_COOKIE['autoName']);
    $coohash = $_COOKIE['autoHash'];
    $sql = "select * from auth where hash = ?";
    $res = $db->getRow($sql, array($coohash), DB_FETCHMODE_OBJECT);
    if (PEAR::isError($res)) {
    	echo "DB Error:". $res->getMessage();
    }
    else {
    	if($res) {
    	//名前をセットしてログイン
		$auth->setAuth($res->username);
        
        //CookieのautoHash値を更新
        $ca-> setCookieAuthHash($res->username);
        }
        else {
        	echo 'ログインできません。AutoHashが一致しない。';
        }
    }
    //$authobj->setAuth(deCrypt($_COOKIE['autoName']));
    echo "自動ログインがされた<br>";
}


if(isset($_POST['autoLogin'])) {
	//echo $_POST['autoLogin']."<br>";
	if($auth->getAuth()) {
    	//$array = enCrypt($_POST['username']);
    	//setcookie('autoName[name]', $array[0], time()+300);
    	//setcookie('autoName[hashKey]', $array[1], time()+300);
    	//$name = $auth->getUsername();
        
        //CookieにautoHash値をセット
    	$ca-> setCookieAuthHash();
        
        echo 'Setted Auto LogIn'."<br>";
    }
    else {
    	echo "AutoLogin-On And ログインされていない";
    }
}

if(isset($_POST['logout']) || is_page('logout')) {
    //setcookie('autoName[name]', '', time()-3600);
    //setcookie('autoName[hashKey]', '', time()-3600);
    //setcookie('autoHash', '', time()-3600, '/');
    $ca->deleteCookieAuthHash();
    
    $auth -> logout();
    
    if(is_page('logout')) {
    	header('HTTP/1.1 200 OK');
        header('Location: '. home_url());
    }
    //echo "ログアウトされた";
}


//AuthのIDを取っておく
$authId = ($auth->getAuth()) ? $ca->getAuthId() : false;
$authName = ($auth->getAuth()) ? $ca->getData('nick_name', $authId) : false;


?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="https://fonts.googleapis.com/css?family=Oxygen|Raleway" rel="stylesheet">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
//print_r($_COOKIE);
//echo get_permalink();
//echo $auth->status;
//print_r($auth->session);
?>

<div id="page" <?php addMainClass(); ?>>
	<div class="belt-head">
    	<div class="clear">
        	<div class="left">
				<p class="desc">
                <?php bloginfo( 'name' ); ?>&nbsp;&nbsp;&nbsp;
                <?php bloginfo( 'description' ); ?>
                </p>
            </div>

            <div class="right <?php echo $auth->getAuth() ? 'authIn' : 'authOut'; ?>">

                <?php
                    if($authName) { ?>

                        <span class="showTgl">ユーザー <?php echo $authName; ?>さん<i class="fa fa-caret-down" aria-hidden="true"></i></span>

                        <div class="show-login">
                            <ul>
                                <li><a href="<?php getUrl('userinfo'); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i>ユーザー情報</a></li>
                                <li><a href="<?php getUrl('userinfo/edit'); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i>ユーザー情報の編集</a></li>
                                <li><a href="<?php getUrl('logout'); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i>ログアウト</a></li>
                            </ul>
                        </div>
                    <?php }
                    else {
                    ?>

                        <span class="showTgl">ログイン</span>

						<div class="login-head">

                        <?php
                            //global $db, $auth;
                            //$slug = 'login';
                            include("inc/auth/login-head.php");
                        ?>

                        </div>
                    <?php
                    }
                ?>

                </div>

        </div>
    </div>

	<header id="masthead" class="site-header" role="banner">
    	<div class="site-branding">

		<?php
//            if($auth->getAuth()) {
//            	
//                $logout = '<form method="post" action="/">'
//                		.'<input type="submit" class="logout" name="logout" value="ログアウト">'
//                		.'</form>';
//    
//    			echo $logout;
//            }
//            else {
//                echo "LogInしていない";
//                echo '<a href="/login/">ログイン</a>';
//            }

        ?>

            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

            <?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>

            <?php if(! $auth->getAuth()) { ?>
            <a href="<?php getUrl('register'); ?>" class="btn">新規会員登録</a>
            <?php } ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '_s' ); ?></button>
			<?php wp_nav_menu(
            	array(
                	'head_menu',
                	'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'before' => '<span>',
                    'after' => '</span>',
                    )
            ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">

<?php

//print_r($_SERVER);

//require_once('inc/auth/register/format/FormatMailClass.php');
//require_once('inc/auth/register/AuthRegisterClass.php');
//$arc = new AuthRegister('','','register');
//$fmc = new FormatMail($arc);
//
//$a = $fmc->format_return(TRUE);
//
//echo nl2br($a);

?>














