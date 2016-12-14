<?php 

//require_once('CustomAuthClass.php');
require_once('AuthRegisterClass.php');
require_once('RegisterFormError.php');

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
$slug = 'reset-passwd';
$mf = new AuthRegister($slug); //slugを入れる inspect or newshop or contact


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
    $toReset = isset($_POST['toReset']) ? $_POST['toReset'] : NULL;
    $toEnd = isset($_POST['toEnd']) ? $_POST['toEnd'] : NULL;
    $toNewPasswd = isset($_POST['toNewPasswd']) ? $_POST['toNewPasswd'] : NULL;
    
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
    if($toReset != NULL || $toNewPasswd != NULL) {
    	$mf->checkInputAndTicket();
        $mf->setDataToSession();
        $mfError = new RegisterFormError($mf);
        
        if($toReset)
	        $errors = $mfError -> checkMail(true); //true:error checkする false:しない
        elseif($toNewPasswd)
        	$errors = $mfError -> checkPassword(true); //true:error checkする false:しない
    }
    //$errors = isset($_SESSION['error']) ? $_SESSION['error'] : NULL;
    ?>
    
    <div id="main-form" class="clear">

    <?php
    if(isset($_GET['rst']) && isset($_GET['u_id'])) {
    	if(!isset($_POST['password']) || count($errors) > 0) {
            $resetHash = $_GET['rst'];
            $u_id = $_GET['u_id'];
            
            //$sql = "select * from auth where reset_hash = ?";
            //$res = $db->getRow($sql, array($resetHash), DB_FETCHMODE_OBJECT);
            //if (PEAR::isError($res))
            //    die("Object by ID が取得できない。". $res->getMessage());
            
            $hash = $mf->getData('reset_hash', $u_id); //DBからも登録Hashを取得して照合するか確認する
            
            if(isset($_COOKIE['resetQueryHash'])) {
            	if($_COOKIE['resetQueryHash'] == $resetHash && $hash == $resetHash) {
                	if(count($errors) > 0) {
                        include_once('displayErrorList.php');
                    }

					include_once('inputNewPasswdForm.php');
            	}
                else {
                	die('Error: Not match Reset Hash 6011');
                }
            
            }
            else { //isset($_COOKIE['resetQueryHash']
                echo '有効期限の24時間を過ぎましたので、再度パスワードリセットの手続きをして下さい。'.'<a href="/reset-passwd/">リセットする</a>';
            }
            
        }
        else if(isset($_POST['password']) && isset($_POST['thisId']) && $_POST['toNewPasswd']) {
        	$id = $_POST['thisId'];
            //$newPass = $mf->deCrypt($_SESSION[$slug]['password'][1]);
            $newPass = $_SESSION[$slug]['password'][1];
            $time = current_time( 'mysql' );
        	
            $sql = "update auth set password = ?,update_time = ? WHERE id = ?";
            $data = array(md5($newPass), $time, $id);
            $res = $db->query($sql, $data);
            
            if (PEAR::isError($res))
                die("新しいパスを登録できない" . $res->getMessage());
            
            echo '新しいパスワードが登録されました。再度ログインして下さい。'. '<a href="/login/">ログインする</a>';
            
            $mf->clear(TRUE);
        }
        else {
        	die('正常な表示が出来ません：error 10031');
        }
    
	}
    else { //isset($_GET['rst'])
    	if($toReset == NULL || count($errors) > 0) {
        	if(count($errors) > 0) {
                include_once('displayErrorList.php');
            }
            
            include_once('inputMailAddressForm.php');
        }
        else if($toReset && count($errors) == 0) {
            // echo $_POST['sz_ticket'];
            //include_once('RegisterFormConfirm.php');
            
            //エラー時にメルアドが登録されたものかどうかを確認する必要がある
            //print_r($_POST);
            //print_r($_SESSION);
            
            $ret = $mf->sendMailAndResetPw();
            
            if($ret) {
                echo 'メールを送信しました。<br>24時間以内にメール内に記載されているリセット用リンクをクリックして手続きを進めて下さい。';
                $mf->clear(true);
                //print_r($_SESSION);
            }
            else {
                echo $ret;
            }
            
        }
        else {
            
        }
    
    }
?>
    
</div>
    
    
    
