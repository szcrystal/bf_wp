<?php
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
//require_once("Auth/Auth.php");
//require_once('DB.php');

require_once(get_template_directory() . '/inc/auth/CustomAuthClass.php');
require_once(get_template_directory() . '/inc/auth/register/AuthRegisterClass.php');

class FormatMail {
	
    //protected $dirName;
    public $db, $auth;
    
    public $currentUrl;
    public $arTitleName;
    public $admin;
    
    public $adminRow;
    
	public function __construct(AuthRegister $authRegister) {
    	//渡されるtypeArgは必ずslugと同名であること
        $this-> ar = $authRegister;
        $this-> type = $this->ar->type;
    	$this->admin = $this->ar->admin;
        $this->session = isset($_SESSION[$this->type]) ? $_SESSION[$this->type] : NULL;
        
//		$this->arTitleName = $this->getTitleAndName();
//        $arTitleName = $this->getTitleAndName();
//        
//        $this -> adminRow = $this-> getAdminRow();
//        $this -> admin = $this->setAdminData();
        
        //$this->szMail = 'scr.bamboo@gmail.com';

		$this->currentUrl = home_url() . $_SERVER['REQUEST_URI']; //ページのURL action=""で使用
    }


	/* ユーザー宛とMaster宛メール用フォーマット ************************* */
    public function format_mail_contents() {
        
//        $adminHead = $this->admin['head']; //EOL内に入れるとエラーになる ObjectからのStringが良くないぽい
//		$title = get_the_title();

		$siteName = get_bloginfo('name');
        $context = array();
        
        //配列のkeyを変数に変える
		extract($this->admin, EXTR_OVERWRITE);
        extract($this->session, EXTR_OVERWRITE);
        
        //$context = file_get_contents(ABSPATH."/wp-content/themes/_s/inc/auth/register/format/mail-foruser.php");
        include_once("mail-userAndMaster.php");
        
        return $context; //array[user], [master]が返る
    }

    
    
    //Reset password メール内容
    public function format_reset_contents($nick_name, $url) {
		
        //配列のkeyを変数に変える
        extract($this->admin, EXTR_OVERWRITE);
		
        include_once("mail-forReset.php");

        return $context;
    }

	//Contact メール内容
    public function format_contact_contents() {
    	
        $siteName = get_bloginfo('name');
		
        //配列のkeyを変数に変える
        extract($this->admin, EXTR_OVERWRITE);
        extract($this->session, EXTR_OVERWRITE);
		
        include_once("mail-forContact.php");

        return $context;
    }
    
    
    public function format_dl_contents($data) {
        
//        $adminHead = $this->admin['head']; //EOL内に入れるとエラーになる ObjectからのStringが良くないぽい
//		$title = get_the_title();

		$siteName = get_bloginfo('name');
        $file_title = get_the_title($data['report_id']);
        
        $context = array();
        
        //配列のkeyを変数に変える
		extract($this->admin, EXTR_OVERWRITE);
        extract($data, EXTR_OVERWRITE);
        
        //$context = file_get_contents(ABSPATH."/wp-content/themes/_s/inc/auth/register/format/mail-foruser.php");
        include_once("mail-forDL.php");
        
        return $context; //array[user], [master]が返る
    }

    

//
//    public function format_func($turnArg) {
//
//            $all_format = array();
//            $out_format = "<p><span>■%s</span><br/>{$turnArg}%s</p>";
//            $out_format_check = "<p><span>%s</span>{$turnArg}　・%s</p>";
//            
//           /* if ( ! $_SESSION['check'][0] == '') {
//                $check_value = implode("{$value}　　・", $_SESSION['check'][0]);
//            }*/
//            
//            //サイト上で改行して表示させるなら
//            //$_SESSION['comment'][0] = str_replace("\n", "<br />", $_SESSION['comment'][0]);
//            
//            foreach ($_SESSION[$this->type] as $key => $val) {
//                
//                if ($key == 'check' && $_SESSION[$this->type]['check'][1] != '') {
//                    $check_value = implode("{$turnArg}　　・", $_SESSION['check'][1]);
//                    $all_format[] = sprintf($out_format_check, $val[0], $check_value);
//                }
//
//                else if ( $this->checkSessionKey($key) ) {
//                    $inputValue = $this->h_esc($val[1]);
//                    
//                    if(strpos($inputValue, "\n")) { // 改行文字があればnl2br()をして返す
//                        $inputValue = nl2br($inputValue);
//                    }
//                    
//                    $all_format[] = sprintf($out_format, $this->h_esc($val[0]), $inputValue);
//                }
//            }
//            
//            return implode("$turnArg", $all_format);
//    }
//
//    /* メール送信用フォーマット */
//    public function format_mail_func($turnArg) {
//        
//        $all_format = array();
//        $out_format = "■ %s{$turnArg}%s";
//        
//        //サイト上で改行表示した場合に、改行コードを戻す
//        //$_SESSION['comment'][0] = str_replace("<br />", "\n", $_SESSION['comment'][0]);
//            
//        foreach ($_SESSION[$this->type] as $key => $val) {
//            if ( $this->checkSessionKey($key) ) {
//                    $val[1] = $this->h_esc($val[1]);
//                    $val[1] = $this->addUnitByKey($key, $val[1]); //単位を付ける
//                    $all_format[] = sprintf($out_format, $val[0], $val[1]);
//                }
//        }
//        
//        return implode("$turnArg"."$turnArg", $all_format);
//    }
//
//    /* DBセット用フォーマット */
//    public function format_db_func($newOrUpdate) {
//        $db_format = array();
//        
//        foreach ($_SESSION[$this->type] as $key => $val) {
//            if ( $this->checkSessionKey($key) ) {
//            	if($key == 'password') {
//                	if(isset($val[1])) { //$val[1] >> $_SESSION['type']['password'][1] に暗号のarray($str, $key)が入っている
//                    	$passwd = $this->deCrypt($val[1]);
//                    	$db_format[$key] = md5($passwd);
//                    }
//                }
//                else {
//                	$db_format[$key] = $val[1]; //nameと値の組みで配列に入れる
//                }
//            }
//        }
//        
//        if($newOrUpdate == 'new')
//	        $db_format['create_time'] = current_time( 'mysql' );
//    	else if($newOrUpdate == 'update')
//        	$db_format['update_time'] = current_time( 'mysql' );
//        
//        return $db_format; //array
//    }
//
//
//    
//    
//    
//    
//    /* Admin用メールフォーマット **************************** */
//    public function format_admin() {
//
//        //$adminRow = getAdminRow();
//        $title = get_the_title();
//        $siteName = get_bloginfo('name');
//
////to Admin mail sentence /* ★ */
//$context = <<<EOL
//
//「{$siteName}」サイトの{$title}より
//メッセージの送信がありました。
//
//頂きました内容は下記となります。
//
//
//---------　{$title} 内容　-----------
//
//
//EOL;
//// END to user mail sentence
//    
//        $context .= $this->format_mail_func($value="\n");
//        $context .= "\n\n\n\n\n" . $this->admin['foot'];
//        return $context;
//    }
//    






} //class End





