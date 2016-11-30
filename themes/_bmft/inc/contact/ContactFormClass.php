<?php
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
//require_once("Auth/Auth.php");
//require_once('DB.php');

//require_once(ABSPATH. "/wp-content/themes/_s/inc/auth/register/AuthRegisterClass.php");
//require_once(ABSPATH. "/wp-content/themes/_s/inc/auth/register/format/FormatMailClass.php");

require_once(get_template_directory() . "/inc/auth/register/AuthRegisterClass.php");
require_once(get_template_directory() . "/inc/auth/register/format/FormatMailClass.php");


class ContactForm extends AuthRegister {
	
    //protected $dirName;
    public $db, $auth;
    
    public $currentUrl;
    public $arTitleName;
    public $admin;
    
    public $adminRow;
    
    //private $tableName;
    
	public function __construct($slug) {
    	
        parent::__construct($slug); // 継承元クラスのコンストラクターを呼ぶ
        
        $this->tableName = $this->getTableName(); //AuthRegisterのtableNameを上書き（オーバーライド）
    	
        //渡されるtypeArgは必ずslugと同名であること
        //$this -> type = $slug;
    
    	//$this-> db = $dbInstance;
        //$this -> auth = $authInstance;
        
        //$dirname = ($dirname == DIRECTORY_SEPARATOR) ? '' : $dirname;
        //$db = $dbInstance;
        //$auth = $authInstance;
        
//		$this->arTitleName = $this->getTitleAndName();
//        $arTitleName = $this->getTitleAndName();
        
//        $this -> adminRow = $this-> getAdminRow();
//        $this -> admin = $this->setAdminData();
        
//        $this->szMail = 'scr.bamboo@gmail.com';
//
//		$this->currentUrl = home_url() . $_SERVER['REQUEST_URI']; //ページのURL action=""で使用
        
        //$this->fm = new FormatMail($this);
    }


	/* Title and Name ************************************* */
    public function getTitleAndName() { //OverRide
        return array(
                'nick_name' => 'お名前',
                'mail_add' => 'メールアドレス',
                'comment' => 'コメント',
            );
    }
    
    //Contact用のtable name
    private function getTableName() {
    	global $wpdb;
        return $wpdb->prefix . 'form_' . $this->type;
    }
    
    //フォームのvalueに書く値 sessionがあればsession値
    public function sessionOrUserdata($key) { //Override
        if(isset($_SESSION[$this->type][$key])) {
            return $_SESSION[$this->type][$key][1];
        }
    }



	public function setInfoSendMail() { //OveRride Authregister
    	/* 件名 */
        $subject = get_bloginfo('name') . 'よりお問い合わせがありました ー'. $this->admin['admin_name'] . 'ー'; //Master用
        $return_subject = $this->admin['subject_contact']; //User用
        
		/* ユーザー名前とメールアドレス */
        $name = $_SESSION[$this->type]['nick_name'][1]; //User name
        $mail_add = $_SESSION[$this->type]['mail_add'][1]; //User mail address
        
		/* Mail 内容 from FormatMailClass() */
        $contents = $this->fm->format_contact_contents(); //arrayが返る $contents['master'] $contents['user']
        $contents_master = $contents['master'];
        $contents_user = $contents['user'];
        
        //変数名をkeyにして配列で返す
        return compact('subject', 'return_subject', 'name', 'mail_add', 'contents_master', 'contents_user');
    }

    
    
    
//    /* SelectBoxの配置とsession戻り時でのselected付け $objNumにsessionの値を渡す*/
//    public function selectBox($first, $last, $objNum=null) {
//	
//    	//先頭Optionの設定
//        if($objNum == null) { //初回表示時
//            echo '<option value="--" selected>--</option>';
//        }
//        else { //sessionがsetされている時にも--のoptionを表示させるため
//            $select = ($objNum == '--') ? ' selected' : '';
//            echo '<option value="--"' . $select .'>--</option>';
//        }
//        
//        //ジェネレータ利用の場合 domainkingでyieldが使えない
//        /*
//        $datas = $this->xrange($first, $last); //ジェネレータ
//            
//        foreach($datas as $data) {
//            if(isset($objNum) && $data == $objNum)
//                echo '<option value="'.$data .'" selected>'.$data.'</option>';
//            else
//                echo '<option value="'.$data .'">'.$data.'</option>';
//        }
//		*/
//        
////		ORGコード ------------------        
//        if($first > $last) { //逆順の時 Yearにて
//            for($first; $first >= $last; $first--) {
//                if(isset($objNum) && $first == $objNum)
//                    echo '<option value="'.$first .'" selected>'.$first.'</option>';
//                else
//                    echo '<option value="'.$first .'">'.$first.'</option>';
//            }
//            
//        }
//        else { //正順
//            for($first; $first <= $last; $first++) {
//                if(isset($objNum) && $first == $objNum)
//                    echo '<option value="'.$first .'" selected>'.$first.'</option>';
//                else
//                    echo '<option value="'.$first .'">'.$first.'</option>';
//            }
//        }
//    
//    }
    
    //selectBox($first, $last, $objNum=null)で使用するジェネレータ。 *** yield->配列を使用せず値をキープできる
    /*
    private function xrange($start, $end) {
    	if($start > $end) //逆順の時 Yearにて
        	for($i = $start; $i >= $end; $i--) yield $i;
        else //正順
            for($i = $start; $i <= $end; $i++) yield $i;

    }
    */
    
    


} //class End





