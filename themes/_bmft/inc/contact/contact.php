<?php 

require_once('ContactFormClass.php');
require_once(get_template_directory() . "/inc/auth/register/AuthRegisterClass.php");
require_once(get_template_directory() . "/inc/auth/register/format/FormatMailClass.php");
require_once(get_template_directory() . "/inc/auth/register/RegisterFormError.php");

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

//$slug = 'contact';


$mf = new ContactForm($slug); //slugを入れる inspect or newshop or contact


//初回のページ表示の時のみsessionを消す 他のページから移動した場合にsessionが残るので
if(! isset($_SESSION[$slug]) && ! isset($_POST['sz_ticket'])) {
	//$mf->clear(true);
    //destroyをすると、sessionのticketが維持されない（ページ遷移1回目で維持されず、２回目で維持される）
    //destroyはページ移動してから初めて効く？
    $_SESSION[$slug] = array(); 
    //print_r($_SESSION[$slug]);
}

//if(isLocal()) ini_set('error_reporting', E_ALL);
//@session_start();
	
    //Postされた時、されない時を判別するための変数 これでどこに遷移するかを決める    
    $toConfirm = isset($_POST['toConfirm']) ? $_POST['toConfirm'] : NULL;
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

	
    
    //入力がPOSTされた時にデータをSessionに入れ、エラーをチェックする
    $errors = array();
    if($toConfirm != NULL) {
        $mf->setDataToSession();
        $mfError = new RegisterFormError($mf);
        
        $errors = $mfError -> checkContactNameMail(true); //true->error checkする false->しない
    }
    //$errors = isset($_SESSION['error']) ? $_SESSION['error'] : NULL;
    ?>
    
    <div id="main-form" class="clear">
    
    <?php if($toConfirm == NULL && $toEnd == NULL || count($errors) > 0) { ?>
    
        <?php if(count($errors) > 0) {
            include_once(realpath(dirname(__FILE__)) . "/../auth/register/displayErrorList.php");
        }
        ?>

        <form id="first-form" method="post" autocomplete="on" name="first-form" action="<?php echo $mf->currentUrl; ?>">
            <table class="table table-form">
                <colgroup>
                    <col class="cth">
                    <col class="ctd">
                </colgroup>
                <tbody>
                    <?php include_once('ContactFormMain.php'); ?>
                
                </tbody>
            </table>

            <input type="hidden" name="sz_ticket" id="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>" />
            <input type="hidden" name="toConfirm" value="1" />
            <input id="submit-1" type="submit" name="submit" value="内容を確認">
        </form>
        
<?php 
	}
    else if($toConfirm && $toEnd == NULL) {
    	if(isset($_POST['password'])) print_r($_SESSION['register']);
    	include_once('ContactFormConfirm.php');
    }
    else {
    	include_once('ContactFormFinish.php');
    }
?>
    
</div>
    
    
    
