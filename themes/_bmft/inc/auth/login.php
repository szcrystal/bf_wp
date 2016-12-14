<?php 

//require_once('CustomAuthClass.php');
require_once('register/AuthRegisterClass.php');
require_once('register/RegisterFormError.php');

/* *************************
jQueryでpostする場合form のaction属性は不要
各ページにフォームがあるので、残るsessionに注意が必要
1ページの中で確認や完了を回転させるので、errorの値をsessionに入れる必要はなし
プラグインsz-formと併用して、管理画面で設定・管理する必要がある

★★★★★
inspect,newshop,contactそれぞれ
カスタムフィールド：name_typeの設定が必ず必要であることに注意!! ->セッションエラーにつながる
★★★★★
***************************** */

//global $post;
//$slug = $post->post_name; //$slugはショートコードの引数で指定し、functions.php内でセットされている（187行目辺り）

//$slug = 'login';
$mf = new AuthRegister($slug); //slugを入れる inspect or newshop or contact


//初回のページ表示の時のみsessionを消す 他のページから移動した場合にsessionが残るので
if(! isset($_SESSION[$slug]) && ! isset($_POST['sz_ticket'])) {
	//$mf->clear(true);
    //destroyをすると、sessionのticketが維持されない（ページ遷移1回目で維持されず、２回目で維持される）
    //destroyはページ移動してから初めて効く？
    
    $_SESSION[$slug] = array();
    
    //print_r($_SESSION[$slug]);
}

//print_r($_SESSION);
//    print_r($_POST);

//if(isLocal()) ini_set('error_reporting', E_ALL);
//@session_start();
	
    //Postされた時、されない時を判別するための変数 これでどこに遷移するかを決める    
    $toLogin = isset($_POST['toLogin']) ? $_POST['toLogin'] : NULL;
    $toEnd = isset($_POST['toEnd']) ? $_POST['toEnd'] : NULL;
    
    // 視察<->出店 間でsessionが残るのでもし消すなら
    /*
    if($toConfirm == NULL) {
    	foreach($_SESSION as $key => $val) {
        	if($key != $slug) {
            	$_SESSION[$key] = array();
            }
        }
    }
    */
    
	$sz_ticket = md5(uniqid(mt_rand(), TRUE));
    $_SESSION[$slug]['sz_ticket'][] = $sz_ticket;
    //print_r($_SESSION[$slug]['sz_ticket']);

	//print_r($_SESSION);
    
    //入力がPOSTされた時にデータをSessionに入れ、エラーをチェックする
    $errors = array();
    //if($toLogin != NULL && ! isset($_SESSION['login']['errors'])) {
    if( ($toLogin != NULL && is_singular('report')) || ($toLogin != NULL && is_page('login')) ) {
        $mf->setDataToSession();
        $mf->checkInputAndTicket();
        $mfError = new RegisterFormError($mf);
        
        $errors = $mfError -> checkLogin(true); //true->error checkする false->しない
    }
    else {
    	$errors = isset($_SESSION[$slug]['errors']) ? $_SESSION[$slug]['errors'] : $errors;
    }
    
        //print_r($_SESSION);
    ?>
    
    <div class="login-wrap clear">
    
    <?php if($toLogin == NULL && $toEnd == NULL || count($errors) > 0) { ?>

		<div class="left">

        <?php if(count($errors) > 0) {
            include('register/displayErrorList.php');
        }
        
        //print_r($_SESSION); echo $mf->currentUrl;
        ?>


        <form class="login-form" method="post" autocomplete="on" name="first-form" action="">
            <table class="table table-form login-table">
                <colgroup>
                    <col class="cth">
                    <col class="ctd">
                </colgroup>
                <tbody>
                    <?php
                    	include('loginForm.php'); ?>
                
                </tbody>
            </table>

            <input type="hidden" name="sz_ticket" id="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>" />
            <input type="hidden" name="toLogin" value="1" />
            <input type="hidden" name="request" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
            <input type="submit" name="submit" value="ログイン">
        </form>
		</div>

		<div class="right">
			会員登録がお済みでない方は<br>会員登録（無料）をして下さい。
			<a href="<?php getUrl('register'); ?>" class="btn">新規会員登録</a>

        </div>


<?php
		//$hash = md5('aaaaa');
        //$key = pack('H*', $hash);
		
        //$enc = $mf->enCrypt('');
        //echo $enc[0]."<br>";
        //echo trim($mf->deCrypt($enc));
    
	
	}
    else if($toLogin && count($errors) == 0) {
		
        //$mf->checkInputAndTicket();
        //print_r($_SESSION);
        
        /*
        header.phpにて$auth->startを入れているので、setAuthは不要 
        start()は、postのusernameとpasswordがあればlogin()する、というもの
        login()の中でsetAuth()があるので、ここにsetAuthを入れると重複となる（session絡みのWarningが出る）
        */
        //$auth->setAuth($_SESSION[$slug]['username'][1]);
        echo "ログインされました。<br>これよりレポートのダウンロードが可能となります。";
		
        $mf->clear(TRUE);
        
    	//include_once('RegisterFormConfirm.php');
    }
    else {
    	include_once('RegisterFormFinish.php');
    }
?>

</div>
    
    
    
