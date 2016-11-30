<?php 
//require_once('mail_form-functions.php'); 
//require_once('MailForm.php');

/* *************************************************** */
   
	//session_start();
	
    //$mf = new MailForm('newshop');
    
    //$_POST = $mf->checkInput($_POST); //データチェック
    //$sz_ticket = $mf->checkTicket(); //チケットの確認＆(POSTの)チケット代入
    
    //POSTデータの代入
    //$check = array(); //複数選択可能なので
    /*
    $detail = isset($_POST['detail']) ? $_POST['detail'] : NULL;
    $comp_name = isset($_POST['comp_names']) ? $_POST['comp_names'] : NULL;
    $post_name = isset($_POST['post_names']) ? $_POST['post_names'] : NULL;
    $name = isset($_POST['nick_names']) ? $_POST['nick_names'] : NULL;
    $mail_add = isset($_POST['mail_add']) ? $_POST['mail_add'] : NULL;
    $tel_num = isset($_POST['tel_num']) ? $_POST['tel_num'] : NULL;
    
    //$select = isset($_POST['select']) ? $_POST['select'] : NULL;
    //$check = isset($_POST['check']) ? $_POST['check'] : NULL; 
    $comment = isset($_POST['comment']) ? $_POST['comment'] : NULL;
    */
    
    
    /* **********
    foreach($ar as $key => $val) { //$ar : function.php global val
    	$_SESSION[$key][0] = $val[0];
    	$_SESSION[$key][1] = isset($_POST[$key]) ? $_POST[$key] : NULL;
    }
    */
    //print_r(format_db_func());
    //エラーチェック＆出力 jQueryで判別しているので不要
    //errorCheckNameAndMail();
    

    //session変数への保存　各Sessionの配列2個目にラベル名を入れる　☆[0],[1]でキーを指定しないと入力ページで表示されない
    /*
    $_SESSION['nick_names'][0] = $name;
    $_SESSION['nick_names'][1] = 'お名前';
    
    $_SESSION['mail_add'][0] = $mail_add;
    $_SESSION['mail_add'][1] = 'メールアドレス';
    */
//    $_SESSION['select'][0] = $select;
//    $_SESSION['select'][1] = 'お問い合わせ内容';

    //複数選択について
    /*$_SESSION['check'][0] = $check;
    $_SESSION['check'][1] = '今までのサイト所持について？';*/
     //print_r($_SESSION['check']); $_SESSION['check'][0][]に値が入っていることを確認する用

//    $_SESSION['comment'][0] = $comment;
//    $_SESSION['comment'][1] = 'コメント';

    //$_SESSION['error'] = $error;
    
    //エラー数確認 jQueryなので不要
    /*if (count($error) > 0) {
        ask_form('submit','disabled');
    } else {
    //画面表示
    ask_form('disabled','disabled');
    */
    
    //$_POST = $mf->checkInput($_POST); //データチェック
    //$sz_ticket = $mf->checkTicket(); //チケットの確認＆(POSTの)チケット代入
    
    //$_POSTとTicketの確認
    $sz_ticket = $mf->checkInputAndTicket();
    
    //Object取得
    $objs = $mf->getObjSendingData(); //echo $mf->format_func($value="\n")
    ?>
    <div class="confFin">
    <p>下記の内容で送信します。<br>よろしければ送信ボタンを押して下さい。</p>
    </div>
        
    <table class="table table-form">
        <colgroup>
            <col class="cth">
            <col class="ctd">
        </colgroup>
        
        <tbody>
            <?php foreach($objs as $key => $val) { ?>
            
            <tr>
                <th><?php echo $key; ?></th>
                <td><?php echo ($key == 'パスワード' && $val != '') ? '**********' : $val; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
   
    <form id="second-form" method="post" name="second-form" action="<?php echo $mf->currentUrl; ?>">
        <input type="hidden" name="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>">
        <input type="hidden" name="toEnd" value="1">
        <input id="submit-2" type="submit" name="submit" value="送　信">
    </form>
    
    <a href="<?php echo $mf->currentUrl; ?>"><i class="fa fa-angle-double-left"></i>入力画面に戻る</a>
