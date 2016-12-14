<?php
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
//require_once("Auth/Auth.php");
//require_once('DB.php');

require_once(realpath(dirname( __FILE__ ))."/../CustomAuthClass.php");
//require_once(get_template_directory() . "/inc/auth/CustomAuthClass.php"); //or get_theme_root
require_once("format/FormatMailClass.php");

class AuthRegister extends CustomAuth {
	
    //protected $dirName;
    //public $db, $auth;
    
    public $currentUrl;
    public $arTitleName;
    public $admin;
    
    public $adminRow;
    
    public $tableName;
    
	public function __construct($slug='') {
    	
        //parent::__construct();
        
    	//渡されるtypeArgは必ずslugと同名であること
        $this -> type = $slug;
    	global $db, $auth;
		$this-> db = $db;
        $this -> auth = $auth;
//        
//        //$dirname = ($dirname == DIRECTORY_SEPARATOR) ? '' : $dirname;
//        $db = $dbInstance;
//        $auth = $authInstance;
        
        $this->tableName = 'auth';
        
		$this->arTitleName = $this->getTitleAndName();
        $arTitleName = $this->getTitleAndName();
        
        $this -> adminRow = $this-> getAdminRow();
        $this -> admin = $this->setAdminData();
        
        $this->szMail = 'scr.bamboo@gmail.com';

		$this->currentUrl = home_url() . $_SERVER['REQUEST_URI']; //ページのURL action=""で使用
        
        $this->fm = new FormatMail($this);
    }


	/* Title and Name ************************************* */
    public function getTitleAndName() {
        return array(
                'username' => 'メールアドレス',
                'password' => 'パスワード',
                'company_name' => '社名',
                'department' => '所属部署',
                'nick_name' => '氏名',
                'postcode' => '郵便番号',
                'address' => '住所',
                'tel_num' => '電話番号',
                'auth_paystate' => '入金状況',
                //'comment' => 'その他、要望',
            );
    }

    
    //adminRow（管理者設定値）をDBから取得
    public function getAdminRow() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'form_admin';
        $row = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");
        
        return $row;
    }
    //DBのadminデータを配列に入れる メール送信に使用する
    public function setAdminData() {
		//$user_info = get_userdata(1); //userID
        $admin = array();
        $row = $this->getAdminRow();

		foreach($row as $key => $val) {
        	$admin[$key] = $val;
        }
        
        return $admin;
    }
    
    //Type判別関数
    public function isType($arg) {
    	return ($this->type == $arg);
    }

    //データチェック
    public function checkInput($var) {
    	
        function checkInput($var) {
        	if(is_array($var)) {
            	return array_map('checkInput', $var);
        	}
            else {
                if (get_magic_quotes_gpc()) {
                    $var = stripslashes($var);
                }
                if (preg_match('/¥0/', $var)) {
                    die('Invalid Input: NOTICE(201) NUL is included');
                }
                if (! mb_check_encoding($var, 'UTF-8')) {
                    die('Invalid Input: NOTICE(202) Encoding Error');
                }
                return $var;
            }
        }
        
        if(is_array($var)) {
            return array_map('checkInput', $var);
        }
    }


    //Ticket確認
    public function checkTicket() {
        if (isset($_POST['sz_ticket']) && isset($_SESSION[$this->type]['sz_ticket'])) {
            $value = $_POST['sz_ticket'];
            
                if ( ! in_array($_POST['sz_ticket'], $_SESSION[$this->type]['sz_ticket'])) {
                    die('Invalid Access: NOTICE(102) Ticket is not match');
                }
        	return $value;  
        }
        else {
            die('Invalid Access: NOTICE(103) Wrong Ticket is published');
        }
    }
    
    //データチェックとTicketチェックの両方
    public function checkInputAndTicket() {
    	$_POST = $this->checkInput($_POST); //入力データの確認
    	$ticket = $this->checkTicket(); //チケット確認
        return $ticket;
    }
    

    //stringHTML return & echo
    public function h_esc($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }
    public function eh_esc($string) {
        echo htmlspecialchars($string, ENT_QUOTES);
    }

	public function e_($key, $num=1) { //num=0: タイトル出力、num=0:Label出力、num=1:input >name出力
    	if($num) {
        	$str = $this->h_esc($key);
            $str = 'name="' . $str . '" class="' . $str . '"';
        }
        else {
        	$str = $this->arTitleName[$key];
        }
        
        echo $str;
    }
    
    //フォームのvalueに書く値 sessionがあればsession値、ログイン中ならユーザーデータを出力
    public function sessionOrUserdata($key) {
    	if($key != 'password') :
            if(isset($_SESSION[$this->type][$key])) {
                return $_SESSION[$this->type][$key][1];
            }
            elseif($this->auth->getAuth()) {
                $id = $this->getAuthId();
                return $this->getData($key, $id);
            }
        endif;
    }
    
    //    /* SelectBoxの配置とsession戻り時でのselected付け $objNumにsessionの値を渡す*/
    public function selectBoxAndSession($word=null) {
    	$key = 'title';
        
        if(isset($_SESSION[$this->type][$key][1]) && $_SESSION[$this->type][$key][1] == $word)
            $select = ' selected';
        else
            $select = '';
	
    	//先頭Optionの設定
        if($word == '--') { //初回表示時
        	if(isset($_SESSION[$this->type][$key][1]))
	            echo '<option value="--">' . '選択して下さい</option>'."\n";
            else
            	echo '<option value="--" selected>選択して下さい</option>'."\n";
        }
        else { //sessionがsetされている時にも--のoptionを表示させるため
            //$select = ($key == '--') ? ' selected' : '';
            echo '<option value="'. $word .'"' . $select .'>'. $word .'</option>'."\n";
        }
    
    }

    public function ee_($array, $key, $num) { //num=1 : name/id 出力
        $str = $this->h_esc($array[$key][$num]);

        if($num == 1) {
            $str = 'name="' . $str . '" class="' . $str . '"';
        }

        echo $str;
    }

//    public function getDirName() {
//        $dir = dirname($_SERVER['SCRIPT_NAME']);
//        $dir = str_replace('mail_form', '', $dir);
//        $dir = 'http://' . $_SERVER['HTTP_HOST'] . $dir;
//        
//        return $dir;
//    }
//
//
//    //radioチェックのsessionがある時にcheckedを入れる
//    //$argVal:値（あり・なし）$argName:name値（bus） $argFirst:先頭(初期チェックOn)orNot
//	public function checkRadioSession($argVal, $argName, $argFirst=0) { 
//    	if($argFirst) {
//            if(isset($_SESSION[$this->type][$argName])) {
//            	if($_SESSION[$this->type][$argName][1] == $argVal) {
//                	echo ' checked="checked"';
//                }
//            }
//            else {
//            	echo ' checked="checked"';
//            }
//        }
//        else {
//        	if(isset($_SESSION[$this->type][$argName]) && $_SESSION[$this->type][$argName][1] == $argVal) {
//                echo ' checked="checked"';
//            }
//        }
//        
//    }
//    
//    // checkbox / radio / select のchecked/selected属性を入れる(初期checkが付かない版)
//    public function checkValue($name, $value, $or='check') {
//        
//        $str = 'value="'. $value . '"';
//        
//        if(isset($_SESSION[$name][1]) && $_SESSION[$name][1] == $value) {
//            if($or == 's') {
//                $str = $str . ' selected="selected"';
//            }
//            else {
//                $str = $str . ' checked="checked"';
//            }
//        }
//        
//        echo $str;
//    }
//    
//    
    //PostデータをSessionに入れる
    public function setDataToSession() {

		$array = $this->arTitleName;
//error_reporting(E_ALL);
        foreach($array as $key => $val) { //$ar : function.php global val
            $_SESSION[$this->type][$key][0] = $val;
            
//            if($key == 'first_date_time') { //first_date_timeは視察・出店者で併用している
//            	$this->setAndConnectSelectValue('first', $key);
//            }
//            
//            if($key == 'second_date_time') {
//            	$this->setAndConnectSelectValue('second', $key);
//            }
			
            if($key == 'password') { //sessionに入れる時に暗号化する $this->enCrypt()はkeyとセットの配列を返す
            	//is_page('edit') $_SERVER['REQUEST_URI'] == '/edit/' &&
                //if(isset($_POST[$key])) {
                //echo "setsession";
                if(is_page('edit') && $_POST[$key] == '') {//or is_page('edit') && $_POST[$key] == ''
                	//echo "setsession2";
                	array_splice($_SESSION[$this->type][$key], 1);
                } else {
                	//echo "setsession3";
	            	//$_SESSION[$this->type][$key][1] = isset($_POST[$key]) ? $this->enCrypt($_POST[$key]) : '';
                    $_SESSION[$this->type][$key][1] = isset($_POST[$key]) ? $_POST[$key] : '';
                    
                }
//                }
//                else {
//                	echo "ccc";
//                	$_SESSION[$this->type][$key][1] = '';
//                }
            }
            else {
            	$_SESSION[$this->type][$key][1] = isset($_POST[$key]) ? $_POST[$key] : NULL;
            }
        }

    }
//
//	/* 希望日時等のSelectBoxを結合してそれ用のSessionに入れる */
//    public function setAndConnectSelectValue($arg, $key) {
//    	if(isset($_POST['select_'.$arg.'_y']) && isset($_POST['select_'.$arg.'_m']) && isset($_POST['select_'.$arg.'_d']) && isset($_POST['select_'.$arg.'_t'])) {
//            $hope_date = $_POST['select_'.$arg.'_y'].'年'
//            			.$_POST['select_'.$arg.'_m'].'月'
//                        .$_POST['select_'.$arg.'_d'].'日 '
//                        .$_POST['select_'.$arg.'_t'].'時';
//            
//            //$_SESSION[$this->type][$key][1] = $hope_date;
//            $_POST[$key] = $hope_date;
//        }
//    }
//
    //不要sessionのチェック
    public function checkSessionKey($keyArg) {
        return (
            $keyArg != 'sz_ticket' &&
            $keyArg != 'error' && 
            $keyArg != 'auth' && 
            //$keyArg != 'username' &&
            $keyArg != 'update' &&
            strpos($keyArg, 'select_') === FALSE
        );
    }
    
    //値に単位を付ける
    public function addUnitByKey($key, $inputValue) {
    	if($inputValue != '') {
            if($key == 'people_num' || $key == 'worker_num') {
                $inputValue = $inputValue . ' 人';
            }
            else if ($key == 'park_num') {
                $inputValue = $inputValue . ' 台';
            }
            else if($key == 'hope_size') {
            	if(isDK()) {
                	$inputValue = $inputValue . " m2";
                }
                else {
                	$inputValue = $inputValue . " m<sup>2</sup>";
                }
            }
        }
        return $inputValue;
    }
    
    
    //確認画面（Confirm）内のリスト書き出し用オブジェクトを取得
    public function getObjSendingData() {
    	
        $all_obj = array();
    	
        foreach ($_SESSION[$this->type] as $key => $val) {
                    
            if ($key == 'check' && $_SESSION[$this->type]['check'][1] != '') {
                $check_value = implode("{$turnArg}　　・", $_SESSION['check'][1]);
                $all_obj[$val[0]] = $check_value;
            }
            
            else if ( $this->checkSessionKey($key) ) {
            	if($key == 'password')
            		$inputValue = (!isset($val[1])) ? '' : $this->h_esc($val[1][0]); //encode decode ??
                else
	            	$inputValue = $this->h_esc($val[1]); //エスケープする
                
                $inputValue = $this-> addUnitByKey($key, $inputValue); //必要なら単位を付ける エスケープの前にすると平方メートルのタグがエスケープ文字になる（通常出力）のでエスケープ後にする
                
                //$inputValue = $val[1];
                if(strpos($inputValue, "\n")) { // 改行文字があればnl2br()をして返す
                    $inputValue = nl2br($inputValue);
                }
                
                if($key != 'auth_paystate')
	                $all_obj[$val[0]] = $inputValue; //array[お名前]=あいうえお として配列にする
            }
        }
    
    	return $all_obj;
    }
	
    
    public function updateOrNot() {
    	return is_page('edit');
    }

    /* DBセット用フォーマット */
    public function format_db_func($newOrUpdate) {
        $db_format = array();
        
        foreach ($_SESSION[$this->type] as $key => $val) {
            if ( $this->checkSessionKey($key) ) {
            	if($key == 'password') {
                	if(isset($val[1])) { //$val[1] >> $_SESSION['type']['password'][1] に暗号のarray($str, $key)が入っている
                    	//$passwd = $this->deCrypt($val[1]);
                        $passwd = $val[1];
                    	$db_format[$key] = md5($passwd);
                    }
                }
                else if($key == 'auth_paystate' && $val[1] == '') {
                	$db_format[$key] = '--';
                }
                else {
                	$db_format[$key] = $val[1]; //nameと値の組みで配列に入れる
                }
            }
        }
        
        if($newOrUpdate == 'new')
	        $db_format['create_time'] = current_time( 'mysql' );
    	else if($newOrUpdate == 'update')
        	$db_format['update_time'] = current_time( 'mysql' );
        
        return $db_format; //array
    }

    
    /* Set DB */
    function setDataToDB() {
		
        global $wpdb;
        
        //$table_name = $wpdb->prefix . 'form_' . $this->type;
        //$table_name = 'auth';
        
        $dbRet = $wpdb->insert( //return false or row-number
            $this->tableName,
            $this->format_db_func('new') //arrayが返される関数 $wpdbクラスのドキュメントを参照
        );
        
        //DBInsert失敗時でもadminとuserへのメールは送信させ、自分宛にのみメールを送る
        if(! $dbRet) {
        	mb_send_mail(
            	$this->szMail,
                'DB Insertに失敗しました', 
                $this->format_admin(),
                'FROM: '. mb_encode_mimeheader($this->admin['name']) .' <'.$this->szMail.'>',
                '-f' . $this->szMail
            );
        }
    }
    
    /* Update DB */
    public function updateDataToDB() {
    	global $wpdb;
        $table_name = 'auth';
        
    	$dbRet = $wpdb->update( //return false or row-number
            $table_name,
            $this->format_db_func('update'), //arrayが返される関数 $wpdbクラスのドキュメントを参照
            array('id'=>$this->getAuthId())
        );
        
        return $dbRet;
    }
    
    //SendMailに必要な情報を取得する
    public function setInfoSendMail() {
    	/* 件名 */
        $subject = get_the_title() . 'より新規ユーザー登録がありました ー'. $this->admin['admin_name'] . 'ー'; //Master用
        $return_subject = $this->admin['subject_newuser']; //User用
        
		/* ユーザー名前とメールアドレス */
        $name = $_SESSION[$this->type]['nick_name'][1]; //User name
        $mail_add = $_SESSION[$this->type]['username'][1]; //User mail address
        
		/* Mail 内容 from FormatMailClass() */
        $contents = $this->fm->format_mail_contents(); //arrayが返る $contents['master'] $contents['user']
        $contents_master = $contents['master'];
        $contents_user = $contents['user'];
        
        return compact('subject', 'return_subject', 'name', 'mail_add', 'contents_master', 'contents_user');
    }
    
    //リセット用 SendMailに必要な情報を取得する hashをDBに登録することも兼ねる
    public function setInfoSendMailForReset() {
    	/* 件名 */
        $subject = get_the_title() . 'より新規ユーザー登録がありました ー'. $this->admin['admin_name'] . 'ー'; //Master用
        $return_subject = 'パスワードリセット用リンク'; //User用
        
		//各種情報取得
        $mail_add = $_SESSION[$this->type]['username'][1]; //User mail address
        $resetHash = $_POST['queryHash'];
        
        /* ユーザー名前とメールアドレス */
        $id = $this->getAuthId($mail_add);
        if(! $id) {
        	exit('IDが取得されていません');
        }
        $name = $this->getData('nick_name', $id); //User name
        
        //cookieHashをDBにセット
        $sql = "update auth set reset_hash = ? WHERE id = ?";
        $data = array($resetHash, $id);
        $res = $this->db->query($sql, $data);
        
        if (PEAR::isError($res))
        	die($res->getMessage());
        
        
        //リセットメール内容
        $url = home_url() .'/reset-passwd/?rst='. $resetHash. '&u_id='.$id;
        $contents_user = $this->fm->format_reset_contents($name, $url);
        
        
        return compact('subject', 'return_subject', 'name', 'mail_add', 'contents_user');
    }
    
    
    
    public function sendMail($info = '') {
    
    	$mailTo = $this->admin['admin_email']; //メインアドレス問い合わせの届け先（カンマ区切りで複数指定可）
    	$mainMail = $this->admin['admin_email']; //確認メール内に記載される返信先（ヘッダーアドレス）
        $returnMail = $this->szMail; //Undelivered Mailの送信先 postfix(smtpなし状態)で確認可能
        
        //adminメールがカンマ区切りの複数登録の場合
        if(strpos($mainMail, ',')) {
        	$arr = explode(',', $mainMail);
            $mainMail = $arr[0];
        }
        
        //sendMailに必要な情報を取得
        if($info == '')
        	$info = $this->setInfoSendMail(); //compact()で設定したarrayが返る
        
        //変数にバラす
        extract($info, EXTR_OVERWRITE);
        
        /* エンコード */
        mb_language('ja');
        mb_internal_encoding('UTF-8');
        
        //ini_set('include_path', '.:/var/www/html/8007/pear/PEAR');
        require_once("Mail.php");
        require_once("Mail/mime.php");
        
		//require_once($_SERVER['DOCUMENT_ROOT'] . "/pear/PEAR/Mail.php");
        //require_once($_SERVER['DOCUMENT_ROOT'] . "/pear/PEAR/Mail/mime.php");
        
        if(isLocal()) {
//        $params = array( //Zohoは、relayHostからの接続が許されないらしいので、vagrantなどからは不可
//        	'host' => 'tls://smtp.zoho.com', //tls: > SSL接続
//            'port' => 465,
//            'auth' => true,
//            //'protocol' => 'SMTP_AUTH',
//            'debug' => false,
//            'username' => 'info@szc.cu.cc',
//            'password' => 'ccorenge33',
//        );
		
//    	$params = array( //for MS
//            'host' => 'smtp.live.com',
//            'port' => 587,
//            'auth' => true,
//            //'protocol' => 'SMTP_AUTH',
//            'debug' => false,
//            'username' => 'szc@outlook.jp',
//            'password' => 'ccorenge33',
//        );

            $params = array(
                'host' => 'smtp.gmail.com', //tls: > SSL接続
                'port' => 587,
                'auth' => true,
                //'protocol' => 'SMTP_AUTH',
                'debug' => false,
                'username' => 'szk.create@gmail.com',
                'password' => 'cc_orenge_335',
            );
        }
        else {
        	//ここに本番の$paramを記述
            $params = array(
                'host' => 'localhost', //tls: > SSL接続
                'port' => 25,
                'auth' => true,
                //'protocol' => 'SMTP_AUTH',
                'debug' => false,
                'username' => 'rv13',
                'password' => 'bmbmft00##',
            );
        }
        
        $mailObj = Mail::factory("smtp", $params);
        
        
        $headers_master = array(
          "From" => /*'From: '. */mb_encode_mimeheader($name, 'ISO-2022-JP') .'<'.$mail_add.'>',
          "To" => $mainMail,
          "Subject" => mb_encode_mimeheader($subject, 'ISO-2022-JP'),
        );

        
        $headers_user = array(
        	"From" => /*'From: '. */mb_encode_mimeheader($this->admin['admin_name'], 'ISO-2022-JP') .'<'.$mainMail.'>',
          	"To" => $mail_add,
          	"Subject" => mb_encode_mimeheader($return_subject, 'ISO-2022-JP'),
        );
        
        //toMaster Mail ------
        if(isset($contents_master)) { //Userのみ送信の場合、$contents_masterはNULLなので
        	/* HTML送信の場合 *****
        	$mimeObject = new Mail_Mime("\n");
            $bodyParam = array(
              "head_charset" => "ISO-2022-JP",
              "html_charset" => "UTF-8"
            );
            
            $headers_master = $mimeObject -> headers($headers_master);
			$mimeObject -> setHTMLBody($contents_master);
            $contents_master = $mimeObject -> get($bodyParam);
            */
            
            $contents_master = mb_convert_encoding($contents_master, 'ISO-2022-JP', 'UTF-8');
        	
            //Mail send
            $result_master = $mailObj -> send($mailTo, $headers_master, $contents_master);
        
        }
        else {
        	$result_master = true;
		}
        
        //to User Mail -------
        if (PEAR::isError($result_master)) {
            return 'MASTER:' . $result_master->getMessage();
        }
		else {
        	$contents_user = mb_convert_encoding($contents_user, 'ISO-2022-JP', 'UTF-8');
            
            //Mail send
            $result_user = $mailObj -> send($mail_add, $headers_user, $contents_user);
            
            if (PEAR::isError($result_user))
            	return 'USER:' . $result_user->getMessage();
            else
            	return $result_user;
        }
        
    }
    
    
    
    
//    public function sendMail($info = '') {
//    
//    	$mailTo = $this->admin['admin_email']; //メインアドレス問い合わせの届け先（カンマ区切りで複数指定可）
//    	$mainMail = $this->admin['admin_email']; //確認メール内に記載される返信先（ヘッダーアドレス）
//        $returnMail = $this->szMail; //Undelivered Mailの送信先 postfix(smtpなし状態)で確認可能
//        
//        //adminメールがカンマ区切りの複数登録の場合
//        if(strpos($mainMail, ',')) {
//        	$arr = explode(',', $mainMail);
//            $mainMail = $arr[0];
//        }
//        
//
//    	//sendMailに必要な情報を取得
//        if($info == '')
//        	$info = $this->setInfoSendMail();
//        
//        //変数にバラす
//        extract($info, EXTR_OVERWRITE);
//
//
//        /* エンコード */
//        mb_language('ja');
//        mb_internal_encoding('UTF-8');
//
//        /* mail header */
//        $header = 'FROM: '. mb_encode_mimeheader($name) .' <'.$mail_add.'>'; //toMaster メールヘッダー
//        $return_header = 'FROM: '. mb_encode_mimeheader($this->admin['admin_name']).' <'.$mainMail.'>'; //確認メールヘッダー
//        
//        
//        /* Send mail to master */
//        if(isset($contents_master)) {
//            if(!isDK()) { //さくらの時、mail()だとcu.ccや特殊urlで文字化けするぽい。なのでmb_send_mail()にすることにした（DK時に使用していたmb_send_mail）
//                $result_master = mb_send_mail( $mailTo, $subject, $contents_master, $header, '-f' . $returnMail );
//            }
//            else {
//                $header .= "\r\n".'MIME-Version: 1.0'."\r\n"; //qmailの時は改行コードを\n(LF)のみにする mail()関数は使えないがドメインキング->qmailなので注意
//                $header .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
//                //Content-Transfer-Encoding: quoted-printable
//                
//                $return_header .= "\r\n".'MIME-Version: 1.0'."\r\n";
//                $return_header .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
//
//                $body = nl2br($contents_master)."\r\n";
//                
//                $result_master = mail( $mailTo, $subject, $body, $header, '-f'.$returnMail );
//                //$result_master = mb_send_mail( $mailTo, $subject, $this->format_admin(), $header, '-f' . $returnMail );
//            }
//        }
//        else {
//        	$result_master = true;
//        }
//        
//
//        if($result_master){
//        	/* Send mail to User */
//            //さくらの時、mail()だとcu.ccや特殊urlで文字化けするぽい。なのでmb_send_mail()にすることにした（DK時に使用していたmb_send_mail）
//        	if(!isDK()) {
//            	$result_user = mb_send_mail( $mail_add, $return_subject, $contents_user, $return_header, '-f'.$returnMail );
//                //ログイン
//                //$this->auth->setAuth($mail_add);
//            }
//            else {
//                $result_user = mail( $mail_add, $return_subject, nl2br($contents_user), $return_header, '-f'.$returnMail );
//                //$result_user = mb_send_mail( $mail_add, $return_subject, $this->format_return($name), $return_header, '-f'.$returnMail );
//        	}
//            return $result_user ? $result_user : 1051; //to User Sending Error
//        }
//        else {
//        	$result_master = 1050; //to Master Sending Error
//        	return $result_master;
//        }
//    }
    
    //Send MailとDBへのinsert
    public function sendMailAndSetDB() {
    	        
        //$_POSTとTicketの確認 エラーがあればdieになる
        $this->checkInputAndTicket();
    
    	/* Set DB */
        $this->setDataToDB();
        
        //SendMail
        $result = $this->sendMail();
        
        return $result;
        
    }
    
    
    //Send Mailとパスワードリセット
    public function sendMailAndResetPw() {
    	        
        //$_POSTとTicketの確認 エラーがあればdieになる
        //$this->checkInputAndTicket();

		$info = $this->setInfoSendMailForReset();
        $result = $this->sendMail($info);
        return $result;

    }



    //Session clear
    public function clear($boolAg = TRUE) {
    	if($boolAg) {
        	if(count($_SESSION['_authsession']) > 0) {
        		$_SESSION[$this->type] = array();
            }
            else {
            	$_SESSION = array();
                session_destroy();
            }
        }
        
    //    if($boolAg) {
    //	    setcookie('compAuth', 1, time()+60);
    //        //echo $_SESSION['compAuth'];
    //    
    //    }
    }
    
    

    
    //selectBox($first, $last, $objNum=null)で使用するジェネレータ。 *** yield->配列を使用せず値をキープできる
    /*
    private function xrange($start, $end) {
    	if($start > $end) //逆順の時 Yearにて
        	for($i = $start; $i >= $end; $i--) yield $i;
        else //正順
            for($i = $start; $i <= $end; $i++) yield $i;

    }
    */
    
    
    //暗号化関数
    public function enCrypt($string) {
        $hash = md5($string);
        $key = pack('H*', $hash);
        
        # show key size use either 16, 24 or 32 byte keys for AES-128, 192
        # and 256 respectively
        $key_size =  strlen($key);
        //echo "Key size: " . $key_size . "\n";
        
        $plaintext = $string;

        # create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential 
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                     $plaintext, MCRYPT_MODE_CBC, $iv);

        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;
        
        # encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);

        return array($ciphertext_base64, $key);
    }

    //暗号化復元だが、復元後の文字列に不明の***が入るので使っていない
    public function deCrypt($ciphertext_base64_array) {
        $ciphertext_dec = base64_decode($ciphertext_base64_array[0]);
        $key = $ciphertext_base64_array[1];
        
        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        
        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        # may remove 00h valued characters from end of plain text
        //$key = $ciphertext_base64_array['hashKey'];
        
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                        $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        
        //復元後の文字列に***が入るのでtrim
        return trim($plaintext_dec); //or mtrim()
    }
    
    
    /* FormatFunc 今は使用していない ******************************************* */
/*
    //Confirm画面でのリストアップに使用していたものか
    public function format_func($turnArg) {

            $all_format = array();
            $out_format = "<p><span>■%s</span><br/>{$turnArg}%s</p>";
            $out_format_check = "<p><span>%s</span>{$turnArg}　・%s</p>";
            
           // if ( ! $_SESSION['check'][0] == '') {
           //     $check_value = implode("{$value}　　・", $_SESSION['check'][0]);
           // }
            
            //サイト上で改行して表示させるなら
            //$_SESSION['comment'][0] = str_replace("\n", "<br />", $_SESSION['comment'][0]);
            
            foreach ($_SESSION[$this->type] as $key => $val) {
                
                if ($key == 'check' && $_SESSION[$this->type]['check'][1] != '') {
                    $check_value = implode("{$turnArg}　　・", $_SESSION['check'][1]);
                    $all_format[] = sprintf($out_format_check, $val[0], $check_value);
                }

                else if ( $this->checkSessionKey($key) ) {
                    $inputValue = $this->h_esc($val[1]);
                    
                    if(strpos($inputValue, "\n")) { // 改行文字があればnl2br()をして返す
                        $inputValue = nl2br($inputValue);
                    }
                    
                    $all_format[] = sprintf($out_format, $this->h_esc($val[0]), $inputValue);
                }
            }
            
            return implode("$turnArg", $all_format);
    }
*/

    /* メール送信用フォーマット */
/*
    public function format_mail_func($turnArg) {
        
        $all_format = array();
        $out_format = "■ %s{$turnArg}%s";
        
        //サイト上で改行表示した場合に、改行コードを戻す
        //$_SESSION['comment'][0] = str_replace("<br />", "\n", $_SESSION['comment'][0]);
            
        foreach ($_SESSION[$this->type] as $key => $val) {
            if ( $this->checkSessionKey($key) ) {
                    $val[1] = $this->h_esc($val[1]);
                    $val[1] = $this->addUnitByKey($key, $val[1]); //単位を付ける
                    $all_format[] = sprintf($out_format, $val[0], $val[1]);
                }
        }
        
        return implode("$turnArg"."$turnArg", $all_format);
    }
*/
    /* ユーザー宛メール用フォーマット ************************* */
//    public function format_return($arg) {
//        
//        $adminHead = $this->admin['head']; //EOL内に入れるとエラーになる ObjectからのStringが良くないぽい
//		$title = get_the_title();
//        
////to user mail sentence
//$context = <<<EOL
//{$arg} 様
//
//
//$adminHead
//
//
//--------　{$title} 内容　-----------
//
//
//EOL;
//// END to user mail sentence
//
//        $context .= $this->format_mail_func($value="\n");
//        $context .= "\n\n\n\n\n";
//        $context .= $this->admin['foot'];
//        //$context .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\t\n\t\n\t\n";
//        
//        return $context;
//    }

    /* Admin用メールフォーマット **************************** */
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
    


} //class End





