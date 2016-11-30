<?php
//require_once('mail_form-functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php'); //'../../../../../wp-config.php'でも可
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/kaibara/functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/sue-blog/wp-load.php');

//require_once('MailForm.php');

//@session_start(); //本来はheaderの上に
	//global $wpdb; //wp-config.phpを読めば$wpdbが使用できる
    /*
    	$wpdb->get_results 一般的なSELECT(object or array)
    	$wpdb->get_col 列のSELECT
        $wpdb->get_row　１行取得
    */
    
    //$idNum = 1; //wpのユーザーID
    //$userRow = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = $idNum");
    
//    $mf = new MailForm('inspect');
//    $adminRow = $mf->getAdminRow(); //AdminデータをDBより取得
//    
//    //$user_info = get_userdata($idNum);
//
//    $mailTo = $adminRow->admin_email; //メインアドレス問い合わせの届け先（カンマ区切りで複数指定可）
//    $mainMail = $adminRow->admin_email; //確認メール内に記載される返信先（ヘッダーアドレス）
//    $subject = '視察募集がありました 〜'. $adminRow->admin_name; //get_bloginfo('name')　/* ★ */
//    
//    $returnMail = 'szk.create@gmail.com'; //Undelivered Mailの送信先 postfix(smtpなし状態)で確認可能
//    
//    $return_subject = '視察募集を承りました ' . $adminRow->admin_name; /* ★ */
//    
//    $_POST = $mf->checkInput($_POST); //入力データの確認
//    
//    $mf->checkTicket(); //チケット確認
//
//    $name = $_SESSION['nick_name'][1];
//    $mail_add = $_SESSION['mail_add'][1];
//
//    /* エンコード */
//    mb_language('ja');
//    mb_internal_encoding('UTF-8');
//
//    /* mail header */
//    $header = 'FROM: '. mb_encode_mimeheader($name) .' <'.$mail_add.'>';
//    $return_header = 'FROM: '. mb_encode_mimeheader(get_bloginfo('name')).' <'.$mainMail.'>'; //確認メールヘッダー
//    
//    /* send mail */
//    $result_master = mb_send_mail( $mailTo, $subject, $mf->format_admin(), $header, '-f' . $returnMail );
//    
//    /* set DB */ 
//    global $wpdb;   
//    $table_name = $wpdb->prefix . 'form_inspect'; /* ★ */
//
//	$wpdb->insert(
//  		$table_name,
//        $mf->format_db_func() //arrayが返される関数 $wpdbクラスのドキュメントを参照
//    );
//    

	//echo $_POST['update'];

	//$mf = new MailForm('newshop');
    if($mf->updateOrNot()) {
    	$ret = $mf->updateDataToDB();
        if($ret)
        	echo 'ユーザー情報が更新されました';
        else
        	echo 'Error:5111 正常に更新されませんでした';
    }
    else {
        $ret = $mf->sendMailAndSetDB();

        if($ret === true){
            echo '<div class="confFin"><p>ありがとうございます。<br />メッセージの送信が完了しました。</p>';
            echo '<a href="/">HOME</a></div>';
        }
        else {
            echo '<p><span>送信に失敗しました。<br>再度送信するか、直接メールにて <a href="mailto:'.$mf->admin['admin_email'].'">'. $mf->admin['admin_email'] ."</a> までお送りください。</span><br>Error:{$ret}</p>";
        }
    }
    
    $mf->clear(false);
        
    ?>
    
    
