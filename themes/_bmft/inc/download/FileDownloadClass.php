<?php
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
//require_once("Auth/Auth.php");
//require_once('DB.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-config.php");
require_once(realpath(dirname( __FILE__ )) . "/../auth/register/AuthRegisterClass.php");
//require_once(realpath(dirname( __FILE__ )) . "/../auth/register/format/FormatMailClass.php");


class FileDownload extends AuthRegister {
	
    //protected $dirName;
    public $db, $auth;
    
    public $currentUrl;
    public $arTitleName;
    public $admin;
    
    public $adminRow;
    
    public $table_name;
    
	public function __construct() {
    	
        parent::__construct('dl');
    	//渡されるtypeArgは必ずslugと同名であること
        //$this -> type = $slug;
    
//    	$this-> db = $dbInstance;
//        $this -> auth = $authInstance;
//        
//        //$dirname = ($dirname == DIRECTORY_SEPARATOR) ? '' : $dirname;
//        $db = $dbInstance;
//        $auth = $authInstance;
//        
//        $this->tableName = 'auth';
        $this->table_name = 'purchase_repo'; // Not OverRide
		$this->arTitleName = $this->getTitleAndName();
        $this->pd = $this->getPostAndUserData();
//        $arTitleName = $this->getTitleAndName();
//        
//        $this -> adminRow = $this-> getAdminRow();
//        $this -> admin = $this->setAdminData();
//        
//        $this->szMail = 'scr.bamboo@gmail.com';
//
//		$this->currentUrl = home_url() . $_SERVER['REQUEST_URI']; //ページのURL action=""で使用
        
        //$this->fm = new FormatMail($this);
    }


	/* Title and Name ************************************* */
    public function getTitleAndName() {
        return array(
                'user_id' => '会員ID',
                'report_id' => 'レポートID',
                'user_name' => '氏名',
                'user_email' => 'メールアドレス',
                'file_name' => 'ファイル名',
                'price' => '料金',
                'pay_state' => 'お支払い状況',
                'create_time' => 'DL日',
            );
    }
    
    private function getTableName() {
        return 'purchase_repo';
    }
    
    public function getPostAndUserData() {
    	$postdata = array();
        
        if(count($_POST) > 0) :
        foreach($_POST as $key => $val) {
        	$postdata[$key] = isset($val) ? $val : NULL;
        }

        //UserDataとドッキングする
        if($postdata['user_id']) {
        	global $wpdb;
        	$userdata = $wpdb -> get_row("SELECT * FROM auth WHERE id = $postdata[user_id]", ARRAY_A);
        	
        	//$postdata = array_merge($this->getAllDatas($postdata['user_id']), $postdata);
            $postdata = array_merge($userdata, $postdata);
        }
        
        //現在の時刻を入れる
        $postdata['dl_time'] = current_time( 'mysql' );
        
        //print_r($postdata);
        endif;
        
        return $postdata;
    }
    
    public function fileDL() {
    	$pd = $this->pd;
		
         // ルート前にするなら -> "/../this_zip/"
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/this_zip/";

		$zipFile = $pd['zip_file'];
    	$dir = $dir . $zipFile;
    
        $fileSize = filesize($dir);
        $mime = 'application/zip';
        
        $ua = $_SERVER['HTTP_USER_AGENT'];
        
        //IE文字化け
        if(strpos($ua, 'MSIE') !== FALSE || strpos($ua, 'Trident') !== FALSE || strpos($ua, 'Edge') !== FALSE) {
            $zipFile = mb_convert_encoding($zipFile, 'SJIS-win', 'UTF-8');
        }
        
        header('Content-Type: "'. $mime .'"');
        header('Content-Disposition: attachment; filename="'. $zipFile .'"');
        
        //IE文字化け別方法：特殊文字が消えるが文字化けはしない
        //header('Content-Disposition:attachment; filename*=UTF-8\'\''. rawurlencode($zipFile));
        
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: no-cache');
        header('Content-Length: '. $fileSize);
        
        readfile($dir);
    }
    
    public function setPurchaseDB() {
    
    	global $wpdb;
        
    	$pd = $this->pd; //all user data
        //print_r($pd);
            
        $setArr = array(
            'user_id' => $pd['user_id'],
            'report_id' => $pd['report_id'],
            'user_name' => $pd['nick_name'],
            'user_email' => $pd['username'],
            'file_name' => $pd['zip_file'],
            'price' => $pd['price'],
            'pay_state' => 0,
            'create_time' => $pd['dl_time'],
        );
        
        $dbRet = $wpdb->insert( //return false or row-number
            $this->table_name,
            $setArr //Array
        );
        
        return $dbRet;

        //DBInsert失敗時でもadminとuserへのメールは送信させ、自分宛にのみメールを送る
//        if(! $dbRet)
//            die("DB Failed");
//        else
//            echo "DB Success";
    }
    
    

    public function setInfoSendMail() { //OveRride Authregister
    
    	$pd = $this->pd;
        
        
    	/* 件名 */
        $subject = get_the_title($pd['report_id']) . 'のダウンロードがされました ー'. $this->admin['admin_name'] . 'ー'; //Master用
        $return_subject = $this->admin['subject_download']; //User用
        
		/* ユーザー名前とメールアドレス */
        $name = $pd['nick_name']; //User name
        $mail_add = $pd['username']; //User mail address
        
		/* Mail 内容 from FormatMailClass() */
        $contents = $this->fm->format_dl_contents($pd); //arrayが返る $contents['master'] $contents['user']
        $contents_master = $contents['master'];
        $contents_user = $contents['user'];
        
        //変数名をkeyにして配列で返す
        return compact('subject', 'return_subject', 'name', 'mail_add', 'contents_master', 'contents_user');
    }
    
    
    public function checkIsSameData() {
    	global $wpdb;
        $table_name = $this->table_name;
        $pd = $this->pd;
    
    	if($pd['user_id']) :
    	    $purchaseObj = $wpdb -> get_row("SELECT * FROM $table_name WHERE user_id = $pd[user_id] AND report_id = $pd[report_id]", OBJECT);
    	endif;
        
        return $purchaseObj; //データがなければ取得オブジェクトは空
    }
    
    
    public function sendMailAndSetDB() {
    	
        $purchaseObj = $this->checkIsSameData();
        
        if(! $purchaseObj) : //重要：データがなければ取得オブジェクトは空であることを確認する
    	
        	$ret = $this->setPurchaseDB();
        
            if($ret) {
                $mailRet = $this-> sendMail();
                
                if(! $mailRet)
                    echo "Mail Error: Not Mail Sending";
                
            }
            else {
                return "DB Error: DB Error";
            }

		endif;
    
    }
    


} //class End





